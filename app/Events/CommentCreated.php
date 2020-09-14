<?php

namespace App\Events;

use App\Post;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Comment;
use test\Mockery\HasUnknownClassAsTypeHintOnMethod;

class CommentCreated implements ShouldBroadcast
{
    use SerializesModels;

    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function broadcastOn()
    {
        $post = Post::find($this->comment->post());
        $users = $post->favoriters()->where('id', '!=', $this->comment->commenter_id)->get();

        $channels = [];

        foreach ($users as $user) {
            array_push($channels, new PrivateChannel('users.' . $user->id));
        }
        if ($this->comment->parent()->first()) {
            array_push($channels, new PrivateChannel('users.' . $this->comment->parent()->first()->commenter->id));
        }


        if (empty($channels))
        {
            array_push($channels, new PrivateChannel('users.' . 99999));
        }

        return $channels;
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->comment->id,
        ];
    }

}
