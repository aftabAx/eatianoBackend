<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Image; 
use Illuminate\Support\Str;
class ExpertChoice extends Controller
{
    function admin_expert(Request $request){

        $request->validate([
            'name'=>'required|max:55',
            'rest_id'=>'required',
            'review'=>'required',
            'rate'=>'required'
        ]);

        $name = $request->name;
        $rest_id = $request->rest_id;
        $review = $request->review;
        $rate = $request->rate;

        if($request->hasFile('image')){
            if ($request->file('image')->isValid()) {
                $file = $request->file('image');
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                $path = public_path() . '/assets/expert/';
                $file->move($path,$mainFilename);
                //$img = Image::make($request->image)->resize(240,200)->save("public/assets/expert/".$mainFilename);
                $db_name = "/public/assets/expert"."/".$mainFilename;

                
            }
            }else{
                $db_name = '';
            }

            $data = array();

            $data = [
                "name" => $name,
                "rest_id" => $rest_id,
                "review" => $review,
                "rate" => $rate,
                "image" => $db_name,
                "created_at" => Carbon::now('Asia/Kolkata'),
                "updated_at" => Carbon::now('Asia/Kolkata')
            ];

            $insert = DB::table('expert')->insert($data);

            if($insert){
        return response()->json([
            'status'=>'success'
       ]);
    }
    else{
        return response()->json([
            'status'=>'fail'
       ]);
    }

    }

    function get_edit_expert($id){
        $query = DB::table('expert')->leftJoin('restaurant','restaurant_id','=','rest_id')->where('expert.expert_id', $id)->first();
        return response()->json([
            'status'=>'success',
            'data'=>$query,
        ]);
    }

    public function edit_expert_post(Request $request, $id){

        $request->validate([
            'name'=>'required|max:55',
            'rest_id'=>'required',
            'review'=>'required',
            'rate'=>'required'
        ]);

        $name = $request->name;
        $rest_id = $request->rest_id;
        $review = $request->review;
        $rate = $request->rate;

        $data = array();
        
        if($request->hasFile('image')){
            if ($request->file('image')->isValid()) {
                $file = $request->file('image');
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                $path = public_path() . '/assets/expert/';
                $file->move($path,$mainFilename);
                //$img = Image::make($request->image)->resize(240,200)->save("public/assets/expert/".$mainFilename);
                $db_name = "/public/assets/expert"."/".$mainFilename;

                
            }
            }

            $data = [
                "name" => $name,
                "rest_id" => $rest_id,
                "review" => $review,
                "rate" => $rate,
                "image" => $db_name,
                'updated_at'=> Carbon::now('Asia/Kolkata'),
            ];

            $update = DB::table('expert')->where('expert_id', $id)->update($data);
            if(isset($update)){
                return response()->json([
                    'status'=>'success',
                    'data'=>'Expert Choice updated successsfully'
                ]);
            }else{
                return response()->json([
                    'status'=>'fail',
                    'data'=>'Something error'
                ]);
            }
       
    }


    public function delete_expert($id){
        $images = DB::table('expert')->where('expert_id',$id)->get()->first();
           $path = $images->image;
            if(file_exists($path)){
                unlink($path);
            }
        $delete_expert = DB::table('expert')->where('expert_id', $id)->delete();

        return response()->json([
            'status'=>'success',
            'data'=>'Expert Choice deleted successfully'
        ]);
        
    }


    function get_expert(){
        $query = DB::table('expert')->leftJoin('restaurant','restaurant_id','=','rest_id')->get();
        return response()->json([
            'status'=>'success',
            'data'=>$query,
        ]);
    }

}
