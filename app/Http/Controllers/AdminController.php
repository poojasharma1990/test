<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Input;
use Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use Symfony\Component\HttpFoundation\Response;
class AdminController extends Controller
{
  public $token = true;  

    public function register(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'name'  =>'required',
            'email'  =>  'required|unique:users',
            'password' => 'required',
         ]);

        if ($validator->fails()) {
            $error = $validator->errors();
           return response()->json(['error'=>$validator->errors()->all()]);
         }
        
       $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }
        
        
    

    public function login(Request $request){
		$input = $request->only('email', 'password');
		//return $input;
		$jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }
        // $user=User::
        return response()->json([
            'username' => Auth::user()->name,
			'email' => Auth::user()->email,
            'token' => $jwt_token,
        ]);
             
	}
	public function getAuthUser(Request $request)
    {    
        return response()->json(['user' => Auth::user()]);
    }
    
}
