<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Image; 
use Illuminate\Support\Str;

class categoryController extends Controller
{
    public function add_category(Request $request){
        $request->validate([
            'category_name'=>'required',
        ]);
        $data = array();
        $data = [
            'category_name'=>$request->category_name,
            'parent_id'=>$request->parent_id,
            'created_at'=> Carbon::now(),
            'updated_at'=>Carbon::now()
        ];
        $insert = DB::table('category')->insert($data);

        if(isset($insert)){
            return response()->json([
                'status'=> 'success',
                'data'=> "Category inserted successfully"
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'data'=>'error'
            ]);
        }
    }

    // public function get_all_category(){
    //     $data = DB::table('category')->orderBy('category_id', 'desc')->get();
    //     if(isset($data)){
    //         return response()->json([
    //             'status'=>'success',
    //             'data'=>$data
    //         ]);
    //     }else{
    //         return response()->json([
    //             'status'=>'fail',
    //             'data'=>'error'
    //         ]);
    //     }
    // }
    
    public function root_category()
    {
        $data = DB::table('category')->where('parent_id', 0)->get();
        if(isset($data)){
            return response()->json([
                'status'=>'success',
                'data'=>$data
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'data'=>'Not found'
            ]);
        }
    }
    
    public function sub_category($cid)
    {
        $data = DB::table('category')->where('parent_id', $cid)->get();
        if(isset($data)){
            return response()->json([
                'status'=>'success',
                'data'=>$data
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'data'=>'Not found'
            ]);
        }
    }

    public function edit_category (request $request, $id){

        $request->validate([
            'category_name'=>'required',
        ]);
        $data = array();
        $data = [
            'category_name'=>$request->category_name,
            'updated_at'=>Carbon::now()
        ];
        
        if( isset($request->parent_id) )
            $data['parent_id'] = $request->parent_id;
        
        $update = DB::table('category')->where('category_id', $id)->update($data);
        if(isset($update)){
                return response()->json([
                'status'=> 'success',
                'data'=> "Category updated successfully"
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'data'=>'error'
            ]);
        }

    }

    public function delete_category($id){
        $delete = DB::table('category')->where('category_id', $id)->delete();
        if(isset($delete)){
            return response()->json([
                'status'=> 'success',
                'data'=> "Category deleted successfully"
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'data'=>'error'
            ]);

        }
    }

    public function tastebuds(){

        $tastebuds = DB::table('tastebuds')->get()->first();
        return response()->json([
            'status'=>'success',
            'data'=>$tastebuds
        ]);

    }

    public function tastebud_post(Request $request){
        $request->validate([
            'tastebud_name'=>'required',
            'tastebud_image'=>'required',
        ]);
        if($request->hasFile('tastebud_image')){
            if ($request->file('tastebud_image')->isValid()) {
                $file = $request->file('tastebud_image');
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                $path = public_path() . '/assets/tastebud/';
                $file->move($path,$mainFilename);
                //$img = Image::make($request->image)->resize(240,200)->save("public/assets/tastebud/".$mainFilename);
                $db_name = "/public/assets/tastebud"."/".$mainFilename;
                
            }
            }
            $data = array();
        $data = [
          'tastebud_name'=>$request->tastebud_name,
          'tastebud_image'=>$db_name,
          'created_at'=>Carbon::now(),
          'updated_at'=> Carbon::now(),
        ];
        $insert = DB::table('tastebuds')->insert($data);
        if($insert){
            return response()->json([
                'status'=>'success',
                'data'=>'Tastebud added successfully'
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'data'=>'something went wrong'
            ]);
        }
    }

    public function edit_tastebud($id){
        $query = DB::table('tastebuds')
        ->where('tastebud_id',$id)
        ->get()
        ->first();
        return response()->json([
            'status'=>'success',
            'data'=>$query,
        ]);
    }

    public function edit_tastebud_post(Request $request){
      
        if($request->hasFile('tastebud_image')){
            if ($request->file('tastebud_image')->isValid()) {
                $file = $request->file('tastebud_image');
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                $path = public_path() . '/assets/tastebud/';
                $file->move($path,$mainFilename);
                //$img = Image::make($request->image)->resize(240,200)->save("public/assets/tastebud/".$mainFilename);
                $db_name = "/public/assets/tastebud"."/".$mainFilename;
                
            }
            }
            $data = array();
        
            $data = [
                'tastebud_name'=>$request->tastebud_name,
                'tastebud_image'=>$db_name,
              'updated_at'=> Carbon::now(),
            ];
    
            $update = DB::table('tastebuds')->where('tastebud_id',$request->tastebud_id)->update($data);
            if($update){
                return response()->json([
                    'status'=>'success',
                    'data'=>'Tastebud updated successfully'
                ]);
            }else{
                return response()->json([
                    'status'=>'fail',
                    'data'=>'something went wrong'
                ]);
            }
    }
    public function tastebud(){
        $tastebuds = DB::table('tastebuds')->get();
        return response()->json([
            'status'=>'success',
            'data'=>$tastebuds
        ]);

    }

    public function delete_tastebud($id){
        $tastebuds = DB::table('tastebuds')->where('tastebud_id',$id)->delete();
        if($tastebuds){
            return response()->json([
                'status'=>'success',
                'data'=>'Deleted Successfully'
            ]);
        }
        else{
            return response()->json([
                'status'=>'fail',
                'data'=>'Something went wrong'
            ]);
        }
      

    }
}