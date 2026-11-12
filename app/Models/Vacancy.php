<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    public const INDUSTRIES = [
        'Information Technology', 'Finance & Accounting', 'Sales & Marketing', 'Education & Training',
        'Healthcare', 'Engineering & Construction', 'Hospitality & Tourism', 'Agriculture',
        'Administration & Office Support', 'Creative & Media', 'Other',
    ];

    public const QUALIFICATIONS = [
        'Secondary (O-Level)', 'Secondary (A-Level)', 'Certificate', 'Diploma',
        "Bachelor's Degree", "Master's Degree", 'Professional Certification (e.g. ACCA, CPA)',
    ];

    public const EXPERIENCE_LEVELS = [
        'No experience', '0 - 2 years', '2 - 5 years', '5 - 10 years', '10+ years',
    ];

    public const TYPES = ['Full-time', 'Part-time', 'Contract', 'Internship', 'Short-term gig'];

    protected $fillable = [
        'employer_id', 'title', 'category', 'description', 'required_skills', 'qualifications', 'experience',
        'location', 'employment_type', 'slots', 'deadline', 'contact_info', 'status', 'moderation',
    ];

    protected $casts = ['deadline' => 'date'];

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /** Published, approved by admin and not past the deadline. */
    public function scopeListed($query)
    {
        return $query->where('status', 'open')
            ->where('moderation', 'approved')
            ->whereDate('deadline', '>=', now()->toDateString());
    }

    public function scopeWithApproved($query)
    {
        return $query->withCount([
            'applications as approved_count' => fn ($w) => $w->where('status', 'approved'),
        ]);
    }

    public function scopeFilter($query, array $f)
    {
        return $query
            ->when($f['q'] ?? null, fn ($w, $v) => $w->where('title', 'like', "%{$v}%"))
            ->when($f['qualification'] ?? null, fn ($w, $v) => $w->where('qualifications', 'like', "%{$v}%"))
            ->when($f['experience'] ?? null, fn ($w, $v) => $w->where('experience', 'like', "%{$v}%"))
            ->when($f['company'] ?? null, fn ($w, $v) => $w->whereHas('employer', fn ($e) => $e->where('company_name', 'like', "%{$v}%")))
            // older filters kept so existing links keep working
            ->when($f['skill'] ?? null, fn ($w, $v) => $w->where('required_skills', 'like', "%{$v}%"))
            ->when($f['location'] ?? null, fn ($w, $v) => $w->where('location', 'like', "%{$v}%"))
            ->when($f['industry'] ?? null, fn ($w, $v) => $w->where('category', $v));
    }

    public function approvedCount(): int
    {
        if (array_key_exists('approved_count', $this->attributes)) {
            return (int) $this->attributes['approved_count'];
        }
        return $this->applications()->where('status', 'approved')->count();
    }

    public function slotsLeft(): int
    {
        return max(0, $this->slots - $this->approvedCount());
    }

    public function isExpired(): bool
    {
        return $this->deadline !== null && $this->deadline->copy()->endOfDay()->isPast();
    }

    public function isListed(): bool
    {
        return $this->status === 'open' && $this->moderation === 'approved' && ! $this->isExpired();
    }

    public function statusLabel(): string
    {
        if ($this->moderation === 'removed') return 'Removed';
        if ($this->status === 'draft') return 'Draft';
        if ($this->status === 'closed') return 'Closed';
        if ($this->isExpired()) return 'Expired';
        if ($this->slotsLeft() < 1) return 'Filled';
        return 'Open';
    }

    public function statusClass(): string
    {
        return match ($this->statusLabel()) {
            'Open' => 'success',
            'Draft' => 'secondary',
            'Closed', 'Expired', 'Filled' => 'warning',
            default => 'danger',
        };
    }
}
