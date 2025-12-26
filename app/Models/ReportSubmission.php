<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportSubmission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'report_id',
        'user_id',
        'member_id'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
