<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $fillable = ['title','slug','summary','content','published','user_id','category_id','file_id'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function File()
    {
        return $this->belongsTo(File::class);
    }
}
