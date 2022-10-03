<section id="orderIndex">
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-8">
            <div class="row">
                @foreach($products as $product)
                <div class="col-3 g-3">
                    <button class="card text-start w-100 prodBtn" data-index="{{$loop->index}}">
                        <div class="card-body">
                            <p class="h5 fw-bold">{{$product->name}}</p>
                            <p>Stock: <span id="prodStock">{{$product->stock}}</span></p>
                            <p class="h5">Rp {{$product->price}}</p>
                        </div>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        <div class="col-4">
            <div class="container" id="cartList">
                @foreach($cart as $item)
                <div class="card m-3">
                    <div class="card-body">
                        <p>{{$item->product->name}}</p>
                        <p>{{$item->qty}}</p>
                        <div class="row">
                            <button class="btn btn-primary minBtn">-</button>
                            <input type="number" class="form-control prodQty" id="modalProdStock" min="0">
                            <button class="btn btn-primary plusBtn">+</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
</section>

<script src="{{asset('js/jquery.js')}}"></script>
<script>
    $(document).ready(function() {
        var products = <?php echo json_encode($products) ?>;
        var orderItems = {};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        addOrderItem();

        function addOrderItem()
        {
            $('.prodBtn').click(function() {
                const index = $(this).data('index');
                const id = products[index]['id'];

                if(products[index]['stock'] > 0)
                {
                    orderItems[id] ? orderItems[id]['qty']++ : appendItem(index, id);

                    updateProdStock(index, 1);
                    $(this).find('#prodStock').html(getProdStock(index));

                    console.log('items', orderItems)
                    console.log('product', products[index])
                }
            });
        }

        function updateProdStock(index, value, isPlus = false)
        {
            products[index]['stock'] += isPlus ? value : -value;
        }

        function setProdStock(index, value)
        {
            products[index]['stock'] = value;
        }

        function getProdStock(index)
        {
            return products[index]['stock'];
        }

        function appendItem(index, id)
        {
            orderItems[id] = {
                'product' : products[index]['name'],
                'qty' : 1,
            };

            var cartItem = '<div class="card m-3"><div class="card-body"><p>'+products[index]['name']+'</p><div class="row"><button class="btn btn-primary minBtn">-</button><input type="number" class="form-control prodQty" id="" min="1" value="1"><button class="btn btn-primary plusBtn">+</button></div></div></div>';

            $('#cartList').append(cartItem);
        }

        $('.plusBtn').click(function() {

        });

        // $('.prodBtn').click(function() {
        //     const id = $(this).data('id');

        //     $.ajax({
        //         url: "{{route('order.addProd')}}",
        //         method: "post",
        //         data: {
        //             "id" : id
        //         },
        //         success:function(data) {
        //             console.log(data);
        //         }
        //     });
        // });
    });
</script>