<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sellers = Seller::with('locations')
            ->paginate(10);

        return view('sellers-table')->with(compact('sellers'));
    }

    public function myLocations(): View
    {
        $seller = $this->determineSeller();

        $seller->locations = $seller
        ->locations()
        ->paginate(10);

        $title = 'locations';
        return view('locations-table')
        ->with(compact('seller', 'title'));
    }

    public function mySales(): View
    {
        $seller = $this->determineSeller();
        $sales = $seller
            ->sales()
            ->with([
                'buyer' => function ($query) {
                    $query->with('user');
                },
                'location',
                'product',
            ])
            ->paginate(10);

        $title = 'Sales';
        return view('purchases-table')
            ->with(compact(['sales', 'title']));
    }

    public function locationOrders(Location $location): View
    {
        $seller = $this->determineSeller();

        $sales = $seller
        ->sales()
        ->where('location_id', '=', $location->id)
        ->with('buyer')
        ->with('location')
        ->with('product')
        ->get();
        $title = sprintf($location->name . 'sales');

        return view('purchases-table')
            ->with(compact(['sales', 'title']));
    }

    public function myCustomers(): View
    {
        $seller = $this->determineSeller();

        $seller->sales = $seller->sales()
        ->with([
            'buyer' => function ($query) {
                $query->with('user');
            },
        ])
        ->distinct()
        ->get();

        return view('customers-table')
            ->with(compact(['seller']));
    }

    public function customerPurchaseHistory(User $user): View
    {
        $seller = $this->determineSeller();

        $user->customer = $user->buyer()->first();

        $sales = $user->customer->purchases()
        ->with([
            'seller' => function ($query) use ($seller) {
                $query->where('id', $seller->id);
            },
        ])
        ->with('location')
        ->with('product')
        ->get();

        $title = 'Purchases of ' . $user->name;
        return view('purchases-table')
            ->with(compact(['sales', 'title']));
    }

    /**
     * @return Seller
     */
    private function determineSeller(): Seller
    {
        $user = User::where('id', Auth::id())->with('seller')->first();
        return $user->seller;
    }
}
