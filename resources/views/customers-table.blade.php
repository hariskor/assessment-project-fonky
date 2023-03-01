@extends('layouts.app')


@section('main-page-content')
@section('page-title')
        My customers
    @stop
@if(count($seller->sales))
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($seller->sales as $sale)
            <tr>
                <td><a href="/seller/customer/{{$sale->buyer->user->id}}">{{$sale->buyer->user->name}}</a></td>
                
            </tr>
            @endforeach
        <tbody>
    </table>

    @endif
@stop

