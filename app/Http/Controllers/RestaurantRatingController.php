<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use DB;

use Carbon\Carbon;



class RestaurantRatingController extends Controller

{

    public function restaurant_rating(Request $request)

    {

        $request->validate([

            'rate'=>'required',

            'review'=>'required',

            'restaurant_id'=>'required'

        ]);



        $data = array();

        $data = [

            'restaurant_id'=>$request->restaurant_id,

            'rate'=>$request->rate,

            'review'=>$request->review,

            'created_at'=>Carbon::now(),

            'user_id'=>auth()->user()->id

        ];



      
        $insert = DB::table('restaurant_rating')->insert($data);




         if(isset($insert))

         {

            return response()->json([

                'status'=>'success',

                'data'=>'Reviews submitted successfully'

            ]);

        }



        else{

            return response()->json([

                'status'=>'success',

                'data'=>'Something went wrong'

            ]);

            }

        

      



        

    }



    public function all_review($id)

    {

        $reviews = DB::table('restaurant_rating')

        ->leftjoin('restaurant', 'restaurant.restaurant_id', '=', 'restaurant_rating.restaurant_id')

        ->leftjoin('users', 'users.id', '=', 'restaurant_rating.user_id')

        ->where('restaurant_rating.restaurant_id', $id)

        ->select('restaurant.restaurant_name', 'restaurant_rating.rate', 'restaurant_rating.review', 'users.name')

        ->orderBy('restaurant_rating.restaurant_id', 'DESC')

        ->get();



         return response()->json([

            'status'=>'success',

            'data'=>$reviews

        ]);

    }

}

