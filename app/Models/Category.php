<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Use for relationships manger using filament Between Post and Category
    public function posts(){
        return $this->hasMany(Post::class);
    }
    //Many to many relationships With post and Category
    public function categories_posts()
    {
        return $this->belongsToMany(Post::class, 'post_category')->withTimestamps();
    }
}
