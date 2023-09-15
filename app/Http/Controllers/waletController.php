<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\Walet;

class waletController extends Controller
{
   public function view_balance(){
    $balance = DB::table('walet')->where('user_id', auth()->user()->id)->select('amount')->get();
    if(!$balance->isEmpty()){
        return response()->json([
            'status'=>'success',
            'data'=>$balance
        ]);
    }else{
        return response()->json([
            'status'=>'fail',
            'data'=>'null'
        ]);
    }
   }

   public function get_logs(){
    $logs = DB::table('walet_log')
    ->leftjoin('walet', 'walet.walet_id','=', 'walet_log.walet_id')
    ->where('walet.user_id', auth()->user()->id)
    ->get();
    if($logs->isEmpty())
    return response()->json([
        'status'=>'success',
        'data'=>$logs
    ]);
   }

    public function check_walet($amount){
        $check = DB::table('walet')->where('user_id', auth()->user()->id)->select('amount','user_id')->first();
        if($check->amount >= $amount){
            return response()->json([
                'status'=>'success',
                'data'=>'You have sufficient balance'
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'data'=>"You don't have sufficient balance"
            ]);
        }
    }

    public function use_walet(Request $request){
        $request->validate([
            'amount'=>'required',
            'order_unique_id'=>'required'
        ]);
        $check = DB::table('walet')->where('user_id', auth()->user()->id)->select('amount','walet_id')->first();
        if($check->amount >= $amount){
            $data = array();
            $data = [
                'amount'=>$check->amount - $amount,
                'updated_at'=>Carbon::now(),
            ];
            $update = DB::table('walet')->where('user_id', auth()->user()->id)->update($data);
            $log = array();
            $log = [
                'order_unique_id'=>$request->order_unique_id,
                'amount'=>$request->amount,
                'type'=>'credited',
                'walet_id'=>$check->walet_id,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ];
            $insert = DB::table('walet_log')->insert($log);
        }
        return response()->json([
            'status'=>'success',
            'data'=>'Amount successfully credited'
        ]);
    }
}
