<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class membershipController extends Controller
{

// USER CONTROLLERS 
// CREATE MEMBER SHIP

   public function register_member(Request $request){
    $request->validate([
        'start_date'=>'required',
        'end_date'=>'required',
        'payment_type'=>'required',
        'payment_status'=>'required',
        'transaction_id'=>'required',
        //'merchant_name'=>'required',
        'membership_type_id'=>'required'
    ]);
    $data = array();
    $data = [
      'user_id'=>auth()->user()->id,
       'start_date'=>$request->start_date,
       'end_date'=>$request->end_date,
       'payment_type'=>$request->payment_type,
       'payment_status'=>$request->payment_status,
       'transaction_id'=>$request->transaction_id,
       //'merchant_name'=>$request->merchant_name,
       'membership_type_id'=>$request->membership_type_id,
       'created_at'=>Carbon::now(),
       'updated_at'=>Carbon::now(),
    ];
    $query = DB::table('membership')->insert($data);

    return response()->json([
         'status'=> 'success',
         'data'=> 'Data inserted successfully'
      ]);
   }

// ALL MEMBER SHIP DETAILS

   public function all_membership(){
      $data = DB::table('membership_type')->orderBy('membership_type_id','desc')->get();
    return response()->json([
        'status'=>'success',
        'data'=>$data
    ]);
   }

 // USER MEMBERSHIP INFO
   
   public function member_info($id){
      $data = DB::table('membership')
      ->leftjoin('membership_type', 'membership_type.membership_type_id', '=', 'membership.membership_type_id')
      ->where('user_id', $id)
      ->select('membership_type.membership_type_name', 'membership.start_date', 'membership.end_date')
      ->first();
      return response()->json([
         'status'=> 'success',
         'data'=>$data
      ]);
   }


   //ADMIN CONTROLLERS

   // VIEW ALL MEMBER

   public function view_all_member(){
    $data = DB::table('membership')->orderBy('membership_id','desc')->get();
    return response()->json([
        'status'=>'success',
        'data'=>$data
    ]);
   }

   // VIEW ALL MEMBERSHIP TYPE

   public function view_all_member_ship(){
    $data = DB::table('membership_type')->orderBy('membership_type_id','desc')->get();
    return response()->json([
        'status'=>'success',
        'data'=>$data
    ]);
   }

   // CREATE MEMBER SHIP TYPE

   public function create_membership_type(Request $request){
     
     //return $request->input();
          $request->validate([
         'membership_type_name'=>'required',
         'membership_price'=>'required',
         'time_period'=>'required',
         'discount_percent'=>'required'
      ]);
      $data = array();
      $data = [
         'membership_type_name'=>$request->membership_type_name,
         'membership_price'=>$request->membership_price,
         'time_period'=>$request->time_period,
         'discount_percent'=>$request->discount_percent
      ];
      $query = DB::table('membership_type')->insert($data);

      return response()->json([
         'status'=> 'success',
         'data'=> 'Data inserted successfully'
      ]);
      
   }
   
   //GET MEMBERSHIP TYPE
   
   public function get_edit_membership_type($id)
   {
   $membership_type = DB::table('membership_type')->where('membership_type_id', $id)->first();

   if(isset($membership_type))
    {
      return response()->json([
         'status'=> 'success',
         'data'=> $membership_type
      ]);
   }
}

   // EDIT MEMBER SHIP TYPE

   public function edit_membership_type(Request $request, $id){
     
          $request->validate([
         'membership_type_name'=>'required',
         'membership_price'=>'required'
      ]);
      $data = array();
      $data = [
         'membership_type_name'=>$request->membership_type_name,
         'membership_price'=>$request->membership_price
      ];
      $query = DB::table('membership_type')->where('membership_type_id', $id)->update($data);

      return response()->json([
         'status'=> 'success',
         'data'=> 'Data Updated successfully'
      ]);
      
   }

   // DELETE MEMBER SHIP TYPE

   public function delete_membership_type($id){
      $delete = DB::table('membership_type')->where('membership_type_id', $id)->delete();
      return response()->json([
         'status'=>'success',
         'data'=>'Deleted successfully'
      ]);
   }
}
