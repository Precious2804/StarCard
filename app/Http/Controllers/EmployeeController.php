<?php

namespace App\Http\Controllers;

use App\Models\AllCards;
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
        $this->middleware('jwt', ['except' => ['employee_login']]);
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

    public function create_card(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required|string',
            'risked_resource' => 'required|string',
            'hazard_description' => 'required|string',
            'probability' => 'required|string',
            'impact' => 'required|string',
            'rating' => 'required|string',
        ]);
        //return validation error(s)
        $errorMess = $this->validatorFails($validator);
        if ($errorMess) {
            return $errorMess;
        } else {
            $organize = Employee::where('company_id', Auth::guard('employee')->user()->company_id)->first();
            $result = AllCards::create([
                'case_id'=>$this->createUniqueRand('all_cards', 'case_id'),
                'organization'=>$organize['organization'],
                'employee'=>$organize['employee_name'],
                'location'=>$request->location,
                'hazard_description'=>$request->hazard_description,
                'risked_resource'=>$request->risked_resource,
                'probability'=>$request->probability,
                'impact'=>$request->impact,
                'existing_control'=>$request->existing_control,
                'existing_prevention'=>$request->existing_prevention,
                'rating'=>$request->rating,
                'other_info'=>$request->other_info,
            ]);

            return response([
                'status'=>true,
                'message'=>"New Star Card has been Created Successfully",
                'data'=>$result
            ], 201);
        }
    }
}
