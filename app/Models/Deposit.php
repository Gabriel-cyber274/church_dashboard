<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deposit extends Model
{
    use SoftDeletes;
    //
    protected $fillable = [
        'program_id',
        'project_id',
        'member_id',
        'pledge_id',
        'amount',
        'deposit_date',
        'description',
        'status',
    ];

    protected $casts = [
        'deposit_date' => 'date',
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

    public function pledge()
    {
        return $this->belongsTo(Pledge::class);
    }
}
