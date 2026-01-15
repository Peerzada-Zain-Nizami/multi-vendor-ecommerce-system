@php
    function payment_status($st)
   {
       if($st == "Paid" || $st == "Return Received")
       {
           echo "text-success";
       }elseif($st == "Pending")
       {
           echo "text-warning";
       }
       elseif($st == "Return")
       {
        echo "text-info";
       }
       else{
           echo "text-danger";
       }
   }
   function payment_status_lang($st)
{
    if($st == "Paid")
    {
        echo __('messages.paid');
    }elseif($st == "Return Received")
    {
        echo __('messages.return-received');
    }elseif($st == "Pending")
    {
        echo __('messages.pending');
    }
    else{
        echo __('messages.unpaid');
    }
}
    function invoice_status($st)
   {
    if($st == "New Order")
        {
            echo "text-warning";
        }
        elseif($st == "Pending")
        {
            echo "text-secondary";
        }
        elseif($st == "Processing")
        {
            echo "text-success";
        }
        elseif($st == "Refund Requested")
        {
            echo "text-secondary";
        }
        elseif($st == "Refund Approved")
        {
            echo "text-secondary";
        }
        elseif($st == "Refunded")
        {
            echo "text-secondary";
        }
        elseif($st == "Complete" || $st == "Completed")
        {
            echo "text-info";
        }
       elseif($st == "Complete" || $st == "Completed" || $st == "return-approved" )
       {
           echo "text-success";
       }elseif($st == "Reject" || $st == "Cancel" || $st == "cancelled"|| $st == "Cancelled" || $st == "return-cancelled" || $st == "Cancelled by Seller" || $st == "Order Cancelled")
       {
           echo "text-danger";
       }elseif($st == "CANCELLED ON CLIENTS REQUEST")
       {
        echo "text-muted";
       }elseif($st == "Received" || $st == "Accepted" || $st == "Resend" || $st == "return-requested" || $st == "DATA RECEIVED" || $st == "PICKED UP" || $st == "Collected from Retail" || $st == "DEPARTED FORM ORIGIN" || $st == "ARRIVED HUB FACILITY" || $st == "DEPARTED HUB FACILITY" || $st == "Out for Delivery" || $st == "PROOF OF DELIVERY CAPTURED" || $st == "AT SMSA FACILITY")
       {
        echo "text-info";
       }elseif($st == "Packing" || $st == "Accept" || $st == "Shipping Process" )
       {
        echo "text-muted";
       }elseif($st == "Dispatch" || $st == "refunded")
       {
        echo "text-info";
       }
       else{
           echo "text-warning";
       }
   }
