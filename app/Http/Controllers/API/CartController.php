<?php

namespace App\Http\Controllers\API;

use App\Cart;
use App\CartDetail;
use App\Product;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends BaseController
{
    public function view_cart()
    {
        $loggedIn_userId = Auth::user()->id;
        $cart = Cart::select('id')->where('user_id', '=', $loggedIn_userId)->first();
        $cartDetail = CartDetail::with('product.photos')->where('cart_id', '=', $cart->id)->orderBy('created_at', 'DESC')->get();
        $subTotal = 0;

        foreach ($cartDetail as $detail) {
            $selectedProductPrice = $detail->qty * $detail->product->price;
            $subTotal = $selectedProductPrice + $subTotal;
        }

        // return view('client-page/cart', compact('cartDetail', 'subTotal'));
        return $this->sendResponse($cartDetail->toArray(), $subTotal, 'Cart Detail retrieved successfully.');
    }

    public function insertProductToCart(Request $request, $id)
    {
        $status = "success";

        $addedProduct = Product::find($id);
        $loggedIn_userId = Auth::user()->id;
        // Selecting 'id' on carts
        $cart = Cart::select('id')->where('user_id', '=', $loggedIn_userId)->first();
        $cartDetail = CartDetail::where('cart_id', '=', $cart->id)->get();

        if ($addedProduct->qty == 0) {
            // return redirect('/cart')->with('success', 'Oops! The product is finished. Please order again next time.');
            return $this->sendResponse($status, 'Cart Detail retrieved successfully.');
        } else if ($request->qty > $addedProduct->qty) {
            $message = 'Oops! The stock is limited. There are ' . $addedProduct->qty . ' left.';
            // return redirect('/cart')->with('success', $message);
            return $this->sendResponse($status, $message);
        }

        // If user created a cart from /products page (instant Cart), then the qty set to 1
        if ($request->quantity == null) {
            $selectedQty = 1;
        }
        // If user created a cart from /products-details page , then the qty is set to the selected qty value
        else {
            $selectedQty = $request->quantity;
        }

        $selectedProductInCartDetails = $cartDetail->where('product_id', '=', $id)->first();

        // // If selected product already exist
        if ($this->productExist($selectedProductInCartDetails, $addedProduct)) {
            // UPDATE QTY = QTY value on Table + SelectedQty
            CartDetail::where('product_id', $id)->update(
                array(
                    'qty' => $selectedProductInCartDetails->qty + $selectedQty
                )
            );
        } else {
            $newCartDetail = new CartDetail;
            $newCartDetail->cart_id = $cart->id;
            $newCartDetail->product_id = $addedProduct->id;
            $newCartDetail->qty = $selectedQty;
            $newCartDetail->save();
        }

        // return redirect('/cart')->with('success', 'Product successfully added to Cart');
        return $this->sendResponse($status, 'Cart Detail retrieved successfully.');
    }

    public function productExist($selectedProduct, $product)
    {
        if ($selectedProduct == null) {
            return false;
        } else {
            return true;
        }
    }

    public function update_cart_details(Request $request, $id)
    {
        $status = "success";

        $loggedIn_userId = Auth::user()->id;
        // Selecting 'id' on carts
        $cart = Cart::select('id')->where('user_id', '=', $loggedIn_userId)->first();
        $cartDetails = CartDetail::where('cart_id', '=', $cart->id)->get();

        $selectedQuantititesOfChosenProducts = $request->all();

        $count = count($selectedQuantititesOfChosenProducts['quantity']);

        foreach ($cartDetails as $cartDetail) {
            $count--;
            CartDetail::where('id', $cartDetail->id)->update(
                array(
                    'qty' => $selectedQuantititesOfChosenProducts['quantity'][$count]
                )
            );
        }
        // return redirect('/cart')->with('success', 'Cart successfully updated');
        return $this->sendResponse($status, 'Cart Detail retrieved successfully.');
    }

    public function  delete_cart_details($id)
    {
        $status = "success";

        $selected_item = CartDetail::find($id);
        $selected_item->delete($selected_item);
        // return redirect('/cart')->with('success', 'Product successfully deleted');
        return $this->sendResponse($status, 'Cart Detail retrieved successfully.');
    }
}
