@extends('layouts.app')


@section('main-page-content')
@section('page-title')
        Sellers
    @stop
@if(count($sellers))
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sellers as $seller)
            <tr>

                <td><a href="{{route('seller-locations', ['seller' => $seller->id])}}">{{$seller->name}}</a></td>
                
            </tr>
            @endforeach
        <tbody>
    </table>
    <div class='container table-pagination-navigation' height='1em'>{!! $sellers->links() !!}</div>
    @endif
@stop

