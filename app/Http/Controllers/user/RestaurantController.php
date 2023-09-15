<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class RestaurantController extends Controller
{
    public function all_restaurant(){
        $restaurants = DB::table('restaurant')->orderBy('restaurant.restaurant_id','desc')->get();
        return response()->json([
            'status'=> '200',
           'data'=> $restaurants]);
    }

    
}
