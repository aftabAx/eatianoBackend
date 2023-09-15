<?php



namespace App\Http\Controllers;



use Illuminate\Http\Request;

use DB;

use Carbon\Carbon;

use Illuminate\Support\Str;

use Mail;

use Razorpay\Api\Api;

use Razorpay\Api\Errors\SignatureVerificationError;

class orderController extends Controller

{

    public function check_membership_offer()

    {

        $current_date = Carbon::now();

        $member_check = DB::table('membership')

        ->leftjoin('membership_type','membership_type.membership_type_id', '=', 'membership.membership_type_id' )

        ->where('membership.user_id', auth()->user()->id)->first();

        if(isset($member_check))

        {

            if($current_date <= $member_check->end_date)

            {

            return response()->json([

            'status'=>'success',

            'data'=>$member_check->discount_percent

        ]); 

           }

           else

           {

            return response()->json([

            'status'=>'fail',

            'data'=>'Membership date has expired',

        ]);

           }

        }

        else

           {

            return response()->json([

            'status'=>'fail',

            'data'=>'Membership doesnot exist',

        ]);

           }



        }





    public function all_orders(){
        //dd(auth()->user()->id);
        if(auth()->user()->role=='admin'){

        $data = DB::table('orders')

        ->leftjoin('order_item', 'order_item.order_unique_id', '=', 'orders.order_unique_id')

        ->leftjoin('products', 'products.product_id','=','order_item.product_id')

        ->leftjoin('restaurant','restaurant.restaurant_id', '=', 'products.restaurant_id')

        ->leftjoin('assign_orders', 'assign_orders.order_unique_id', '=', 'orders.order_unique_id')

        ->select('orders.order_unique_id','orders.total_amount','orders.order_status','orders.delivery_date','order_item.quantity','order_item.product_price','products.product_name','restaurant.restaurant_name')

        ->where('assign_orders.user_id', auth()->user()->id)

        ->orderBy('orders.order_id', 'DESC')->get();

        }

        elseif(auth()->user()->role=='super_admin'){

           $data = DB::table('orders')

        ->leftjoin('order_item', 'order_item.order_unique_id', '=', 'orders.order_unique_id')

        ->leftjoin('products', 'products.product_id','=','order_item.product_id')

        ->leftjoin('restaurant','restaurant.restaurant_id', '=', 'products.restaurant_id')

        ->orderBy('orders.order_id', 'DESC')->get();

        }

        else

        {

            $data = DB::table('orders')

        ->leftjoin('order_item', 'order_item.order_unique_id', '=', 'orders.order_unique_id')

        ->leftjoin('products', 'products.product_id','=','order_item.product_id')

        ->leftjoin('restaurant','restaurant.restaurant_id', '=', 'products.restaurant_id')

        ->select('orders.order_unique_id','orders.total_amount','orders.order_status','orders.delivery_date','order_item.quantity','order_item.product_price','products.product_name','restaurant.restaurant_name')

        ->where('orders.user_id', auth()->user()->id)

        ->orderBy('orders.order_id', 'DESC')->get();

        }



        return response()->json([

            'status'=>'success',

            'data'=>$data,

        ]);

    }



    public function get_order($id){

        $data = DB::table('orders')

        ->where('order_unique_id', $id)

        ->where('user_id', auth()->user()->id)->first();

        if(isset($data)){

            return response()->json([

                'status'=>'success',

                'data'=>$data

            ]);

        }else{

            return response()->json([

                'status'=>'fail',

                'data'=>'No order found'

            ]);

        }

    }





