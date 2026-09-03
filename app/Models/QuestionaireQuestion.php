<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionaireQuestion extends Model
{
    protected $fillable = [
        'questionaire_id',
        'question',
    ];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(QuestionaireProgramme::class, 'questionaire_id');
    }
}
