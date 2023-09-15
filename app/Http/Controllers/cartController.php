<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use DB;

use Carbon\Carbon;



class cartController extends Controller

{

    public function add_cart(request $request){

        // return $request->input();

        $request->validate([

            'product_id'=>'required',

            'restaurant_id'=>'required',

            'quantity'=>'required'

        ]);

        $data = array();

        $data = [

            'product_id'=>$request->product_id,

            'restaurant_id'=>$request->restaurant_id,

            'user_id'=>auth()->user()->id,

            'quantity'=>$request->quantity

        ];



        $cart = DB::table('cart')

        ->where('user_id',auth()->user()->id)

        ->select('restaurant_id')

        ->first();

       // return $cart->restaurant_id;

        if(isset($cart))

        {

           if($cart->restaurant_id != null)

            {

              if($cart->restaurant_id != $request->restaurant_id)

                {

                    return response()->json([

                       'status'=>'success',

                        'data'=>'do you want to replace it?'

                  ]);

                }

             else

            {

              $insert = DB::table('cart')->insert($data);

            } 

       }

        else

          {

            $insert = DB::table('cart')->insert($data);

          }

    } 

       else

          {

            $insert = DB::table('cart')->insert($data);

          }

          

          $product = DB::table( 'products' )->select('product_name', 'product_selling_price', 'product_image')->where( 'product_id', $request->product_id )->first();

          $product->quantity = $request->quantity;

          

          $cart = DB::table( 'cart' )->select('cart_id')->where( 'product_id', $request->product_id )->where( 'user_id', auth()->user()->id )->first();

          $product->cart_id = $cart->cart_id;



        if(isset($insert)){

            return response()->json([

                'staus'=>'success',

                'data'=>$product

            ]);

        }

    }



    public function view_cart_restaurant()

    {

        $restaurant = DB::table('cart')

        ->leftjoin('restaurant', 'restaurant.restaurant_id', '=', 'cart.restaurant_id')

        ->where('cart.user_id', auth()->user()->id)

        ->get();

        return response()->json([

            'status'=>'success',

            'data'=>$restaurant

        ]);

    }



    public function view_cart(){

        return $cart = DB::table('cart')

        ->leftjoin('products', 'products.product_id', '=', 'cart.product_id')

        ->leftjoin('restaurant', 'restaurant.restaurant_id','=', 'cart.restaurant_id')

        ->where('cart.user_id', auth()->user()->id)

        ->select('cart.cart_id', 'cart.restaurant_id', 'cart.product_id',  'restaurant.restaurant_name','products.product_name','products.product_description', 'products.product_image', 'products.product_selling_price', 'products.product_status', 'products.product_quantity',  'cart.quantity')

        ->orderby('cart.cart_id', 'DESC')->get();

        return response()->json([

            'status'=>'success',

            'data'=>$cart

        ]);

    }



    public function delete_cart($id){

        $delete = DB::table('cart')

        ->where('user_id', auth()->user()->id)

        ->where('cart_id', $id)->delete();



        return response()->json([

            'status'=>'success',

            'data'=>'Cart deleted successfully'

        ]);

    }



    public function order_cart_delete(){

       

        $delete = DB::table('cart')

        ->where('user_id', auth()->user()->id)

        ->delete();

        if(isset($delete)){

            return response()->json([

                'status'=>'success',

                'data'=>'Cart deleted successfully'

            ]);

        }else{

            return response()->json([

                'status'=>204,

                'data'=> 'not found'

            ]);

        }

    }

}

