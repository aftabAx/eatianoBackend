<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use DB;

use Carbon\Carbon;

use Image;

use Illuminate\Support\Str;



class ProductController extends Controller

{

    // USER GET ALL PRODUCT



    public function all_products(){

        $products = DB::table('products')

        ->leftjoin('restaurant', 'products.restaurant_id','=', 'restaurant.restaurant_id')

        ->select('products.product_id', 'restaurant.restaurant_id', 'restaurant.restaurant_name','products.product_name','products.product_description', 'products.product_image', 'products.product_selling_price', 'products.offer_percent', 'products.product_status', 'products.product_quantity', 'products.product_rating', 'products.product_rating_count', 'products.product_sell_count')

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



//MOST SELLING PRODUCT



    public function best_selling()

    {

        $topsales = DB::table('order_item')

           ->leftJoin('products','products.product_id','=','order_item.product_id')

           ->select('products.product_id','products.product_name','order_item.product_id','order_item.quantity', 

            DB::raw('SUM(order_item.quantity) as total'))

           ->groupBy('products.product_id','order_item.product_id','products.product_name', 'order_item.quantity')

           ->orderBy('total','desc')

           ->limit(5)

           ->get();



           if(isset($topsales)){

            return response()->json([

                'status'=>'success',

                'data'=>$topsales

            ]);

        } 

    }



//MONTHLY SELLING PRODUCTS



    public function monthly_selling(Request $request) 

    {

        // dd($request->toArray());

        $request->validate([

            'month'=>'required',

            'year'=>'required',

            'product_id'=>'required'

        ]);

        $data = array();

        $data = [

            $date = $request->year ."-". $request->month ,

            'product_id'=>$request->product_id 

        ];

       

        $monthly_selling = DB::table('order_item')

        ->where('product_id', $request->product_id)

        ->where('created_at', 'like', $date.'%')

        // ->where('order_item.created_at', '>=', $date)

        ->count();



        if(isset($monthly_selling))

        {

            return response()->json([

                'status'=>'success',

                'data'=>$monthly_selling

            ]);

        } 

}       





// GET ONE PRODUCT



    public function get_product($id){

      

        $products = DB::table('products')

        ->leftjoin('restaurant', 'products.restaurant_id','=', 'restaurant.restaurant_id')

        ->where('products.product_id', $id)

        ->select('products.product_id', 'restaurant.restaurant_id', 'restaurant.restaurant_name','products.product_name','products.product_description', 'products.product_image', 'products.product_selling_price', 'products.offer_percent', 'products.product_status', 'products.product_quantity', 'products.product_rating', 'products.product_rating_count', 'products.product_sell_count')

        ->orderBy('products.product_id', 'desc')

        ->get();



        $image = DB::table('product_images')->where('product_id', $id)->orderby('product_images_id', 'desc')->limit(5)->get();



        if(isset($products)){

            return response()->json([

                'status'=>'success',

                'data'=>$products,

                'images'=>$image

            ]);

        }else{

            return response()->json([

                'status'=>'fail',

                'data'=>'Not found'

            ]);

        }

    }



    // GET ALL PRODUCT ADMIN AND SUPER ADMIN



    public function admin_all_products(){

        if(auth()->user()->role == 'super_admin'){

            $products = DB::table('products')

             ->leftjoin('restaurant', 'products.restaurant_id','=', 'restaurant.restaurant_id')

            ->orderBy('products.product_id', 'DESC')->get();

            return response()->json([

                'status'=>'success',

                'data'=>$products

            ]);

        }else{

            return response()->json([

                'status'=>'success',

                'data'=>'unautherized'

            ]);

        }

    }



    // GET ALL RESTAURANT PRODUCT



    public function restaurant_products($id){

        $products = DB::table('products')

        ->leftjoin('restaurant', 'products.restaurant_id','=', 'restaurant.restaurant_id')

        ->where('products.restaurant_id', $id)

        ->select('products.product_id', 'products.category_name', 'restaurant.restaurant_name','products.product_name','products.product_description', 'products.product_image', 'products.product_selling_price', 'products.product_status', 'products.product_quantity', 'products.product_rating', 'products.product_rating_count', 'products.product_sell_count')

        ->orderBy('products.product_id', 'desc')

        ->get();

    

        foreach( $products as $product )

        {

            $product->category_name = explode(",",$product->category_name);

        }

    

        if(isset($products)){

            return response()->json([

                'status'=>'success',

                'data'=>$products

            ]);

        }else{

            return response()->json([

                'status'=>'fail',

                'data'=>'Not found',

            ]);

        }

    }



// GET ADMIN RESTAURANT PRODUCT



     public function admin_restaurant_products($id){

        if(auth()->user()->role == 'admin'){

           $products = DB::table('products')

        ->leftjoin('restaurant', 'products.restaurant_id','=', 'restaurant.restaurant_id')

        ->where('products.restaurant_id', $id)

        ->where('restaurant.restaurant_added_by', auth()->user()->id)

        ->select('products.product_id', 'restaurant.restaurant_name','products.product_name','products.product_desciption', 'products.product_image', 'products.product_selling_price', 'products.product_status', 'products.product_quantity', 'products.product_rating', 'products.product_rating_count', 'products.product_sell_count')

        ->orderBy('products.product_id', 'desc')

        ->get(); 

        }elseif(auth()->user()->role == 'admin')

        



        if(isset($products)){

            return response()->json([

                'status'=>'success',

                'data'=>$products

            ]);

        }else{

            return response()->json([

                'status'=>'fail',

                'data'=>'Not found',

            ]);

        }

    }



    // ADMIN AND SUPER ADMIN GET PRODUCT



    public function admin_get_product($id){

        $products = DB::table('products')

        ->leftjoin('restaurant', 'products.restaurant_id','=', 'restaurant.restaurant_id')

        ->where('products.product_id', $id)

        ->where('restaurant.restaurant_added_by', auth()->user()->id)

        ->select('products.product_id', 'restaurant.restaurant_name','products.product_name','products.product_desciption', 'products.product_image', 'products.product_selling_price', 'products.product_status', 'products.product_quantity', 'products.product_rating', 'products.product_rating_count', 'products.product_sell_count')

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



// ADMIN SUPER ADMIN ADD PRODUCT



    public function add_product(Request $request){

        $request->validate([

            'restaurant_id'=>'required',

            'product_name'=>'required',

            'product_description'=>'required',

            'product_selling_price'=>'required',

            'product_meta_data'=>'required',

            'category'=>'required'

        ]);



        // preg_match_all('!\d+!', $request->category, $category);

        $category = explode( ",", $request->category );

        $fetch_cat = DB::table( 'category' )->select('category_name')->whereIn( 'category_id', $category )->get();

        $x=0;

        $category = "";

        foreach( $fetch_cat as $fetch_cat )

        {

            if( $x == 0 )

            {

                $category = $fetch_cat->category_name;

                $x++;

            }

            else{

                $category = $category.",".$fetch_cat->category_name;

            }

        }

        

        $data = array();

        $data = [

            'restaurant_id'=>$request->restaurant_id,

            'product_name'=>$request->product_name,

            'product_description'=>$request->product_description,

            'product_selling_price'=>$request->product_selling_price,

            'product_meta_data'=>$request->product_meta_data,

            'product_buying_price'=>$request->product_buying_price,

            'product_status'=>'active',

            'product_quantity'=>$request->product_quantity,

            'product_sell_count'=>0,

             'category_name'=>$category,

            'created_at'=>Carbon::now(),

            'updated_at'=> Carbon::now(),

        ];

        if($request->hasFile('product_image')){

            if ($request->file('product_image')->isValid()) {

                $file = $request->file('product_image');

                $ext= $file->getClientOriginalExtension();

                $mainFilename = Str::random(6).date('h-i-s').".".$ext;

                // $img = Image::make($request->product_image)->save("assets/product/".$mainFilename);

                $request->file('product_image')->move('assets/product/', $mainFilename);

                $db_name = "/assets/product/".$mainFilename;

                

                $data['product_image'] = $db_name;

            }

        }



        $insert = DB::table('products')->insert($data);



        // if(isset($db_name)){

        //     $product_id = DB::table('products')->where('product_image', $db_name)->select('product_id')->first();

        // }



        // if($request->hasFile('product_images')){

        //     foreach($request->file('product_images') as $image){

        //         $file = $image;

        //         $ext= $file->getClientOriginalExtension();

        //         $mainFilename = Str::random(6).date('h-i-s').".".$ext;

        //         $img = Image::make($image)->save("assets/product/".$mainFilename);

        //         $db_name = "/assets/product"."/".$mainFilename;

        //         $images = array();

        //         $images = [

        //             'p_image'=>$db_name,

        //             'product_id'=> $product_id,

        //             'created_at'=>Carbon::now(),

        //             'updated_at'=> Carbon::now(),

        //         ];



        //         $insert = DB::table('product_images')->insert($images);

        //     }

        // }

        return response()->json([

            'status'=>'success',

            'data'=>'product upload successfully',

        ]);



    }



// ADMIN SUPER ADMIN EDIT PRODUCT



    public function edit_product(Request $request, $id){

        $request->validate([

            'product_name'=>'required',

            'product_description'=>'required',

            'product_buying_price'=>'required',

            'product_selling_price'=>'required',

            'product_meta_data'=>'required',

           

        ]);

        $data = array();



        $data = [

            'product_name' => $request->product_name,

            'product_description'=>$request->product_description,

            // 'restaurant_added_by'=>auth()->user()->id,

            'product_buying_price'=>$request->product_buying_price,

            'product_selling_price'=>$request->product_selling_price,

            'product_quantity'=>$request->product_quantity,

            'product_meta_data'=>$request->product_meta_data,

            'updated_at'=> Carbon::now(),

        ];



        if($request->hasFile('product_image')){

            if ($request->file('product_image')->isValid()) {

                $file = $request->file('product_image');

                $ext= $file->getClientOriginalExtension();

                $mainFilename = Str::random(6).date('h-i-s').".".$ext;

                $img = Image::make($request->product_image)->resize(240,200)->save("assets/product/".$mainFilename);

                $db_name = "/assets/product/".$mainFilename;



                $new_data = array();

                $new_data = ['product_image'=>$db_name];

                $data = array_merge($data,$new_data);

                

            }

            }

        

        $update = DB::table('products')->where('product_id', $id)->update($data);



        if(isset($update)){

            return response()->json([

                'status'=>'success',

                'data'=>$data,

            ]);

        }else{

            return response()->json([

                'status'=>'fail',

                'data'=>'something went wrong',

            ]);

        }

    }





    // public function edit_product(Request $request, $id){

    //      if(auth()->user()->role == 'admin'){

    //         $check = DB::table('products')

    //         ->leftjoin('restaurant', 'products.restaurant_id','=', 'restaurant.restaurant_id')

    //         ->where('restaurant.restaurant_added_by', auth()->user()->id)

    //         ->get();

    //         if(!$check->isEmpty()){

    //                $data = array();

    //         $fields = ['product_name','product_image', 'product_desciption', 'product_buying_price', 'product_selling_price', 'product_status', 'product_quantity', 'product_rating', 'product_rating_count' ,'product_sell_count', 'product_type' ,'product_meta_data'];

    //         foreach($fields as $field){ 

    //             if($request->has($field)){

    //                 $dta = [$field => $request->$field];

    //                 $data = array_merge($data, $dta);

    //             } 

    //         }

            

    //         if($request->hasFile('product_image')){

    //         if ($request->file('product_image')->isValid()) {

    //             $file = $request->file('product_image');

    //             $ext= $file->getClientOriginalExtension();

    //             $mainFilename = Str::random(6).date('h-i-s').".".$ext;

    //             $img = Image::make($request->product_image)->save("assets/product/".$mainFilename);

    //             $db_name = "/assets/product/".$mainFilename;



    //             $new_data = array();

    //             $new_data = ['product_image'=>$db_name];

    //             $data = array_merge($data,$new_data);

                

    //         }

    //         }



    //          $update = DB::table('products')->where('product_id', $id)->update($data);

    //          return response()->json([

    //             'staus'=> 'success',

    //             'data'=>'Data Updated successfully'

    //         ]);

        

    //      }elseif(auth()->user()->role == 'super_admin'){

    //         $data = array();

    //         $fields = ['product_name','product_image', 'product_desciption', 'product_buying_price', 'product_selling_price', 'product_status', 'product_quantity', 'product_rating', 'product_rating_count' ,'product_sell_count', 'product_type' ,'product_meta_data'];

    //         foreach($fields as $field){ 

    //             if($request->has($field)){

    //                 $dta = [$field => $request->$field];

    //                 $data = array_merge($data, $dta);

    //             } 

    //         }



    //         if($request->hasFile('product_image')){

    //         if ($request->file('product_image')->isValid()) {

    //             $file = $request->file('product_image');

    //             $ext= $file->getClientOriginalExtension();

    //             $mainFilename = Str::random(6).date('h-i-s').".".$ext;

    //             $img = Image::make($request->product_image)->save("assets/product/".$mainFilename);

    //             $db_name = "/assets/product/".$mainFilename;



    //             $new_data = array();

    //             $new_data = ['product_image'=>$db_name];

    //             $data = array_merge($data,$new_data);

                

    //         }

    //         }



    //          $update = DB::table('products')->where('product_id', $id)->update($data);

    //          return response()->json([

    //             'staus'=> 'success',

    //             'data'=>'Data Updated successfully'

    //         ]);

    //     }else{

    //         return response()->json([

    //             'staus'=> 'fail',

    //             'error'=>'Unauthorized'

    //         ]);

    //     } 

    //         }

        

    // }



    public function delete_product($id){

        $check = DB::table('products')->where('product_id', $id)->select('product_image', 'product_id')->first();



        unlink(public_path($check->product_image));



        $images = DB::table('product_images')->where('product_id', $id)->select('product_images', 'product_id')->first();



            foreach($images as $image){

                unlink($image->product_images);

            }



        $delete = DB::table('product')->where('product_id', $id)->delete();

    }

}



