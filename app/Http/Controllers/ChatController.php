<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\PrivateMessageSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ChatController extends Controller
{
    
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
    
    public function getMessages($id,Request $request)
    {
        $result = DB::select(
                    "SELECT `messages`.* "
                    . " , `users`.`name` as user_name"
                    . " , `users`.`avatar` as user_avatar"                    
                    . " FROM `messages` "
                    . " LEFT JOIN `users` ON `users`.`id` = `messages`.`user_id`"
                    . " WHERE `messages`.`status` = 1"
                    . " AND (`messages`.`user_id` = ".Auth::user()->id." AND `messages`.`receiver_id` = ".$id.")"
                    . " OR (`messages`.`user_id` = ".$id." AND `messages`.`receiver_id` = ".Auth::user()->id.")"
                    //. " LIMIT ".$limit." OFFSET ".$offset
                );
        
         return json_encode($result);
    }
    
     public function getDialogs(Request $request)
    {
        $ids = $request->post('ids') ? $request->post('ids') : [];
        $messages = [];
        $allMessages = Auth::user()->allMessages()->get();
        
        foreach ($allMessages as $message) {
            array_push($messages, $message->receiver_id);
            array_push($messages, $message->user_id);
        }
        $result = array_unique($messages);

        if (($key = array_search(Auth::id(), $result)) !== false) {
            unset($result[$key]);
        }
        

        $users = User::with('messages')
            ->whereIn('id', $result)
            ->whereNotIn('id', $ids)
            ->limit(10)->get();

        $allMessages = collect();
        
        foreach ($users as $user) {
            $user->lastMessage = $user->lastMessage();
            $user->unreads = $user->unreadMessagesBy(Auth::id());
            $allMessages->push($user->lastMessage());
        }
        $allMessages = $allMessages->sortByDesc('created_at');
        $newUsers = collect();

        foreach ($allMessages as $message) {
            for ($i = 0; $i < $users->count(); $i++) {
                if ($message == $users[$i]->lastMessage) {
                    $newUsers->push($users[$i]);
                }
            }
        }
        
        
        return response()->json($newUsers);
        /*if (!$request->ajax()) {
            return view('dialogs', ['users' => $newUsers]);
        } else {
            return $newUsers;
        }*/
    }

    
    
    public function index($filter=null,$name){
        $user = User::where('name', $name)->first();
        Auth::user()->markMessages($user->id);
        
        return view('chat.index', compact('user'));
    }
    
    public function sendMessage(Request $request, User $user){
        $message = new Message();

        $message->user_id = request()->user()->id;
        $message->receiver_id = $user->id;
        $message->message = $request->all('message')['message'];
        /*if (!$request->files->all()) {
            $message->message = $request->all('message')['message'];
        } elseif ($request->files->all() && !$request->all('message')['message']) {
            $message->message = '';
        }*/
        $message->read_at = null;
        $message->save();
        
        $result = DB::select(
                "SELECT `messages`.* "
                . " , `users`.`name` as user_name"
                . " , `users`.`avatar` as user_avatar"                    
                . " FROM `messages` "
                . " LEFT JOIN `users` ON `users`.`id` = `messages`.`user_id`"
                . " WHERE `messages`.`status` = 1"
                . " AND `messages`.`id` = ".$message->id
                . " LIMIT 1"                
            );
        

        /*$message->user()->associate($user);

        $comment_images = collect();

        if ($request->files->all()){
            $images = $request->files->all()['files'];
            $i = 1;
            foreach ($images as $image) {
                $fileName = $i . time() . '.' . $image->getClientOriginalExtension();
                $destination_path = 'public/images/chat';
                $img = Image::make($image->getRealPath());
                $img->resize(650, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->stream(); // <-- Key point

                Storage::disk('local')->put($destination_path . '/' . $fileName, $img, 'public');
                // Start order from 1 instead of 0
                $messageImg = new \App\MessageImage;
                $messageImg->path = $fileName;
                $messageImg->message_id = $message->id;
                $messageImg->message()->associate($message);
                $messageImg->save();
                $i++;
                $comment_images->push($messageImg);
            }
        }*/

        //broadcast(new PrivateMessageSent($message->load('user')))->toOthers();// TODO

        return response()->json(array('message' => $result[0], 'status' => 'success'));
        //return response()->json(array('message' => $message, 'images' => $comment_images));
    }
    
    /*public function getMessages(User $user)
    {
        $privateCommunication = Message::with('user')
            ->with('images')
            ->where(['user_id' => auth()->id(), 'receiver_id' => $user->id])
            ->orWhere(function ($query) use ($user) {
                $query->where(['user_id' => $user->id, 'receiver_id' => auth()->id()]);
            })
            ->get();
        return $privateCommunication;
    }*/

    
}
