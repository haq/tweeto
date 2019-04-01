<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property array|string|null message
 * @property int|null user_id
 * @property mixed created_at
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
        return $this->belongsToMany('App\User', 'favorites', 'message_id', 'user_id')->withTimestamps();
    }

    public function userFavorites(int $user): bool
    {
        return $this->favorites()->exists($user);
    }
}
