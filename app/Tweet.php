<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Overtrue\LaravelFollow\Traits\CanBeFavorited;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;

/**
 * @property array|string|null message
 * @property int|null user_id
 * @property mixed created_at
 * @property mixed favorites
 * @property mixed|null pivot
 * @property bool|null remessage
 * @method static findOrFail($id)
 */
class Tweet extends Model
{
    use CanBeFollowed, CanBeFavorited;

    protected $fillable = [
        'message'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
