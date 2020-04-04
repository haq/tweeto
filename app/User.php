<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Overtrue\LaravelFollow\Traits\CanFavorite;
use Overtrue\LaravelFollow\Traits\CanFollow;

/**
 * @property mixed email
 * @property mixed name
 * @property mixed following
 * @property mixed id
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, CanFollow, CanFavorite, CanBeFollowed;

    protected $fillable = [
        'name', 'email', 'password', 'api_token',
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

    public function reTweets()
    {
        return $this->belongsToMany('App\Tweet', 're_tweets')->withTimestamps();
    }

    public function image(): string
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
    }

    public function cleanedName(): string
    {
        return strtolower(trim(str_replace(' ', '', $this->name)));
    }

    public static function getUserByName(string $name)
    {
        $name = strtolower(trim(str_replace(' ', '', $name)));
        return User::all()->filter(function ($user) use ($name) {
            return strcmp($user->cleanedName(), $name) == 0;
        })->first();
    }

}
