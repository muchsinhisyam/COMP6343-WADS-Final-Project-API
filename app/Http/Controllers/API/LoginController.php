<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;

class LoginController extends BaseController
{
    public $successStatus = 200;

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['name'] = $user->name;
            $success['role'] = $user->role;
            // if ($user->role === 'Admin') {
            //     return redirect()->intended('admin'); //redirect to admin panel
            // }
            // return redirect()->intended('/');
            return $this->sendResponse($user, 'User login successfully.');
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
