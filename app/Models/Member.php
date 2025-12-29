<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Member extends Model
{
    use HasFactory, Notifiable, SoftDeletes;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'address',
        'phone_number',
        'gender',
        'marital_status',
        'country',
        'state',
        'city'
    ];




    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class)
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    public function programsCoordinated()
    {
        return $this->belongsToMany(Program::class, 'program_coordinators')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }

    public function programs()
    {
        return $this->programsCoordinated();
    }

    public function pledges()
    {
        return $this->hasMany(Pledge::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function reports()
    {
        return $this->belongsToMany(Report::class)
            ->withTimestamps();
    }


    public function submissions()
    {
        return $this->hasMany(\App\Models\ReportSubmission::class);
    }

    public function departmentRoles()
    {
        return $this->hasMany(DepartmentRole::class);
    }
}
