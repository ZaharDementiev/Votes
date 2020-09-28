<?php

namespace App;

use App\Traits\Voter;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Overtrue\LaravelFollow\Traits\CanBookmark;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanLike;
use Overtrue\LaravelFollow\Traits\CanFavorite;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use Laravelista\Comments\Commenter;


class User extends Authenticatable
{
    use Notifiable;
    use Voter;
    use CanFollow, CanLike, CanFavorite, CanBeFollowed, Commenter, CanBookmark;

    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 0;

    public static $BRONZE_MIN_AMOUNT = 1;
    public static $SILVER_MIN_AMOUNT = 1001;
    public static $GOLD_MIN_AMOUNT = 10001;
    public static $PLATINUM_MIN_AMOUNT = 50001;
    public static $BRILLIANT_MIN_AMOUNT = 100001;

    public static $ENTRY_TEXT_STATUS = 'Начальный';
    public static $BRONZE_TEXT_STATUS = 'Бронза';
    public static $SILVER_TEXT_STATUS = 'Серебро';
    public static $GOLD_TEXT_STATUS = 'Золото';
    public static $PLATINUM_TEXT_STATUS = 'Платина';
    public static $BRILLIANT_TEXT_STATUS = 'Бриллиант';

    public const STATUSES = ['ENTRY', 'BRONZE', 'SILVER', 'GOLD', 'PLATINUM', 'BRILLIANT'];

    protected $appends = ['online', 'age'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        "last_online_at" => "datetime",
        'services' => 'array',
    ];

    protected $dates = ['birth'];

    public function posts()
    {
       return $this->hasMany('App\Post');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function allMessages()
    {
        return Message::where('receiver_id', '=',  $this->id)->orWhere('user_id', '=', $this->id);
    }

    public function lastMessage()
    {
        return Message::where(['user_id' => auth()->id(), 'receiver_id' => $this->id])
            ->orWhere(function ($query){
                $query->where(['user_id' => $this->id, 'receiver_id' => auth()->id()]);
            })
            ->orderBy('id', 'DESC')
            ->first();
    }

    public function unreadMessages()
    {
        return Message::where(['receiver_id' => $this->id, 'read_at' => null])->count();
    }
    public function unreadMessagesBy($id)
    {
        return Message::where(['receiver_id' => $id, 'user_id' =>$this->id, 'read_at' => null])->count();
    }

    public function markMessages($id) : void
    {
        $messages = Message::where(['receiver_id' => $this->id, 'user_id' => $id, 'read_at' => null])->get();
        foreach ($messages as $message) {
            $message->read_at = Carbon::now()->toDateString();
            $message->save();
        }
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function incomes()
    {
        return $this->hasMany(Transaction::class, 'id', 'to_id');
    }

    // Суммирует эмаунт ($user->status)
//    public function getStatusAttribute()
//    {
//        $amount = $this->transactions()->sum('amount');
//        for ($i = 1; $i < count(self::STATUSES); $i++)
//        {
//            $staticAmount = sprintf('%s_MIN_AMOUNT', self::STATUSES[$i]);
//            $staticStatus = sprintf('%s_TEXT_STATUS', self::STATUSES[$i - 1]);
//            if ($amount <= self::$$staticAmount)
//                return self::$$staticStatus;
//        }
//    }

    public function getAgeAttribute()
    {
        return Carbon::now()->diffInYears($this->birth);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_user');
    }

    public function getOnlineAttribute() {
        return Carbon::now()->diffInMinutes($this->last_online_at) <= 5;
    }
}
