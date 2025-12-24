<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tithe extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'program_id',
        'amount',
        'tithe_date',
        'description',
        'status',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
