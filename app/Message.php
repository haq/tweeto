<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property array|string|null message
 * @property int|null user_id
 * @property mixed created_at
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
}
