<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Document;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\ReportService;
use Illuminate\Validation\Rule;

class YouthController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $counts = $user->applications()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $documents = $user->documents()->count();
        $recent = $user->applications()->with('vacancy.employer')->latest()->take(5)->get();

        return view('youth.dashboard', compact('user', 'counts', 'documents', 'recent'));
    }

    // ---------------------------------------------------------------- profile
    public function profile()
    {
        return view('youth.profile', ['user' => auth()->user()]);
    }

    public function profileUpdate(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'profile_photo' => 'nullable|image|max:2048',
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'required|string|max:30',
            'location' => 'required|string|max:255',
            'professional_title' => 'nullable|string|max:255',
            'skills' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:2000',
            'education_level' => ['nullable', Rule::in(User::EDUCATION_LEVELS)],
            'work_experience' => 'nullable|string|max:3000',
            'portfolio_url' => 'nullable|url|max:255',
        ]);

        unset($data['profile_photo']);
        if ($request->hasFile('profile_photo')) {
            $this->deleteOldPhoto($user);
            $data['profile_photo'] = $request->file('profile_photo')->store('avatars', 'public');
        }
        $data['visible_to_employers'] = $request->boolean('visible_to_employers');

        $user->update($data);

        return back()->with('success', 'Your profile has been saved successfully.');
    }

    private function deleteOldPhoto(User $user): void
    {
        if ($user->profile_photo && ! str_starts_with($user->profile_photo, 'assets/')) {
            Storage::disk('public')->delete($user->profile_photo);
        }
    }

    // ---------------------------------------------------------------- documents
    public function documents()
    {
        $documents = auth()->user()->documents->keyBy('type');

        return view('youth.documents', compact('documents'));
    }

    public function documentUpload(Request $request, string $type)
    {
        abort_unless(array_key_exists($type, Document::TYPES), 404);

        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:5120',
        ], [
            'file.required' => 'Please choose a file to upload.',
            'file.mimes' => 'Allowed file types: PDF, Word, JPG, PNG or ZIP.',
            'file.max' => 'The file may not be larger than 5 MB.',
        ]);

        $user = $request->user();
        $file = $request->file('file');
        $old = $user->documents()->where('type', $type)->first();
        if ($old) {
            Storage::disk('public')->delete($old->path);
        }

        $path = $file->store('documents/' . $user->id, 'public');
        $user->documents()->updateOrCreate(
            ['type' => $type],
            ['path' => $path, 'original_name' => $file->getClientOriginalName()]
        );

        return back()->with('success', 'Your document has been uploaded successfully.');
    }

    public function documentDelete(Request $request, string $type)
    {
        abort_unless(array_key_exists($type, Document::TYPES), 404);

        $doc = $request->user()->documents()->where('type', $type)->first();
        if ($doc) {
            Storage::disk('public')->delete($doc->path);
            $doc->delete();
        }

        return back()->with('success', 'Your document has been deleted.');
    }

    // ---------------------------------------------------------------- jobs
    public function jobs(Request $request)
    {
        $vacancies = Vacancy::listed()->withApproved()->with('employer')
            ->filter($request->only(['q', 'qualification', 'experience', 'company', 'skill', 'location', 'industry']))
            ->latest()->paginate(9)->withQueryString();

        $applied = Application::where('user_id', $request->user()->id)->pluck('status', 'vacancy_id');

        return view('youth.jobs', compact('vacancies', 'applied'));
    }

    public function apply(Request $request, Vacancy $vacancy)
    {
        $data = $request->validate(['cover_note' => 'nullable|string|max:1000']);
        $vacancy->loadCount(['applications as approved_count' => fn ($w) => $w->where('status', 'approved')]);

        if (! $vacancy->isListed() || $vacancy->slotsLeft() < 1) {
            return back()->with('error', 'Sorry, this vacancy is no longer accepting applications.');
        }

        $existing = Application::where('vacancy_id', $vacancy->id)->where('user_id', $request->user()->id)->first();

        if ($existing && $existing->status !== 'withdrawn') {
            return back()->with('error', 'You have already applied for this vacancy.');
        }

        if ($existing) {
            $existing->update(['status' => 'pending', 'cover_note' => $data['cover_note'] ?? null]);
        } else {
            Application::create([
                'vacancy_id' => $vacancy->id,
                'user_id' => $request->user()->id,
                'cover_note' => $data['cover_note'] ?? null,
            ]);
        }

        return redirect()->route('youth.applications')->with('success', 'Your application has been submitted successfully.');
    }

    // ---------------------------------------------------------------- applications
    public function applications()
    {
        $applications = auth()->user()->applications()->with('vacancy.employer')->latest()->get();

        return view('youth.applications', compact('applications'));
    }

    public function applicationShow(Application $application)
    {
        abort_unless($application->user_id === auth()->id(), 403);
        $application->load('vacancy.employer');

        return view('youth.application-show', compact('application'));
    }

    public function withdraw(Application $application)
    {
        abort_unless($application->user_id === auth()->id(), 403);

        if ($application->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be withdrawn.');
        }

        $application->update(['status' => 'withdrawn']);

        return back()->with('success', 'Your application has been withdrawn.');
    }

    public function status()
    {
        $applications = auth()->user()->applications()->with('vacancy.employer')->latest()->get()->groupBy('status');

        return view('youth.status', compact('applications'));
    }

    // ---------------------------------------------------------------- report
    public function report(Request $request)
    {
        $user = auth()->user();
        $period = ReportService::period($request->query('period'));
        $apps = $user->applications()->with('vacancy.employer')->get();

        $rows = ReportService::buckets($apps, $period);
        $totals = ReportService::totals($rows);

        $byIndustry = $apps->groupBy(fn ($a) => $a->vacancy->category ?? 'Other')
            ->map(fn ($g) => ['total' => $g->count(), 'approved' => $g->where('status', 'approved')->count()])
            ->sortByDesc('total');

        $recent = $apps->sortByDesc('created_at')->take(10);
        $docs = $user->documents()->count();
        $profileFields = ['phone', 'location', 'professional_title', 'skills', 'bio', 'education_level', 'work_experience', 'profile_photo'];
        $profilePct = (int) round(collect($profileFields)->filter(fn ($f) => filled($user->{$f}))->count() / count($profileFields) * 100);

        $employersContacted = $apps->pluck('vacancy.employer_id')->unique()->count();

        return view('youth.report', compact('user', 'period', 'rows', 'totals', 'byIndustry', 'recent', 'docs', 'profilePct', 'employersContacted'));
    }
}
