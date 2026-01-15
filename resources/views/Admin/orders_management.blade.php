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
        elseif($st == "Cancelled")
        {
            echo "text-secondary";
        } elseif($st == "Return Initiated")
        {
            echo "text-secondary";
        }
        elseif($st == "In Transit")
        {
            echo "text-secondary";
        }elseif($st == "Delivered")
        {
            echo "text-secondary";
        }elseif($st == "Out for Delivery")
        {
            echo "text-secondary";
        }elseif($st == "Returned")
        {
            echo "text-secondary";
        }elseif($st == "Awaiting Collection")
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
        }elseif($st == "Delivery Attempted")
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
     elseif($st == "Complete" || $st == "completed" || $st == "Completed" || $st == "Shipping Process")
       {
           echo "text-success";
       }elseif($st == "Reject" || $st == "Cancel"|| $st == "cancelled"||$st == "Cancelled"|| $st == "Order Cancelled" || $st == "Cancelled by Seller")
       {
           echo "text-danger";
       }elseif($st == "Received" || $st == "Accepted" || $st == "DATA RECEIVED" || $st == "Accept" || $st == "Resend")
       {
        echo "text-info";
       }elseif($st == "Packing")
       {
        echo "text-muted";
       }elseif($st == "CANCELLED ON CLIENTS REQUEST")
       {
        echo "text-muted";
       }elseif($st == "Dispatch" || $st == "DATA RECEIVED")
       {
        echo "text-success";
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
    elseif($st == "Cancelled")
    {
        echo __('messages.cancelled');
    }elseif($st == "Awaiting Collection")
    {
        echo __('messages.Awaiting-Collection');
    }
    elseif($st == "Refunded")
    {
        echo __('messages.refunded');
    }
    elseif($st == "Complete" || $st == "Completed")
    {
        echo __('messages.complete');
    }
    elseif($st == "Complete" || $st == "completed" || $st == "Completed")
    {
        echo __('messages.complete');
    }elseif($st == "Pending")
    {
        echo __('messages.pending');
    }elseif($st == "Return Initiated")
    {
        echo __('messages.Return-Initiated');
    }elseif($st == "Requested")
    {
        echo __('messages.requested');
    }elseif($st == "New Order")
    {
        echo __('messages.new-order');
    }elseif($st == "Dispatch")
    {
        echo __('messages.dispatch');
    }elseif($st == "return-requested")
    {
        echo __('messages.return-requested');
    }elseif($st == "return-cancelled")
    {
        echo __('messages.return-cancelled');
    }elseif($st == "Delivery Attempted")
    {
        echo __('messages.Delivery-Attempted');
    }elseif($st == "Out for Delivery")
    {
        echo __('messages.OUT-FOR-DELIVERY');
    }elseif($st == "return-approved")
    {
        echo __('messages.return-approved');
    }elseif($st == "Delivered")
    {
        echo __('messages.DELIVERED');
    }elseif($st == "SMSA Processing")
    {
        echo __('messages.SMSA-Processing');
    }elseif($st == "Reject")
    {
        echo __('messages.reject');
    }elseif($st == "Cancel")
    {
        echo __('messages.cancel');
    }elseif($st == "cancelled" || $st == "Cancelled")
    {
        echo __('messages.cancelled');
    }elseif($st == "Cancelled by Seller")
    {
        echo __('messages.cancelled-by-seller');
    }elseif($st == "Return Received")
    {
        echo __('messages.return-received');
    }elseif($st == "Cancelled by Admin")
    {
        echo __('messages.canceled-by-admin');
    }elseif($st == "Packed")
    {
        echo __('messages.packed');
    }elseif($st == "Dispatched")
    {
        echo __('messages.dispatched');
    }elseif($st == "Resend")
       {
        echo __('messages.resend');
       }elseif($st == "Received")
       {
        echo __('messages.received');
       }elseif($st == "DATA RECEIVED")
       {
        echo __('messages.DATA-RECEIVED');
       }elseif($st == "Accepted")
       {
        echo __('messages.accepted');
       }elseif($st == "Returned")
       {
        echo __('messages.returned');
       }elseif($st == "Order Returned")
       {
        echo __('messages.order-returned');
       }elseif($st == "Collected from Retail")
       {
        echo __('messages.COLLECTED-FROM-RETAIL');
       }elseif($st == "return-received")
       {
        echo __('messages.return-received');
       }elseif($st == "Accept")
       {
        echo __('messages.accepted-on-warehouse');
       }elseif($st == "In Transit")
       {
        echo __('messages.in-transit');
       }elseif($st == "Shipping Process")
    {
        echo __('messages.processing-in-warehouse');
    }elseif($st == "Return and Refund")
    {
        echo __('messages.return-and-refund');
    }elseif($st == "Process")
    {
        echo __('messages.process');
    }elseif($st == "Packing")
       {
        echo __('messages.packing');
       }elseif($st == "Order Cancelled")
       {
        echo __('messages.order-canceled');
       }elseif($st == "CANCELLED ON CLIENTS REQUEST")
       {
        echo __('messages.CANCELLED-ON-CLIENTS-REQUEST');
       }
       elseif($st == "DATA RECEIVED")
       {
        echo __('messages.DATA-RECEIVED');
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
                        <h4 class="page-title mb-0 text-primary">{{__('messages.orders-management')}}</h4>
                    </div>
                    <div class="page-rightheader">
                        <label class="custom-switch">
                            <label class="page-title mb-0 text-primary mx-4" >{{__('messages.auto-order-processing')}}</label>
                            <input id="order_auto_processing" type="checkbox" value="{{$user->id}}" name="woo" class="custom-switch-input on_off" @if($user->order_process_status == 1)checked @endif>
                            <span class="custom-switch-indicator"></span>
                        </label>
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
                        <div class="table-responsive-lg">
                            <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                                <thead class="border-bottom-0 pt-3 pb-3">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{__('messages.order-no')}}</th>
                                    <th>{{__('messages.platform')}}</th>
                                    <th>{{__('messages.shipping-address')}}</th>
                                    <th>{{__('messages.subtotal')}}</th>
                                    <th>{{__('messages.total')}}</th>
                                    <th>{{__('messages.paid')}}</th>
                                    <th>{{__('messages.return')}}</th>
                                    <th>{{__('messages.payment-status')}}</th>
                                    <th>{{__('messages.status')}}</th>
                                    <th>{{__('messages.refund-status')}}</th>
                                    <th>{{__('messages.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($orders as $order)
                                    <tr class="@if($order->api_status == 'Cancelled' && $order->payment == "Paid") not-paid @endif">
                                        <td class="text-center">{{$i++}}</td>
                                        <td>{{$order->order_no}}</td>
                                        <td>{{$order->platform}}</td>
                                        @php
                                            $address = json_decode($order->shipping_address);
                                        @endphp
                                        <td>{{$address->address_1}}</td>
                                        <td>{{$order->sub_total}}</td>
                                        <td>{{$order->total}}</td>
                                        <td>{{$order->paid}}</td>
                                        <td>{{$order->return_payment}}</td>
                                        <td class="font-weight-bold {{payment_status($order->payment)}}">{{payment_status_lang($order->payment)}}</td>
                                        <td>
                                            <span class="font-weight-bold  ms-auto {{invoice_status($order->status)}}">{{invoice_status_lang($order->status)}}</span>
                                        </td>
                                        <td>
                                            @if(in_array($order->api_status,["return-requested","return-cancelled","return-approved","Refunded"]))
                                                <span class="font-weight-bold  ms-auto @if($order->refund_status == null){{invoice_status($order->api_status)}} @else {{invoice_status($order->refund_status)}} @endif">@if($order->refund_status == null){{invoice_status_lang($order->api_status)}} @else {{invoice_status_lang($order->refund_status)}} @endif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-light btn-pill dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('messages.action')}}</button>
                                                <div class="dropdown-menu" style="">
                                                    <a href="{{route('admin.order.invoice.checkout.view',$order->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> {{__('messages.view')}}</a>
                                                    @if($order->api_status == 'Cancelled' && $order->payment == "Paid")
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="model({{$order->id}},{{$order->remaining}})"><i class="fa fa-dollar me-2"></i> {{__('messages.refund')}}</a>
                                                    @endif
                                                    @if($order->company_name == "SMSA" && $order->delivery_status!= "CANCELLED ON CLIENTS REQUEST")
                                                        @php
                                                            $smsa_order = \App\Models\SMSAorder::where('order_id',$order->id)->first();
                                                        @endphp
                                                        @if($smsa_order != null && $order->refund_status == null)
                                                            <a href="{{route('admin.order.tracking',$order->id)}}" class="dropdown-item"><i class="fa fa-bus"></i> {{__('messages.order-tracking')}}</a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>
        <!--/div-->


    </div>
    <div class="modal fade" id="pay">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <form method="POST" id="pay_form" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h6 class="modal-title">{{__('messages.return-order-payment')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="error">
                        </div>
                        <input type="number" name="id" id="id" hidden>
                        <div class="custom-controls-stacked">
                            <span >{{__('messages.full-payment')}} (<span id="amount"></span>)</span>
                        </div>
                        <div class="col-md-12 position-relative">
                            <label class="form-label">{{__('messages.attach-proof')}}</label>
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
    <!-- CONTAINER END -->

@endsection
@section('query')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //".checkbox" change
        $(".on_off").click(function () {
            var id = $(this).attr("id");
            var user_id = $(this).val();
            var checkBox = document.getElementById(id);
            if (checkBox.checked === true){
                console.log(checkBox);
                var data = {
                    'user_id': user_id,
                    'tab': id,
                    'value': 1,
                };
                $.ajax({
                    url:"{{route('admin.order.auto.processing.on_off')}}",
                    type:"POST",
                    dataType:"json",
                    data: data,
                })
            } else {
                var data = {
                    'user_id': user_id,
                    'tab': id,
                    'value': 0,
                };
                $.ajax({
                    url:"{{route('admin.order.auto.processing.on_off')}}",
                    type:"POST",
                    dataType:"json",
                    data: data,
                })
            }
        });

        //Payment model
        function model(id,amount) {
            console.log(id);
            $('#error').html('');
            $('#error').removeClass('alert alert-light-danger');
            $('#pay').modal('show');
            $('#id').val(id);
            $('#amount').html(amount);
        }
        $(document).ready(function () {
            $('#pay_form').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                console.log(formData);
                $.ajax({
                    url: "{{route('admin.order.refund.cancelorder')}}",
                    method:"POST",
                    contentType: false,
                    processData: false,
                    data: formData,
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
                            $('#pay').modal('hide');
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                                window.location.href = '{{route('admin.woo.order.management')}}';
                            }, 2000);
                        }
                    }
                })
            });
        });
    </script>
@endsection
