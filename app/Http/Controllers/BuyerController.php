<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BuyerController extends Controller
{
    
    public function index(): View
    {
        $buyer = $this->determineBuyer();

        $sales = $buyer->purchases()
        ->with('seller')
        ->with('location')
        ->with('product')
        ->get();

        $title = 'My Purchases';
        return view('purchases-table')
            ->with(compact('sales', 'title'));
    }

    /**
     * @return Buyer
     */
    private function determineBuyer(): Buyer
    {
        $user = User::where('id', Auth::id())->with('buyer')->first();
        return $user->buyer;
    }
}
