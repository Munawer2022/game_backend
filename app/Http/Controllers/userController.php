<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{
    public function register(Request $request){
         $request->validate([
            'name' => 'required',
            'mobile_no' => 'required|digits:11', 
            'password' => 'required',
        ]);
        if(User::where('mobile_no',$request->mobile_no)->first()){
            return response([
                'message'=>'Number already exists',
                'status'=>'failed'
            ],200);
        }
        $user = User::create([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken($request->mobile_no)->plainTextToken;
      
        return response([
            'user' => 'Registration success',
            'status' => 'success',
            'token' => $token,
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'id' => $user->id,
        ], 201);
    }

    public function login(Request $request){
        $request->validate([
            'name' => 'required',
            'mobile_no' => 'required|digits:11', 
            'password' => 'required',
        ]);

        $user = User::where('mobile_no', $request->mobile_no)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response([
                'message' => 'The provided credentials are incorrect.',
           ], 401);
        }

        $token = $user->createToken($request->mobile_no)->plainTextToken;

        return response([
            'user' => 'login success',
            'status' => 'success',
            'token' => $token,
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'id' => $user->id,
        ], 200);

    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'Succefully Logged Out !!',
            'status'=>'success'
        ],200);
    }
    public function logged_user(){
        $loggeduser=auth()->user();
        return response([
            'user'=>$loggeduser,
            'message' => 'Logged User Data',
            'status'=>'success'
        ],200);
    }
    
}