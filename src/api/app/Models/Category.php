<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = ['name'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function Post()
    {
        return $this->hasMany(Post::class);
    }
}
