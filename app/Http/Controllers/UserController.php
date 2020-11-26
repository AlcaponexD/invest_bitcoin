<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6'
        ]);

       try{
           $user = User::create($request->all());
           return response()->json([
               'user' => $user,
               'message' => 'created'
           ],201);
       }catch (\Exception $exception)
       {
           Log::error("registration_user_".$exception->getMessage());
           return response()->json(['message' => 'User Registration Failed!'], 409);
       }

    }
}
