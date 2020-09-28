<?php

namespace App\Http\Controllers;

use App\Tag;
use App\User;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use const http\Client\Curl\AUTH_ANY;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function registered()
    {
        $users = User::orderBy('created_at', 'DESC')->get();

        return view('users', compact('users'));
    }

    public function profile($name)
    {
        $user = User::withCount('posts')->with('posts')->where('name', $name)->first();
        $favorites = $user->favorites(Post::class)->count();
        $followings = $user->followings()->count();
        $followers = $user->followers()->count();

        return view('profile', [
            'user' => $user,
            'favorites' => $favorites,
            'followers' => $followers,
            'followings' => $followings
        ]);
    }

    public function addAvatar(Request $request)
    {
        $user = Auth::user();
        if ($request->hasFile('avatar')) {
            $image = $request->file('avatar');
            $fileName = time() . '.' . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->stream(); // <-- Key point
            Storage::disk('local')->put('public/images/avatars' . '/' . $fileName, $img, 'public');


            $user->avatar = $fileName;
            $user->save();

        }

        return redirect()->route('profile', Auth::user()->name);
    }

    public function deleteAvatar()
    {
        Auth::user()->avatar = 'default.jpg';
        Auth::user()->save();
        return redirect()->route('profile', Auth::user()->name);
    }

    public function follow(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }

        Auth::user()->follow($request->post('users_id'), User::class);
        echo 'unfollow';
    }

    public function unfollow(Request $request)
    {
        if (!$request->ajax()) {
            abort(403);
        }

        Auth::user()->unfollow($request->post('users_id'), User::class);
        echo 'follow';
    }

    public function posts($name, Request $request)
    {
        $ids = $request->post('ids') ? $request->post('ids') : [];
        $user = User::withCount('posts', 'followings', 'followers')->where('name', $name)->first();
        $posts = $user->posts()
            ->where('approved', true)
            ->with('images', 'user')
            ->withCount('comments', 'likers', 'favoriters', 'bookmarkers')
            ->whereNotIn('posts.id', $ids)
            ->orderBy('created_at', 'DESC')
            ->limit(6)
            ->get();
        $posts = PostController::addActivities($posts);

        if (!$request->ajax()) {
            $followings = $user->followings()->count();
            $followers = $user->followers()->count();
            $favorites = $posts->count();

            return view('user-parts.posts', [
                'user' => $user,
                'favorites' => $favorites,
                'followers' => $followers,
                'followings' => $followings,
                'posts' => $posts
            ]);
        } else {
            return $posts;
        }
    }

    public function follows(Request $request, $name)
    {
        $user = User::withCount('posts', 'followings', 'followers')->where('name', $name)->first();
        $ids = $request->post('ids') ? $request->post('ids') : [];
        $ids = array_unique($ids);
        $tags = $user->followings(Tag::class)->withCount('posts', 'followers')
            ->whereNotIn('id', $ids)
            ->limit(16)
            ->orderBy('posts_count', 'DESC')->get();
        $followings = $user->followings_count;
        $followers = $user->followers_count;
        if (!$request->ajax()) {
            return view('user-parts.follows', compact('user', 'tags', 'followings', 'followers'));
        } else {
            TagController::loadTags($tags);
        }
    }

    public function followsUsers(Request $request, $name)
    {
        $ids = $request->post('ids') ? $request->post('ids') : [];
        $ids = array_unique($ids);

        $user = User::withCount('posts', 'followings', 'followers')->where('name', $name)->first();
        $follows = $user->followings()
            ->whereNotIn('id', $ids)
            ->limit(16)
            ->get();
        if (!$request->ajax()) {
            return view('user-parts.followingsUsers', compact('user', 'follows'));
        } else {
            $this->loadUsers($follows);
        }
    }

    public function followers(Request $request, $name)
    {
        $ids = $request->post('ids') ? $request->post('ids') : [];
        $ids = array_unique($ids);
        $user = User::withCount('posts', 'followings', 'followers')->where('name', $name)->first();
        $followers = $user->followers()->whereNotIn('id', $ids)->limit(20)->get();

        $followings = $user->followings_count;
        if (!$request->ajax()) {
            return view('user-parts.followers', compact('user', 'followers', 'followings', 'followers'));
        } else {
            $this->loadUsers($followers);
        }

    }

    public static function loadUsers($users)
    {
        $output = '';
        foreach ($users as $user) {
            $output .= '

            <div class="followers-el" >
                <a href = "' . route('profile', $user->name) .
                '" >
                    <div class="profile_user" >
                        <div class="profile_user_img" style = "background-image: url(/storage/images/avatars/' . $user->avatar . '" ></div >
                        <div class="profile_user_name" >
                            <p >' . $user->name . '
            </p >
                        </div >
                    </div >
                </a >';
            if (Auth::check()) {

            $output .= '
        
                    <div onclick = "ajaxAction($(this), \'users\')" class="follow"';
            if (Auth::user()->isFollowing($user)) {
                $output .= ' hidden ';
            }

            $output .=
                'data - id = "' . $user->id . '" >
                        <div class="btn_subscribe subscribe profile_user_btn btn-green" >
                            <a class="" > Подписаться</a >
                        </div >
                    </div >

                    <div onclick = "ajaxAction($(this), \'users\')" class="unfollow"';
            if (!Auth::user()->isFollowing($user)) {
                $output .= ' hidden ';
            }
            $output .= 'data - id = "' . $user->id . '" >
                        <div class="btn_unscribe profile_user_btn btn-green" >
                            <a class="" > Отписаться</a >
                        </div >
                    </div >';

            }
            $output .= '</div >';
        }

        echo $output;

    }

    public function settings()
    {
        return view('settings', ['user' => Auth::user()]);
    }

    public function changeLogin(Request $request) {
        $request->validate(['name' => 'required|string|max:255|min:3|unique:users|regex:/^[a-z](-?[a-z0-9]+)*-?\.?(-?[a-z0-9]+)+$/mi']);
        Auth::user()->name = $request->post('name');
        Auth::user()->save();
        session()->flash('notify', 'Вы успешно изменили ваш логин');
        return redirect()->back();
    }

    public function changeEmail(Request $request) {
        $request->validate(['email' => 'required|string|max:255|unique:users']);
        if ((Auth::user()->email == $request->post('old')) && Hash::check($request->post('password'), Auth::user()->getAuthPassword())) {
            Auth::user()->email = $request->post('email');
            Auth::user()->save();
            session()->flash('notify', 'Вы успешно изменили ваш email');
        } else {
            session()->flash('notify', 'error');
        }
        return redirect()->back();
    }

    public function changePassword(Request $request)
    {
        if (Hash::check($request->post('old'), Auth::user()->getAuthPassword())) {
            $request->validate(['password' => 'required|string|min:8|confirmed']);
            Auth::user()->password = Hash::make($request->post('password'));
            Auth::user()->save();
            session()->flash('notify', 'Вы успешно изменили ваш пароль');
        } else {
            session()->flash('notify', 'error');
        }

        return redirect()->back();
    }

    public function feedbacks($name)
    {
        $user = User::with('feedbacks')->where('name', $name)->first();
        return view('user-parts.feedback', [
            'user' => $user
        ]);
    }

    public function transactions($name)
    {
        if (auth()->user()->gender == User::GENDER_MALE) {
            $user = User::with('transactions')->where('name', $name)->first();
            return view('supports', [
                'user' => $user
            ]);
        }
        else {
            return redirect()->back();
        }
    }

    public function saveAbout(Request $request)
    {
        $user = auth()->user();
        $user->about = $request->input('about_user');
        $user->save();
        return response()->json(['success' => true], 200);
    }

    public function saveLink(Request $request)
    {
        $user = auth()->user();
        $user->link = $request->input('link');
        $user->save();
        return response()->json(['success' => true], 200);
    }

    public function saveContacts(Request $request)
    {
        $user = auth()->user();
        if ($request->input('whatsapp'))
            $user->whatsapp = $request->input('whatsapp');
        if ($request->input('telegram'))
            $user->telegram = $request->input('telegram');
        if ($request->input('viber'))
            $user->viber = $request->input('viber');
        if ($request->input('skype'))
            $user->skype = $request->input('skype');
        $user->save();
        return response()->json(['success' => true], 200);
    }

    public function saveServices(Request $request)
    {
        $user = auth()->user();
        $user->services = $request->input('service');
        $user->save();
        return response()->json(['success' => true], 200);
    }

    public function saveTags(Request $request)
    {
        $user = auth()->user();
        $user->tags()->attach($request->input('tags'));
        return response()->json(['success' => true], 200);
    }
}
