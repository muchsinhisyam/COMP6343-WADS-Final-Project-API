<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Cart;
use App\CartDetail;

class CartNotNull
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        $loggedIn_userId = Auth::user()->id;
        $cart = Cart::select('id')->where('user_id', '=', $loggedIn_userId)->first();
        $cartDetail = CartDetail::where('cart_id', '=', $cart->id)->get();

        if (!$cartDetail->isEmpty()) {
            return $next($request);
        }
        return redirect('/cart');
    }
}
