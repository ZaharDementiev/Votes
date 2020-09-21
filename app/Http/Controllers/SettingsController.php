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

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        return view('settings.index', ['user' => Auth::user()]);
    }
    
    public function changeLogin(Request $request) {
        $request->validate(['name' => 'required|string|max:255|min:3|unique:users|regex:/^[a-z](-?[a-z0-9]+)*-?\.?(-?[a-z0-9]+)+$/mi']);
        
        Auth::user()->name = $request->post('name');
        if(Auth::user()->save()){
            return '{"status":"success","message":"Вы успешно изменили Логин"}';
        }
        
        return '{"status":"error"}';
    }

    public function changeEmail(Request $request) {
        $request->validate(['email' => 'required|string|max:255|unique:users']);
        if ((Auth::user()->email == $request->post('old')) && Hash::check($request->post('password'), Auth::user()->getAuthPassword())) {
            Auth::user()->email = $request->post('email');
            Auth::user()->save();            
            return '{"status":"success","message":"Вы успешно изменили ваш email"}';
        } else {
            return '{"status":"error"}';
        }        
    }

    public function changePassword(Request $request)
    {
        if (Hash::check($request->post('old'), Auth::user()->getAuthPassword())) {
            $request->validate(['password' => 'required|string|min:8|confirmed']);
            Auth::user()->password = Hash::make($request->post('password'));
            Auth::user()->save();            
            return '{"status":"success","message":"Вы успешно изменили ваш пароль"}';
        } else {            
            return '{"status":"error"}';
        }
    }
    
    public function changePrices(Request $request)
    {
        $message = (int)($request->input('message')*100);
        $photo = (int)($request->input('photo')*100);
        $video = (int)($request->input('video')*100);
        
        Auth::user()->message_price = $message;
        Auth::user()->photo_price = $photo;
        Auth::user()->video_price = $video;
        Auth::user()->save();
        
    }
}
