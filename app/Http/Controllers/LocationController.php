<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Seller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function findBySeller(Seller $seller): View
    {
        $seller->locations = $seller
            ->locations()
            ->paginate(10);

        return view('locations-table')
        ->with(compact('seller'));
    }
}
