<?php

namespace App\Http\Controllers\API;

use App\CustomerInfo;
use App\User;
use Redirect;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerInfoController extends BaseController
{
    public function index()
    {
        $customerInfo = CustomerInfo::all();

        return $this->sendResponse($customerInfo->toArray(), 'Products retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'user_id' => 'required|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required',
            'phone' => 'required|regex:/(08)[0-9]{8}/|max:14',
            'city' => 'required',
            'zip_code' => 'required|digits:5',
            'address' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $customerInfo = CustomerInfo::create($input);

        return $this->sendResponse($customerInfo->toArray(), 'Customer Info created successfully.');
    }

    public function update(Request $request)
    {
        $input = $request->all();
        $user_id = Auth::user()->id;

        $validator = Validator::make($input, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required',
            'phone' => 'required|regex:/(08)[0-9]{8}/|max:14',
            'city' => 'required',
            'zip_code' => 'required|digits:5',
            'address' => 'required|max:255'
            // Photo Size needs to be limited
        ]);

        $customerInfo = CustomerInfo::where('user_id', $user_id)->update(
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

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        return $this->sendResponse($customerInfo->toArray(), 'Customer Info updated successfully.');
    }

    public function show($id)
    {
        $user = User::find($id);
        $customer_info = $user->customer;
        // return view('client-page/customer-info', compact('customer_info'));
        return $this->sendResponse($customer_info->toArray(), 'Customer Info retrieved successfully.');
    }
}
