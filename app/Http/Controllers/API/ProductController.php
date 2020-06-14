<?php

namespace App\Http\Controllers\API;

use \App\Photos;
use \App\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function view_products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(8);
        $countProducts = count($products);
        // $photos = DB::table('photos')
        //     ->select('*')
        //     ->join('products', 'photos.product_id', '=', 'products.id')
        //     ->get();
        // select * from photos inner join products on photos.product_id = products.id WHERE products.id = 1;
        return view('client-page/products', compact('products', 'countProducts'));
    }

    public function view_products_by_category($id)
    {
        $category_products = Product::where('category_id', '=', $id)->orderBy('created_at', 'DESC')->paginate(8);
        return view('client-page/products-category', compact('category_products'));
    }

    public function view_product_details(Request $request, $id)
    {
        $selected_product = Product::find($id);
        $category = $selected_product->category;
        return view('client-page/product-details', compact('selected_product', 'category'));
    }

    public function view_product_photos()
    {
        $photos = Photos::all();
        return view('/admin-page/view-products-photo', compact('photos'));
    }
}
