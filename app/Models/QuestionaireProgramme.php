<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionaireProgramme extends Model
{
    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(QuestionaireQuestion::class, 'questionaire_id');
    }

    public static function generateUniqueCode(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $chars[random_int(0, strlen($chars) - 1)];
            }
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
