<?php

namespace App\Http\Controllers\API;

use App\CustomerInfo;
use App\User;
use Redirect;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerInfoController extends Controller
{
    public function create(Request $request)
    {
        $user_id = Auth::user()->id;

        $validatedData  =  $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required',
            'phone' => 'required|regex:/(08)[0-9]{8}/|max:14',
            'city' => 'required',
            'zip_code' => 'required|digits:5',
            'address' => 'required|max:255'
            // Photo Size needs to be limited
        ]);

        CustomerInfo::where('user_id', $user_id)->update(
            array(
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'address' => $request->address
            )
        );

        return redirect::back()->with('success', 'Account successfully updated');
    }
}
