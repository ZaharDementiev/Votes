<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\PostRequest;
use App\Notifications\PostToApprove;
use App\Option;
use App\Post;
use App\DB;
use App\Tag;
use App\User;
use App\Vote;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;
use App\Events\NewPost;
use Illuminate\Support\Str;

//use Illuminate\Support\Facades\DB as database;
use Artesaos\SEOTools\Facades\SEOTools as SEO;

use phpDocumentor\Reflection\Types\Collection;
use function Sodium\add;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param $filter
     * @param string $time
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        Carbon::setLocale('ru');
        define('LIMIT_OF_POSTS', 10);
    }

    public function all(Request $request, $filter, $time = 'week')
    {        
        switch ($filter) {
            case 'live':
                $order_by = 'created_at';
                $time = 'Century';
                break;
            case 'popular':
                $order_by = 'likers_count';
                break;
            case 'discussed';
                $order_by = 'comments_count';
                break;
            default:
                $order_by = 'created_at';
        }
        $ids = $request->post('ids') ? $request->post('ids') : [];
        $sub = 'sub' . $time;
        $posts = Post::with('images', 'tags', 'options', 'user')
            ->where('approved', true)
            ->withCount('likers', 'comments', 'favoriters', 'bookmarkers')
            ->whereNotIn('id', $ids)
            ->where('created_at', '>', Carbon::now()->$sub())
            ->orderBy($order_by, 'DESC')
            ->limit(LIMIT_OF_POSTS)
            ->get();

        $posts = $this->addActivities($posts);
        
        

        if (!$request->ajax()) {
            $last_modified = $posts->first() ? $posts->first()->updated_at->toDateTimeString() : null;
            return response()
                ->view('post.index', compact('posts', 'filter', 'time'));
            } else {
            return $posts;
        }
    }

    public function popular(Request $request, $time = 'day')
    {
        $filter = 'popular';
        return $this->all($request, $filter, $time);
    }
    public function discussed(Request $request, $time = 'day')
    {        
        $filter = 'discussed';
        return $this->all($request, $filter, $time);
    }
    public function live(Request $request, $time = 'day')
    {
        $filter = 'live';
        return $this->all($request, $filter, $time);
    }

    public function favorites(Request $request, $filter = null)
    {
        $order = 'created_at';
        if ($filter) {
            $order = $filter . '.' . $order;
        }

        $ids = $request->post('ids') ? $request->post('ids') : [];
        $posts = Auth::user()->favorites(Post::class)->with('images', 'tags', 'options', 'user')
            ->whereNotIn('id', $ids)
            ->where('approved', true)
            ->withCount('likers', 'comments', 'favoriters', 'bookmarkers')
            ->orderBy($order, 'DESC')
            ->limit(LIMIT_OF_POSTS)
            ->get();
        $posts = $this->addActivities($posts);

        if ($filter) {
            foreach ($posts as $post) {
                $post->favorited_at = $post->pivot->created_at;
            }
        }

        if (!$request->ajax()) {
            $last_modified = $posts->first() ? $posts->first()->updated_at : null;

            return response()
                    ->view('post.favorites', ['posts' => $posts])
                ->header('Last-Modified: ' . $last_modified, true, 304);
        } else {
            return $posts;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addPost()
    {
        $tags = Tag::get();
        return view('post/add', ['tags' => $tags]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        $tags = $request->input('tags');

        if (count($tags) > 4) {
            new ValidationException('Вы можете выбрать только 4 тега');
        }

        $post = new Post();
        $post->title = $request->post('title');
        if (preg_match('~(http*(\.com)|(\.ru)|(\.ua)|(\.org))|(.*(\.com)|(\.org)|(\.ru)|(\.ua))~',
            $request->post('description'))
            ) {
            $post->approved = false;
        }
        $post->description = $this->nofollow($request->post('description'));

        $post->type = $request->post('options')[0] ? 'vote' : 'post';
        if ((preg_match('~.*?\?.*?~', $post->title) || preg_match('~.*?\?.*?~', $post->description)) && $post->type != 'vote') {
            $post->type = 'question';
        }
        $post->is_anonimous = $request->has('anon') ? true : false;
        $post->user_id = Auth::id();
        $post->user()->associate(Auth::user());

        $post->save();
        $post->slug = Str::slug($post->title . '-' . $post->id);

        $post->save();

        $post->tags()->sync($tags);
        $tags = Tag::whereIn('id', $tags)->get();
        $tags_followers = [];

        foreach($tags as $tag) {
            $tags_followers = array_merge($tags_followers, $tag->followers()->where('id' ,'>' ,0)->pluck('id')->toArray());
        }
        $result = array_merge($tags_followers, Auth::user()->followers()->where('id' ,'>' ,0)->pluck('id')->toArray());
        $result = array_unique($result);

        if (($key = array_search(Auth::id(), $result)) !== false) {
            unset($result[$key]);
        }

        broadcast(new NewPost($result))->toOthers();

        if ($request->has('files')) {

            $images = $request->files->all()['files'];
            $i=1;
            foreach ($images as $image) {
                $fileName = $i . time() . '.' . $image->getClientOriginalExtension();
                $destination_path = 'public/images/posts';
                $img = Image::make($image->getRealPath());
                $img->resize(650, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->stream(); // <-- Key point

                Storage::disk('local')->put($destination_path . '/' . $fileName, $img, 'public');
                // Start order from 1 instead of 0
                $postImage = new \App\Image;
                $postImage->path = $fileName;
                $postImage->post_id = $post->id;
                $postImage->post()->associate($post);
                $postImage->save();
                $i++;
            }
        }

        if ($post->type == 'vote') {
            foreach ($request->post('options') as $name) {
                $option = new Option();
                $option->name = $name;
                $option->post_id = $post->id;
                $option->post()->associate($post);
                $option->save();
            }
        }
        Auth::user()->favorite($post, Post::class);

        return response()->json($post->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug, Request $request)
    {
        $ids = $request->post('ids') ? $request->post('ids') : [];

        $post = Post::with('images', 'tags', 'options', 'user', 'votes')
            ->withCount('likers', 'comments', 'favoriters', 'bookmarkers')
            ->where('slug', $slug)->first();
        if (!$post) {
            abort(404);
        }

        $comments = $post->comments()
            ->where('child_id', null)
            ->whereNotIn('id', $ids)
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->with('allChildren', 'images')
            ->withCount('likers', 'bookmarkers')
            ->get();


        if (auth()->check()) {
            $comments = $this->commentActivities($comments);
        }

        if ($request->ajax()) {
            return $comments;
        }

//        $comments = $comments->sortBy('id');
//        $result = collect();
//        foreach ($comments as $comment) {
//            $result->push($comment);
//        }
//        $comments = $result;

        $desc = strlen($post->description) > 220 ? mb_substr($post->description, 0, 240) : $post->description;
        SEO::setTitle($post->title);
        SEO::setDescription($desc);

        SEO::opengraph()->setTitle($post->title);
        SEO::opengraph()->setDescription($desc);
        SEO::opengraph()->setUrl(url()->current());
        foreach ($post->images() as $image) {
            SEO::opengraph()->addImage(asset('storage/images/posts') . '/' . $image->path);
        }


        $LastModified_unix = $comments->first() ? $comments->first()->updated_at->timestamp : $post->updated_at->timestamp;
        $LastModified = gmdate("D, d M Y H:i:s \G\M\T", $LastModified_unix);
        $IfModifiedSince = false;
        if (isset($_ENV['HTTP_IF_MODIFIED_SINCE']))
            $IfModifiedSince = strtotime(substr($_ENV['HTTP_IF_MODIFIED_SINCE'], 5));
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']))
            $IfModifiedSince = strtotime(substr($_SERVER['HTTP_IF_MODIFIED_SINCE'], 5));
        if ($IfModifiedSince && $IfModifiedSince >= $LastModified_unix) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
            exit;
        }
        if ($IfModifiedSince && $IfModifiedSince < $LastModified_unix) {
            return response()
                ->view('post.show', [
                    'post' => $post, 'tags' => $post->tags,
                    'images' => $post->images, 'user' => $post->user,
                    'options' => $post->options()->get(),
                    'comments' => $comments
                ])->header('Last-Modified', $LastModified);
        }

        return response()
        ->view('post.show', [
            'post' => $post, 'tags' => $post->tags,
            'images' => $post->images, 'user' => $post->user,
            'options' => $post->options()->get(),
            'comments' => $comments
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function edit(string $slug)
    {
        if (auth()->user()->role != 'admin' && auth()->user()->role != 'moderator') {
            abort(404);
        }
        $tags = Tag::all();
        $post = Post::with('tags')->where('slug', $slug)->first();

        return view('post.edit', compact('post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if (auth()->user()->role != 'admin' && auth()->user()->role != 'moderator') {
            abort(404);
        }

        if ($post->title == $request->post('title')) {
            $request->request->remove('title');
        }

        $request->validate([
            'title' => 'sometimes|required|unique:posts|max:255',
            'description' => 'required',
        ]);
        if (preg_match('~(http*(\.com)|(\.ru)|(\.ua)|(\.org))|(.*(\.com)|(\.org)|(\.ru)|(\.ua))~',
            $request->post('description'))
        ) {
            $post->approved = false;
        }

        $post->title = $request->post('title') ?? $post->title;
        $post->description = $request->post('description');
        $post->slug = Str::slug($post->title . '-' . $post->id);

        $post->save();

        return redirect()->to('/posts/' . $post->slug);
    }

    public function unapproved()
    {
        if (!Gate::allows('approve-post')) {
            abort(403);
        }
        $posts = Post::where('approved', false)->get();
        return view('post.unapproved', compact('posts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Post $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($post_id)
    {
        Post::destroy($post_id);
        return redirect()->route('live');
    }



    public function getOptions($post_id)
    {
        $results = [];
        $post = Post::with('options')->find($post_id);
        $options = $post->options;
        foreach ($options as $option) {
            $result = [];
            $result["name"] = $option->name;
            $result["id"] = $option->id;
            $result["votesCount"] = $option->countVotes();
            $result["percent"] = (int)$option->percent();
            array_push($results, $result);
        }

        return json_encode($results);
    }

    /**
     * Make a Vote
     *
     * @param Post $post
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */


    public function vote(Request $request)
    {
//        $post = Post::with('options')->find($request->post('post_id'));
//        $options = $post->options;
//
        if (!$request->ajax()) {
            abort(403);
        }
        $post = Post::with('options')->find($request->post('post_id'));
        $options = $post->options()->get();
        try {
            $option = $post->options()->find($request->post('option_id'));
            $vote = $this->resolveVoter($request)
                ->post($post)
                ->vote($option);
            $option->updateTotalVotes();

            if ($vote) {
                $results = [];

                foreach ($options as $option) {
                    $result["name"] = $option->name;
                    $result["id"] = $option->id;
                    $result["votesCount"] = $option->countVotes();
                    $result["percent"] = (int)$option->percent();
                    array_push($results, $result);
                }

                return json_encode($results);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Get the instance of the voter
     *
     * @param Request $request
     * @param Post $post
     * @return Guest|mixed
     */
    protected function resolveVoter(Request $request)
    {
        return $request->user();
    }

    public function like(Request $request)
    {
        $this->checkAjax($request);
        Auth::user()->like($request->post('post_id'), Post::class);
        echo 'dislike';
    }

    public function dislike(Request $request)
    {
        $this->checkAjax($request);
        Auth::user()->unlike($request->post('post_id'), Post::class);
        echo 'like';
    }

    public function upvote(Request $request)
    {
        $this->checkAjax($request);
        Auth::user()->bookmark($request->post('post_id'), Post::class);
        echo 'downvote';
    }

    public function downvote(Request $request)
    {
        $this->checkAjax($request);
        Auth::user()->unbookmark($request->post('post_id'), Post::class);
        echo 'upvote';
    }

    public function favorite(Request $request)
    {
        $this->checkAjax($request);
        Auth::user()->favorite($request->post('post_id'), Post::class);
        echo 'unfavorite';
    }

    public function unfavorite(Request $request)
    {
        $this->checkAjax($request);
        Auth::user()->unfavorite($request->post('post_id'), Post::class);
        echo 'favorite';
    }


    private function checkAjax(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        if (!Auth::user()) {
            abort(403);
        }
        return true;
    }

    public static function addActivities($posts)
    {
        if (Auth::check()) {
            $user = Auth::user();
            foreach ($posts as $post) {
                $post->hasLiked = $user->hasLiked($post->id, Post::class);
                $post->hasDisliked = $user->hasBookmarked($post->id, Post::class);
                $post->hasFavorited = $user->hasFavorited($post->id, Post::class);
            }
        } else {
            foreach ($posts as $post) {
                $post->hasLiked = false;
                $post->hasDisliked = false;
                $post->hasFavorited = false;
            }
        }
        return $posts;
    }

    public static function nofollow($data, $skip = null)
    {
        $d= preg_replace_callback('~href=(["\'])([a-z0-9]++://(?![a-z0-9\.]*?lsecrets\.ru).*?)\1~', function ($matches) {
            return "$matches[0] rel='nofollow'";
        }, $data);
        $result= preg_replace_callback('~<a\s.*?href=\"(?!.*?lsecrets)([^\"]*)\"\s.*?>(.*)<\/a>~', function ($matches) {
            return "<noindex>$matches[0]</noindex>>";
        }, $d);

        return $result;
    }

    public function commentActivities($comments)
    {
        foreach ($comments as $comment) {
            $comment->liked = $comment->isLikedBy(auth()->user()) ? 1 : 0;
            $comment->disliked = $comment->isBookmarkedBy(auth()->user()) ? 1 : 0;
            if ($comment->allChildren->count() > 0) {
                $comment->allChildren = $this->commentActivities($comment->allChildren);
            }
        }
        return $comments;
    }

    public function approve(Post $post)
    {
        $post->approved = true;
        $post->save();

        return redirect()->back();
    }

}
