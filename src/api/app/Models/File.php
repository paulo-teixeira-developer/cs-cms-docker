<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $table = 'files';
    protected $fillable = ['name', 'hash', 'size', 'file_format_id', 'file_path_id'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function User()
    {
        return $this->hasOne(User::class);
    }

    public function Post()
    {
        return $this->hasMany(Post::class);
    }

    public function FileFormat()
    {
        return $this->belongsTo(FileFormat::class);
    }

    public function FilePath()
    {
        return $this->belongsTo(FilePath::class);
    }
}
