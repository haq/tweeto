<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property array|string|null message
 * @property int|null user_id
 * @property mixed created_at
 * @property mixed favorites
 * @method static findOrFail($id)
 */
class Message extends Model
{
    protected $fillable = [
        'message'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function favorites()
    {
        return $this->belongsToMany('App\User', 'favorites')->withTimestamps();
    }

    public function userFavorites($userId): bool
    {
        return !$this->favorites->filter(function (User $user) use ($userId) {
            return $user->id == $userId;
        })->isEmpty();
    }
}
