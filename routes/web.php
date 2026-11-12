<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\YouthController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

// ------------------------------------------------------------ public pages
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/contact-us', [PageController::class, 'contact'])->name('contact');
Route::post('/contact-us', [PageController::class, 'contactSend'])->name('contact.send');
Route::get('/find-jobs', [PageController::class, 'jobs'])->name('jobs.index');
Route::get('/find-jobs/{vacancy}', [PageController::class, 'jobShow'])->name('jobs.show');
Route::get('/find-skilled-youth', [PageController::class, 'findYouth'])->name('find-youth');

// ------------------------------------------------------------ register / login
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'choose'])->name('register');
    Route::get('/register/youth', [AuthController::class, 'youthForm'])->name('register.youth');
    Route::post('/register/youth', [AuthController::class, 'registerYouth'])->name('register.youth.store');
    Route::get('/register/employer', [AuthController::class, 'employerForm'])->name('register.employer');
    Route::post('/register/employer', [AuthController::class, 'registerEmployer'])->name('register.employer.store');
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'employer' => redirect()->route('employer.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('youth.dashboard'),
        };
    })->name('dashboard');
});

// ------------------------------------------------------------ youth dashboard
Route::middleware(['auth', EnsureRole::class . ':youth'])->prefix('youth')->group(function () {
    Route::get('/dashboard', [YouthController::class, 'dashboard'])->name('youth.dashboard');
    Route::get('/profile', [YouthController::class, 'profile'])->name('youth.profile');
    Route::put('/profile', [YouthController::class, 'profileUpdate'])->name('youth.profile.update');
    Route::get('/documents', [YouthController::class, 'documents'])->name('youth.documents');
    Route::post('/documents/{type}', [YouthController::class, 'documentUpload'])->name('youth.documents.upload');
    Route::delete('/documents/{type}', [YouthController::class, 'documentDelete'])->name('youth.documents.delete');
    Route::get('/browse-jobs', [YouthController::class, 'jobs'])->name('youth.jobs');
    Route::post('/jobs/{vacancy}/apply', [YouthController::class, 'apply'])->name('youth.apply');
    Route::get('/my-applications', [YouthController::class, 'applications'])->name('youth.applications');
    Route::get('/my-applications/{application}', [YouthController::class, 'applicationShow'])->name('youth.applications.show');
    Route::patch('/my-applications/{application}/withdraw', [YouthController::class, 'withdraw'])->name('youth.withdraw');
    Route::get('/application-status', [YouthController::class, 'status'])->name('youth.status');
    Route::get('/my-report', [YouthController::class, 'report'])->name('youth.report');
});

// ------------------------------------------------------------ employer dashboard
Route::middleware(['auth', EnsureRole::class . ':employer'])->prefix('employer')->group(function () {
    Route::get('/dashboard', [EmployerController::class, 'dashboard'])->name('employer.dashboard');
    Route::get('/company-profile', [EmployerController::class, 'company'])->name('employer.company');
    Route::put('/company-profile', [EmployerController::class, 'companyUpdate'])->name('employer.company.update');

    Route::get('/post-vacancy', [EmployerController::class, 'createVacancy'])->name('employer.vacancies.create');
    Route::post('/vacancies', [EmployerController::class, 'storeVacancy'])->name('employer.vacancies.store');
    Route::get('/manage-vacancies', [EmployerController::class, 'vacancies'])->name('employer.vacancies');
    Route::get('/vacancies/{vacancy}/edit', [EmployerController::class, 'editVacancy'])->name('employer.vacancies.edit');
    Route::put('/vacancies/{vacancy}', [EmployerController::class, 'updateVacancy'])->name('employer.vacancies.update');
    Route::patch('/vacancies/{vacancy}/toggle', [EmployerController::class, 'toggleVacancy'])->name('employer.vacancies.toggle');
    Route::delete('/vacancies/{vacancy}', [EmployerController::class, 'destroyVacancy'])->name('employer.vacancies.destroy');
    Route::get('/vacancies/{vacancy}/applicants', [EmployerController::class, 'applicants'])->name('employer.applicants');
    Route::patch('/applications/{application}', [EmployerController::class, 'decide'])->name('employer.decide');

    Route::get('/find-skilled-youth', [EmployerController::class, 'findYouth'])->name('employer.find-youth');
    Route::get('/find-skilled-youth/{user}', [EmployerController::class, 'youthShow'])->name('employer.youth.show');
    Route::get('/application-statistics', [EmployerController::class, 'statistics'])->name('employer.statistics');
    Route::get('/recruitment-report', [EmployerController::class, 'report'])->name('employer.report');
});

// ------------------------------------------------------------ admin dashboard
Route::middleware(['auth', EnsureRole::class . ':admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/verify-accounts', [AdminController::class, 'verify'])->name('admin.verify');
    Route::patch('/users/{user}/verification', [AdminController::class, 'verification'])->name('admin.verification');
    Route::get('/manage-users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/manage-users/{user}', [AdminController::class, 'userShow'])->name('admin.users.show');
    Route::patch('/users/{user}/status', [AdminController::class, 'userStatus'])->name('admin.users.status');
    Route::get('/manage-vacancies', [AdminController::class, 'vacancies'])->name('admin.vacancies');
    Route::patch('/vacancies/{vacancy}/moderate', [AdminController::class, 'moderate'])->name('admin.vacancies.moderate');
    Route::get('/messages', [AdminController::class, 'messages'])->name('admin.messages');
    Route::delete('/messages/{message}', [AdminController::class, 'messageDelete'])->name('admin.messages.delete');
    Route::get('/platform-report', [AdminController::class, 'report'])->name('admin.report');
});
