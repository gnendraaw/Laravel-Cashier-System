@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($products as $product)
        <div class="col">
            <button class="card prodBtn"data-id="{{$product->id}}">
                <div class="card-body">
                    <p>{{$product->name}}</p>
                    <p>{{$product->stock}}</p>
                    <p>{{$product->price}}</p>
                </div>
            </button>
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

        $('.prodBtn').click(function() {
            const id = $(this).data('id');

            $.ajax({
                url: "{{route('order.addProd')}}",
                method: "post",
                data: {"id" : id},
                success:function() {
                    console.log('added to cart!');
                }
            });
        });
    });
</script>