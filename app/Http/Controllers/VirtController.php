<?php

namespace App\Http\Controllers;

use App\Events\CommentCreated;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Faker\Factory;

class VirtController extends Controller
{
    use ValidatesRequests, AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('web');

        /*if (config('comments.guest_commenting') == true) {
            $this->middleware('auth')->except('store');
        } else {
            $this->middleware('auth');
        }*/
    }

    /**
     * Creates a new comment for given model.
     */
    public function index($filter=null,Request $request)
    {
         return view('virt.index',['filter'=>$filter]);
    }
    
    public function getData(Request $request)
    {
        $page = $request->input('page') ?? 1 ;
        $limit = 20;
        $offset = ($page-1) * $limit;
        $filter = $request->input('filter');
        
        $result = DB::select(
                    "SELECT `users`.`name` "
                    . " , `users`.`avatar`"
                    . " , `users`.`last_online_at`"
                    . " FROM `users` WHERE `users`.`status` = 1"
                    . " AND `users`.`gender` = 2"
                    . ($filter == 'online' ? " AND `users`.`last_online_at` > '".date("Y-m-d H:i:s", time()-300)."'" : "")
                    . " ORDER BY `id` DESC"
                    . " LIMIT ".$limit." OFFSET ".$offset
                );
         return json_encode($result);
    }
}
