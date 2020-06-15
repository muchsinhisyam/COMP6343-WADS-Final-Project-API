<?php

namespace App\Http\Controllers\API;

use App\Category;
use \App\Photos;
use \App\Product;
use \App\Color;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // $iteration = 1;
        if ($request->hasFile('file')) {
            // foreach ($request->file as $file) {
            //     $extension = $file->getClientOriginalExtension();
            //     $filename = 'ProductID-' . $product->id . '-Image-' . $iteration . '.' . $extension;
            //     $path = public_path() . '/images';
            //     $file->move($path, $filename);
            //     $photo = new \App\Photos;
            //     $photo->product_id = $product->id;
            //     $photo->image_name = $filename;
            //     $photo->save();

            //     $iteration++;
        }
        // // }

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

    public function showProductPhotos($id)
    {
        $productPhotos = Photos::where('product_id', '=', $id)->get();

        if (is_null($productPhotos)) {
            return $this->sendError('Product Photos not found.');
        }

        return $this->sendResponse($productPhotos->toArray(), 'Product Photos retrieved successfully.');
    }

    public function showProductCategory($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        $productCategory = Category::where('id', '=', $product->category_id)->first();

        if (is_null($productCategory)) {
            return $this->sendError('Product Category not found.');
        }

        return $this->sendResponse($productCategory->toArray(), 'Product Category retrieved successfully.');
    }

    public function showProductColor($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        $productColor = Color::where('id', '=', $product->color_id)->first();

        if (is_null($productColor)) {
            return $this->sendError('Product Color not found.');
        }

        return $this->sendResponse($productColor->toArray(), 'Product Color retrieved successfully.');
    }

    public function showAllPhotos()
    {
        $photos = DB::table('photos')
            ->select('*')
            ->join('products', 'photos.product_id', '=', 'products.id')
            ->get();

        if (is_null($photos)) {
            return $this->sendError('Photos not found.');
        }

        return $this->sendResponse($photos->toArray(), 'Photos retrieved successfully.');
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
}
