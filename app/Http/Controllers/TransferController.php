<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Account;
use App\Transfer;


class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $account = new Account();
        $account_number = $account->getAccountNumber();
        $transfers = new Transfer();
        $inbound = $transfers->getInbound($account_number);
        $outbound = $transfers->getOutbound($account_number);
        $balance = $transfers->getBalance($inbound,$outbound);
        return view('transfer',['account_number' => $account_number, 'inbound' => $inbound,
        'outbound' => $outbound, 'balance' => $balance]);
    }

    public function transfer(Request $request){
        $request->validate([
            'amount' => 'required|numeric',
            'account' => 'required|integer|max:9999999999999999|min:1000000000000000',
        ]);
        $amount = $request->input('amount'); 
        $account = $request->input('account');
        $transfers = new Transfer();
        $accountobj = new Account();
        $amount = $transfers->convertInputToInt($amount);
        $from = $accountobj->getAccountNumber();
        if ($transfers->checkBalance($amount)){
            if($accountobj->checkAccount($account)){
                $transfers->makeTransfer($from,$account,$amount);
                return redirect('/home');
            }
            else{
                return view('transfer',['balance' => 0, 'er' => "Account doesn't exist."]);
            }
        }
        else 
        {
            return view('transfer', ['balance' => 0, 'er' => "Insuficient balance."]);
        }
    }

}
