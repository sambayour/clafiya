<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //requests validation rules
        $validator = Validator::make($request->all(), [
            "name" => ['string','required',],
	    	"email" => ['required_without:phone','email:dns,rfc','unique:users'],
	    	"phone" => ['string'],	    	
	    	"password" => ['string','required','min:6']
        ]);
        
        //return validation error if requests is not validated
        if ($validator->fails()) {
            return response([
                "status" => 'failed',
                "success" => false,
                "message" => $validator->errors()->all()
            ], 400);
        }

        //save user details to users table
        $user = User::create($request->all());
        
        //return access token and user details
        return response([
            "token" => $user->createToken($request->email)->accessToken,
            "data" => User::find($user->id),
            "status" => 'ok',
            "success" => true,
            "message" => "New User Registration Successful"
        ],Response::HTTP_OK);

    }

    public function login(Request $request)
    {
        //requests validation rules
        $validator = Validator::make($request->all(), [
            'email' => ['required_without:phone', 'email'],
            'phone' => ['string'],
            'password' => ['required'],
        ]);
        //return validation error if requests is not validated
        if ($validator->fails()) {
            return response([
                "status" => 'failed',
                "success" => false,
                "message" => $validator->errors()->all()
            ], 400);
        }
        //check if the request has email or phone and set it to $field variable
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        //set the field and password back to request format
        $credentials = request([$field, 'password']);

        //try to authenticate the user credentials 
        if (!Auth::attempt($credentials)) {
        	return response([
	            "status" => 'failed',
	            "success" => false,
	            "message" => "Unauthorized"
	        ], 400);
        }

        //set the authenticated user details to the $user variable
         $user = auth()->user();

        //return access token and user details
        return response([
            "token" => $user->createToken($field)->accessToken,
            "data" => $user,
            "status" => 'ok',
            "success" => true,
            "message" => "Login Successful"
        ],Response::HTTP_OK);

    }

}
