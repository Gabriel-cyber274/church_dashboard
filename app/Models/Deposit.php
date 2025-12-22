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
        'amount',
        'deposit_date',
        'description',
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
}
