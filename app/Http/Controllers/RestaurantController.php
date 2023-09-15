<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Image;
use Illuminate\Support\Str;



// $sql="
//          SELECT
//   *, (
//     6371 * acos (
//       cos ( radians('".$lat."') )
//       * cos( radians( lat ) )
//       * cos( radians( lng ) - radians('".$lng."') )
//       + sin ( radians('".$lat."') )
//       * sin( radians( lat ) )
//     )
//   ) AS distance
// FROM places 
// ORDER BY distance
// LIMIT 0 , 100;";
        
//     //  error_log($sql);
//         $result=mysqli_query($connection, $sql);
//         if ($result->num_rows>0)
        
//         { 
            
//             $arr = array();
//             while($r=$result->fetch_assoc())
             
//             {
//                 //@$productData = NULL; 
//                 @$productData = new \stdClass();
                 
                 
//                 $productData->id=$r["id"];
//                 $productData->route_name=$r["route_name"];
//                 $productData->pickup_point=$r["pickup_point"];
//                 $productData->state=$r["state"];
//                 $productData->country=$r["country"];
//                 $prod


class RestaurantController extends Controller
{
    //VIEW ALL RESTAURANT FOR USER

    public function all_restaurant(){
        // dd($_GET);
        if(isset($_GET['lat']) && isset($_GET['lng'])){
            $lat = $_GET['lat'];
            $lng = $_GET['lng'];
            $restaurants = DB::table(function($query) use ($lat, $lng) {
                $query->selectRaw("*,
                    (6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)))) AS distance", 
                    [$lat, $lng, $lat])
                    ->from('restaurant');
            }, 'sub')
            ->where("distance", "<", 270)
            ->orderBy("distance", 'asc')
            ->get();

                    // dd($restaurants);
                    
            
                    
         
 // SELECT Name, StreetAddress__c 
