<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

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
