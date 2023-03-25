<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilePath extends Model
{
    use HasFactory;
    protected $table = 'file_paths';
    protected $fillable = ['id','name'];
    public $timestamps = false;

    public function File()
    {
        return $this->hasMany(File::class);
    }
}
