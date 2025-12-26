<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'report_submission_id',
        'question_id',
        'answer',
    ];

    public function submission()
    {
        return $this->belongsTo(ReportSubmission::class, 'report_submission_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