// FROM Warehouse__c 
// WHERE DISTANCE(Location__c, GEOLOCATION(37.775,-122.418), 'mi') < 20 
// ORDER BY DISTANCE(Location__c, GEOLOCATION(37.775,-122.418), 'mi')
// LIMIT 10

        // $restaurants = DB::table('restaurant')
        // ->orderBy('restaurant.restaurant_id','desc')
        // ->select('restaurant_id', 'restaurant_name','restaurant_address','restaurant_image', 'restaurant_rating','restaurant_rating_count')
        // ->get();
        return response()->json([
            'status'=> '200',
           'data'=> $restaurants]);
        }else{
            return response()->json([
                'status'=>'success',
                'data'=>'Please set your location'
                ]);
        }

        // $restaurants = DB::select("SELECT  *, ( 6371 * acos ( cos ( radians('25.04269269636217') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('87.97807955657181') ) + sin ( radians('25.04269269636217')) * sin( radians( lat ) ) ) ) AS distance FROM restaurant HAVING distance > 250 && distance < 300 ORDER BY distance LIMIT 0 , 100");

       
    }

    //VIEW ALL RESTAURANT FOR ADMIN & SUPER ADMIN

    public function admin_all_restaurant(){

        if(auth()->user()->role == 'admin'){
            $restaurants = DB::table('restaurant')
            ->leftjoin('users', 'users.id','=', 'restaurant.restaurant_added_by')
            ->orderBy('restaurant.restaurant_id','desc')
            ->where('restaurant.restaurant_added_by', auth()->user()->id)
            ->select('users.name', 'restaurant.*')
            ->get();
            return response()->json([
                'status'=> '200',
               'data'=> $restaurants]);
        }elseif(auth()->user()->role == 'super_admin'){
           $restaurants = DB::table('restaurant')
            ->leftjoin('users', 'users.id','=', 'restaurant.restaurant_added_by')
            ->orderBy('restaurant.restaurant_id','desc')
            ->select('users.name', 'restaurant.*')
            ->get();
            return response()->json([
                'status'=> '200',
               'data'=> $restaurants]); 
        }else{
            return response()->json([
                'status'=>400,
                'data'=>'Unauthorized'
            ]);
        }

        
    }

    //ADD RESTAURANT FOR ADMIN & SUPER ADMIN

    public function add_restaurant(Request $request){
       // dd($request); 
        $request->validate([
            'restaurant_name'=>'required',
            // 'restaurant_image'=>'required',
            'restaurant_address'=>'required',
            'restaurant_meta_deta'=>'required',
            'restaurant_ph'=>'required',
            'lat'=>'required',
            'lng'=>'required',
        ]);
        //dd($request);
        $data = array();
          
        $data = [
            'restaurant_name' => $request->restaurant_name,
            'restaurant_address'=>$request->restaurant_address,
            'restaurant_added_by'=>auth()->user()->id,
            'restaurant_meta_deta'=>$request->restaurant_meta_deta,
            'restaurant_ph'=>$request->restaurant_ph,
            'lat'=>$request->lat,
            'lng'=>$request->lng,
             'created_at'=>Carbon::now(),
             'updated_at'=> Carbon::now(),
        ];

        if($request->hasFile('restaurant_image')){
            if ($request->file('restaurant_image')->isValid()) {
                $file = $request->file('restaurant_image');
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                $img = Image::make($request->restaurant_image)->resize(240,200)->save("public/assets/restaurant/".$mainFilename);
                $db_name = "/public/assets/restaurant"."/".$mainFilename;
                $new_data = array();
                $new_data = ['restaurant_image'=>$db_name];
                $data = array_merge($data,$new_data);
                
            }
            }

        $query = DB::table('restaurant')->insert($data);
        if(isset($query)){
            return response()->json([
                'status'=>200,
                'success'=>'Data insert successfully'
            ]);
        }
        
    }

    //GET SINGLE RESTAURANT FOR ADMIN & SUPER ADMIN

    public function get_edit_restaurant($id){

        if(auth()->user()->role == 'admin'){
            $restaurant = DB::table('restaurant')
            ->leftjoin('users', 'users.id', '=', 'restaurant.restaurant_added_by')
            ->where('restaurant.restaurant_id', $id)
            ->where('restaurant.restaurant_added_by', auth()->user()->id)
            ->select('users.name', 'restaurant.*')
            ->first(); 
            // dd($restaurant);

        }elseif(auth()->user()->role == 'super_admin'){
            $restaurant = DB::table('restaurant')
            ->leftjoin('users', 'users.id', '=', 'restaurant.restaurant_added_by')
            ->where('restaurant.restaurant_id', $id)
            ->select('users.name', 'restaurant.*')
            ->first(); 
        }else{
            return response()->json([
                'status'=>204,
                'data'=>"Unauthorized",
            ]);
        }
       
        if(isset($restaurant)){
            return response()->json([
                'status'=>200,
                'data'=>$restaurant
            ]);
        }else{
            return response()->json([
                'status'=>204,
                'data'=>'Not found'
            ]);
        }

    }

    //EDIT RESTAURANT FOR ADMIN & SUPER ADMIN

    public function edit_restaurant(Request $request, $id){
   
        $request->validate([
            
            'restaurant_name'=>'required',
            'restaurant_address'=>'required',
            'restaurant_meta_deta'=>'required',
            'restaurant_ph'=>'required',
            'lat'=>'required',
            'lng'=>'required'
        ]);
        
        $data = array();

        $data = [
            'restaurant_name' => $request->restaurant_name,
            'restaurant_address'=>$request->restaurant_address,
            'restaurant_added_by'=>auth()->user()->id,
            'restaurant_meta_deta'=>$request->restaurant_meta_deta,
            'restaurant_ph'=>$request->restaurant_ph,
            'lat'=>$request->lat,
            'lng'=>$request->lng,
            'updated_at'=> Carbon::now(),
        ];

        if($request->hasFile('restaurant_image')){
            if ($request->file('restaurant_image')->isValid()) {
                $file = $request->file('restaurant_image');
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                $img = Image::make($request->restaurant_image)->resize(240,200)->save("public/assets/restaurant/".$mainFilename);
                $db_name = "/public/assets/restaurant/".$mainFilename;

                $new_data = array();
                $new_data = ['restaurant_image'=>$db_name];
                $data = array_merge($data,$new_data);
                
            }
            }
        
        $update = DB::table('restaurant')->where('restaurant_id', $id)->update($data);

        if(isset($update)){
            return response()->json([
                'status'=>'success',
                'data'=>$data,
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'data'=>'something wrong',
            ]);
        }
    }
}
