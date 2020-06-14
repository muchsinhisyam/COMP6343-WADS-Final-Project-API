<?php

namespace App\Http\Controllers\API;

use \App\Photos;
use \App\Product;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class ProductController extends BaseController
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(8);
        $countProducts = count($products);
        // return view('client-page/products', compact('products', 'countProducts'));
        return $this->sendResponse($products->toArray(), $countProducts, 'Products retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'product_name'  => 'required|max:255',
            'category_id' => 'required',
            'color_id' => 'required',
            'price' => 'required|digits_between:0,2147483646|numeric',
            'qty' => 'required|digits_between:0,2147483646|numeric',
            'description' => 'required|max:255'
            // Photo Size need to be limited
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Product::create($input);
        $iteration = 1;

        // if ($request->hasFile('file')) {
        foreach ($request->file as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = 'ProductID-' . $product->id . '-Image-' . $iteration . '.' . $extension;
            $path = public_path() . '/images';
            $file->move($path, $filename);
            $photo = new \App\Photos;
            $photo->product_id = $product->id;
            $photo->image_name = $filename;
            $photo->save();

            $iteration++;
        }
        // }

        return $this->sendResponse($product->toArray(), 'Product created successfully.');
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse($product->toArray(), 'Product retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $selected_product = $request->all();

        $validator = Validator::make($selected_product, [
            'product_name'  => 'required|max:255',
            'price' => 'required|digits_between:0,2147483646|numeric',
            'qty' => 'required|digits_between:0,2147483646|numeric',
            'description' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $selected_product = Product::find($id);
        $selected_product->update($request->all());

        return $this->sendResponse($selected_product->toArray(), 'Products updated successfully.');
    }

    public function destroy($id)
    {
        $selected_product = Product::find($id);
        $selected_product->delete($selected_product);
        return $this->sendResponse($selected_product->toArray(), 'Product deleted successfully.');
    }

    public function view_products_by_category($id)
    {
        $category_products = Product::where('category_id', '=', $id)->orderBy('created_at', 'DESC')->paginate(8);
        // return view('client-page/products-category', compact('category_products'));
        return $this->sendResponse($category_products->toArray(), 'Products by category retrieved successfully.');
    }

    public function view_product_details(Request $request, $id)
    {
        $selected_product = Product::find($id);
        $category = $selected_product->category;
        $photos = $selected_product->photos;
        // return view('client-page/product-details', compact('selected_product', 'category'));
        return $this->sendResponse($selected_product->toArray(), $category, $photos, 'Products details retrieved successfully.');
    }

    public function view_product_photos()
    {
        $photos = Photos::all();
        // return view('/admin-page/view-products-photo', compact('photos'));
        return $this->sendResponse($photos->toArray(), 'Product photos retrieved successfully.');
    }
}
