<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;
    protected $table = 'persons';
    protected $fillable = ['name','last_name','birth','profession','biography','file_id'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function User(){
        return $this->hasOne(User::class);
    }

    public function File()
    {
        return $this->belongsTo(File::class);
    }

}
