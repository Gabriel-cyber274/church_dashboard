<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function members()
    {
        return $this->belongsToMany(Member::class)
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }



    public function users()
    {
        return $this->hasMany(User::class);
    }
}
