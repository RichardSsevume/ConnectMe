<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const EDUCATION_LEVELS = [
        'Secondary (O-Level)', 'Secondary (A-Level)', 'Certificate', 'Diploma',
        "Bachelor's Degree", "Master's Degree", 'Other',
    ];

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'location', 'profile_photo',
        'account_status', 'verification_status',
        'professional_title', 'skills', 'bio', 'education_level', 'work_experience', 'portfolio_url', 'visible_to_employers',
        'company_name', 'industry', 'company_description', 'website',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'visible_to_employers' => 'boolean',
    ];

    public function isYouth(): bool    { return $this->role === 'youth'; }
    public function isEmployer(): bool { return $this->role === 'employer'; }
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isVerified(): bool { return $this->verification_status === 'verified'; }

    public function displayName(): string
    {
        return $this->isEmployer() && $this->company_name ? $this->company_name : $this->name;
    }

    public function firstName(): string
    {
        return explode(' ', trim($this->name))[0] ?? $this->name;
    }

    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->displayName()));
        return strtoupper(substr($words[0] ?? '', 0, 1) . substr($words[1] ?? '', 0, 1));
    }

    public function photoUrl(): ?string
    {
        if (! $this->profile_photo) {
            return null;
        }
        return str_starts_with($this->profile_photo, 'assets/')
            ? asset($this->profile_photo)
            : asset('storage/' . $this->profile_photo);
    }

    public function skillList(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', (string) $this->skills))));
    }

    public function whatsappNumber(): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $this->phone);
        if ($digits === '') {
            return null;
        }
        if (str_starts_with($digits, '0')) {
            $digits = '256' . substr($digits, 1);
        }
        return $digits;
    }

    public function vacancies()
    {
        return $this->hasMany(Vacancy::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
