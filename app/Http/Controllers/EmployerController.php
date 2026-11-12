<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ReportService;
use Illuminate\Validation\Rule;

class EmployerController extends Controller
{
    private function counts()
    {
        return [
            'applications as applicants_count' => fn ($w) => $w->where('status', '!=', 'withdrawn'),
            'applications as pending_count' => fn ($w) => $w->where('status', 'pending'),
            'applications as approved_count' => fn ($w) => $w->where('status', 'approved'),
            'applications as rejected_count' => fn ($w) => $w->where('status', 'rejected'),
        ];
    }

    public function dashboard()
    {
        $user = auth()->user();
        $vacancies = $user->vacancies()->withCount($this->counts())->get();

        $stats = [
            'vacancies' => $vacancies->count(),
            'open' => $vacancies->filter(fn ($v) => $v->statusLabel() === 'Open')->count(),
            'applicants' => $vacancies->sum('applicants_count'),
            'pending' => $vacancies->sum('pending_count'),
            'slots_left' => $vacancies->filter(fn ($v) => $v->isListed())->sum(fn ($v) => $v->slotsLeft()),
        ];

        $recent = Application::whereIn('vacancy_id', $vacancies->pluck('id'))
            ->where('status', 'pending')->with(['applicant', 'vacancy'])->latest()->take(5)->get();

        return view('employer.dashboard', compact('user', 'stats', 'recent'));
    }

    // ---------------------------------------------------------------- company profile
    public function company()
    {
        return view('employer.company', ['user' => auth()->user()]);
    }

    public function companyUpdate(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'profile_photo' => 'nullable|image|max:2048',
            'company_name' => 'required|string|max:255',
            'industry' => ['required', Rule::in(Vacancy::INDUSTRIES)],
            'company_description' => 'nullable|string|max:2000',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'required|string|max:30',
            'location' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        unset($data['profile_photo']);
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && ! str_starts_with($user->profile_photo, 'assets/')) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('logos', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Your company profile has been updated successfully.');
    }

