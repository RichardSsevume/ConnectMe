<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ContactMessage;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $latest = Vacancy::listed()->withApproved()->with('employer')->latest()->take(6)->get();

        return view('home', compact('latest'));
    }

    public function about()
    {
        return view('about');
    }

    public function contact()
    {
        return view('contact');
    }

    public function contactSend(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:3000',
        ]);

        ContactMessage::create($data);

        return back()->with('success', 'Thank you for contacting Connect Me. Your message has been received successfully.');
    }

    public function jobs(Request $request)
    {
        $vacancies = Vacancy::listed()->withApproved()->with('employer')
            ->filter($request->only(['q', 'qualification', 'experience', 'company', 'skill', 'location', 'industry']))
            ->latest()->paginate(9)->withQueryString();

        $applied = $this->appliedMap();

        return view('jobs.index', compact('vacancies', 'applied'));
    }

    public function jobShow(Vacancy $vacancy)
    {
        $user = auth()->user();
        $canSee = $vacancy->isListed() || ($user && ($user->isAdmin() || $user->id === $vacancy->employer_id));
        abort_unless($canSee, 404);

        $vacancy->load('employer')->loadCount([
            'applications as approved_count' => fn ($w) => $w->where('status', 'approved'),
        ]);

        $myApplication = $user && $user->isYouth()
            ? $vacancy->applications()->where('user_id', $user->id)->first()
            : null;

        return view('jobs.show', compact('vacancy', 'myApplication'));
    }

    public function findYouth()
    {
        if (auth()->check() && auth()->user()->isEmployer()) {
            return redirect()->route('employer.find-youth');
        }

        $count = User::where('role', 'youth')->where('visible_to_employers', true)
            ->where('account_status', 'active')->count();

        return view('find-youth-guest', compact('count'));
    }

    private function appliedMap()
    {
        $user = auth()->user();

        return $user && $user->isYouth()
            ? Application::where('user_id', $user->id)->pluck('status', 'vacancy_id')
            : collect();
    }
}
