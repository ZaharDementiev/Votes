<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Faker\Factory;

class CommentController extends Controller
{
    use ValidatesRequests, AuthorizesRequests;


    public function __construct()
    {
        $this->middleware('web');

        if (config('comments.guest_commenting') == true) {
            $this->middleware('auth')->except('store');
        } else {
            $this->middleware('auth');
        }
    }

    /**
     * Creates a new comment for given model.
     */
    public function store(Request $request)
    {
        // If guest commenting is turned off, authorize this action.
        if (config('comments.guest_commenting') == false) {
            $this->authorize('create-comment', Comment::class);
        }

        // Define guest rules if guest commenting is enabled.
        if (config('comments.guest_commenting') == true) {
            $guest_rules = [
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'required|string|email|max:255',
            ];
        }

        // Merge guest rules, if any, with normal validation rules.
        if ($request->files->all()) {
            $this->validate($request, array_merge($guest_rules ?? [], [
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|string|min:1',
            ]));
        } else {
            $this->validate($request, array_merge($guest_rules ?? [], [
                'commentable_type' => 'required|string',
                'commentable_id' => 'required|string|min:1',
                'message' => 'required|string'
            ]));
        }

        $model = $request->commentable_type::findOrFail($request->commentable_id);

        $commentClass = config('comments.model');
        $comment = new $commentClass;

        if (config('comments.guest_commenting') == true) {
            $comment->guest_name = $request->guest_name;
            $comment->guest_email = $request->guest_email;
        } else {
            $comment->commenter()->associate(auth()->user());
        }
        $comment->commentable()->associate($model);
        if ($request->files->all() && !$request->message) {
            $comment->comment = '';
        } else {
            $comment->comment = PostController::nofollow($request->message);
        }

        if ($request->anon) {
            $str = url()->previous();
            preg_match_all('!\d+!', $str, $matches);
            $post = Post::with('comments')->find($matches[0][0]);
            $prevComment = $post->comments()
                ->where('commenter_id', '=', auth()->id())
                ->where('anonimous', '!=', 0)
                ->first();

            if (!$prevComment) {
                $faker = Factory::create('ru_RU'); // create Russian
                $name = $faker->firstNameFemale . ' ' . $faker->middleName('female');
            } else {
                $name = $prevComment->fake_name;
            }

            $comment->anonimous = true;
            $comment->fake_name = $name;
        }
        $comment->approved = !config('comments.approval_required');
        $comment->save();
        $comment_images = collect();
        if ($request->files->all()) {
            $images = $request->files->all()['files'];
            $i = 1;
            foreach ($images as $image) {
                $fileName = $i . time() . '.' . $image->getClientOriginalExtension();
                $destination_path = 'public/images/comments';
                $img = Image::make($image->getRealPath());
                $img->resize(650, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->stream(); // <-- Key point

                Storage::disk('local')->put($destination_path . '/' . $fileName, $img, 'public');
                // Start order from 1 instead of 0
                $commentImg = new \App\CommentImage;
                $commentImg->path = $fileName;
                $commentImg->comment_id = $comment->id;
                $commentImg->comment()->associate($comment);
                $commentImg->save();
                $i++;
                $comment_images->push($commentImg);
            }
        }

        broadcast(new CommentCreated($comment))->toOthers();

//      return redirect()->to(url()->previous() . '#comment-' . $comment->id);
        return response()->json(array('comment' => $comment, 'images' => $comment_images));
    }

    /**
     * Updates the message of the comment.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('edit-comment', $comment);

        $this->validate($request, [
            'message' => 'required|string'
        ]);

        $comment->update([
            'comment' => $request->message
        ]);

        return response()->json($comment->comment);
    }

    /**
     * Deletes a comment.
     * @param Comment $comment
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Exception
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete-comment', $comment);

        $comment->delete();

        return '1';
    }

    /**
     * Creates a reply "comment" to a comment.
     * @param Request $request
     * @param Comment $comment
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reply(Request $request, Comment $comment)
    {
        $this->authorize('reply-to-comment', $comment);

        if (!$request->files->all()) {
            $this->validate($request, [
                'message' => 'required|string'
            ]);
        }

        $commentClass = config('comments.model');
        $reply = new $commentClass;
        $reply->commenter()->associate(auth()->user());
        $reply->commentable()->associate($comment->commentable);
        $reply->parent()->associate($comment);
        if ($request->files->all() && !$request->message) {
            $reply->comment = '';
        } else {
            $reply->comment = PostController::nofollow($request->message);
        }
        $reply->approved = !config('comments.approval_required');
        $reply->save();
        $reply_images = collect();

        if ($request->files->all()) {
            $images = $request->files->all()['files'];
            $i = 1;
            foreach ($images as $image) {
                $fileName = $i . time() . '.' . $image->getClientOriginalExtension();
                $destination_path = 'public/images/comments';
                $img = Image::make($image->getRealPath());
                $img->resize(650, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->stream(); // <-- Key point

                Storage::disk('local')->put($destination_path . '/' . $fileName, $img, 'public');
                // Start order from 1 instead of 0
                $commentImg = new \App\CommentImage;
                $commentImg->path = $fileName;
                $commentImg->comment_id = $reply->id;
                $commentImg->comment()->associate($reply);
                $commentImg->save();
                $i++;
                $reply_images->push($commentImg);
            }
        }

        broadcast(new CommentCreated($reply))->toOthers();

        return response()->json(array('comment' => $reply, 'images' => $reply_images));
    }

    /*public function like(Request $request)
    {
        Auth::user()->like($request->post('comment_id'), Comment::class);
        echo 'dislike';
    }

    public function dislike(Request $request)
    {
        Auth::user()->unlike($request->post('comment_id'), Comment::class);
        echo 'like';
    }*/

    public function upvote(Request $request)
    {
        Auth::user()->bookmark($request->post('comment_id'), Comment::class);
        echo 'downvote';
    }

    public function downvote(Request $request)
    {
        Auth::user()->unbookmark($request->post('comment_id'), Comment::class);
        echo 'upvote';
    }

}
