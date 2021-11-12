<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait Generics
{

    function createNewToken($token)
    {
        return response()->json([
            'status' => true,
            'message' => "Login Was Successful",
            'data' => auth()->user(),
            'access_token' => 'Bearer ' . $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60000
        ], 201);
    }
    function createNewToken2($token)
    {
        return response()->json([
            'status' => true,
            'message' => "Login Was Successful",
            'data' => auth()->guard('employee')->user(),
            'access_token' => 'Bearer ' . $token,
            'token_type' => 'bearer',
            'expires_in' =>auth()->guard('employee')->factory()->getTTL() * 60000
        ], 201);
    }

    //a method that returns any validator message
    function validatorFails($validator)
    {
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'data' => "Empty"
            ], 422);
        }
    }

    // a function that generates a random unique ID
    function generateId()
    {
        $unique_id = (string) Str::uuid();
        $exploded = explode('-', $unique_id);
        $n_unique_id = $exploded[4];
        return $n_unique_id;
    }
    function createUniqueID($table, $column)
    {
        $id = $this->generateId();
        return DB::table($table)->where($column, $id)->first() ? $this->createUniqueID($table, $column) :  $id;
    }

    //a function that generates random numbers
    function generateRand()
    {
        $random = rand(100000, 999999);
        return $random;
    }
    function createUniqueRand($table, $column)
    {
        $id = $this->generateRand();
        return DB::table($table)->where($column, $id)->first() ? $this->createUniqueRand($table, $column) :  $id;
    }

    //function that returns result response
    function responseResult($result)
    {
        if ($result) {
            $response =  response([
                'status' => true,
                'message' => "Operation was successful",
                'data' => $result
            ], 201);
        } elseif ($result == NULL) {
            $response =  response([
                'status' => false,
                'message' => "Operation Failed",
                'data' => "Empty"
            ], 400);
        }
        return $response;
    }
}
