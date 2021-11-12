<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Generics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MainController extends Controller
{
    //
    use Generics;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        // running the validation rules on the inputs
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        //return validation error(s)
        $errorMess = $this->validatorFails($validator);
        if ($errorMess) {
            return $errorMess;
        } else {
            $unique_id = $this->createUniqueID('users', 'company_id');

            $user = User::create([
                'company_id' => $unique_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'organization' => $request->organization,
                'industry' => $request->industry,
                'password' => Hash::make($request->password)
            ]);

            if ($user) {
                return response([
                    'status' => true,
                    'message' => "User registration was Successfull",
                    'data' => $user
                ]);
            }
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        //return validation error(s)
        $errorMess = $this->validatorFails($validator);
        if ($errorMess) {
            return $errorMess;
        } else {;
            if (User::where('email', $request->email)->exists()) {
                if (!$token = Auth::attempt($validator->validated())) {
                    return response([
                        'status' => false,
                        "message" => "Password is Incorrect",
                        "data" => "Empty",
                    ], 401);
                } else {
                    return $this->createNewToken($token);
                }
            } else {
                return response([
                    'status' => false,
                    'message' => "Email does not exist on this platform",
                    'data' => NULL
                ], 422);
            }
        }
    }
}
