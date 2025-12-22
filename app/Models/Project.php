<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'name',
        'description',
        'deadline',
        'budget',
    ];

    public function banks()
    {
        return $this->belongsToMany(Bank::class, 'bank_projects')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
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
}
