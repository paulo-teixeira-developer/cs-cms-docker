<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = ['email', 'password', 'person_id'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function Person()
    {
        return $this->belongsTo(Person::class);
    }

    public function Post()
    {
        return $this->hasMany(Post::class);
    }
}
