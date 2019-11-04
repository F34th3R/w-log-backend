<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = ['path', 'tag'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
