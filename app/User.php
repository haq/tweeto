<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Debug\Debug;

/**
 * @property mixed email
 * @property mixed name
 * @property mixed following
 * @property mixed id
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function image(): string
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email)));
    }

    public function cleanedName(): string
    {
        return strtolower(trim(str_replace(' ', '', $this->name)));
    }

    public function followers()
    {
        return $this->belongsToMany('App\User', 'followers', 'leader_id', 'follower_id')->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany('App\User', 'followers', 'follower_id', 'leader_id')->withTimestamps();
    }

    public function followsUser(int $userId): bool
    {
        return !$this->following->filter(function (User $user) use ($userId) {
            return $user->id == $userId;
        })->isEmpty();
    }

    public static function getUserByName(string $name)
    {
        $name = strtolower($name);
        return User::all()->filter(function ($user) use ($name) {
            return strcmp($user->cleanedName(), $name) == 0;
        })->first();
    }

}