    // ---------------------------------------------------------------- vacancies
    private function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => ['required', Rule::in(Vacancy::INDUSTRIES)],
            'description' => 'required|string|max:5000',
            'required_skills' => 'required|string|max:500',
            'qualifications' => 'nullable|string|max:500',
            'experience' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'employment_type' => ['required', Rule::in(Vacancy::TYPES)],
            'slots' => 'required|integer|min:1|max:1000',
            'deadline' => 'required|date|after_or_equal:today',
            'contact_info' => 'required|string|max:255',
        ];
    }

    private function own(Vacancy $vacancy): Vacancy
    {
        abort_unless($vacancy->employer_id === auth()->id(), 403);

        return $vacancy;
    }

    public function createVacancy()
    {
        $user = auth()->user();
        $vacancy = new Vacancy([
            'slots' => 1,
            'location' => $user->location,
            'category' => $user->industry,
            'contact_info' => trim($user->email . ($user->phone ? ' / ' . $user->phone : '')),
        ]);

        return view('employer.vacancy-form', compact('vacancy'));
    }

    public function storeVacancy(Request $request)
    {
        $data = $request->validate($this->rules());
        $draft = $request->input('action') === 'draft';
        $data['status'] = $draft ? 'draft' : 'open';
        $request->user()->vacancies()->create($data);

        return redirect()->route('employer.vacancies')->with('success', $draft
            ? 'Your vacancy has been saved as a draft.'
            : 'Your vacancy has been published successfully. Skilled individuals can now view and apply.');
    }

    public function vacancies()
    {
        $vacancies = auth()->user()->vacancies()->withCount($this->counts())->latest()->get();

        return view('employer.vacancies', compact('vacancies'));
    }

    public function editVacancy(Vacancy $vacancy)
    {
        return view('employer.vacancy-form', ['vacancy' => $this->own($vacancy)]);
    }

    public function updateVacancy(Request $request, Vacancy $vacancy)
    {
        $this->own($vacancy);
        $data = $request->validate($this->rules());
        $draft = $request->input('action') === 'draft';
        $data['status'] = $draft ? 'draft' : 'open';
        $vacancy->update($data);

        return redirect()->route('employer.vacancies')->with('success', $draft
            ? 'Your vacancy has been saved as a draft.'
            : 'Your vacancy has been published successfully. Skilled individuals can now view and apply.');
    }

    public function toggleVacancy(Vacancy $vacancy)
    {
        $this->own($vacancy);
        abort_if($vacancy->status === 'draft', 404);

        $vacancy->update(['status' => $vacancy->status === 'open' ? 'closed' : 'open']);

        return back()->with('success', $vacancy->status === 'open' ? 'The vacancy has been reopened.' : 'The vacancy has been closed.');
    }

    public function destroyVacancy(Vacancy $vacancy)
    {
        $this->own($vacancy)->delete();

        return back()->with('success', 'The vacancy has been deleted.');
    }

    // ---------------------------------------------------------------- applicants
    public function applicants(Request $request, Vacancy $vacancy)
    {
        $this->own($vacancy);
        $vacancy->loadCount(['applications as approved_count' => fn ($w) => $w->where('status', 'approved')]);

        $filter = $request->query('status');
        $applications = $vacancy->applications()->with(['applicant.documents'])
            ->where('status', '!=', 'withdrawn')
            ->when(in_array($filter, ['pending', 'approved', 'rejected'], true), fn ($q) => $q->where('status', $filter))
            ->latest()->get();

        return view('employer.applicants', compact('vacancy', 'applications', 'filter'));
    }

    public function decide(Request $request, Application $application)
    {
        $vacancy = $application->vacancy;
        abort_unless($vacancy->employer_id === $request->user()->id, 403);

        $data = $request->validate(['status' => 'required|in:pending,approved,rejected']);

        if ($application->status === 'withdrawn') {
            return back()->with('error', 'This applicant has withdrawn the application.');
        }

        $vacancy->loadCount(['applications as approved_count' => fn ($w) => $w->where('status', 'approved')]);

        if ($data['status'] === 'approved' && $application->status !== 'approved' && $vacancy->slotsLeft() < 1) {
            return back()->with('error', 'All available positions have been filled. No further applicants can be approved.');
        }

        $application->update(['status' => $data['status']]);

        return back()->with('success', 'The application has been marked as ' . $data['status'] . '.');
    }

    // ---------------------------------------------------------------- statistics
    public function statistics(Request $request)
    {
        $vacancies = auth()->user()->vacancies()->where('status', '!=', 'draft')
            ->withCount($this->counts())->latest()->get();

        $selected = $vacancies->firstWhere('id', (int) $request->query('vacancy')) ?? $vacancies->first();

        return view('employer.statistics', compact('vacancies', 'selected'));
    }

    // ---------------------------------------------------------------- find skilled youth
    public function findYouth(Request $request)
    {
        $f = $request->only(['name', 'skill', 'location', 'qualification']);

        $youth = User::where('role', 'youth')->where('visible_to_employers', true)->where('account_status', 'active')
            ->when($f['name'] ?? null, fn ($q, $v) => $q->where('name', 'like', "%{$v}%"))
            ->when($f['skill'] ?? null, fn ($q, $v) => $q->where(fn ($w) => $w->where('skills', 'like', "%{$v}%")->orWhere('professional_title', 'like', "%{$v}%")))
            ->when($f['location'] ?? null, fn ($q, $v) => $q->where('location', 'like', "%{$v}%"))
            ->when($f['qualification'] ?? null, fn ($q, $v) => $q->where(fn ($w) => $w->where('education_level', 'like', "%{$v}%")
                ->orWhere('work_experience', 'like', "%{$v}%")->orWhere('bio', 'like', "%{$v}%")->orWhere('skills', 'like', "%{$v}%")))
            ->orderByRaw("verification_status = 'verified' desc")->latest()->paginate(9)->withQueryString();

        return view('employer.find-youth', compact('youth'));
    }

    public function youthShow(User $user)
    {
        abort_unless($user->isYouth() && $user->account_status === 'active', 404);

        $myVacancyIds = auth()->user()->vacancies()->pluck('id');
        $applied = Application::where('user_id', $user->id)->whereIn('vacancy_id', $myVacancyIds)
            ->where('status', '!=', 'withdrawn')->exists();

        abort_unless($user->visible_to_employers || $applied, 404);

        $documents = $applied ? $user->documents : collect();

        return view('employer.youth-show', compact('user', 'applied', 'documents'));
    }

    // ---------------------------------------------------------------- report
    public function report(Request $request)
    {
        $user = auth()->user();
        $period = ReportService::period($request->query('period'));
        $vacancies = $user->vacancies()->where('status', '!=', 'draft')->withCount($this->counts())->get();
        $apps = Application::whereIn('vacancy_id', $vacancies->pluck('id'))->with(['applicant', 'vacancy'])->get();

        $rows = ReportService::buckets($apps, $period);
        $totals = ReportService::totals($rows);

        $slots = $vacancies->sum('slots');
        $filled = $vacancies->sum('approved_count');
        $fillRate = $slots > 0 ? min(100, (int) round($filled / $slots * 100)) : 0;

        $posted = ReportService::buckets($vacancies, $period);   // vacancies posted per period (uses created_at)
        $postedByLabel = collect($posted)->pluck('total', 'label');

        $byEducation = $apps->groupBy(fn ($a) => $a->applicant->education_level ?: 'Not specified')->map->count()->sortDesc();
        $byLocation = $apps->groupBy(fn ($a) => $a->applicant->location ?: 'Not specified')->map->count()->sortDesc()->take(6);
        $byIndustry = $vacancies->groupBy('category')->map->count()->sortDesc();

        $decided = $apps->whereIn('status', ['approved', 'rejected']);
        $avgDays = $decided->count()
            ? round($decided->avg(fn ($a) => max(0, $a->created_at->diffInHours($a->updated_at, true)) / 24), 1) : null;

        $openNow = $vacancies->filter(fn ($v) => $v->statusLabel() === 'Open')->count();
        $contactable = User::where('role', 'youth')->where('visible_to_employers', true)->where('account_status', 'active')->count();

        return view('employer.report', compact('user', 'period', 'rows', 'totals', 'vacancies', 'slots', 'filled', 'fillRate',
            'postedByLabel', 'byEducation', 'byLocation', 'byIndustry', 'avgDays', 'openNow', 'contactable'));
    }
}
