<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageImage extends Model
{
    protected $table = 'message_images';
    protected $primaryKey = 'id';

    public function message() {
        return $this->belongsTo(Message::class);
    }

}