    public function order_assign(Request $request){

        
        $id = $request->order_unique_id;

       

        $restaurant = DB::table('orders')

        ->leftjoin('restaurant', 'orders.restaurant_id','=', 'restaurant.restaurant_id')

        ->where('orders.order_unique_id', $id)->select('orders.restaurant_id', 'restaurant.restaurant_added_by', 'restaurant.lat', 'restaurant.lng', 'orders.user_id')->first();

         //dd($restaurant);

       //return response()->json(['status'=>'success', 'data'=>$restaurant]);

        // if(auth()->user()->id == $restaurant->restaurant_added_by){

          $delivery = DB::table('delivery_address')

                    ->selectRaw("delivery_address_id,user_id,lat,lng, ( 6371 * acos( cos( radians(?) ) * cos( radians(lat) ) * cos( radians( lng ) - radians(?)  ) + sin(radians(?) ) * sin( radians( lat ) ) ) ) AS distance", [$restaurant->lat, $restaurant->lng, $restaurant->lat])

                    ->having("distance", "<", 500)->where('order_unique_id',$id)

                    ->orderBy("distance",'asc')

                    ->offset(0)

                    ->limit(1)

                    ->get();

                    //dd($delivery);

                    //return response()->json(['status'=>'success', 'data'=>$delivery[0]->distance]);



            if( $delivery[0]->distance >= 21 )

            {

                if(isset($delivery)){

                $data = array();

                $data = [

                    'order_unique_id'=>$id,

                    'warehouse_id'=>$delivery[0]->user_id,

                    'created_at'=>Carbon::now(),

                    'updated_at'=>Carbon::now(),

                ];

                $insert1 = DB::table('assign_orders')->insert($data);

                 if(isset($insert1)){

                    $data1 = array();

                    $data1 = [

                        'delivery_boy_id'=> $request->deliveryboy_id ,

                        'order_unique_id'=>$id,

                        'pickup_lat' => $restaurant->lat,

                        'pickup_lng' => $restaurant->lng,

                        'del_lat' => $request->warehouse_lat,

                        'del_lng' => $request->warehouse_lng

                     

                     

                    ];

                    $insert = DB::table('assign_deliveryboy')->insert($data1);

                    }

                 }

              





                // }else{

                //     return response()->json([

                //         'status'=>'fail',

                //         'data'=>'Unauthorized'

                //     ]);

                // }



             $email = array();

             $email = [

                'order_id'=>$id,

                'order_status'=>'Placed'

             ];



             $admin_email = DB::table('users')->where('id', $warehouse[0]->user_id)->select('email')->first();

             

             $admin['to'] = $admin_email->email;



             mail::send('order_assign', $email, function($message) use ($admin){

                $message->to($admin['to']);

                $message->subject('Order Assigned');

             });



             // return response()->json(['status'=>'success', 'data'=>'Order Assigned successfully']); 

            }

            else{



   $data1 = array();

                $data1 = [

                    'delivery_boy_id'=> $request->deliveryboy_id ,

                    'order_unique_id'=>$id,

                    'pickup_lat' => $restaurant->lat,

                        'pickup_lng' => $restaurant->lng,

                        'del_lat' => $delivery->lat,

                        'del_lng' => $delivery->lng

                        

                

                ];

                $insert = DB::table('assign_deliveryboy')->insert($data1);

               // return response()->json(['status'=>'success', 'data'=>'Order Assigned successfully']);

            }

             if(isset($insert)){

                return response()->json(['status'=>'success', 'data'=>'Order Assigned successfully']);

             }

             else{

                return response()->json(['status'=>'fail', 'data'=>'Order Assigned successfully']);

             }

        

    }

    //order assign from warehouse order

    public function warehouse_assign(){

           $warehouse = DB::table('order_status')->where('status','at warehouse')->get();

           

           return response()->json(['status'=>'success', 'data'=>$warehouse]);

    }

public function warehouse_details($id){

  $order = DB::table('orders')->where('order_unique_id',$id)->get()->first();

  return response()->json(['status'=>'success', 'data'=>$order]);

}



public function warehouse_delivery(Request $request){

    $data1 = array();

    $warehouse = DB::table('assign_orders')->leftJoin('warehouse','assign_orders.warehouse_id','=','warehouse.user_id')->where('assign_orders.order_unique_id',$request->order_unique_id)->get()->first();

    $delivery = DB::table('delivery_address')->where('order_unique_id',$request->order_unique_id)->get()->first();

    $data1 = [

        'delivery_boy_id'=> $request->delivery_boy_id ,

        'order_unique_id'=>$request->order_unique_id,

        'pickup_lat' => $warehouse->lat,

        'pickup_lng' => $warehouse->lng,

        'del_lat' => $delivery->lat,

        'del_lng' => $delivery->lng

        

    ];

   $insert = DB::table('assign_deliveryboy')->insert($data1);

    return response()->json(['status'=>'success', 'data'=>'Order Assigned successfully']);

}

    // public function cancel_order($id){



    // }



    public function previous_order()

    {

        $previous_orders = DB::table('orders')

        ->leftjoin('restaurant', 'restaurant.restaurant_id','=', 'orders.restaurant_id')

        ->where('orders.user_id', auth()->user()->id)

        ->select('orders.restaurant_id', 'orders.total_amount', 'restaurant.restaurant_name', 'restaurant.restaurant_image','orders.user_id')

        ->get();

        return response()->json([

             'status'=>'success',

             'data'=>$previous_orders

        ]); 

    }





