@extends('layouts.app')


@section('main-page-content')
@section('page-title')
    @if($seller)    
    Locations of seller {{$seller->name}}
    @endif
    @stop
@if($seller)
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($seller->locations as $location)
            <tr>

                <td>
                        <a href="/seller/location/{{$location->id}}">{{$location->name}}</a>
                </td>
                
            </tr>
            @endforeach
        <tbody>
    </table>
    <div class='container table-pagination-navigation' height='1em'>{!! $seller->locations->links() !!}</div>
    @endif
@stop

