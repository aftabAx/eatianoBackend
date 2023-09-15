<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class warehouseController extends Controller
{

    public function get_warehouse($id)
    {
         $warehouse = DB::table('warehouse')
            ->leftjoin('users', 'users.id', '=', 'warehouse.user_id')
            ->where('warehouse.user_id', $id)
            ->select('users.name', 'warehouse.*')
            ->first();

           return response()->json([
            'status'=>'success',
            'data'=>$warehouse
        ]);
    }
     
        public function all_warehouse()
        {
         $data = DB::table('warehouse')->orderBy('warehouse_id','desc')->get();

             return response()->json([
            'status'=>'success',
            'data'=>$data
           ]);
        }
}
