@extends('layouts.app')


@section('main-page-content')
@section('page-title')
        My Sales
    @stop
@if(count($seller->sales))
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Amount</th>
                <th>Datetime</th>
                <th>Buyer</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($buyer->sales as $sale)
            <tr>
                <td>{{$sale->product->name}} </td>
                <td>{{$sale->amount}} </td>
                <td>{{$sale->dateTime}} </td>
                <td>{{$sale->buyer->name}} </td>
                <td><a href="/seller/location/{{$sale->location->id}}">{{$sale->location->name}}</a></td>
                
            </tr>
            @endforeach
        <tbody>
    </table>

    @endif
@stop

