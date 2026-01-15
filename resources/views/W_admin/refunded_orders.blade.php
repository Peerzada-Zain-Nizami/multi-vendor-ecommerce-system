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
    }elseif($st == "Pending")
    {
        echo __('messages.pending');
    }elseif($st == "Return Received")
    {
        echo __('messages.return-received');
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
        elseif($st == "Cancelled")
        {
            echo "text-secondary";
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
         elseif ($st == 'Complete' || $st == 'Completed' || $st == 'return-approved') {
            echo 'text-success';
        } elseif ($st == 'Reject' || $st == 'Cancel' || $st == 'Cancelled' || $st == 'cancelled' || $st == 'return-cancelled' || $st == 'Cancelled by Seller' || $st == 'Order Cancelled') {
            echo 'text-danger';
        } elseif ($st == 'CANCELLED ON CLIENTS REQUEST') {
            echo 'text-muted';
        } elseif ($st == 'Received' || $st == 'Accepted' || $st == 'Resend' || $st == 'return-requested' || $st == 'DATA RECEIVED' || $st == 'PICKED UP' || $st == 'Collected from Retail' || $st == 'DEPARTED FORM ORIGIN' || $st == 'ARRIVED HUB FACILITY' || $st == 'DEPARTED HUB FACILITY' || $st == 'Out for Delivery' || $st == 'PROOF OF DELIVERY CAPTURED' || $st == 'AT SMSA FACILITY') {
            echo 'text-info';
        } elseif ($st == 'Packing' || $st == 'Accept' || $st == 'Shipping Process') {
            echo 'text-muted';
        } elseif ($st == 'Dispatch' || $st == 'refunded') {
            echo 'text-info';
        } else {
            echo 'text-warning';
        }
    }
    function invoice_status_lang($st)
    {
        if ($st == 'New Order') {
            echo __('messages.new-order');
        } elseif ($st == 'Pending') {
            echo __('messages.pending');
        } elseif ($st == 'Processing') {
            echo __('messages.processing');
        }
        elseif($st == "Refunded")
        {
            echo __('messages.refunded');
        }
        elseif ($st == 'Complete' || $st == 'Completed') {
            echo __('messages.complete');
        } elseif ($st == 'New Order') {
            echo __('messages.new-order');
        } elseif ($st == 'Packed') {
            echo __('messages.packed');
        } elseif ($st == 'refunded') {
            echo __('messages.refunded');
        } elseif ($st == 'return-received') {
            echo __('messages.return-received');
        } elseif ($st == 'Order Returned') {
            echo __('messages.order-returned');
        } elseif ($st == 'return-cancelled') {
            echo __('messages.return-cancelled');
        } elseif ($st == 'cancelled' || $st == 'Cancelled') {
            echo __('messages.cancelled');
        } elseif ($st == 'Order Cancelled') {
            echo __('messages.order-canceled');
        } elseif ($st == 'DATA RECEIVED') {
            echo __('messages.DATA-RECEIVED');
        } elseif ($st == 'Cancelled by Seller') {
            echo __('messages.cancelled-by-seller');
        } elseif ($st == 'return-approved') {
            echo __('messages.return-approved');
        } elseif ($st == 'Return and Refund') {
            echo __('messages.return-and-refund');
        } elseif ($st == 'Return Received') {
            echo __('messages.return-received');
        } elseif ($st == 'return-requested') {
            echo __('messages.return-requested');
        } elseif ($st == 'Dispatch') {
            echo __('messages.dispatch');
        } elseif ($st == 'Reject') {
            echo __('messages.reject');
        } elseif ($st == 'Cancel') {
            echo __('messages.cancel');
        } elseif ($st == 'Dispatched') {
            echo __('messages.dispatched');
        } elseif ($st == 'Received') {
            echo __('messages.received');
        } elseif ($st == 'Accepted') {
            echo __('messages.accepted');
        } elseif ($st == 'DEPARTED FORM ORIGIN') {
            echo __('messages.DEPARTED-FORM-ORIGIN');
        } elseif ($st == 'Collected from Retail') {
            echo __('messages.COLLECTED-FROM-RETAIL');
        } elseif($st == "Awaiting Collection")
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
       }elseif ($st == 'PROOF OF DELIVERY CAPTURED') {
            echo __('messages.PROOF-OF-DELIVERY-CAPTURED');
        } elseif ($st == 'Out for Delivery') {
            echo __('messages.OUT-FOR-DELIVERY');
        } elseif ($st == 'PICKED UP') {
            echo __('messages.PICKED-UP');
        } elseif ($st == 'Process') {
            echo __('messages.process');
        } elseif ($st == 'CANCELLED ON CLIENTS REQUEST') {
            echo __('messages.CANCELLED-ON-CLIENTS-REQUEST');
        } elseif ($st == 'Resend') {
            echo __('messages.resend');
        } elseif ($st == 'Packing' || $st == 'Accept' || $st == 'Shipping Process') {
            echo __('messages.packing');
        } else {
            echo __('messages.reject');
        }
    }
