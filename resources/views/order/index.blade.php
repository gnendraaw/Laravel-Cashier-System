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
            <div class="container">
                <p class="h3 fw-bold">Total Price : <span id="totalPrice"> Rp 00</span></p>
                <button id="confirmOrder" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#paymentModal" disabled>Pay</button>
            </div>
            <div class="container" id="cartList">
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Payment</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-6 my-3">
                <p class="h5 fw-bold">Total Price</p>
                <p class="h5">Rp <span id="modalTotalPrice">00</span></p>
            </div>
            <div class="col-6">
                <div class="my-3">
                    <p class="h5 fw-bold">Input Payment</p>
                    <input type="number" id="modalPayInput" min="0" class="form-control">
                </div>
                <div class="my-3">
                    <p class="h5 fw-bold">changes</p>
                    <p class="h5">Rp <span id="modalChanges">00</span></p>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmPayment" disabled>Submit Payment</button>
    </div>
    </div>
</div>
</div>

{{-- payment success modal --}}
<div class="modal fade" id="paySuccessModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Payment</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <p class="h1 fw-bold">Payment Success!</p>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="printInvoice">Print Invoice</button>
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
        var total = 0;
        var changes = 0;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        setProductList();
        addCartItem();
        plusBtn();
        minBtn();
        confirmOrder();
        confirmPayment();
        checkPayInput();

        function reset()
        {
            orderItems = {};
            total = 0;
            changes = 0;

            setProductList();
            disableConfirmPayment();

            $('#modalTotalPrice').html(0);
            $('#modalPayInput').val(0);
            $('#modalChanges').html(0);
        }

        function setProductList()
        {
            for(let i = 0; i < products.length; ++i)
            {
                const prod = products[i];
                const id = prod['id'];
                const name = prod['name'];
                const stock = prod['stock'];
                const price = prod['price'];

                productList[id] = {
                    'name' : name,
                    'stock' : stock,
                    'price' : price,
                }
            }

            console.log(productList);
        }

        function addCartItem()
        {
            $('.prodBtn').click(function() {
                const id = $(this).data('item');

                if(productList[id]['stock'] > 0)
                {
                    orderItems[id] ? plusItemQty(id) : appendItem(id);

                    console.log('items', orderItems)
                    console.log('product', productList[id])
                }
            });
        }

        function confirmOrder()
        {
            $('#confirmOrder').on('click', function() {
                const modal = $('#paymentModal');

                modal.find('#modalTotalPrice').html(total);
            });
        }

        function confirmPayment()
        {
            $('#confirmPayment').on('click', function() {
                $.ajax({
                    url: "{{route('order.addOrder')}}",
                    method: "post",
                    data : {
                        "orderItems" : orderItems
                    },
                    success:function(data)
                    {
                        console.log(data);
                        showSuccessModal();
                        reset();
                    }
                });
            });
        }

        function checkPayInput()
        {
            $('#modalPayInput').on('keyup', function() {
                const val = $(this).val();
                changes = val - total;

                disableConfirmPayment();
                updateChanges();
            });
        }

        function updateChanges()
        {
            $('#modalChanges').html(changes);
        }

        function disableConfirmPayment()
        {
            $('#confirmPayment').prop('disabled', changes >= 0 && total > 0 ? false : true);
        }

        function disablePayBtn()
        {
            $('#confirmOrder').prop('disabled', total > 0 ? false : true);
        }

        function showSuccessModal()
        {
            $('#paySuccessModal').modal('show');
        }

        function minBtn()
        {
            $('#cartList').on('click', '.minBtn', function() {
                const id = $(this).parents('.cartItem').data('cartitem');

                minusItemQty(id);
            });
        }

        function plusBtn()
        {
            $('#cartList').on('click', '.plusBtn', function() {
                const cartItem = $(this).parents('.cartItem');
                const id = cartItem.data('cartitem');

                plusItemQty(id);
            });
        }

        function updateTotal(val)
        {
            total += val;

            $('#totalPrice').html('Rp ' + total);

            disablePayBtn();

            console.log('total', total)
        }

        function updateProdStock(id, val)
        {
            productList[id]['stock'] += val;

            $('[data-item="'+id+'"]').find('#prodStock').html(getProdStock(id));
            $('[data-cartitem="'+id+'"]').find('.cartItemQty').val(orderItems[id]['qty']);
        }

        function minusItemQty(id)
        {
            if(orderItems[id]['qty'] >= 1)
                orderItems[id]['qty']--;

            if(orderItems[id]['qty'] == 0)
                removeCartItem(id);

            updateTotal(-productList[id]['price']);
            updateProdStock(id, 1);
        }

        function plusItemQty(id)
        {
            if(productList[id]['stock'] > 0)
            {
                orderItems[id]['qty']++;
                updateTotal(productList[id]['price']);
                updateProdStock(id, -1);
            }
        }

        function removeCartItem(id)
        {
            $('[data-cartitem="'+id+'"]').remove();
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
                'id' : id,
                'product' : productList[id]['name'],
                'qty' : 1,
                'price' : productList[id]['price'],
            };

            updateTotal(productList[id]['price']);
            updateProdStock(id, -1);

            var cartItem = `
                <div class="card m-3 cartItem" data-cartitem="`+id+`">
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
    });
</script>