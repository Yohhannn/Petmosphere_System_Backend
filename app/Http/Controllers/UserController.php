<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getAllUser(){
        $users = User::all();
        return response()->json(["message" => "Successfully get data of users","data" => $users],200);
    }

    public function getUserById($id){
        $users = User::find($id);
        if(!$users){
            return response()->json(["message" => "User not found"],404);
        }
        return response()->json(["message" => "Successfully get data of user","data" => $users],200);
    }

    public function createUser(Request $request)
    {
        // Check if the email already exists
        if (User::where('user_email', $request->user_email)->exists()) {
            return response()->json(["message" => "User email already exists"], 409);
        }

        // Validate the input data
        $validated = $request->validate([
            'user_name' => 'required|string|max:50',
            'user_phone' => 'required|string|max:11',
            'user_location' => 'required|string|max:100',
            'user_email' => 'required|email|max:50',
            'user_pass' => 'required|string|max:100',
            'user_createdate' => 'required|date',
        ]);

        // Hash the password
        $validated['user_pass'] = Hash::make($request->user_pass);
        // Create the user
        $user = User::create($validated);

        // Return the success response
        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        $rules = [
            'user_name' => 'sometimes|nullable|string|max:50',
            'user_phone' => 'sometimes|nullable|string|max:11',
            'user_location' => 'sometimes|nullable|string|max:100',
            'user_prof_pic' => 'sometimes|nullable|string|max:250',
            'user_verified' => 'sometimes|nullable|integer',
            'is_active' => 'sometimes|nullable|integer',
            'user_socmed' => 'sometimes|nullable|string|max:250',
            'user_valid_id_pic' => 'sometimes|nullable|string'
        ];

        $validated = $request->validate($rules);

        $user->update($validated);

        return response()->json([
            "message" => "User updated successfully",
            "data" => $user
        ], 200);
    }

    public function deleteUser($id){
        $user = User::find($id);
        if(!$user){
            return response()->json(["message" => "User not found"],404);
        }
        $updateInactive = [
            "is_active" => 0
        ];
        $user->update($updateInactive);
        return response()->json(["message" => "User deleted successfully"],200);
    }
    public function countUser(){
        $users = User::all()->count();
        $verified = User::where('user_verified',1)->count();
        $unverified = User::where('user_verified',0)->count();
        return response()->json(["users" => $users, "verified" => $verified, "unverified" => $unverified]);
    }
}
