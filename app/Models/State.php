<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    use HasFactory; // This line is used to enable the HasFactory trait in the State model.
    protected $fillable = ['name', 'country_id']; // This line is used to define the fields that can be mass assigned in the database.
    public function country(): BelongsTo // This function is used to define the relationship between the State and Country models using the BelongsTo method.
    {
        return $this->belongsTo(Country::class);
    }
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
