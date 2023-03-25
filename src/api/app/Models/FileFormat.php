<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileFormat extends Model
{
    use HasFactory;
    protected $table = 'file_formats';
    protected $fillable = ['id','name'];
    public $timestamps = false;

    public function File()
    {
        return $this->hasMany(File::class);
    }
}
