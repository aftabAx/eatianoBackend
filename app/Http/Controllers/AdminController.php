<?php



namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Hash;

use DB;

use Carbon\Carbon;

use Mail;

use Image;

use Illuminate\Support\Str;




class AdminController extends Controller

{

    public function get_all_admin()

    {

        $admins = DB::table( 'users' )->where( 'role', 'admin' )->get();



        if( isset($admins) )

        {

            return response()->json([

            'status'=>'success',

            'data'=>$admins

            ]);

        }

    }



    function admin_delete($id)

    {

        $delete_admin = DB::table( 'users' )->where('id', $id)->delete();

        $delete_warehouse = DB::table( 'warehouse' )->where('user_id', $id)->delete();



        if( isset($delete_admin) && isset($delete_warehouse) )

        {

            return response()->json([

            'status'=>'success',

            'data'=>'Deleted Successfully'

            ]);

        }

    }

    public function admin_register(Request $request)

    {

      $request->validate([

            'name'=>'required|max:50',

            'email'=>'email|required|unique:users',

            'phone'=>'required',

            'country'=>'required',

            'password'=>'required|min:6|max:12',  

            'address'=>'required',

            'lat'=>'required',

            'lng'=>'required',

            'status'=>'required'

        ]);



        $admin = array();

        $admin = [

            'name'=>$request->name,

            'email'=>$request->email,

            'phone'=>$request->phone,

            'country'=>$request->country,

            'password'=>Hash::make($request->password),

            'role'=>'admin'

        ];



        // dd($admin);

        $aquery = DB::table('users')->insert($admin);

        $user_id = DB::table('users')->select('users.id')->where('email', $request->email)->first();

        $warehouse = array();

        $warehouse = [

            'user_id'=>$user_id->id,

            'address'=>$request->address,

            'lat'=>$request->lat,

            'lng'=>$request->lng,

            'status'=>$request->status,

            'created_at'=>Carbon::now(),

            'updated_at'=> Carbon::now(),

        ];



        $wquery = DB::table('warehouse')->insert($warehouse);



        if(isset($aquery) && isset($wquery) ){

            return $this->r_login($request->email, $request->password);   

        }

    } 

    public function r_login($email, $password)

    {

        $credentials = [

           'email'=> $email, 

           'password'=> $password

       ];



        if (! $token = auth()->attempt($credentials)) {

            return response()->json(['error' => 'Unauthorized'], 401);

        }



        return $this->respondWithToken($token);

    }

    protected function respondWithToken($token)

    {

        return response()->json([

            'access_token' => $token,

            'token_type' => 'bearer',

            'role'=> auth()->user()->role,

            'expires_in' => auth()->factory()->getTTL() * 60 * 24 * 365

        ]);

    }

}

