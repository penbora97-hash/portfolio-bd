<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['user_id', 'author_name', 'author_role', 'content', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
