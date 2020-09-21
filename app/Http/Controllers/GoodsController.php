<?php

namespace App\Http\Controllers;

use App\Goods;
use App\User;
use App\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\PrivateMessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GoodsController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Creates a new comment for given model.
     */
    
    
    public function index(Request $request){        
        return view('goods.index');
    }
    
    public function getData(Request $request){
        $results = DB::select(
                    "SELECT `goods`.* "
                    . " FROM `goods` "
                    . " WHERE `goods`.`user_id` = ".Auth::user()->id
                    . " AND `goods`.`status` = 1"
                );
        return response()->json($results);
    }
    
    public function loadImage(Request $request){
        if ($request->has('files')) {
            $images = $request->files->all()['files'];
            
            $i=1;
            foreach ($images as $image) {                
                $fileName = $i . time() . '.' . $image->getClientOriginalExtension();
                $destination_path = 'public/goods/images';
                $img = Image::make($image->getRealPath());
                $img->resize(650, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->stream(); // <-- Key point

                Storage::disk('local')->put($destination_path . '/' . $fileName, $img, 'public');
                // Start order from 1 instead of 0
                $goods = new Goods();
                $goods->user_id = Auth::user()->id;
                $goods->file = $fileName;
                $goods->type = 1;
                //dd($request->files->all());
                $goods->save();
            }
        }
        
        return '{"status":"success"}';
    }
}
