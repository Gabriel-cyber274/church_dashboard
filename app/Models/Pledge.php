<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pledge extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'member_id',
        'program_id',
        'project_id',
        'amount',
        'pledge_date',
        'status',
        'name',
        'phone_number',
        'note',
        'email'
    ];

    protected $casts = [
        'pledge_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }
}
