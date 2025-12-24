<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Program extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'location',
        'is_budgeted',
        'budget',
        'flier_url'
    ];

    public function banks()
    {
        return $this->belongsToMany(Bank::class, 'bank_program')->withTimestamps()->withPivot('created_at', 'updated_at');
    }
    public function coordinators()
    {
        return $this->belongsToMany(Member::class, 'program_coordinators')->withTimestamps()->withPivot('created_at', 'updated_at');
    }

    public function members()
    {
        return $this->coordinators();
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function offerings()
    {
        return $this->hasMany(Offering::class);
    }

    public function tithes()
    {
        return $this->hasMany(Tithe::class);
    }

    public function attendees()
    {
        return $this->hasMany(ProgrammeAttendee::class);
    }
}
