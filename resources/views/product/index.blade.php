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
                    <p class="fs-6">Stock : <span class="productStock">{{$product->stock}}</span></p>
                    <div class="row justify-content-start">
                            <div class="row justify-content-center text-center align-items-center">
                                <div class="col">
                                    <button class="btn btn-primary minProd" data-id="{{$product->id}}""> - </button>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" id="qty{{$product->id}}" value="0">
                                </div>
                                <div class="col">
                                    <button class="btn btn-primary plusProd" data-id="{{$product->id}}" data-max="{{$product->stock}}"> + </button>
                                </div>
                            </div>
                        <div class="col">
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

        $('.plusProd').on('click', function() {
            const id = $(this).data('id');
            const max = $(this).data('max');
            const qty = $('#qty'+id);
            var qtyVal = qty.val();
            
            if(qtyVal < max)
                qtyVal++;

            qty.val(qtyVal);
        });

        $('.minProd').on('click', function() {
            const id = $(this).data('id');
            const min = 0;
            const qty = $('#qty'+id);
            var qtyVal = qty.val();

            if(qtyVal > min)
                qtyVal--;
                
            qty.val(qtyVal);
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