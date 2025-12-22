<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'bank_name',
        'account_number',
        'account_holder_name',
    ];

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'bank_program')->withTimestamps()->withPivot('created_at', 'updated_at');
    }


    public function projects()
    {
        return $this->belongsToMany(Project::class, 'bank_projects')
            ->withTimestamps()
            ->withPivot('created_at', 'updated_at');
    }
}
