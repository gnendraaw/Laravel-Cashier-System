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
                <div class="card m-3 cartItem" data-id="{{$item->product_id}}">
                    <div class="card-body">
                        <p>{{$item->product->name}}</p>
                        <div class="row">
                            <button class="btn btn-primary minBtn">-</button>
                            <input type="number"  class="form-control cartItemQty" value="1" min="1" max="{{$item->product->stock}}">
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
        plusBtn();

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

        function plusBtn()
        {
            $('.plusBtn').click(function() {
                const cartItem = $(this).parents('.cartItem');
                const id = cartItem.data('id');

                const product = products.find(function(product, index) {
                    if(product.id == id) return true;
                });

                console.log('id', id);
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

            var cartItem = `
                <div class="card m-3 cartItem" data-id="` + id + `">
                    <div class="card-body">
                        <p>`+ products[index]['name'] +`</p>
                        <div class="row">
                            <button class="btn btn-primary minBtn">-</button>
                            <input type="number"  class="form-control cartItemQty" value="1" min="1" max="`+ products[index]['stock'] +`">
                            <button class="btn btn-primary plusBtn">+</button>
                        </div>
                    </div>
                </div>`;

            $('#cartList').append(cartItem);

            plusBtn();
        }

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