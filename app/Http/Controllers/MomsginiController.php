<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class MomsginiController extends Controller
{

    //PICKUP AND DELIVERY

    public function momsgini(Request $request)
    {
        $request->validate([
            'fromCity'=>'required',
            'fromState'=>'required',
            'fromCountry'=>'required',
            'fromPincode'=>'required',
            'fromArea'=>'required',
            'fromLat'=>'required',
            'fromLng'=>'required',
            'toCity'=>'required',
            'toState'=>'required',
            'toCountry'=>'required',
            'toPincode'=>'required',
            'toArea'=>'required',
            'toLat'=>'required',
            'toLng'=>'required'
        ]);

        $pickup = array();
        $pickup = [
            'city'=>$request->fromCity,
            'user_id'=>auth()->user()->id,
            'state'=>$request->fromState,
            'country'=>$request->fromCountry,
            'pincode'=>$request->fromPincode,
            'area'=>$request->fromArea,
            'lat'=>$request->fromLat,
            'lng'=>$request->fromLng,
            // 'nearby'=>$request->fromNearby,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ];

        $pickup_insert = DB::table('momsgini_pickup')->insert($pickup);

        $delivery = array();
        $delivery = [
            'city'=>$request->toCity,
            'user_id'=>auth()->user()->id,
            'state'=>$request->toState,
            'country'=>$request->toCountry,
            'pincode'=>$request->toPincode,
            'area'=>$request->toArea,
            'lat'=>$request->toLat,
            'lng'=>$request->toLng,
            // 'nearby'=>$request->toNearby,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ];

        $delivery_insert = DB::table('momsgini_delivery')->insert($delivery);

        if(isset($pickup_insert) && isset($delivery_insert)) 
        {
            return response()->json([
              'status'=>'success',
              'data'=>"Succesfully ordered",
            ]);
        }
    }
}
