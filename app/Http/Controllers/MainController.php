<?php

namespace App\Http\Controllers;

use App\Models\AllCards;
use App\Models\Employee;
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
        } else {
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

    public function organization_details()
    {
        $data = User::where('company_id', Auth::user()->company_id)->first();

        if (!$data) {
            return response([
                'status' => false,
                'message' => "Company ID does not exist",
                'data' => NULL
            ], 404);
        } else {
            return response([
                'status' => true,
                'message' => "Company Details fetched successfully",
                'data' => $data
            ], 201);
        }
    }

    public function create_employee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_name' => 'required|string',
            'employee_email' => 'required|email',
            'password' => 'required|string',
        ]);
        //return validation error(s)
        $errorMess = $this->validatorFails($validator);
        if ($errorMess) {
            return $errorMess;
        } else {
            $employeed_id = $this->createUniqueRand('employees', 'employee_id');
            $organizationDet = User::where('company_id', Auth::user()->company_id)->first();
            $select_emp = Employee::where('employee_email', $request->employee_email)
                ->where('company_id', Auth::user()->company_id)->first();
                // return $select_emp;

            if ($select_emp) {
                return response([
                    'status' => false,
                    'message' => "This employee has already been registered to this organization",
                    'data' => NULL
                ]);
            } elseif(!$select_emp) {
                $result = Employee::create([
                    'employee_id' => $employeed_id,
                    'employee_name' => $request->employee_name,
                    'employee_email' => $request->employee_email,
                    'company_id' => Auth::user()->company_id,
                    'organization' => $organizationDet['organization'],
                    'password' => Hash::make($request->password)
                ]);

                if ($result) {
                    return response([
                        'status' => true,
                        'message' => "A new Employee has been created for the Company " . $organizationDet['organization'],
                        'data' => $result
                    ], 201);
                }
            }
        }
    }

    public function all_employees()
    {
        $data = Employee::where('company_id', Auth::user()->company_id)->get();

        if ($data) {
            return response([
                'status' => true,
                'message' => "All Employees for this Organization ",
                'data' => $data
            ], 201);
        }
    }

    public function all_cards()
    {
        $data = AllCards::where('company_id', Auth::user()->company_id)->get();

        if ($data) {
            return response([
                'status' => true,
                'message' => "The Star Card Dasboard for an organization",
                'data' => $data
            ], 201);
        }
    }

    public function logout()
    {
        Auth::logout();

        return response([
            'status' => true,
            'message' => "Log Out was Successfull",
            'data' => auth()->user()
        ]);
    }
}
