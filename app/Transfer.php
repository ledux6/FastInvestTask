<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Transfer extends Model
{
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

    public function checkBalance($amount){
        $account = new Account();
        $account_number = $account->getAccountNumber();
        $inbound = $this->getInbound($account_number);
        $outbound = $this->getOutbound($account_number);
        $balance = $this->getBalance($inbound,$outbound);
        if ($balance >= $amount){
            return true;
        }
        else{
            return false;
        }
    }

    public function makeTransfer(int $from, int $to, int $amount){
        if($amount === 0){
            return;
        }
        DB::table('transfers')->insert(
            ['from' => $from, 'to' => $to, 'amount' => $amount,"created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now()]
        );
    }

    public function convertInputToInt($amount){
        $conversion = $amount * 100;
        $int = (int) $conversion;
        return $int;
    }
    
}