    public function previous_order_details()

    {

       $orders = DB::table('orders')

        ->leftjoin('products', 'products.restaurant_id', '=', 'orders.restaurant_id')

        ->leftjoin('restaurant', 'restaurant.restaurant_id','=', 'orders.restaurant_id')

        ->leftjoin('order_item', 'order_item.order_unique_id', '=', 'orders.order_unique_id')

        ->where('orders.user_id', auth()->user()->id)

        ->select('order_item.product_id','restaurant.restaurant_name', 'restaurant.restaurant_image','products.product_name', 'products.product_image', 'orders.total_amount', 'order_item.quantity', 'order_item.product_price', 'order_item.order_unique_id')

        ->orderby('orders.order_id', 'DESC')

        ->get();

           return response()->json([

             'status'=>'success',

             'data'=>$orders

        ]); 

    }





 //MONTHLY SELL 



    public function monthly_sell(Request $request) 

    {

        $request->validate([

            'month'=>'required',

            'year'=>'required'

        ]);



       $date = $request->year ."-". $request->month ;

       

       $data = DB::table('orders')

       ->select('orders.total_amount')

       ->where('created_at', 'like', $date.'%')

       ->count();



    // $data = DB::table("orders")

    //         ->whereRaw('MONTH(created_at) = ?',[$currentMonth])

    //         ->count();  



    if(isset($data))

        {

            return response()->json([

                'status'=>'success',

                'data'=>$data

            ]);

        }    

   }



    





   // public function monthly_profit(Request $request)

   // {

   //  $request->validate([

   //      'month'=>'required',

   //      'year'=>'required'

   //  ]);



   //     $date = $request->year ."-". $request->month ;

   //     $orders = DB::table('orders')->where('created_at', 'like', $date.'%')->get();



   //     // foreach( $orders as  )

   //     $profit = $order->total_amount - $order->buying_price;

       

   //    return response()->json([

   //              'status'=>'success',

   //              'data'=>$profit

   //          ]);



   // }



    function order_date($date)

    {

        $date = Carbon::parse( $date )->format( 'Y-m-d' );

        $orders = DB::table( 'orders' )->where('created_at', 'like', $date.'%')->count();



        if( isset( $orders ) )

        {

            return response()->json([

                'status'=>'success',

                'data'=>$orders

            ]);

        }

    }





    //REVENUE RANGE



    public function revenue_range(Request $request) 

    {

         $request->validate([

            'start_date'=>'required',

            'end_date'=>'required',

        ]);



        $start_date = Carbon::parse( $request->start_date )->format( 'Y-m-d' )." 00:00:00";

        $end_date = Carbon::parse( $request->end_date )->format( 'Y-m-d' )." 00:00:00";

        

        $orders = DB::table( 'orders' )->select('transaction_amount')->whereBetween( 'orders.created_at', [$start_date, $end_date] )->sum('transaction_amount');

  



    if(isset($orders))

        {

            return response()->json([

                'status'=>'success',

                'data'=>$orders

            ]);

        }    

   }





 function profit_range(Request $request)

    {

        $request->validate([

            'start_date'=>'required',

            'end_date'=>'required',

        ]);



        $start_date = Carbon::parse( $request->start_date )->format( 'Y-m-d' );

        $end_date = Carbon::parse( $request->end_date )->format( 'Y-m-d' );

        

        return $orders = DB::table( 'orders' )->leftJoin('order_tax', 'order_tax.order_unique_id', '=', 'orders.order_unique_id')->select('transaction_amount', 'buying_price', 'tax_amount')->whereBetween( 'orders.created_at', [$start_date."%", $end_date."%"] )->get();



        $profit = 0;

        $cp = 0;

        foreach ($orders as $order)

        {

            $cp += $order->buying_price + $order->tax_amount;

            $profit += $order->transaction_amount - ( $order->buying_price + $order->tax_amount);

        }



        $expenses = DB::table( 'expenses' )->whereBetween( 'created_at', [$start_date, $end_date] )->get();



        foreach( $expenses as $expense )

        {

            $cp += $expense->amount;

            $profit -= $expense->amount;

        }



        $profit_percent = round( ($profit/$cp)*100 , 2 );



        if( isset( $orders ) )

        {

            return response()->json([

                'status'=>'success',

                'profit_percent'=>$profit_percent

            ]);

        }

    }



    function highest_rated_restaurant()

