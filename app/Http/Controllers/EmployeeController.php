<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Traits\Generics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class EmployeeController extends Controller
{
    //
    use Generics;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['employee_login']]);
    }

    public function employee_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_email' => 'required|email',
            'password' => 'required|string',
        ]);
        //return validation error(s)
        $errorMess = $this->validatorFails($validator);
        if ($errorMess) {
            return $errorMess;
        } else {
            if (Employee::where('employee_email', $request->employee_email)->exists()) {
                if (!$token = Auth::guard('employee')->attempt($validator->validated())) {
                    return response([
                        'status' => false,
                        "message" => "Password is Incorrect",
                        "data" => "Empty",
                    ], 401);
                } else {
                    return $this->createNewToken2($token);
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
