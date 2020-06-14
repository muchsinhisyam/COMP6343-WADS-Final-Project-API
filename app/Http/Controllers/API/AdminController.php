<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Photos;
use App\Product;
use App\Color;
use App\CustomerInfo;
use App\User;
use App\CustomOrders;
use App\Order;
use App\OrderDetail;
use File;
use ZipArchive;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin-page/dashboard');
    }

    public function create(Request $request)
    {
        $validatedData  =  $request->validate([
            'product_name'  => 'required|max:255',
            'category_id' => 'required',
            'color_id' => 'required',
            'price' => 'required|digits_between:0,2147483646|numeric',
            'qty' => 'required|digits_between:0,2147483646|numeric',
            'description' => 'required|max:255'
            // Photo Size need to be limited
        ]);

        $input = $request->all();

        $id = Product::create($input)->id;
        $iteration = 1;
        // if ($request->hasFile('file')) {
        foreach ($request->file as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = 'ProductID-' . $id . '-Image-' . $iteration . '.' . $extension;
            $path = public_path() . '/images';
            $file->move($path, $filename);
            $photo = new \App\Photos;
            $photo->product_id = $id;
            $photo->image_name = $filename;
            $photo->save();

            $iteration++;
        }
        // }
        return redirect('/admin/products')->with('success', 'Product successfully added');
    }

    public function insert_product_photo(Request $request)
    {
        // if ($request->hasFile('file')) {
        $iteration = 1;
        foreach ($request->file as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = 'ProductID-' . $request->id . '-Image-' . $iteration . '.' . $extension;
            // $file->storeAs('public/images', $filename);
            $path = public_path() . '/images';
            $file->move($path, $filename);
            $photo = new \App\Photos;
            $photo->product_id = $request->product_name; // product_name value on <select> is product_id
            $photo->image_name = $filename;
            $photo->save();

            $iteration++;
        }
        // }
        return redirect('/admin/products-photo')->with('success', 'Product\'s Photo successfully added');
    }

    public function insert_user(Request $request)
    {
        $validatedData  =  $request->validate([
            'name'  => 'required|max:255',
            'email' => 'required|max:255',
            'password' => 'required|max:255'
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();

        return redirect('/admin/users')->with('success', 'User successfully added');
    }

    public function edit($id)
    {
        $selected_product = Product::find($id);
        $colors = Color::all();
        $categories = Category::all();
        return view('admin-page/update-product-form', compact('colors', 'categories', 'selected_product'));
    }

    public function edit_user($id)
    {
        $selected_user = User::find($id);
        return view('admin-page/update-user-form', compact('selected_user'));
    }

    public function edit_stock_order($id)
    {
        $selected_order = Order::find($id);
        return view('admin-page/update-stock-order-form', compact('selected_order'));
    }

    public function edit_users_info($id)
    {
        $selected_users_info = CustomerInfo::find($id);
        return view('admin-page/update-user-info-form', compact('selected_users_info'));
    }

    public function update(Request $request, $id)
    {
        $validatedData  =  $request->validate([
            'product_name'  => 'required|max:255',
            'price' => 'required|digits_between:0,2147483646|numeric',
            'qty' => 'required|digits_between:0,2147483646|numeric',
            'description' => 'required|max:255'
        ]);

        $selected_product = Product::find($id);
        $selected_product->update($request->all());
        return redirect('/admin/products')->with('success', 'Product successfully updated');
    }

    public function update_user(Request $request, $id)
    {
        $validatedData  =  $request->validate([
            'name'  => 'required|max:255',
            'email' => 'required|max:255'
        ]);

        $selected_user = User::find($id);
        $selected_user->update($request->all());
        return redirect('/admin/users')->with('success', 'User successfully updated');
    }

    public function update_users_info(Request $request, $id)
    {
        $validatedData  =  $request->validate([
            'first_name'  => 'required|max:255',
            'last_name' => 'required|max:255',
            'email'  => 'required|max:255',
            'phone' => 'required|regex:/(08)[0-9]{8}/|max:14',
            'zip_code' => 'required|max:5',
            'address' => 'required|max:255'
        ]);

        $selected_users_info = CustomerInfo::find($id);
        $selected_users_info->update($request->all());
        return redirect('/admin/users-info')->with('success', 'User\'s Info successfully updated');
    }

    public function update_stock_orders(Request $request, $id)
    {
        Order::where('id', $id)->update(
            array(
                'order_status' => $request->order_status
            )
        );
        return redirect('/admin/view-stock-orders')->with('success', 'Order status  successfully updated');
    }

    public function delete($id)
    {
        $selected_product = Product::find($id);
        $selected_product->delete($selected_product);
        return redirect('/admin/products')->with('success', 'Product successfully deleted');
    }

    public function delete_product_photo($id)
    {
        $selected_product_photo = Photos::find($id);
        $selected_product_photo->delete($selected_product_photo);
        return redirect('/admin/products-photo')->with('success', 'Product\'s photo successfully deleted');
    }

    public function delete_user($id)
    {
        $selected_user = User::find($id);
        $selected_user->delete($selected_user);
        return redirect('/admin/users')->with('success', 'User successfully deleted');
    }

    public function delete_stock_orders($id)
    {
        $selected_order = Order::find($id);
        $selected_order->delete($selected_order);
        return redirect('/admin/view-stock-orders')->with('success', 'Order successfully deleted');
    }

    public function delete_stock_order_details($id)
    {
        $selected_order_detail = OrderDetail::find($id);
        $selected_order_detail->delete($selected_order_detail);
        return redirect('/admin/view-stock-order-details')->with('success', 'Order successfully deleted');
    }

    public function delete_users_info($id)
    {
        $selected_users_info = CustomerInfo::find($id);
        $selected_users_info->delete($selected_users_info);
        return redirect('/admin/users-info')->with('success', 'User\'s Info successfully deleted');
    }

    public function view_products()
    {
        $products = Product::all();
        return view('/admin-page/view-products', compact('products'));
    }

    public function view_products_photo()
    {
        return view('admin-page/view-products-photo');
    }

    public function view_insert_products()
    {
        $colors = Color::all();
        $categories = Category::all();
        return view('admin-page/insert-product-form', compact('colors', 'categories'));
    }

    public function view_insert_product_photo()
    {
        $products = Product::all();
        return view('admin-page/insert-product-photo-form', compact('products'));
    }

    public function view_users()
    {
        $users = User::all();
        return view('admin-page/view-users', compact('users'));
    }

    public function view_users_info()
    {
        $users_info = CustomerInfo::all();
        return view('admin-page/view-user-info', compact('users_info'));
    }

    public function view_insert_user()
    {
        return view('admin-page/insert-user-form');
    }

    public function view_stock_orders()
    {
        $stock_orders = Order::where('order_type', '=', 'Stock Order')->with('user')->get();
        return view('admin-page/view-stock-orders', compact('stock_orders'));
    }

    public function view_stock_order_details()
    {
        $stock_order_details = OrderDetail::with('product')->get();
        return view('admin-page/view-stock-order-details', compact('stock_order_details'));
    }
}
