<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Http\Requests\UserStoreRequest;

class UserController extends Controller
{
    public function saveUser(UserStoreRequest $request){

        $response = array();
        $http_code = 200;

        // Will return only validated data

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            User::create($validated);
            DB::commit();
            $response = array("data" => [],"message" => "User saved!");
        } catch (\Exception $e) {
            DB::rollback();
            $response = array("data" => [],"message" => "An error occurred, try again");
            $http_code = 500;
        }
        
        return response()->json($response,$http_code);
    }
}
