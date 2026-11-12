<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    /** type => [label, upload button text] */
    public const TYPES = [
        'cv' => ['Curriculum Vitae (CV)', 'Upload CV'],
        'academic' => ['Academic Documents', 'Upload Academic Documents'],
        'certificates' => ['Professional Certificates', 'Upload Certificates'],
        'recommendation' => ['Recommendation Letters', 'Upload Recommendation Letter'],
        'portfolio' => ['Portfolio', 'Upload Portfolio'],
    ];

    protected $fillable = ['user_id', 'type', 'path', 'original_name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function url(): string
    {
        return asset('storage/' . $this->path);
    }
}
