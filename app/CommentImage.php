<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentImage extends Model
{
    protected $table = 'comment_images';
    protected $primaryKey = 'id';

    public function comment() {
        return $this->belongsTo(Comment::class);
    }

}
