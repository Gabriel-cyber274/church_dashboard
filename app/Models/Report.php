<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function submissions()
    {
        return $this->hasMany(ReportSubmission::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class)
            ->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(Member::class)
            ->withTimestamps();
    }
}
