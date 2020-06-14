<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use App\CustomerInfo;
use App\Cart;
use Validator;

class RegisterController extends BaseController
{
    protected function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
            'role' => User::defaultRole
        ]);

        CustomerInfo::create([
            'user_id' => $user['id'],
            'email' => $request['email']
        ]);

        Cart::create([
            'user_id' => $user['id']
        ]);

        return $this->sendResponse($user->toArray(), 'User registered successfully.');
    }
}
