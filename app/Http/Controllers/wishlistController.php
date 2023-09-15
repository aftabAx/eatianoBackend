<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use Carbon\Carbon;

use DB;



class wishlistController extends Controller

{

    public function __construct()

    {

        $this->middleware('auth:api', ['except' => ['login', 'admin_login', 'super_admin_login', 'delivery_login', 'signup']]);

    }



    public function add_wishlist(Request $request){

        $data = array();

        $data = [

            'product_id'=>$request->product_id,

            'user_id'=>auth()->user()->id,

            'created_at'=>Carbon::now(),

            'updated_at'=>Carbon::now(),

        ];

        $check = DB::table('wishlist')->where('product_id', $request->product_id)->where('user_id', auth()->user()->id)->first();

        if(empty($check)){

           $query = DB::table('wishlist')->insert($data);

        if($query){

            return response()->json([

                'status'=>'success',

                'data'=>'Product added to wishlist'

            ]);

        }else{

            return response()->json([

                'status'=>'failed',

                'data'=>'Somthing went wrong'

            ]);

        } 

    }else{

        return response()->json([

                'status'=>'duplicate',

                'data'=>$check

            ]);

    }

        

    }



    public function get_wishlist(){

        $products = DB::table('wishlist')

        ->leftjoin('products', 'wishlist.product_id','=', 'products.product_id')

        ->leftjoin('restaurant','restaurant.restaurant_id','=','products.restaurant_id')

        ->select('products.product_id', 'restaurant.restaurant_name','products.product_name','products.product_description', 'products.product_image', 'products.product_selling_price', 'products.product_status', 'products.product_quantity', 'products.product_rating', 'products.product_rating_count', 'products.product_sell_count')

        ->where('wishlist.user_id', auth()->user()->id)

        ->orderBy('products.product_id', 'desc')

        ->get();



        if(isset($products)){

            return response()->json([

                'status'=>'success',

                'data'=>$products

            ]);

        }else{

            return response()->json([

                'status'=>'fail',

                'data'=>'Not found'

            ]);

        }

    }



    public function delete_wishlist($id)
    {
        $user_id = auth()->user()->id;
    
        $delete = DB::table('wishlist')
            ->where('product_id', $id)
            ->where('user_id', $user_id)
            ->delete();
    
        if ($delete) {
            return response()->json([
                'status' => 'success',
                'message' => 'Removed from wishlist',
            ]);
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Item not found in the wishlist',
            ]);
        }
    }
    

}

