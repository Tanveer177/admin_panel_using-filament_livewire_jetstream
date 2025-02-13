<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'zip_code',
        'address',
        'city_id',
        'country_id',
        'department_id',
        'date_hired',
        'date_of_birth'
    ];
}
