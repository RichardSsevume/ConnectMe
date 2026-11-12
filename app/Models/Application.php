<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public const STATUS_CLASS = [
        'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'withdrawn' => 'secondary',
    ];

    protected $fillable = ['vacancy_id', 'user_id', 'cover_note', 'status'];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function applicant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function statusClass(): string
    {
        return self::STATUS_CLASS[$this->status] ?? 'secondary';
    }
}
