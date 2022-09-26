@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-3">
        <div class="col">
            <a href="" class="btn btn-primary">Cart</a>
        </div>
    </div>
    <div class="row g-3">
        @foreach($products as $product)
        <div class="col-4">
            <div class=" card">
                <div class="card-body">
                    <p class="fw-bold fs-4">{{$product->name}}</p>
                    <p class="fs-6">{{$product->stock}}</p>
                    <button class="btn btn-primary">Add to cart</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection