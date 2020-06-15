<?php

namespace App\Http\Controllers\API;

use App\CustomerInfo;
use App\User;
use Redirect;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

class UserController extends BaseController
{
    public function index()
    {
        $users = User::all();
        return $this->sendResponse($users->toArray(), 'Users retrieved successfully.');
    }

    public function showAllCustomerInfo()
    {
        $customerInfo = CustomerInfo::all();
        return $this->sendResponse($customerInfo->toArray(), 'Customer Info retrieved successfully.');
    }

    public function createCustomerInfo(Request $request)
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

    public function update(Request $request, $id)
    {
        $user = $request->all();

        $validator = Validator::make($user, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $user = User::find($id);
        $user->update($request->all());
        return $this->sendResponse($user->toArray(), 'User updated successfully.');
    }

    public function updateCustomerInfo(Request $request, $id)
    {
        $customerInfo = $request->all();

        $validator = Validator::make($customerInfo, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required',
            'phone' => 'required|regex:/(08)[0-9]{8}/|max:14',
            'city' => 'required',
            'zip_code' => 'required|digits:5',
            'address' => 'required|max:255'
            // Photo Size needs to be limited
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $customerInfo = CustomerInfo::find($id);
        $customerInfo->update($request->all());
        return $this->sendResponse($customerInfo->toArray(), 'Customer Info updated successfully.');
    }

    public function show($id)
    {
        $user = User::find($id);
        return $this->sendResponse($user->toArray(), 'Customer Info retrieved successfully.');
    }

    public function showCustomerInfo($id)
    {
        $customer_info = CustomerInfo::find($id);
        return $this->sendResponse($customer_info->toArray(), 'Customer Info retrieved successfully.');
    }

    public function destroy($id)
    {
        $selected_user = User::find($id);
        $selected_user->delete($selected_user);
        return $this->sendResponse($selected_user->toArray(), 'User deleted successfully.');
    }

    public function destroyCustomerInfo($id)
    {
        $selected_info = CustomerInfo::find($id);
        $selected_info->delete($selected_info);
        return $this->sendResponse($selected_info->toArray(), 'Customer Info deleted successfully.');
    }
}