@endphp
@extends('W_admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">
                <!--Page header-->
                <div class="page-header">
                    <div class="page-leftheader">
                        <h4 class="page-title mb-0 text-primary">{{__('messages.woocomerce-orders')}}</h4>
                    </div>
                </div>
                <!--End Page header-->
                @if (Session::has('success'))
                        <div class="alert alert-light-success" role="alert">
                            <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                            <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                        </div>
                @elseif(Session::has('danger'))
                        <div class="alert alert-light-danger" role="alert">
                            <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                            <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}}
                        </div>
                @endif
            <div id="msg">

            </div>
                <!--div-->
                <div class="card">
                    <div class="card-body">
                        <table id="example" class="table table-responsive-lg table-hover table-bordered text-nowrap key-buttons">
                            <thead class="border-bottom-0 pt-3 pb-3">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('messages.order-no')}}</th>
                                <th>{{__('messages.warehouse-name')}}</th>
                                <th>{{__('messages.receiver-admin')}}</th>
                                <th>{{__('messages.platform')}}</th>
                                <th>{{__('messages.shipping-address')}}</th>
                                <th>{{__('messages.subtotal')}}</th>
                                <th>{{__('messages.shipping-company')}}</th>
                                <th>{{__('messages.shipping-fee')}}</th>
                                <th>{{__('messages.total')}}</th>
                                {{-- <th>{{__('messages.status')}}</th> --}}
                                <th>{{__('messages.order-status')}}</th>
                                <th>{{__('messages.refund-status')}}</th>
                                <th>{{__('messages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach($orders as $order)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$order->order_no}}</td>
                                    @php
                                        $warehouse = \App\Models\Warehouse::where('id',$order->order_warehouse_id)->first();
                                        $receiver_admin = \App\Models\User::where('id',$order->receiver_admin)->first();
                                    @endphp
                                    <td> @if($warehouse){{$warehouse->warehouse_id}}/{{$warehouse->warehouse_name}}@else @endif</td>
                                    <td>@if($receiver_admin){{$receiver_admin->name}}@else @endif</td>
                                    <td>{{$order->platform}}</td>
                                    @php
                                        $address = json_decode($order->shipping_address);
                                        $product = json_decode($order->product);
                                        $shipping_company = \App\Models\shipping::where('id',$order->shipping_id)->first();
                                        $shipping = json_decode($order->shipping_fee);
                                    @endphp
                                    <td>{{$address->address_1}}</td>
                                    <td>{{$order->sub_total}}</td>
                                    {{-- <td>{{$shipping_company->company_name != null ? $shipping_company->company_name :'Multan'}}</td> --}}
                                    <td>
                                        @if($shipping_company != null)
                                            {{ $shipping_company->company_name }}
                                        @else
                                            'Multan'
                                        @endif
                                    </td>
                                    <td>{{$shipping->price}}</td>
                                    @php
                                            $tax_price = $product[0]->p_tax/100*$product[0]->p_price;
                                            $total = $order->sub_total + $shipping->price + $tax_price - $order->discount;
                                    @endphp
                                    <td>{{$total}}</td>
                                    <td><span class="font-weight-bold  ms-auto {{ invoice_status($order->status) }}">{{ invoice_status_lang($order->status) }}</span></td>
                                    {{-- <td><span class="font-weight-bold  ms-auto {{invoice_status($order->status)}}">{{invoice_status_lang($order->order_status)}}</span></td> --}}
                                    <td><span class="font-weight-bold  ms-auto @if(in_array($order->api_status,["return-requested","return-cancelled","refunded"])) {{invoice_status($order->api_status)}} @else {{invoice_status($order->refund_status)}} @endif" >@if(in_array($order->api_status,["return-requested","return-cancelled","refunded"])) {{invoice_status_lang($order->api_status)}} @else {{invoice_status_lang($order->refund_status)}} @endif</span></td>
                                    <td>
                                        <a href="{{route('wadmin.refunded.order.view',$order->id)}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        <!--/div-->


    </div>
    <!-- CONTAINER END -->

@endsection
@section('query')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //select all checkboxes
        $("#select_all").change(function(){  //"select all" change
            $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
        });

        //".checkbox" change
        $('.checkbox').change(function(){
            //uncheck "select all", if one of the listed checkbox item is unchecked
            if(false == $(this).prop("checked")){ //if this item is unchecked
                $("#select_all").prop('checked', false); //change "select all" checked status to false
            }
            //check "select all" if all checkbox items are checked
            if ($('.checkbox:checked').length == $('.checkbox').length ){
                $("#select_all").prop('checked', true);
            }
        });
        $(document).on('click','#woocommerce',function () {
            if ($('.checkbox:checked').length > 10)
            {
                $('#msg').html(
                    '<div class="alert alert-light-danger" role="alert">\n' +
                    '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '    <strong>Oopps! </strong>You can select only 10 products.\n' +
                    '</div>');
            }
            else{
                var ids_array = [];
                $(".checkbox:checked").each(function() {
                    ids_array.push($(this).val());
                });
                $.ajax({
                    url: "{{route('seller.woo.add')}}",
                    type:"POST",
                    dataType: "json",
                    data: {ids:ids_array},
                    beforeSend:function(){
                        $('#global-loader').show();
                    },
                    success: function(response){
                        if (response.status == 200)
                        {
                            $('#global-loader').hide();
                            $('input:checkbox').prop('checked',false);
                            $('#msg').html(
                                '<div class="alert alert-light-success" role="alert">\n' +
                                '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Well done! </strong>'+response.message+'\n' +
                                '</div>');
                            setTimeout(function() {
                                $(".alert").alert('close');
                            }, 2000);
                        }
                        else{
                            $('#global-loader').hide();
                            $('input:checkbox').prop('checked',false);
                            $('#msg').html(
                                '<div class="alert alert-light-danger" role="alert">\n' +
                                '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Oppss! </strong>'+response.message+'\n' +
                                '</div>');
                            setTimeout(function() {
                                $(".alert").alert('close');
                            }, 2000);
                        }
                    }
                })
            }
        });
        $(document).on('click','#shopify',function () {
            if ($('.checkbox:checked').length > 10)
            {
                $('#msg').html(
                    '<div class="alert alert-light-danger" role="alert">\n' +
                    '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '    <strong>Oopps! </strong>You can select only 10 products.\n' +
                    '</div>');
            }
            else{
                var ids_array = [];
                $(".checkbox:checked").each(function() {
                    ids_array.push($(this).val());
                });
                $.ajax({
                    url: "{{route('seller.shopify.add')}}",
                    type:"POST",
                    dataType: "json",
                    data: {ids:ids_array},
                    beforeSend:function(){
                        $('#global-loader').show();
                    },
                    success: function(response){
                        if (response.status == 200)
                        {
                            $('#global-loader').hide();
                            $('input:checkbox').prop('checked',false);
                            $('#msg').html(
                                '<div class="alert alert-light-success" role="alert">\n' +
                                '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Well done! </strong>'+response.message+'\n' +
                                '</div>');
                            setTimeout(function() {
                                $(".alert").alert('close');
                            }, 2000);
                        }
                        else{
                            $('#global-loader').hide();
                            $('input:checkbox').prop('checked',false);
                            $('#msg').html(
                                '<div class="alert alert-light-danger" role="alert">\n' +
                                '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Oppss! </strong>'+response.message+'\n' +
                                '</div>');
                            setTimeout(function() {
                                $(".alert").alert('close');
                            }, 2000);
                        }
                    }
                })
            }
        });
    </script>
@endsection
