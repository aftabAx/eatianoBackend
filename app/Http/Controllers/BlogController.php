<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Image; 
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function all_blogs(){
        $blogs = DB::table('blogs')
        ->leftjoin('users', 'users.id', '=', 'blogs.user_id')
        ->leftjoin('blog_images', 'blog_images.blog_id', '=', 'blogs.blog_id')
        ->select('users.name', 'blog_images.*', 'blogs.*')
        ->orderBy('blogs.blog_id', 'desc')
        ->get();

        if(isset($blogs)){
            return response()->json([
                'status'=>200,
                'data'=>$blogs
            ]);
        }else{
            return response()->json([
                'status'=>204,
                'data'=> 'not found'
            ]);
        }
    }

    public function like_blog($id){
        $data = array();
        $data = [
            'user_id'=>1,
            'blog_id'=>$id,
            'created_at'=>Carbon::now(),
            'updated_at'=> Carbon::now(),
        ];
        $like = DB::table('blog_likes')->insert($data);
        $like = DB::table('blogs')->where('blog_id', $id)->select('blog_likes')->first();
        
        $update = DB::table('blogs')->where('blog_id',$id)->update(['blog_likes'=> $like->blog_likes +1]);
        
    }


    public function admin_all_blogs(){
        if(auth()->user()->role == 'admin'){
            $blogs = DB::table('blogs')
            ->leftjoin('users', 'users.id', '=', 'blogs.user_id')
            ->leftjoin('blog_images', 'blogs.blog_id','=', 'blog_images.blog_id')
            ->where('blogs.user_id', auth()->user()->id)
            ->select('users.name', 'blog_images.blog_image', 'blogs.*')
            ->orderBy('blogs.blog_id', 'desc')
            ->get(); 
        }elseif(auth()->user()->role == 'super_admin'){
           $blogs = DB::table('blogs')
            ->leftjoin('users', 'users.id', '=', 'blogs.user_id')
            ->leftjoin('blog_images', 'blogs.blog_id','=', 'blog_images.blog_id')
            ->select('users.name', 'blog_images.blog_image', 'blogs.*')
            ->orderBy('blogs.blog_id', 'desc')
            ->get();
        }else{
            return response()->json([
                'status'=>204,
                'data'=>'Unauthorized'
            ]);
        }

        if(isset($blogs)){
           return response()->json([
                'status'=>200,
                'data'=>$blogs
            ]); 
        }else{
            return response()->json([
                'status'=>204,
                'data'=>'Not found'
            ]);
        }

        
       
    }
    public function add_blog(Request $request){
        $request->validate([
            'blog_heading'=>'required|max:250',
            'blog_details'=>'required',
            // 'blog_main_image'=>'required',
            'blog_meta_data'=>'required'
        ]);
        if($request->hasFile('blog_main_image')){
            if ($request->file('blog_main_image')->isValid()) {
                $file = $request->file('blog_main_image');
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                $img = Image::make($request->blog_main_image)->resize(240,200)->save("public/assets/blog/".$mainFilename);
                $db_name = "/public/assets/blog"."/".$mainFilename;
                
            }
            }else{
                $db_name = '';
            }
        $data = array();
        $data = [
          'blog_heading'=>$request->blog_heading,
          'blog_subheading'=>$request->blog_subheading,
          'blog_main_image'=>$db_name,
          'blog_details'=>$request->blog_details,
          'blog_meta_data'=>$request->blog_meta_data,
          'user_id'=>auth()->user()->id,
          'blog_likes'=>0,
          'created_at'=>Carbon::now(),
          'updated_at'=> Carbon::now(),
        ];
        $insert = DB::table('blogs')->insert($data);

        $blog_id = DB::table('blogs')->where('blog_main_image', $db_name)->select('blog_id')->first();


        if($request->hasFile('blog_images')){
            foreach($request->file('blog_images') as $image){
                $file = $image;
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                $img = Image::make($image)->resize(240,200)->save("public/assets/blog/".$mainFilename);
                $db_name = "/public/assets/blog"."/".$mainFilename;
                $images = array();
                $images = [
                    'blog_image'=>$db_name,
                    'blog_id'=> $blog_id,
                    'created_at'=>Carbon::now(),
                    'updated_at'=> Carbon::now(),
                ];

                $insert = DB::table('blog_images')->insert($images);
            }
        }
        return response()->json([
            'status'=>'success',
            'data'=>'blog upload successfully',
        ]);

    }

    public function get_edit_blog($id){
        $query = DB::table('blogs')->leftjoin('blog_images', 'blogs.blog_id','=', 'blog_images.blog_id')->where('blogs.blog_id', $id)
        ->select('blogs.*', 'blog_images.blog_image')
        ->first();
        return response()->json([
            'status'=>'success',
            'data'=>$query,
        ]);
    }

    public function edit_blog(Request $request, $id){
        $data = array();
        
        $data = [
          'blog_heading'=>$request->blog_heading,
          'blog_subheading'=>$request->blog_subheading,
          'blog_details'=>$request->blog_details,
          'blog_meta_data'=>$request->blog_meta_data,
          'updated_at'=> Carbon::now(),
        ];

        if($request->hasFile('blog_main_image')){
            if ($request->file('blog_main_image')->isValid()) {
                $file = $request->file('blog_main_image');
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                $img = Image::make($request->blog_main_image)->resize(240,200)->save("public/assets/blog/".$mainFilename);
                $db_name = "/public/assets/blog"."/".$mainFilename;
                
                $new_data = array();
                $new_data = ['blog_main_image'=>$db_name];
                $data = array_merge($data,$new_data);
            }
            }

            $update = DB::table('blogs')->where('blog_id', $id)->update($data);
            if(isset($update)){
                return response()->json([
                    'status'=>'success',
                    'data'=>'Blog updated successsfully'
                ]);
            }else{
                return response()->json([
                    'status'=>'fail',
                    'data'=>'Something error'
                ]);
            }
       
    }

    public function delete_blog($id){
        $images = DB::table('blogs')
        ->leftjoin('blog_images','blogs.blog_id', '=', 'blog_images.blog_id')
        ->where('blogs.blog_id',$id)
        ->select('blogs.blog_main_image','blog_images.blog_image')->get();
        foreach($images as $image){
           $path = $image->blog_image;
            $path_main = $image->blog_main_image;
            if(file_exists($path)){
                unlink($path);
            }
            if(file_exists($path_main)){
                 unlink($path_main);
            }
            
        }
        $delete_blog = DB::table('blogs')->where('blog_id', $id)->delete();
        $delete_blog_image = DB::table('blog_images')->where('blog_id', $id)->delete();

        return response()->json([
            'status'=>'success',
            'data'=>'Blog deleted successfully'
        ]);
        
    }
}
