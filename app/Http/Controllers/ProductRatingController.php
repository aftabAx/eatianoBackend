<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class ProductRatingController extends Controller
{
    public function product_rating(Request $request)
    {
        $request->validate([
            'product_id'=>'required',
            'restaurant_id'=>'required',
            'rate'=>'required',
            'review'=>'required'
        ]);

        $data = array();
        $data = [
            'restaurant_id'=>$request->restaurant_id,
            'product_id'=>$request->product_id,
            'rate'=>$request->rate,
            'review'=>$request->review,
            'created_at'=>Carbon::now(),
            'user_id'=>auth()->user()->id
        ];

        $order = DB::table('orders')
        ->leftjoin('order_item', 'order_item.order_unique_id', '=', 'orders.order_unique_id')
        ->where('orders.user_id', auth()->user()->id)->first();
        if(isset($order))
        {
            if($order->product_id == $request->product_id)
            {
                $insert = DB::table('product_rating')->insert($data);

                $product = DB::table('products')->where('product_id', $request->product_id)->first();

                $datas = array();
                $datas = [
                    'product_rating'=> (int)$product->product_rating + 1,
                    'product_rating_count'=> (int)$product->product_rating_count + 1,
                ];

             $update = DB::table('products')->where('product_id', $request->product_id)->update($datas);


         if(isset($insert))
         {
            return response()->json([
                'staus'=>'success',
                'data'=>'Reviews submitted successfully'
            ]);
        }

        else{
            return response()->json([
                'staus'=>'success',
                'data'=>'Something went wrong'
            ]);
            }
        }
         else{
            return response()->json([
                'staus'=>'success',
                'data'=>'Something went wrong'
            ]);
            }
        }

    }

    public function all_reviews($id)
    {
        $reviews = DB::table('product_rating')
        ->leftjoin('restaurant', 'restaurant.restaurant_id', '=', 'product_rating.restaurant_id')
        ->leftjoin('products', 'products.product_id', '=', 'product_rating.product_id')
        ->leftjoin('users', 'users.id', '=', 'product_rating.user_id')
        ->where('product_rating.product_id', $id)
        ->select('restaurant.restaurant_name', 'product_rating.rate', 'product_rating.review', 'users.name', 'products.product_name')
        ->orderBy('product_rating.product_id', 'DESC')
        ->get();

         return response()->json([
            'status'=>'success',
            'data'=>$reviews
        ]);
    }
}
