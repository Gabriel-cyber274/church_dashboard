<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentRole extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_id',
        'department_id',
        'role_name',
        'role_description',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
