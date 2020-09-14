<?php

namespace App\Notifications;

use App\Comment;
use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewComment extends Notification implements ShouldBroadcast
{
    use Queueable;


    private $user;
    private $post;
    private $comment;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     * @param Comment $comment
     * @param User|null $user
     */
    public function __construct(Post $post, Comment $comment, User $user = null)
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
        if (!is_null($this->user)) {
            $name = $this->user->name;
            return [
                'data' => "<strong><a href='" . route('profile', $this->user->name) .
                    "'>$name</a></strong> прокомментировала запись <a href='" .
                    route('show', $this->post->slug) ."#comment-" . $this->comment->id ."'><strong>$title</strong></a>."
            ];
        }
        $name = 'Аноним';
        return [
            'data' => "<strong>$name</strong> прокомментировала запись <a href='" .
                route('show', $this->post->slug) . "#comment-" . $this->comment->id ."'><strong>$title</strong></a>."
        ];


    }
}
