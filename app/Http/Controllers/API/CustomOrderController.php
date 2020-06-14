<?php

namespace App\Http\Controllers\API;

use App\CustomerInfo;
use App\Order;
use App\CustomPhotos;
use App\TransferPhoto;
use App\User;
use Redirect;
use Illuminate\Support\Facades\Auth;
use ZipArchive;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomOrderController extends Controller
{
    private $order_type = "Custom Order";

    public function update_and_create(Request $request)
    {
        $user_id = Auth::user()->id;

        $validatedData  =  $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required',
            'phone' => 'required|regex:/(08)[0-9]{8}/|max:14',
            'description' => 'required|max:255'
            // Photo Size needs to be limited
        ]);

        CustomerInfo::where('user_id', $user_id)->update(
            array(
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone
            )
        );

        $order_info = Order::create([
            'user_id' => $user_id,
            'order_type' => $this->order_type,
            'order_status' => Order::defaultCustomOrderStatus,
            'description' => $request->description
        ]);

        $order_id = $order_info->id;
        $iteration = 1;

        foreach ($request->file as $file) {
            $filename = 'OrderID-' . $order_id . '-Custom-Photo-' . $iteration . '.jpg';
            $path = public_path() . '/custom_images';
            $file->move($path, $filename);
            $custom_photo = new CustomPhotos;
            $custom_photo->order_id = $order_id;
            $custom_photo->image_name = $filename;
            $custom_photo->save();
            $iteration++;
        }

        return redirect('/orders/' . $user_id)->with('success', 'Your order is successfully added');
    }

    public function download_custom_images($id)
    {
        $custom_orders = Order::find($id);
        $custom_photos = $custom_orders->custom_photo;

        $files = [];
        foreach ($custom_photos as $custom_photo) {
            $files[$custom_photo->id] = public_path('custom_images') . '/' . $custom_photo->image_name;
        }

        $folderName = 'OrderID-' . $custom_orders->id . '-' . 'Custom-Photos' . '.zip';
        $zip = new ZipArchive;
        // $zipFile    = public_path().'/'.$folderName.'.zip';

        if ($zip->open(public_path('downloads') . '/' . $folderName, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }

            $zip->close();
        }

        return response()->download(public_path('downloads') . '/' . $folderName);
    }

    public function download_payment_images($id)
    {
        $selected_custom_order = Order::find($id);
        $transfer_photos = $selected_custom_order->transfer_photo;

        $files = [];
        foreach ($transfer_photos as $transfer_photo) {
            $files[$transfer_photo->id] = public_path('payment_photos') . '/' . $transfer_photo->image_name;
        }

        $folderName = 'OrderID-' . $selected_custom_order->id . '-' . 'Payment-Photos' . '.zip';
        $zip = new ZipArchive;
        // $zipFile    = public_path().'/'.$folderName.'.zip';

        if ($zip->open(public_path('downloads') . '/' . $folderName, ZipArchive::CREATE) === TRUE) {
            foreach ($files as $key => $value) {
                $relativeNameInZipFile = basename($value);
                $zip->addFile($value, $relativeNameInZipFile);
            }

            $zip->close();
        }

        return response()->download(public_path('downloads') . '/' . $folderName);
    }

    public function view_customer_info($id)
    {
        $user = User::find($id);
        $customer_info = $user->customer;
        return view('client-page/custom-order-form', compact('customer_info'));
    }

    public function view_custom_orders()
    {
        $custom_orders = Order::where('order_type', '=', 'Custom Order')->with('user')->get();
        return view('admin-page/view-custom-orders', compact('custom_orders'));
    }

    public function update_custom_orders(Request $request, $id)
    {
        Order::where('id', $id)->update(
            array(
                'order_status' => $request->order_status
            )
        );
        return redirect('/admin/view-custom-orders')->with('success', 'Order status  successfully updated');
    }

    public function edit_custom_order($id)
    {
        $selected_order = Order::find($id);
        return view('admin-page/update-custom-order-form', compact('selected_order'));
    }

    public function delete_custom_orders($id)
    {
        $selected_order = Order::find($id);
        $selected_order->delete($selected_order);
        return redirect('/admin/view-custom-orders')->with('success', 'Order successfully deleted');
    }

    public function view_custom_order_details($id)
    {
        $selected_order = Order::find($id);
        $selected_order_photos = CustomPhotos::where('order_id', '=', $selected_order->id)->get();
        return view('client-page/custom-order-details', compact('selected_order', 'selected_order_photos'));
    }
}
