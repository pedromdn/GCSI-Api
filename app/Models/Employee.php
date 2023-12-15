<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'name',
        'last_name1',
        'last_name2',
        'company',
        'area',
        'department',
        'position',
        'photo',
        'startDate',
        'status',
    ];
}
