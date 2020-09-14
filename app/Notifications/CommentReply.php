<?php

namespace App\Notifications;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentReply extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $user;
    private $post;
    private $comment;

    public function __construct(Post $post, User $user, Comment $comment)
    {
        $this->user = $user;
        $this->post = $post;
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $title = $this->post->title;
        $name = $this->user->name;
        return [
            'data' => "<strong><a href='" . route('profile', $this->user->name) .
                "'>$name</a></strong> ответила на ваш комментарий к записи <a href='" .
                route('show', $this->post->slug) ."#comment-" . $this->comment->id ."'><strong>$title</strong></a>."
        ];
    }
}
