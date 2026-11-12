<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ContactMessage;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'youth' => User::where('role', 'youth')->count(),
            'employers' => User::where('role', 'employer')->count(),
            'pending' => User::whereIn('role', ['youth', 'employer'])->where('verification_status', 'pending')->count(),
            'vacancies' => Vacancy::where('status', '!=', 'draft')->count(),
            'applications' => Application::count(),
        ];
        $messages = ContactMessage::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'messages'));
    }

    public function verify()
    {
        $users = User::whereIn('role', ['youth', 'employer'])
            ->orderByRaw("case verification_status when 'pending' then 0 when 'rejected' then 1 else 2 end")
            ->latest()->paginate(15);

        return view('admin.verify', compact('users'));
    }

    public function verification(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 403);
        $data = $request->validate(['action' => 'required|in:verify,reject']);

        $user->update(['verification_status' => $data['action'] === 'verify' ? 'verified' : 'rejected']);

        return back()->with('success', $data['action'] === 'verify'
            ? $user->displayName() . ' has been verified.'
            : 'Verification was rejected for ' . $user->displayName() . '.');
    }

    public function users()
    {
        $users = User::whereIn('role', ['youth', 'employer'])->latest()->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function userShow(User $user)
    {
        abort_if($user->isAdmin(), 404);
        $user->load('documents');
        $user->loadCount(['applications', 'vacancies']);

        return view('admin.user-show', compact('user'));
    }

    public function userStatus(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 403);
        $data = $request->validate(['action' => 'required|in:suspend,activate']);

        $user->update(['account_status' => $data['action'] === 'suspend' ? 'suspended' : 'active']);

        return back()->with('success', $data['action'] === 'suspend'
            ? 'The account has been suspended.'
            : 'The account has been activated.');
    }

    public function vacancies()
    {
        $vacancies = Vacancy::where('status', '!=', 'draft')->with('employer')->latest()->paginate(15);

        return view('admin.vacancies', compact('vacancies'));
    }

    public function moderate(Request $request, Vacancy $vacancy)
    {
        $data = $request->validate(['action' => 'required|in:approve,remove']);

        $vacancy->update(['moderation' => $data['action'] === 'approve' ? 'approved' : 'removed']);

        return back()->with('success', $data['action'] === 'approve'
            ? 'The vacancy has been approved.'
            : 'The vacancy has been removed from public view.');
    }

    // ---------------------------------------------------------------- messages from the contact form
    public function messages()
    {
        $messages = ContactMessage::latest()->paginate(15);

        return view('admin.messages', compact('messages'));
    }

    public function messageDelete(ContactMessage $message)
    {
        $message->delete();

        return back()->with('success', 'The message has been deleted.');
    }

    // ---------------------------------------------------------------- platform report
    public function report()
    {
        $apps = Application::with('vacancy')->get();
        $rows = \App\Services\ReportService::buckets($apps, 'monthly');
        $totals = \App\Services\ReportService::totals($rows);
        $users = User::whereIn('role', ['youth', 'employer'])->get();

        $stats = [
            'youth' => $users->where('role', 'youth')->count(),
            'employers' => $users->where('role', 'employer')->count(),
            'verified' => $users->where('verification_status', 'verified')->count(),
            'pending' => $users->where('verification_status', 'pending')->count(),
            'suspended' => $users->where('account_status', 'suspended')->count(),
            'vacancies' => Vacancy::where('status', '!=', 'draft')->count(),
            'removed' => Vacancy::where('moderation', 'removed')->count(),
        ];

        return view('admin.report', compact('rows', 'totals', 'stats'));
    }
}
