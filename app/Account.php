<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class Account extends Model
{
    public function checkIfNew(){
        $id = Auth::user()->id;
        $user = DB::table('accounts')->where('user_id', $id)->get();
        if(!$user->first()){
            $this->createAccount($id);
        }
    }

    private function createAccount($id)
    {
        $account_number = rand(1000000000000000,9999999999999999);
        DB::table('accounts')->insert(
            ['account_number' => $account_number, 'user_id' => $id, "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now()]
        );
        $this->creditAccount($account_number);
    }

    private function creditAccount($account_number)
    {
        $amount = 100000;
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

    public function checkAccount(int $account){
        $account_number = $this->getAccountNumber();
        if ($account_number === $account){
            return false;
        }
        $account = DB::table('accounts')->where('account_number', $account)->get();
        if(!$account->first()){
            return false;
        }
        return true;
    }
}
