<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $this->checkIfNew();
        $account_number = $this->getAccountNumber();
        $inbound = $this->getInbound($account_number);
        $outbound = $this->getOutbound($account_number);
        $balance = $this->getBalance($inbound,$outbound);
        return view('home',['account_number' => $account_number, 'inbound' => $inbound,
        'outbound' => $outbound, 'balance' => $balance]);
    }

    public function checkIfNew()
    {
        $id = Auth::user()->id;
        $user = DB::table('accounts')->where('user_id', $id)->get();
        if(!$user->first()){
            $this->createAccount($id);
        }
    }

    public function createAccount($id)
    {
        $account_number = rand(1000000000000000,9999999999999999);
        DB::table('accounts')->insert(
            ['account_number' => $account_number, 'user_id' => $id, "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now()]
        );
        $this->creditAccount($account_number);
    }

    public function creditAccount($account_number)
    {
        $amount = 1000000;
        $from = 1111;
        DB::table('transfers')->insert(
            ['from' => $from, 'to' => $account_number, 'amount' => $amount,"created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now()]
        );
    }

    public function getAccountNumber(){

        $id = Auth::user()->id;
        $user = DB::table('accounts')->where('user_id', $id)->get('account_number');
        return $user->first()->account_number;
    }

    public function getInbound($account_number){
        return DB::table('transfers')->where('to', $account_number)->get();
    }

    public function getOutbound($account_number){
        return DB::table('transfers')->where('from', $account_number)->get();
    }

    public function getBalance($inbound, $outbound){
        $in_sum = 0;
        foreach($inbound as $in){
            $in_sum += $in->amount;
        }
        $out_sum = 0;
        foreach($outbound as $out){
            $out_sum += $out->amount;
        }
        return $in_sum - $out_sum;
    }
}