function invoice_status_lang($st)
{
    if($st == "New Order")
    {
        echo __('messages.new-order');
    }
    elseif($st == "Pending")
    {
        echo __('messages.pending');
    }
    elseif($st == "Processing")
    {
        echo __('messages.processing');
    }
    elseif($st == "Complete" || $st == "Completed")
    {
        echo __('messages.complete');
    }
    elseif($st == "Complete" || $st == "Completed")
    {
        echo __('messages.complete');
    }elseif($st == "New Order")
    {
        echo __('messages.new-order');
    }elseif($st == "Return and Refund")
    {
        echo __('messages.return-and-refund');
    }elseif($st == "refunded")
    {
        echo __('messages.refunded');
    }elseif($st == "Packed")
    {
        echo __('messages.packed');
    }elseif($st == "Dispatched")
    {
        echo __('messages.dispatched');
    }elseif($st == "return-cancelled")
    {
        echo __('messages.return-cancelled');
    }elseif($st == "cancelled" || $st == "Cancelled")
    {
        echo __('messages.cancelled');
    }elseif($st == "Order Cancelled")
    {
        echo __('messages.order-canceled');
    }elseif($st == "DATA RECEIVED")
    {
        echo __('messages.DATA-RECEIVED');
    }elseif($st == "Cancelled by Seller")
    {
        echo __('messages.cancelled-by-seller');
    }elseif($st == "return-approved")
    {
        echo __('messages.return-approved');
    }elseif($st == "return-requested")
    {
        echo __('messages.return-requested');
    }elseif($st == "Dispatch")
    {
        echo __('messages.dispatch');
    }elseif($st == "Reject")
    {
        echo __('messages.reject');
    }elseif($st == "Order Returned")
       {
        echo __('messages.order-returned');
       }elseif($st == "Return Received")
    {
        echo __('messages.return-received');
    }elseif($st == "return-received")
       {
        echo __('messages.return-received');
       }elseif($st == "Cancel")
    {
        echo __('messages.cancel');
    }elseif($st == "Delivered")
    {
        echo __('messages.DELIVERED');
    }elseif($st == "Received")
       {
        echo __('messages.received');
       }elseif($st == "Accepted")
       {
        echo __('messages.accepted');
       }elseif($st == "DEPARTED FORM ORIGIN")
       {
        echo __('messages.DEPARTED-FORM-ORIGIN');
       }elseif($st == "Collected from Retail")
       {
        echo __('messages.COLLECTED-FROM-RETAIL');
       }elseif($st == "Awaiting Collection")
       {
        echo __('messages.Awaiting-Collection');
       }elseif($st == "In Transit")
       {
        echo __('messages.in-transit');
       }elseif($st == "Delivery Attempted")
       {
        echo __('messages.Delivery-Attempted');
       }elseif($st == "SMSA Processing")
       {
        echo __('messages.SMSA-Processing');
       }elseif($st == "Return Initiated")
       {
        echo __('messages.Return-Initiated');
       }elseif($st == "PROOF OF DELIVERY CAPTURED")
       {
        echo __('messages.PROOF-OF-DELIVERY-CAPTURED');
       }elseif($st == "Out for Delivery")
       {
        echo __('messages.OUT-FOR-DELIVERY');
       }elseif($st == "PICKED UP")
       {
        echo __('messages.PICKED-UP');
       }elseif($st == "Process")
    {
        echo __('messages.process');
    }elseif($st == "CANCELLED ON CLIENTS REQUEST")
       {
        echo __('messages.CANCELLED-ON-CLIENTS-REQUEST');
       }elseif($st == "Resend")
       {
        echo __('messages.resend');
       }elseif($st == "Packing" || $st == "Accept" || $st == "Shipping Process")
       {
        echo __('messages.packing');
       }
    else{
        echo __('messages.reject');
    }
}
@endphp
@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.refunded-orders')}}</h4>
                </div>
            </div>

            <!--div-->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="ms-3">{{__('messages.invoice-items')}}</span>
                            </div>
                        </div>
                        <div class="table-responsive-lg">
                            <table class="table card-table table-responsive-lg-sm table-vcenter text-nowrap shopping-carttable">
                                <thead class="border-bottom-0 pt-3 pb-3 ">
                                <tr>
                                    <th class="fs-15">{{__('messages.product')}}</th>
                                    <th>{{__('messages.product-name')}}</th>
                                    <th>{{__('messages.price')}}</th>
                                    <th>{{__('messages.tax')}}%</th>
                                    <th>{{__('messages.tax-price')}}</th>
                                    <th>{{__('messages.discount')}}</th>
                                    <th>{{__('messages.sub-total')}}</th>
                                    <th>{{__('messages.quantity')}}</th>
                                    <th>{{__('messages.total')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                        $json_products = json_decode($datas->product);
                                        $sub_total = array();
                                        $tax_sub_total = array();
                                @endphp
                                @foreach($json_products as $json_product)
                                    <tr>
                                        @php
                                            $product_id = $json_product->product_id;
                                            $product = \App\Models\Product::where('id',$product_id)->first();
                                            $address = json_decode($datas->shipping_address);
                                        @endphp
                                        <td class="fs-15"><img class="avatar avatar-lg br-7" src="{{asset('uploads/featured_images/'.$product->featured_image)}}"></td>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$json_product->rate}}</td>
                                        <td>{{$json_product->tax}}</td>
                                        <td>{{$json_product->tax_price}}</td>
                                        <td>{{$json_product->discount}}</td>
                                        <td>{{$json_product->total}}</td>
                                        <td>{{$json_product->quantity}}</td>
                                        <td>{{$json_product->sub_total}}</td>
                                        @php
                                            $sub_total[] = $json_product->sub_total;
                                            $tax_sub_total[] = $json_product->tax_price*$json_product->quantity;
                                        @endphp
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-4 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.invoice-summary')}}</div>
                        </div>
                        <div class="card-body">
                            <h5>{{__('messages.shipping-details')}}</h5>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                    @php
                                        $json_address = json_decode($datas->shipping_address);
                                        $output = \App\MyClasses\Helpers::get_shipping_address($json_address->country_code);
                                    @endphp
                                    <tr>
                                        <td class="text-start">{{__('messages.shipping-address')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$json_address->address_1}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.city')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$json_address->city}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.state')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$json_address->state}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.country')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$output}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.shipping-company')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{ucfirst($datas->company_name)}}</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div><br/>
                            <h4>{{__('messages.amount-details')}}</h4>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                    <tr>
                                        <td class="text-start">{{__('messages.sub-total')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{array_sum($sub_total)}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.shipping-price')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$datas->shipping_fee}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start fs-18">{{__('messages.total-bill')}}</td>
                                        <td class="text-end"><span class="ms-2 font-weight-bold  fs-22">{{$datas->total}}</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h4>{{__('messages.additional-details')}}</h4>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                    <tr>
                                        <td class="text-start">{{__('messages.payment')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto {{payment_status($datas->payment)}}">{{payment_status_lang($datas->payment)}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.invoice-status')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto {{invoice_status($datas->order_status)}}">{{invoice_status_lang($datas->order_status)}} </span></td>
                                    </tr>
                                    @if(!empty($datas->order_warehouse_id))
                                        @php
                                            $warehouse = \App\Models\Warehouse::where('id',$datas->order_warehouse_id)->first();
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{__('messages.warehouse')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto">{{ucfirst($warehouse->warehouse_name)}}</span></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End Row-->
        </div>
        <!--/div-->


    </div>
    <!-- CONTAINER END -->
    <div class="modal fade" id="refund">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <form method="POST" id="refund_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">{{__('messages.return-order-payment')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="error_1">
                        </div>
                        <input type="number" name="id" id="id" hidden>
                        <div class="custom-controls-stacked fs-5">
                            <span >{{__('messages.paid-amount')}} (<span class="fw-bold" id="paid"></span>)</span>
                        </div>
                        <div class="col-md-12 position-relative">
                            <label class="form-label fs-6">{{__('messages.attach-proof')}}</label>
                            <input id="proof" type="file" class="form-control" name="proof" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">{{__('messages.pay')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="pay2">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <form method="POST" id="pay_form2" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">{{__('messages.return-order-payment')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="error">
                        </div>
                        <input type="number" name="id" id="O_id" hidden>
                        <div class="custom-controls-stacked fs-5">
                            <span >{{__('messages.paid-amount')}} (<span class="fw-bold" id="paid_am"></span>)</span>
                        </div>
                        <div class="custom-controls-stacked fs-5">
                            <span >{{__('messages.shipping-price')}} (<span class="fw-bold" id="shipping_fee"></span>)</span>
                        </div>
                        <div class="custom-controls-stacked fs-5">
                            <span >{{__('messages.return-price-/-cancellation-fee')}} (<span class="fw-bold" id="return_amount"></span>)</span>
                        </div>
                        <div class="custom-controls-stacked fs-5">
                            <span >{{__('messages.return-order-payment')}} (<span class="fw-bold" id="total"></span>)</span>
                        </div>
                        <div class="col-md-12 position-relative">
                            <label class="form-label fs-6">{{__('messages.attach-proof')}}</label>
                            <input id="proof" type="file" class="form-control" name="proof" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">{{__('messages.pay')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="pay3">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <form method="POST" id="pay_form3" enctype="multipart/form-data">`
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">{{__('messages.return-order-payment')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="error_3">
                        </div>
                        <input type="number" name="id" id="O_id_3" hidden>
                        <div class="custom-controls-stacked fs-5">
                            <span >{{__('messages.paid-amount')}} (<span class="fw-bold" id="paid_am_3"></span>)</span>
                        </div>
                        <div class="custom-controls-stacked fs-5">
                            <span >{{__('messages.shipping-price')}} (<span class="fw-bold" id="shipping_fee_3"></span>)</span>
                        </div>
                        <div class="custom-controls-stacked fs-5">
                            <span >{{__('messages.return-order-payment')}} (<span class="fw-bold" id="total_3"></span>)</span>
                        </div>
                        <div class="col-md-12 position-relative">
                            <label class="form-label fs-6">{{__('messages.attach-proof')}}</label>
                            <input id="proof" type="file" class="form-control" name="proof" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">{{__('messages.pay')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('query')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Payment model
        function refund(id,payment) {
            console.log(id);
            $('#error_1').html('');
            $('#error_1').removeClass('alert alert-light-danger');
            $('#refund').modal('show');
            $('#id').val(id);
            $('#paid').html(payment);
        }
        function model2(O_id,paid_am,shipping_fee,return_amount,total) {
            console.log(id);
            $('#error').html('');
            $('#error').removeClass('alert alert-light-danger');
            $('#pay2').modal('show');
            $('#O_id').val(O_id);
            $('#paid_am').html(paid_am);
            $('#shipping_fee').html(shipping_fee);
            $('#return_amount').html(return_amount);
            $('#total').html(total);
        }
        function model3(O_id_3,paid_am_3,shipping_fee_3,total_3) {
            console.log(id);
            $('#error_3').html('');
            $('#error_3').removeClass('alert alert-light-danger');
            $('#pay3').modal('show');
            $('#O_id_3').val(O_id_3);
            $('#paid_am_3').html(paid_am_3);
            $('#shipping_fee_3').html(shipping_fee_3);
            $('#total_3').html(total_3);
        }
        $(document).ready(function () {
            $('#refund_form').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                console.log(formData);
                $.ajax({
                    url: "{{route('admin.order.pay')}}",
                    method:"POST",
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response){
                        if (response.status == 400)
                        {
                            $('#error_1').html('');
                            $('#error_1').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#error').append('<li>'+error+'</li>');
                            })
                        }
                        else{
                            $('#error_1').html('');
                            $('#error_1').removeClass('alert alert-light-danger');
                            $('#refund').modal('hide');
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                                window.location.href = '{{route('admin.order.invoice.checkout.view',$datas->id)}}';
                            }, 2000);
                        }
                    }
                })
            });
        });
        $(document).ready(function () {
            $('#pay_form2').submit(function (e) {
                e.preventDefault();
                let formDatas = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                console.log(formDatas);
                $.ajax({
                    url: "{{route('admin.order.pay.refund.return')}}",
                    method:"POST",
                    contentType: false,
                    processData: false,
                    data: formDatas,
                    success: function(response){
                        if (response.status == 400)
                        {
                            $('#error').html('');
                            $('#error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#error').append('<li>'+error+'</li>');
                            })
                        }
                        else{
                            $('#error').html('');
                            $('#error').removeClass('alert alert-light-danger');
                            $('#pay2').modal('hide');
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                                window.location.href = '{{route('admin.order.invoice.checkout.view',$datas->id)}}';
                            }, 2000);
                        }
                    }
                })
            });
        });
        $(document).ready(function () {
            $('#pay_form3').submit(function (e) {
                e.preventDefault();
                let formDatas = new FormData(this);
                console.log(formDatas);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                console.log(formDatas);
                $.ajax({
                    url: "{{route('admin.order.pay.refund.return.3')}}",
                    method:"POST",
                    contentType: false,
                    processData: false,
                    data: formDatas,
                    success: function(response){
                        console.log(response);
                        if (response.status == 400)
                        {
                            $('#error').html('');
                            $('#error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#error').append('<li>'+error+'</li>');
                            })
                        }
                        else{
                            $('#error_3').html('');
                            $('#error_3').removeClass('alert alert-light-danger');
                            $('#pay3').modal('hide');
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                                window.location.href = '{{route('admin.order.invoice.checkout.view',$datas->id)}}';
                            }, 2000);
                        }
                    }
                })
            });
        });

        $('#mySelect').change(function(){
            var id = $(this).val();
            console.log(id);
            $.ajax({
                url:'{{route('seller.invoice.shipping.get')}}',
                type:'post',
                data:{id:id},
                success:function(data){
                    $('#company_id').val(data.data.id);
                    $("#shipping_price1").val(data.data.price);
                    $("#shipping_price").html(data.data.price);
                    var a = parseInt(data.data.price);
                    var b = parseInt($('#my_value').val());
                    var sum = a + b;
                    console.log(sum);
                    $("#total1").val(sum);
                    $("#total").html(sum);
                }
            });
        });
    </script>
@endsection
