<?php

namespace App\Http\Controllers\API;

use App\Order;
use App\OrderDetail;
use App\User;
use App\Product;
use App\CustomerInfo;
use App\Cart;
use App\CartDetail;
use App\TransferPhoto;
use Illuminate\Support\Facades\Auth;
use Redirect;
use ZipArchive;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createTransaction(Request $request)
    {
        $user_id = Auth::user()->id;

        $validatedData  =  $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required',
            'phone' => 'required|regex:/(08)[0-9]{8}/|max:14',
            'zip_code' => 'required|max:5',
            'address' => 'required|max:255'
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

        $newOrder = new Order;
        $newOrder->user_id = $user_id;
        $newOrder->order_type = $this->order_type;
        $newOrder->order_status = Order::defaultStockOrderStatus;
        $newOrder->save();

        $this->truncateAndDuplicateCartTable($newOrder);

        return redirect('/orders/' . $user_id)->with('success', 'Your order is successfully added');
    }

    public function truncateAndDuplicateCartTable($newOrder)
    {
        $user_id = Auth::user()->id;
        $cart = Cart::select('id')->where('user_id', '=', $user_id)->first();
        $cartDetails = CartDetail::where('cart_id', '=', $cart->id)->get();

        foreach ($cartDetails as $cartDetail) {
            $order_detail = new OrderDetail;
            $order_detail->order_id = $newOrder->id;
            $order_detail->product_id = $cartDetail->product_id;
            $order_detail->qty = $cartDetail->qty;
            $product = Product::where('id', $cartDetail->product_id)->update(
                array(
                    'qty' => $cartDetail->product->qty - $order_detail->qty
                )
            );
            $order_detail->save();

            $cartDetail->delete();
        }
    }

    public function delete_order($id)
    {
        $selected_order = Order::find($id);
        $orderDetails = OrderDetail::where('order_id', '=', $selected_order->id)->get();

        if ($selected_order->order_status = 'Stock Order') {
            foreach ($orderDetails as $orderDetail) {
                $product = Product::where('id', '=', $orderDetail->product_id)->first();
                $product->qty = $orderDetail->qty + $product->qty;

                $product->update();
            }
        }

        $selected_order->delete($selected_order);
        return redirect('/orders/' . Auth::user()->id)->with('success', 'Order successfully deleted or canceled');
    }

    public function insertTransactionPhotos($id, Request $request)
    {
        $iteration = 1;
        foreach ($request->file as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = 'OrderID-' . $id . '-Payment-Photo-' . $iteration . '.' . $extension;
            $path = public_path() . '/payment_photos';
            $file->move($path, $filename);
            $transfer_photo = new TransferPhoto;
            $transfer_photo->order_id = $id;
            $transfer_photo->image_name = $filename;
            $transfer_photo->save();

            $iteration++;
        }

        Order::where('id', $id)->update(
            array(
                'order_status' => 'Payment on Verification'
            )
        );

        return redirect('/orders/' . Auth::user()->id)->with('success', 'Your payment is successfully added, please wait for approval');
    }

    public function download_payment_images($id)
    {
        $selected_stock_order = Order::find($id);
        $transfer_photos = $selected_stock_order->transfer_photo;

        $files = [];
        foreach ($transfer_photos as $transfer_photo) {
            $files[$transfer_photo->id] = public_path('payment_photos') . '/' . $transfer_photo->image_name;
        }

        $folderName = 'OrderID-' . $selected_stock_order->id . '-' . 'Payment-Photos' . '.zip';
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
}