    {

        // return $rating = DB::table('restaurant_rating')->avg('rate');

         $rating = DB::select( "SELECT avg(rate) AS average, restaurant_id FROM restaurant_rating GROUP BY restaurant_id ORDER BY average DESC LIMIT 1" );



         if( isset( $rating ) )

        {

            return response()->json([

                'status'=>'success',

                'data'=>$rating

            ]);

        }

    }



    public function Payment_credencial($marchent){

        if($marchent == 'rezor_pay'){

            $key = $_ENV['RAZORPAY_KEY'];

            $order_unique_id = auth()->user()->id.Str::random(5).rand(99,999);

            return response()->json([

                'status'=>'success',

                'kye'=>$key,

                'order_unique_id'=>$order_unique_id

            ]);

        }elseif($marchent == 'rezor_pay'){

            $key = $_ENV['RAZORPAY_KEY'];

            $order_unique_id = auth()->user()->id.Str::random(5).rand(99,999);

            return response()->json([

                'status'=>'success',

                'kye'=>$key,

                'order_unique_id'=>$order_unique_id

            ]);

        }

    }



    public function delivery_status(Request $request, $oid)

    {

        $request->validate([

            'status'=>'required'

        ]);



        $data = array();

        $data['deliveryboy_id'] = auth()->user()->id;

        $data['order_unique_id'] = $oid;

        $data['status'] = $request->status;



        $insert = DB::table( 'deliveryboy_status' )->insert($data);



        if( isset( $insert ) )

        {

            return response()->json([

                'status'=>'success',

                'data'=>'Added Successfully'

            ]);

        }

    }



    public function delivery_status_all()

    {

        $id = auth()->user()->id;

        $orders = DB::table( 'deliveryboy_status' )->leftJoin('orders', 'orders.order_unique_id', '=', 'deliveryboy_status.order_unique_id')->where('deliveryboy_id', $id)->get();



        if( isset( $orders ) )

        {

            return response()->json([

                'status'=>'success',

                'data'=>$orders

            ]);

        }

    }

    

    private function amount_to_pay( $coupon_code, $state, $total_amount, $delivery_charge )

    {

        // if(isset($coupon_code)){

            $discount_amount=0;

       $code_check = DB::table('coupon')->where('coupon_id', $coupon_code)->first();

       if(isset($code_check))

       {

        $discount = $code_check->discount;

        $discount_amount = (($discount)/100)*($total_amount);

        }

        // }

        // else{

        //     $discount_amount = 0;

        // }

        //return $discount_amount;

        



       $tax = DB::table('tax')->where('state', $state)->first();

        $tax_amount = 0;

       if( $tax )

        $tax_amount = ($tax->total/100) * (int)$total_amount;

       

       $amount_to_pay = ($total_amount + $delivery_charge + $tax_amount) - $discount_amount;

       return $amount_to_pay;

    }

    

    public function create_order_id(Request $request){

        

        // $request->validate([

        //     'state'=>'required',

        //     'coupon_code'=>'required'

        // ]);

        

        $user_id = auth()->user()->id;



        // $total_amount = DB::table( 'cart' )->select('products.product_buying_price')->leftJoin('products', 'cart.product_id', '=', 'products.product_id')->where( 'cart.user_id', $user_id )->sum('products.product_selling_price');

        $total_amount = 0;

        $cart_items = DB::table( 'cart' )->select('products.product_selling_price', 'cart.quantity')->leftJoin('products', 'cart.product_id', '=', 'products.product_id')->where( 'cart.user_id', $user_id )->get();

        foreach( $cart_items as $cart_item )

        {

            $total_amount += $cart_item->product_selling_price * $cart_item->quantity;

        }



        $delivery_charge=0;

        $amount = $this->amount_to_pay( $request->coupon_code, $request->state, $total_amount, $delivery_charge );

        //return $amount;

        

        $data = [

            'amount'=>$amount*100,

            'currency'=>'INR',

            'receipt'=>time(),

        ];



        $curl = curl_init();

    

        curl_setopt_array($curl, array(

          CURLOPT_URL => 'https://api.razorpay.com/v1/orders',

          CURLOPT_RETURNTRANSFER => true,

          CURLOPT_ENCODING => '',

          CURLOPT_MAXREDIRS => 10,

          CURLOPT_TIMEOUT => 0,

          CURLOPT_FOLLOWLOCATION => true,

          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,

          CURLOPT_CUSTOMREQUEST => 'POST',

          CURLOPT_POSTFIELDS =>'{   

            "amount": "'.$data['amount'].'",

            "currency": "'.$data['currency'].'",

            "receipt": "'.$data['receipt'].'"

        }',



          CURLOPT_HTTPHEADER => array(

            'Authorization: Basic '.env('RAZORPAY_KEY'),

            'Content-Type: application/json'

          ),

        ));



