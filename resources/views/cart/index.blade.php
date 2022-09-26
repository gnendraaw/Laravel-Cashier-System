@extends('layouts.app')

@section('content')
<div class="container">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <th>#</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </thead>
            <tbody>
                @foreach($cartProduct as $prod)
                <tr>
                    <td>
                        {{$loop->iteration}}
                    </td>
                    <td>
                        {{$prod->product->name}}
                    </td>
                    <td>
                        {{$prod->qty}}
                    </td>
                    <td>
                        {{$prod->product->price}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection