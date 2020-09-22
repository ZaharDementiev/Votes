<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\SearchEvent;
use App\Notifications\NewPost;
use App\Post;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\Array_;

class HomeController extends Controller
{

    public function sitemap()
    {
        $posts = Post::where('approved', true)
        ->get();
        $tags = Tag::get();

        return view('sitemap')->with(compact('posts', 'tags'));
    }

    public function ms()
    {
        $comments = Comment::all();
        foreach ($comments as $comment) {
            if ($comment->parent()->first()) {
                $comment->reply = true;
                $comment->save();
            }
        }

//        $posts = Post::get();
//        $tags = Tag::get();
//        foreach ($posts as $post) {
//            $post->slug = Str::slug($post->title . '-' . $post->id);
//            $post->save();
//        }
//
//        foreach ($tags as $tag) {
//            $tag->slug = Str::slug($tag->title);
//            $tag->save();
//        }
//        DB::table('tags')->where('id', '>', 0)->delete();
//        $text = file_get_contents('1.txt');
//        $text = explode("\n", $text);
//        $tags = [];
//
//        foreach ($text as $item) {
//            $tags = array_merge($tags, explode(",", $item));
//        }
//
//        foreach ($tags as $tag) {
//            if ($tag != '') {
//                $tag = ucfirst($tag);
//                DB::table('tags')->insert(['name' => $tag]);
//            }
//        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        define('LIMIT_OF_POSTS', 10);
//        $this->middleware(['auth','verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $nots = Auth::user()->unreadNotifications()->where('type', '=', NewPost::class)->get();
        if (!$nots->isEmpty()) {
            Auth::user()->unreadNotifications()->where('type', '=', NewPost::class)->get()->markAsRead();
        }
        $ids = $request->post('ids') ? $request->post('ids') : [];
        $tags = Auth::user()->followings('App\Tag')->pluck('id')->toArray();
        $posts = Post::whereHas('tags', function ($q) use ($tags) {
            return $q->whereIn('tags.id', $tags);
        })
            ->withCount('likers', 'comments', 'favoriters', 'bookmarkers')
            ->where('approved', true)
            ->whereNotIn('posts.id', $ids)
            ->with('images', 'user')
            ->orderBy('created_at', 'DESC')
            ->limit(LIMIT_OF_POSTS)
            ->get();
        $posts = PostController::addActivities($posts);

        if (!$request->ajax()) {
            return view('home', compact('posts'));

        } else {
            return $posts;
        }

    }

    public function byUser(Request $request)
    {
        Auth::user()->unreadNotifications()->where('type', '=', NewPost::class)->get()->markAsRead();
        $ids = $request->post('ids') ? $request->post('ids') : [];
        $followings = Auth::user()->followings()->get();
        $users = [];
        foreach ($followings as $fol) {
            array_push($users, $fol->id);
        }
        $posts = Post::whereNotIn('posts.id', $ids)
            ->where('approved', true)
            ->whereIn('user_id', $users)
            ->withCount('likers', 'comments', 'favoriters', 'bookmarkers')
            ->with('images', 'user')
            ->orderBy('created_at', 'DESC')
            ->limit(LIMIT_OF_POSTS)
            ->get();
        $posts = PostController::addActivities($posts);


        if (!$request->ajax()) {
            return view('home', compact('posts'));

        } else {
            return $posts;
        }
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        $tags = Tag::where('name', 'like', '%' . $query . '%')
            ->limit(20)
            ->get();

        $posts = Post::where('title', 'like', '%' . $query . '%')
            ->where('approved', true)
            ->limit(10)
            ->get();

        //broadcast search results with Pusher channels
//        event(new SearchEvent($tags, $posts));

        return [$tags, $posts];
    }

    public function virts()
    {
        if (auth()->check())
            $women = User::where('gender', User::GENDER_FEMALE)->where('id', '!=', auth()->id())->get();
        else
            $women = User::where('gender', User::GENDER_FEMALE)->get();
        return view('virt', [
            'women' => $women
        ]);
    }

    public function sort(Request $request)
    {
        $women = null;
        $range = explode(';', $request->input('my_range'));

        if ($request->input('online') == 'on')
            $women = User::where('last_online_at', '>', Carbon::now()->subMinutes(5)->toDateTimeString())
                ->whereBetween()->get();

        dd($women);
    }
}
