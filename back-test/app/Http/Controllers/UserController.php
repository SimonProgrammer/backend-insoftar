<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserEditRequest;

class UserController extends Controller
{
    public function saveUser(UserStoreRequest $request){

        $response = array();
        $http_code = 200;

        // Will return only validated data

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $validated["password"] = Hash::make($validated["password"]);
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

    public function editUser(UserEditRequest $request){

        $response = array();
        $http_code = 200;

        // Will return only validated data

        $validated = $request->validated();

        DB::beginTransaction();
        try {
            $validated["password"] = Hash::make($validated["password"]);
            User::updateOrCreate(["id" => $validated["id"]],$validated);
            DB::commit();
            $response = array("data" => [],"message" => "User updated!");
        } catch (\Exception $e) {
            DB::rollback();
            $response = array("data" => [],"message" => "An error occurred, try again");
            $http_code = 500;
        }
        
        return response()->json($response,$http_code);
    }

    public function getUsers(Request $request){

        $page = $request->input("page");
        $count = $request->input("count");
        $sort = $request->input("sort");
        $order = $request->input("order");
        $filter = $request->input("filter");

        $offset = ($page - 1) * $count;

        $users = DB::table('users')
                ->orWhere('identification',$filter)
                ->orWhereRaw("name REGEXP '$filter'")
                ->orWhereRaw("email REGEXP '$filter'")
                ->orWhere('phone',$filter)
                ->offset($offset)
                ->limit($count)
                ->orderBy($sort,$order)
                ->get();
        $total_count = DB::table('users')->count();
        return response()->json(array("items" => $users,"total_count" => $total_count));
    }
}
