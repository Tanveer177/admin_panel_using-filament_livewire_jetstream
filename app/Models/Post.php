<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'thumbnail', 'title',  'color', 'tags', 'published', 'navmenu_id', 'content', 'status', 'image', 'category_id'];

    public function navmenu()
    {

        return $this->belongsTo(NavMenu::class, 'navmenu_id', 'id');
    }
    public function category()
    {
        // return $this->belongsTo(Category::class);
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function post_category()
    {
        return $this->belongsToMany(Category::class, 'post_category', 'post_id', 'category_id');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected $casts = [
        'tags' => 'array',
    ];
}
