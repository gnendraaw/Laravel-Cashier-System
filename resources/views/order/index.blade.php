@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-8">
            <div class="row">
                @foreach($products as $product)
                <div class="col-3 g-3">
                    <button class="card text-start w-100 prodBtn" data-item="{{$product->id}}">
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
                {{-- @foreach($cart as $item)
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
                @endforeach --}}
            </div>
        </div>
    </div>
</div>
@endsection

<script src="{{asset('js/jquery.js')}}"></script>
<script>
    $(document).ready(function() {
        var products = <?php echo json_encode($products) ?>;
        var productList = {};
        var orderItems = {};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        setProductList();
        addCartItem();
        plusBtn();

        function setProductList()
        {
            for(let i = 0; i < products.length; ++i)
            {
                const prod = products[i];
                const id = prod['id'];
                const name = prod['name'];
                const stock = prod['stock'];

                productList[id] = {
                    'name' : name,
                    'stock' : stock
                }
            }

            console.log(productList);
        }

        function plusBtn()
        {
            $('#cartList').on('click', '.plusBtn', function() {
                const id = $(this).parents('.cartItem').data('cartItem');
                const product = products.find(function(product, index) {
                    if(product.id == id) return true;
                });
                
                console.log('product', product);
            });
        }

        function addCartItem()
        {
            $('.prodBtn').click(function() {
                const id = $(this).data('item');

                if(productList[id]['stock'] > 0)
                {
                    orderItems[id] ? orderItems[id]['qty']++ : appendItem(id);

                    updateProdStock(id, -1);

                    console.log('items', orderItems)
                    console.log('product', productList[id])
                }
            });
        }

        function updateCartQty(id)
        {

        }

        function updateProdStock(id, val)
        {
            productList[id]['stock'] += val;
            $('[data-item="'+id+'"]').find('#prodStock').html(getProdStock(id));
        }

        function setProdStock(id, value)
        {
            productList[id]['stock'] = value;
        }

        function getProdStock(id)
        {
            return productList[id]['stock'];
        }

        function appendItem(id)
        {
            orderItems[id] = {
                'product' : productList[id]['name'],
                'qty' : 1,
            };

            var cartItem = `
                <div class="card m-3 cartItem" data-cartItem="` + id + `">
                    <div class="card-body">
                        <p>`+ productList[id]['name'] +`</p>
                        <div class="row">
                            <button class="btn btn-primary minBtn">-</button>
                            <input type="number"  class="form-control cartItemQty" value="1" min="1" max="`+ productList[id]['stock'] +`">
                            <button class="btn btn-primary plusBtn">+</button>
                        </div>
                    </div>
                </div>`;

            $('#cartList').append(cartItem);
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