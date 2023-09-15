<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Hash;
use DB;
use Carbon\Carbon;
use Mail;
use Image;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'admin_login', 'super_admin_login', 'delivery_login', 'signup', 'forget_password', 'check_otp', 'change_password']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    //USER REGISTRATION
    public function signup(Request $request)
    {
        $request->validate([
            'name'=>'required|max:50',
            'email'=>'required|email|unique:users',
            'phone'=>'required|unique:users',
            'password'=>'required|min:6|max:12',
            //'fcm_code'=>'required' 
        ]);

        $data = array();
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'country'=>$request->country,
            'password'=>Hash::make($request->password),
            'role'=>'user',
            'refarel_id'=>rand(11111,99999),
            'refer_id'=>$request->refer,
            'fb_id'=>$request->fb_id,
            'o_auth_id'=>$request->o_auth_id,
            'fcm_code'=>$request->o_auth_id,
        ];
        
        if($request->hasFile('profile_pic')){
            if ($request->file('profile_pic')->isValid()) {
                $file = $request->file('profile_pic');
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                // $img = Image::make($request->profile_pic)->save("assets/product/".$mainFilename);
                $request->file('profile_pic')->move('assets/product/', $mainFilename);
                $db_name = "/assets/product/".$mainFilename;
                
                $data['profile_pic'] = $db_name;
            }
        }else{
                 $data['profile_pic'] =$request->profile_pic;
            }

        $query = DB::table('users')->insert($data);

         if(isset($query))
         {
            $user_id = DB::table( 'users' )->select('id')->where( 'email', $request->email )->first();
        $datas = array();
        $datas = [
            'user_id'=>$user_id->id,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now()
        ];

        $pquery = DB::table('profile')->insert($datas);
        if(isset($pquery))
        {
            return $this->r_login($request->email, $request->password);   
        
        }
    }
 }  


 //DELIVERY REGISTRATION
    public function delivery_register(Request $request)
    {
      $request->validate([
            'name'=>'required|max:50',
            'email'=>'required|email',
            'phone'=>'required',
            'country'=>'required',
            'password'=>'required|min:6|max:12',
            'address'=>'required',
            // 'adhar'=>'required',
            // 'idprof'=>'required|mimes:pdf,jpg',
            // 'photo'=>'required|mimes:png,jpg,jpeg'
        ]);

        $data = array();
        $data = [
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'country'=>$request->country,
            'password'=>Hash::make($request->password),
            'role'=>'delivery'
        ];
        $query = DB::table('users')->insert($data);


        if(isset($query)){
            $delivery_id = DB::table( 'users' )->select('id')->where( 'email', $request->email )->first();

            $data = array();
            $data['delivery_id'] = $delivery_id->id;
            $data['address'] = $request->address;
            $data['adhar'] = $request->adhar;
            $data['official_email'] = $request->official_email;
            $data['status'] = 'Not Approved';

            // if($request->hasFile('idprof')){
            //     if ($request->file('idprof')->isValid()) {
            //         $file = $request->file('idprof');
            //         $ext= $file->getClientOriginalExtension();
            //         $mainFilename = Str::random(6).date('h-i-s').".".$ext;
            //         $img = Image::make($request->idprof)->save("assets/delivery/".$mainFilename);
            //         $db_name = "/public/assets/delivery/".$mainFilename;

            //         $data['idprof'] = $db_name;
            //     }
            // }

            // if($request->hasFile('photo')){
            //     if ($request->file('photo')->isValid()) {
            //         $file = $request->file('photo');
            //         $ext= $file->getClientOriginalExtension();
            //         $mainFilename = Str::random(6).date('h-i-s').".".$ext;
            //         $img = Image::make($request->photo)->save("assets/delivery/".$mainFilename);
            //         $db_name = "/public/assets/delivery/".$mainFilename;

            //         $data['photo'] = $db_name;
            //     }
            // }

            $insert_delivery_boy = DB::table( 'delivery_boy' )->insert( $data );

             $email = array();
             $email = [
                'password'=>$request->password
             ];

             $delivery['to'] = $request->email;

             mail::send('delivery_register', $email, function($message) use ($delivery)
             {
                $message->to($delivery['to']);
                $message->subject('Here is your password for login');
             });


            if( isset( $insert_delivery_boy ) )
                return $this->r_login($request->email, $request->password);   
        }
    }


    //ADMIN REGISTRATION 
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
    

    //R LOGIN
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


    //LOGIN
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $check_fcm = DB::table('users')->where('email',request(['email']))->get()->first();
        //return response()->json(request(['fcm']));
        $fcm = request(['fcm']);
        $fcm_code = request(['fcm_code']);
        
        //return response()->json($fcm_code['fcm_code']);
        
        if($check_fcm->fcm_code == "" && $fcm != null){
            $update_fcm = DB::table('users')->where('email',request(['email']))->update(['fcm_code'=>$fcm_code['fcm_code']]);
        }

        return $this->respondWithToken($token);
    }


    //ADMIN LOGIN
    public function admin_login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if( auth()->user()->role == 'admin'){
            return $this->respondWithToken($token);
        }else{
            auth()->logout();
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        
    }


    //SUPER ADMIN LOGIN
    public function super_admin_login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if( auth()->user()->role == 'super_admin'){
            return $this->respondWithToken($token);
        }else{
            auth()->logout();
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }


    //DELIVERY LOGIN
    public function delivery_login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if( auth()->user()->role == 'delivery'){
            $status = DB::table('delivery_boy')->select('status')->where('delivery_id', auth()->user()->id)->first();
            return $this->respondWithTokenDelivery($token, $status->status);
        }else{
            auth()->logout();
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    //PROFILE
    public function me()
    {
        return response()->json(auth()->user());
    }


    //EDIT USER PROFILE
    public function edit_user_profile(Request $request)
    {
        $data = array();
        if($request->phone != null){
           return $data = [
                'phone' => $request->phone,
                ]; 
        }
          if($request->hasFile('profile_pic')){
            if ($request->file('profile_pic')->isValid()) {
                $file = $request->file('profile_pic');
                $ext= $file->getClientOriginalExtension();
                $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                // $img = Image::make($request->profile_pic)->save("assets/product/".$mainFilename);
                $request->file('profile_pic')->move('assets/product/', $mainFilename);
                $db_name = "/assets/product/".$mainFilename;
                
                $data['profile_pic'] = $db_name;
            }
        }else if($request->profile_pic != null){
                 $data['profile_pic'] = $request->profile_pic;
            }

          $p_update = DB::table('users')->where('id', auth()->user()->id)->update($data);

        
        //$datas = [
            
            if(isset($p_update))
                {
                    return response()->json([
                        'status'=>'success',
                        'data'=>'Profile updated successsfully'
                    ]);
                }
                else
                {
                    return response()->json([
                        'status'=>'fail',
                        'data'=>'Something went error'
                    ]);
                }
        //];
          
    }
   

   //DELIVERY EDIT PROFILE
   public function edit_delivery_profile(Request $request)
    {
      $request->validate([
            'name'=>'required|max:50',
            'phone'=>'required',
            'country'=>'required',
            'address'=>'required',
            'adhar'=>'required',
            'idprof'=>'required|mimes:pdf,jpg, jpeg',
            'photo'=>'required|mimes:png,jpg,jpeg'
        ]);

        $data = array();
        $data = [
            'name'=>$request->name,
            'phone'=>$request->phone,
            'country'=>$request->country,
        ];

        $query = DB::table('users')->where('id', auth()->user()->id)->update($data);

            $datas = array();
            $datas['address'] = $request->address;
            $datas['adhar'] = $request->adhar;

            if($request->hasFile('idprof'))
            {
                if ($request->file('idprof')->isValid()) 
                {
                    $file = $request->file('idprof');
                    $ext= $file->getClientOriginalExtension();
                    $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                    // $img = Image::make($request->idprof)->save("assets/delivery/".$mainFilename);
                    $request->file('idprof')->move('public/assets/delivery'.$mainFilename);
                    $db_name = "/public/assets/delivery/".$mainFilename;

                    $datas['idprof'] = $db_name;
                }
            }

            if($request->hasFile('photo'))
            {
                if ($request->file('photo')->isValid()) 
                {
                    $file = $request->file('photo');
                    $ext= $file->getClientOriginalExtension();
                    $mainFilename = Str::random(6).date('h-i-s').".".$ext;
                    $img = Image::make($request->photo)->save("public/assets/delivery/".$mainFilename);
                    $db_name = "/public/assets/delivery/".$mainFilename;

                    $datas['photo'] = $db_name;
                }
            }

            $update_delivery = DB::table( 'delivery_boy' )->where('delivery_id', auth()->user()->id)->update( $datas );

            if( isset( $update_delivery ) )
               {
                    return response()->json([
                        'status'=>'success',
                        'data'=>'Profile updated successsfully'
                    ]);
                }
                else
                {
                    return response()->json([
                        'status'=>'fail',
                        'data'=>'Something went error'
                    ]);
                }
        }


    //LOGOUT
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    //REFRESH
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'role'=> auth()->user()->role,
            'expires_in' => auth()->factory()->getTTL() * 60 * 24 * 365
        ]);
    }

    protected function respondWithTokenDelivery($token, $status)
    {
        return response()->json([
            'status'=>$status,
            'access_token' => $token,
            'token_type' => 'bearer',
            'role'=> auth()->user()->role,
            'expires_in' => auth()->factory()->getTTL() * 60 * 24 * 365
        ]);
    }


    //FORGET PASSWORD
    public function forget_password(Request $request){
        $request->validate([
            'email'=>'required|email'
        ]);
        $otp = rand(1000,9999);
        $db_otp = hash::make($otp);
        $check = DB::table('users')->where('email', $request->email);
        if(isset($check)){
            $remember_token = array();
            $remember_token = [
                'remember_token'=>$db_otp,
                'updated_at'=>Carbon::now()
            ];
            $email = [
                'otp'=>$otp
            ];
            $update = DB::table('users')->where('email', $request->email)->update($remember_token);
            $user['to'] = $request->email;
         mail::send('forgot_password', $email, function($message) use ($user){
            $message->to($user['to']);
            $message->subject('Password Reset');
         });
         return response()->json([
                'staus'=>'success',
                'data'=>'Email sent successfully'
            ]);
        }else{
            return response()->json([
                'staus'=>'fail',
                'data'=>'Email is not registered'
            ]);
        }
    }

    //CHECK OTP
    public function check_otp(Request $request){
        $request->validate(['otp'=>'required', 'email'=>'required|email']);

        $get_password = DB::table('users')->where('email', $request->email)->first();
         if(isset($get_password)){
            if(hash::check($request->otp, $get_password->remember_token)){
            return response()->json([
                'status'=>'success',
                'data'=>'OTP verified sucessfully',
                'forget_password_token'=>$get_password->remember_token
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'data'=>'Otp does not match'
            ]);
        }
         }
        
    }


    //CHANGE PASSWORD
    public function change_password(Request $request){
        $request->validate(['email'=>'required', 'password'=>'required', 'forget_password_token'=>'required']);
        //$email = $request->email;
        $data = array();
        $data = [
            'password'=>hash::make($request->password),
            'remember_token'=>$request->forget_password_token,
            'created_at'=>Carbon::now()
        ];
        $update = DB::table('users')->where('remember_token',$request->forget_password_token)->update($data);


        if(isset($update)){
            return $this->r_login($request->email, $request->password);
           
        }
    }


    //UPDATE PASSWORD
    public function update_password(Request $request){
        $request->validate(['password'=>'required', 'new_password'=>'required']);
        $check = DB::table('users')->where('email', auth()->user()->email)->first();
        if(isset($check)){
            if(hash::check($request->password, $check->password)){
                $password = array();
                $password = [
                    'password'=>hash::make($request->new_password)
                ];
                $update = DB::table('users')->where('email', auth()->user()->email)->update($password);
                return $this->r_login(auth()->user()->email, $request->new_password);
            }
        }
    }
}