<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function make(Request $request)
    {
        $transaction = new Transaction();
        $transaction->user_id = Auth::id();
        $transaction->to_id = $request->to_id;
        $transaction->amount = $request->amount;
        $transaction->transaction_status = Transaction::TRANSACTION_WAIT;

        $transaction->save();
        //return redirect()->route('feedback', ['$transaction' => $transaction]);
        return redirect()->back();
    }

    public function transaction()
    {
        return view('user-parts.transaction');
    }
}
