<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Punch extends Model
{
    protected $fillable = [
        'employeeId',
        'refNo',
        'day_in',
        'lunch_out',
        'lunch_in',
        'day_out'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
