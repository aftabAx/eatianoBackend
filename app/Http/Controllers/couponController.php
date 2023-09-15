<?php



namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use Carbon\Carbon;



class couponController extends Controller

{

   public function apply_coupon(Request $request){

    $request->validate([

        'coupon_code'=> 'required',

        'total_amount'=> 'required'

    ]);



    $check = DB::table('coupon')->where('coupon_code', $request->coupon_code)->first();

    if(isset($check)){

        if($check->condition != null){

            if($check->condition <= $request->total_amount){

               return response()->json([

            'status'=>'success',

            'data'=>$check->discount

        ]); 

           }else{

            return response()->json([

            'status'=>'fail',

            'data'=>'coupon is not applicable for this amount',

        ]);

           }

        }else{

            return response()->json([

            'status'=>'success',

            'data'=>$check->discount

            ]);

        }

        

    }else{

        return response()->json([

            'status'=>'fail',

            'data'=>'Coupon not exist'

        ]);

    }



   }



   public function all_coupon(){



    $coupon = DB::table('coupon')->orderBy('coupon_id', 'desc')->get();

    if(!$coupon->isEmpty()){



        return response()->json([

            'data'=>'success',

            'data'=>$coupon

        ]);

    }else{

        return response()->json([

            'data'=>'fail',

            'data'=>'Data not exist'

        ]);

    }

   }



   public function add_coupon(Request $request){

    $request->validate([

        'coupon_code'=>'required',

        'discount'=>'required'

    ]);

    $data = array();

    $data = [

        'coupon_code'=> $request->coupon_code,

        'discount'=> $request->discount,

        'condition'=>$request->condition,

        'created_at'=>Carbon::now(),

        'updated_at'=>Carbon::now()

    ];

    if($request->hasFile('coupon_image')){

            if ($request->file('coupon_image')->isValid()) {

                $file = $request->file('coupon_image');

                $ext= $file->getClientOriginalExtension();

                $mainFilename = Str::random(6).date('h-i-s').".".$ext;

                // $img = Image::make($request->coupon_image)->save("assets/product/".$mainFilename);

                $request->file('coupon_image')->move('assets/product/', $mainFilename);

                $db_name = "/assets/product/".$mainFilename;

                

                $data['coupon_image'] = $db_name;

            }

        }

    $insert = DB::table('coupon')->insert($data);

    if(isset($insert)){

        return response()->json([

            'status'=>'success',

            'data'=> 'Data inserted successfully'

        ]);

    }else{

        return response()->json([

            'status'=>'fail',

            'data'=> 'Somthing went wrong'

        ]);

    }

   }



   public function get_coupon($id){



    $coupon = DB::table('coupon')

    ->where('coupon_id', $id)

    ->orderBy('coupon_id', 'desc')->first();

    if($coupon){

        return response()->json([

            'data'=>'success',

            'data'=>$coupon

        ]);

    }else{

        return response()->json([

            'data'=>'fail',

            'data'=>'Data doesnot exist'

        ]);

    }

   }



   public function edit_coupon(Request $request, $id){

    $data = array();

    $data = [

        'coupon_code'=> $request->coupon_code,

        'discount'=> $request->discount,

        'condition'=>$request->condition,

        'updated_at'=>Carbon::now()

    ];

    $update = DB::table('coupon')->where('coupon_id', $id)->update($data);

    if(isset($update)){

        return response()->json([

            'status'=>'success',

            'data'=>'Updated successfully'

        ]);

    }else{

        return response()->json([

            'status'=>'fail',

            'data'=>'Somthing went wrong'

        ]);

    }

   }



   public function delete_coupon($id){

    $delete = DB::table('coupon')->where('coupon_id', $id)->delete();

    if(isset($delete)){

      return response()->json([

        'status'=>'success',

        'data'=>'deleted successfully'

    ]);  

  }else{

    return response()->json([

        'status'=>'success',

        'data'=>'deleted successfully'

    ]);

  }

    

   }

}

