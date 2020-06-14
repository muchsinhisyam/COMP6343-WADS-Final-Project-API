<?php

namespace App\Http\Middleware;

use Closure;
use App\Order;

class IsOrderedByLoggedUser
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
        $selected_order = Order::find($request->id);
        if ($selected_order == null) {
            return redirect('/orders/' . auth()->user()->id);
        }
        if (auth()->user()->id == $selected_order->user_id) {
            return $next($request);
        }
        return redirect('/orders/' . auth()->user()->id);
    }
}
