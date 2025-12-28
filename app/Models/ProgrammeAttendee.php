<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgrammeAttendee extends Model
{
    use softDeletes;
    //
    protected $fillable = [
        'program_id',
        'member_id',
        'attendance_time',
        'name',
        'phone_number',
        'email'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
