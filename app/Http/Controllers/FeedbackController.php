<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Payment;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Transaction $transaction)
    {
        if (auth()->user()->gender == User::GENDER_MALE)
        {
            $feedback = new Feedback();
            $feedback->user_id = Auth::id();
            $feedback->text = $request->input('text');
            $feedback->points = $request->input('points');
            $feedback->positive = $request->input('positive');

            $feedback->payment_id = $transaction->id;
            $feedback->to_id = $transaction->to_id;
            $feedback->save();
        }
        return redirect()->back();
    }

    public function index(Transaction $transaction)
    {
        return view('user-parts.makeFeedback', [
            'transaction' => $transaction
        ]);
    }
}

