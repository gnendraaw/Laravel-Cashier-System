@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-3">
        <div class="col">
            <a href="{{route('cart.index')}}" class="btn btn-primary">Cart</a>
        </div>
    </div>
    <div class="row g-3">
        @foreach($products as $product)
        <div class="col-4">
            <div class=" card">
                <div class="card-body">
                    <p class="fw-bold fs-4">{{$product->name}}</p>
                    <p class="fs-6">Stock : {{$product->stock}}</p>
                    <div class="row justify-content-start">
                        <div class="col-3">
                            <input type="number" class="form-control" name="" id="qty{{$product->id}}" min="0" max="99">
                        </div>
                        <div class="col-9">
                            <button type="button" class="btn btn-primary productBtn" data-id="{{$product->id}}">Add to cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

<script src="{{asset('js/jquery.js')}}"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });

        $('.productBtn').on('click', function() {
            const id = $(this).data('id');
            const qty = $('#qty'+id).val();

            console.log('id', id);
            console.log('qty', qty);

            if(qty <= 0)
                console.log('qty must be more than 0');
            else
            {
                $.ajax({
                    url: "{{route('addToCart')}}",
                    method: "post",
                    data: {
                        'id' : id,
                        'qty' : qty
                    },
                    success:function(data) {
                        console.log(data);
                    },
                });
            }

        });
    });
</script>