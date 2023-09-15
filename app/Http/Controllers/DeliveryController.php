<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class DeliveryController extends Controller
{
    //FOR ADMIN AND SUPER ADMIN

    public function get_all_delivery()
    {
        $delivery = DB::table( 'users' )
                    ->leftJoin('delivery_boy' , 'delivery_boy.delivery_id', '=', 'users.id')
                    ->where( 'users.role', 'delivery' )
                    ->get();

        if( isset($delivery) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>$delivery
            ]);
        }
    }


    //FOR ADMIN AND SUPER ADMIN

      public function edit_delivery(Request $request, $id)
    {   
        $data =  array();
        $data = [
            'status' =>$request->status,
        ];

        $update = DB::table('delivery_boy')->where('delivery_id', $id)->update($data);
       
       if( isset($update) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>'Approved',
            ]);
        }
    }

 //FOR ADMIN AND SUPER ADMIN

    public function get_delivery($id)
    {
        $single_delivery = DB::table( 'users' )
                    ->leftJoin('delivery_boy' , 'delivery_boy.delivery_id', '=', 'users.id')
                    ->leftJoin('deliveryboy_status','deliveryboy_status.deliveryboy_id','=','users.id')
                    ->where( 'users.id', $id )
                    ->first();

        if( isset($single_delivery) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>$single_delivery
            ]);
        }
}

//for delivery partner edit delivery STATUS
public function edit_delivery_status(request $request){
    $data = array();
    $data['status'] = $request->status;
  $status_update = DB::table('deliveryboy_status')->where('order_unique_id',$request->order_unique_id)->where('deliveryboy_id',auth()->user()->id )->update($data);
if($status_update){
    $data1 = array();
    $data1 = [
        'status'=> $request->status ,
        'status_time'=> Carbon::now(),
    
    ];
      $insert = DB::table('order_status')->where('order_unique_id',$request->order_unique_id)->update($data1);
      if($insert){
        return response()->json([
            'status'=>'success',
            'data'=>'updated successfully'
            ]); 
    }
    else{
        return response()->json([
           
            'data'=>'something went wrong'
            ]); 
    }
}
else{
    return response()->json([
           
        'data'=>'something went wrong2'
        ]);  
}
 

 
}
 //FOR DELIVERY STATUS

    public function check_status()
    {
        $check_status = DB::table( 'delivery_boy' )->select('status')->where( 'delivery_id', auth()->user()->id )->get();

        if( isset($check_status) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>$check_status
            ]);
        }
    }

    //FOR ADMIN AND SUPER ADMIN

    function delivery_delete($id)
    {
        $delete_delivery_user = DB::table( 'users' )->where('id', $id)->delete();
        $delete_delivery_boy = DB::table( 'delivery_boy' )->where('delivery_id', $id)->delete();

        if( isset($delete_delivery_user) && isset($delete_delivery_boy) )
        {
            return response()->json([
            'status'=>'success',
            'data'=>'Deleted Successfully'
            ]);
        }
    }

    //FOR DELIVERY BOY

    function view_delivery()
    {
        // return $delivery = DB::table('orders')
        // ->leftjoin('restaurant', 'restaurant.restaurant_id', '=', 'orders.restaurant_id')
        // ->leftjoin('restaurant_rating', 'restaurant_rating.restaurant_id', '=', 'restaurant.restaurant_id')
        // ->leftjoin('deliveryboy_status', 'deliveryboy_status.order_unique_id', '=', 'orders.order_unique_id')
        // ->select('orders.order_id', 'restaurant.restaurant_image', 'restaurant.restaurant_name', 'restaurant.restaurant_rating_count', 'restaurant_rating.rate', 'deliveryboy_status.status', 'orders.transaction_amount')
        // ->where('deliveryboy_status.order_unique_id', auth()->user()->id)
        // ->get();

        $delivery = DB::table('deliveryboy_status')
        ->leftjoin('orders', 'orders.order_unique_id', '=', 'deliveryboy_status.order_unique_id')
        ->leftjoin('restaurant', 'restaurant.restaurant_id', '=', 'orders.restaurant_id')
        ->select('orders.order_id', 'orders.order_unique_id', 'restaurant.restaurant_image', 'restaurant.restaurant_name', 'restaurant.restaurant_rating_count', 'deliveryboy_status.status', 'orders.transaction_amount')
        ->where('deliveryboy_status.deliveryboy_id', auth()->user()->id)
        ->get();


        return response()->json([
            'status'=>'success',
            'data'=>$delivery
        ]);
    }

    function delivery_details($id)
    {
       // $data = $id;
        $delivery_details = DB::table('orders')
        ->leftjoin('restaurant', 'restaurant.restaurant_id', '=', 'orders.restaurant_id')
        // ->leftjoin('restaurant_rating', 'restaurant_rating.restaurant_id', '=', 'restaurant.restaurant_id')
        ->leftjoin('order_item', 'order_item.order_unique_id', '=', 'orders.order_unique_id')
        ->leftjoin('products', 'products.product_id', '=', 'order_item.product_id')
        ->leftjoin('assign_deliveryboy','assign_deliveryboy.order_unique_id', '=','orders.order_unique_id')
         ->leftjoin('delivery_address', 'delivery_address.order_unique_id', '=', 'orders.order_unique_id')
        ->select('restaurant.restaurant_address', 'restaurant.lat AS res_lat', 'restaurant.lng AS res_lng', 'products.product_name', 'order_item.product_price', 'order_item.quantity', 'delivery_address.city', 'delivery_address.state', 'delivery_address.area', 'delivery_address.pincode', 'delivery_address.country', 'delivery_address.lat AS address_lat', 'delivery_address.lng AS address_lng','assign_deliveryboy.pickup_lat','assign_deliveryboy.pickup_lng','assign_deliveryboy.del_lat','assign_deliveryboy.del_lng')
        ->where('orders.order_unique_id', $id)
        ->where('assign_deliveryboy.delivery_boy_id', auth()->user()->id)
        ->get()->first();



        return response()->json([
            'status'=>'success',
            'data'=>$delivery_details
        ]);
    }
    
    function active_status($status)
    {
        $change_status = DB::table( 'delivery_boy' )->update(['active'=>$status]);
        if( isset( $change_status ) )
        {
            return response()->json([
                'status'=>$status == 1 ? 'online' : 'offline',
            ]);
        }
        else
        {
            return response()->json([
                'status'=>'fail',
            ]);
        }
    }
    
    public function assign_delivery_boy($id){
        $request->validate([
            'delivery_boy_id'=>required
            ]);
            
            $assign = DB::table('assign_deliveryboy')->insert(['order_unique_id'=>$id, 'delivery_boy_id'=>$request->delivery_boy_id, 'created_at'=>Carbon::now()]);
            if(isset($assign)){
                $user = DB::table('deliver_boy')
                ->leftJoin('users', 'deliver_boy.delivery_id', 'users.id')
                ->where('delivery_boy.delivery_boy_id', $request->delivery_boy_id)
                ->first();
                $device_token = $user->fcm_code;
                $message = "New order assing";

                try {
                    $SERVER_API_KEY = 'AAAAo-l86rw:APA91bHJhbMDarKCEDNhhh8tO22d4kC-qS9hU3TTbWf0LAELnVh_RhdIXRfLFX75NezI9dDoiU_rLhWroJNshyCTVIbibVLblSHpv8YoIy8jleSPM-ftydmXTUDpH-loF0pAWAvK427f';
        
                    // payload data, it will vary according to requirement
                    $data = [
                        "registration_ids" => $device_token, // for multiple device ids
                        "data" => $message,
                    ];
                    $dataString = json_encode($data);
        
                    $headers = [
                        'Authorization: key=' . $SERVER_API_KEY,
                        'Content-Type: application/json',
                    ];
        
                    $ch = curl_init();
        
                    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $data_r['status'] = '1';
                    $data_r['msg'] = 'success';
                    $data_r['data'] = json_decode($response);
                    $response = json_decode($response);
                    return response()->json($data_r, 200);
                    //return $response;
                } catch (\Throwable $th) {
                    //throw $th;
                    $datae['status'] = "0";
                    $datae["msg"] = "failed";
                    $datae["data"] = "something went wrong";
                    return response()->json($datae, 501);
                }

                return response()->json([
                    'status'=>'success',
                    'data'=>'assign successfully'
                    ]);
            }
    }
  

    // public function check_orders(){
    //     return response()->json([
    //         'status'=>204,
    //         'data'=> auth()->user()->id 
    //     ]);
    //     $order = DB::table('deliveryboy_status')->leftjoin('orders','orders.order_unique_id = deliveryboy_status.order_unique_id')->where('deliveryboy_status.deliveryboy_id', auth()->user()->id)->get();
    //     if(isset($order)){
    //         return response()->json([
    //             'status'=>'success',
    //             'data'=>$order
    //         ]);
    //     }else{
    //         return response()->json([
    //             'status'=>204,
    //             'data'=> 'not found' 
    //         ]);
    //     }
    
    // }
}
