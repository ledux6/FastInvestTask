<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Account;
use App\Transfer;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $account = new Account();
        $account->checkIfNew();
        $account_number = $account->getAccountNumber();
        $transfers = new Transfer();
        $inbound = $transfers->getInbound($account_number);
        $outbound = $transfers->getOutbound($account_number);
        $balance = $transfers->getBalance($inbound,$outbound);
        return view('home',['account_number' => $account_number, 'inbound' => $inbound,
        'outbound' => $outbound, 'balance' => $balance]);
    }
}
