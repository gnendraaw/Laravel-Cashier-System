<section id="orderIndex">
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-8">
            <div class="row">
                @foreach($products as $product)
                <div class="col-3 g-3">
                    <button class="card text-start w-100 prodBtn" data-id={{$product->id}}>
                        <div class="card-body">
                            <p class="h5 fw-bold">{{$product->name}}</p>
                            <p>Stock: {{$product->stock}}</p>
                            <p class="h5">Rp {{$product->price}}</p>
                        </div>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-4">
            @foreach($cart as $item)
            <div class="row m-3">
                <div class="card">
                    <div class="card-body">
                        <p>{{$item->product->name}}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
</section>

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
                data: {
                    "id" : id
                },
                success:function(data) {
                    console.log(data);
                    location.reload();
                }
            });
        });
    });
</script>