         $response = json_decode(curl_exec($curl));



        curl_close($curl);

    

        return response()->json([

        'data'=>$response

        ]);

    

        }

        

        public function payment_validation(Request $request)

        {

            $api = new Api('rzp_test_pLeJZKECvw4lZM', 'RmcoYFt33WzBhC3I3rsb8G1C');

            $request->validate([

                'razorpay_sig'=>'required',

                'payment_id'=>'required',

                'order_id'=>'required'

            ]);

            

            try

    {

        $attributes = array(

            'razorpay_order_id' => $request->order_id,

            'razorpay_payment_id' => $request->payment_id,

            'razorpay_signature' => $request->razorpay_sig,

        );



        $api->utility->verifyPaymentSignature($attributes);

        $success = 1;

    }

    catch(SignatureVerificationError $e)

    {

        $success = 0;

        $error = 'Razorpay Error : ' . $e->getMessage();

    }

    if($success == 1){

        return response()->json(["status"=> "success", 'data'=>'payment verified']);

    }else{

        return response()->json(["status"=> "failed", 'data'=>$error]);

    }





            



          

        }

    

        public function create_order(Request $request){

            $request->validate([

                'total_amount'=>'required',

                'marchent_name'=>'required',

                'transaction_amount'=>'required',

                'restaurant_id'=>'required',

                'transaction_id'=>'required',

                'order_unique_id'=>'required',

                'city'=>'required',

                'state'=>'required',

                'area'=>'required',

                'pincode'=>'required',

                'lat'=>'required',

                'lng'=>'required',

                'phone'=>'required',

                'coupon_code'=>'required'

            ]);

            

            $user_id = auth()->user()->id;

            

            $buying_price = DB::table( 'cart' )->select('products.product_buying_price')->leftJoin('products', 'cart.product_id', '=', 'products.product_id')->where( 'cart.user_id', $user_id )->sum('products.product_buying_price');



            $total_amount = DB::table( 'cart' )->select('products.product_selling_price')->leftJoin('products', 'cart.product_id', '=', 'products.product_id')->where( 'cart.user_id', $user_id )->sum('products.product_selling_price');

            

            $delivery_charge=0;

            $amount = $this->amount_to_pay( $request->coupon_code, $request->state, $total_amount, $delivery_charge );

    

               if( $request->transaction_amount != $amount )

               {

                return response()->json([

                    'status'=>'fail',

                    'data'=>'Transaction amount is not equal to amount to pay',

                ]);

               }

            

            // ORDER INSERT

            $datas = array();

            $datas = [

                'user_id'=>auth()->user()->id,

                'order_unique_id'=>$request->order_unique_id,

                'marchent_name'=>$request->marchent_name,

                'transaction_amount'=>$request->transaction_amount,

                'discount_amount'=>$request->discount_amount,

                'restaurant_id'=>$request->restaurant_id,

                'transaction_id'=>$request->transaction_id,

                'buying_price'=>$buying_price,

                'total_amount'=>$request->total_amount,

                'delivery_charge'=>100,

                'delivery_date'=>"4/3/2022",

                'order_status'=>'Placed',

                'created_at'=>Carbon::now(),

                'updated_at'=>Carbon::now(),

            ];

            $insert = DB::table('orders')->insert($datas);

    

    

             // ORDER TAX INSERT

             $order_tax = array();

             $order_tax['order_unique_id'] = $request->order_unique_id;

             $order_tax['tax_id'] = $tax->tax_id;

             $order_tax['tax_amount'] = $tax_amount;

             $order_tax['created_at'] = Carbon::now();

             $order_tax['updated_at'] = Carbon::now();

             $insert_order_tax = DB::table('order_tax')->insert( $order_tax );

                

    

            // ORDER ADDRESS INSERT

            $address = array();

            $address = [

                'user_id'=>auth()->user()->id,

                'city'=>$request->city,

                'order_unique_id'=>$request->order_unique_id,

                'state'=>$request->state,

                'area'=>$request->area,

                'pincode'=>$request->pincode,

                'country'=>$request->country,

                'lat'=>$request->lat,

                'lng'=>$request->lng,

                'phone'=>$request->phone,

                'created_at'=>Carbon::now(),

                'updated_at'=>Carbon::now(),

            ];

             $address = DB::table('delivery_address')->insert($address);

         

    

             if(isset($address))

             {

             $products = array();

             $products = [

                [

                    'order_unique_id'=>1234,

                    'product_id'=>1,

                    'restaurant_id'=>2,

                    'quantity'=>10,

                    'product_price'=>250,

                ],

                [

                    'order_unique_id'=>1234,

                    'product_id'=>1,

                    'restaurant_id'=>2,

                    'quantity'=>10,

                    'product_price'=>250,

                ],

    

             ];

    

             foreach($products as $product){

                $array = [

                    'created_at'=>Carbon::now(),

                    'updated_at'=>Carbon::now(),

                    ];

                $product = array_merge($product,$array);

    

                $insert = DB::table('order_item')->insert($product);

                //return $product;

    

                $product = DB::table('products')->where('product_id', $product['product_id'])->first();

    

                    $sell_count = array();

                    $sell_count = [

                        'product_sell_count'=> (int)$product->product_sell_count + 1,

                        'updated_at'=>Carbon::now()

                    ];

    

                 $update = DB::table('products')->where('product_id', $product->product_id)->update($sell_count);

             }

              

            $delete = DB::table('cart') ->where('user_id', auth()->user()->id)->delete();

    

             if(auth()->user()->refarel_id != null)

             {

                $refer_check = DB::table('users')->where('refer_id', auth()->user()->refarel_id)->select('id')->first();

    

                 if($refer_check != null)

                 {

                   $order_check = DB::table('orders')->where('user_id', auth()->user()->id)->count();

    

                   if($order_check == 1 || $order_check ==2 || $order_check == 3)

                   {

                    switch($order_check)

                    {

                        case "1":

                        $amount = 100;

                        break;

                        case "2":

                        $amount = 50;

                        break;

                        case "3":

                        $amount = 25;

                        break;

                        default :

                        $amount = 0;

                    }

    

                    $walet = DB::table('walet')->where('user_id', $refer_check->id)->first();

                    $data = array();

                    $data = [

                        'amount'=>$walet->amount + (int)$amount,

                        'updated_at'=>Carbon::now(),

                    ];

    

                  $update_walet = DB::table('walet')->where('user_id', $refer_check->id)->update($data);

                  

                  $log = array();

                  $log = [

                    'walet_id'=>$walet->walet_id,

                    // 'user_id'=>$refer_check->id,

                    'type'=>'refarel',

                    'amount'=>$amount,

                    'order_unique_id'=>$datas['order_unique_id'],

                    'created_at'=>Carbon::now(),

                    'updated_at'=>Carbon::now(),

                  ];

    

                  $insert_waletlog = DB::table('walet_log')->insert($log);

                   }

    

                 }

             }

    

             $email = array();

             $email = [

                'order_id'=>$datas['order_unique_id'],

                'transaction_id'=>$datas['transaction_id'],

                'payment_mode'=>'Online',

                'transaction_amount'=>$datas['transaction_amount'],

                'order_status'=>'Placed'

             ];

    

             $user['to'] = auth()->user()->email;

             mail::send('order_confirm', $email, function($message) use ($user){

                $message->to($user['to']);

                $message->subject('Order Confirmation');

             });

            

              return $this->order_assign($datas['order_unique_id']);

    

             }

             

        }   



        public function order_complete(){



            $order = DB::table('orders')->where('order_status', 'completed')->where('user_id', auth()->user()->id)->get();

            if(isset($order)){

                return response()->json([

                    'status'=>'success',

                    'data'=>$order

                ]);

            }else{

                return response()->json([

                    'status'=>204,

                    'data'=> 'not found'

                ]);

            }

       

        }

    

        public function order_upcoming(){

    

            $order = DB::table('orders')->where('order_status', '!=', 'completed')->where('user_id', auth()->user()->id)->get();

            if(isset($order)){

                return response()->json([

                    'status'=>'success',

                    'data'=>$order

                ]);

            }else{

                return response()->json([

                    'status'=>204,

                    'data'=> 'not found'

                ]);

            }

       

        }





    public function order_status($id){

        $order = DB::table('order_status')->where('order_unique_id',  $id)->get();

        if(isset($order)){

            return response()->json([

                'status'=>'success',

                'data'=>$order

            ]);

        }else{

            return response()->json([

                'status'=>204,

                'data'=> 'not found'

            ]);

        }

    }

    

}

