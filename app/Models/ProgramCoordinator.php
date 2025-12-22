<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramCoordinator extends Model
{
    use SoftDeletes;

    //
    protected $fillable = [
        'program_id',
        'member_id',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
