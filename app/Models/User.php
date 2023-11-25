<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN = 'ADMIN';
    const ROLE_EDITOR = 'EDITOR';
    const ROLE_USER = 'USER';
    const ROLE_DEFAULT = self::ROLE_USER;

    const ROLES = [
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_EDITOR => 'Editor',
        self::ROLE_USER => 'User',
    ];

    protected $fillable = ['name', 'email', 'password',];

    protected $hidden = ['password', 'remember_token',];


    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed',];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
        //return str_ends_with($this->email, '@hotmail.com') && $this->hasVerifiedEmail();
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post__users')->withPivot('nota')->withTimestamps();
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function isAdmin(){
        return $this->role === self::ROLE_ADMIN;
    }

    public function isEditor(){
        return $this->role === self::ROLE_EDITOR;
    }
}
