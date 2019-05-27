<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanFavorite;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanLike;
use Symfony\Component\Debug\Debug;
use Laravel\Scout\Searchable;

/**
 * @property mixed email
 * @property mixed name
 * @property mixed following
 * @property mixed id
 */
class User extends Authenticatable
{
    use Notifiable, Searchable, CanFollow, CanFavorite, CanBeFollowed;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tweets()
    {
        return $this->hasMany('App\Tweet');
    }

    public function image(): string
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
    }

    public function cleanedName(): string
    {
        return strtolower(trim(str_replace(' ', '', $this->name)));
    }

    public function reMessages()
    {
        return $this->belongsToMany('App\Tweet', 're_messages')->withTimestamps();
    }

    public static function getUserByName(string $name)
    {
        $name = strtolower(trim(str_replace(' ', '', $name)));
        return User::all()->filter(function ($user) use ($name) {
            return strcmp($user->cleanedName(), $name) == 0;
        })->first();
    }

}