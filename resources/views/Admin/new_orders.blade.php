@php
    function payment_status($st)
   {
       if($st == "Paid")
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
        elseif($st == "Complete" || $st == "Completed")
        {
            echo "text-info";
        }
      elseif($st == "Complete" || $st == "Completed" || $st == "return-approved" )
       {
           echo "text-success";
       }elseif($st == "Reject" || $st == "Cancel" || $st == "cancelled" || $st == "return-cancelled" || $st == "Cancelled by Seller" || $st == "Order Cancelled")
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
    }elseif($st == "refunded")
    {
        echo __('messages.refunded');
    }elseif($st == "return-cancelled")
    {
        echo __('messages.return-cancelled');
    }elseif($st == "Process")
    {
        echo __('messages.process');
    }elseif($st == "cancelled")
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
    }elseif($st == "Return Received")
    {
        echo __('messages.return-received');
    }elseif($st == "Dispatch")
    {
        echo __('messages.dispatch');
    }elseif($st == "Reject")
    {
        echo __('messages.reject');
    }elseif($st == "Packed")
    {
        echo __('messages.packed');
    }elseif($st == "Dispatched")
    {
        echo __('messages.dispatched');
    }elseif($st == "Cancel")
    {
        echo __('messages.cancel');
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
                        <div class="table-responsive-lg">
                        <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                            <thead class="border-bottom-0 pt-3 pb-3">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('messages.order-no')}}</th>
                                <th>{{__('messages.seller-name')}}</th>
                                <th>{{__('messages.platform')}}</th>
                                <th>{{__('messages.shipping-address')}}</th>
                                <th>{{__('messages.shipping-company')}}</th>
                                <th>{{__('messages.subtotal')}}</th>
                                <th>{{__('messages.shipping-fee')}}</th>
                                <th>{{__('messages.total')}}</th>
                                <th>{{__('messages.order-status')}}</th>
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
                                        $seller = \App\Models\User::where('id',$order->user_id)->first();
                                    @endphp
                                    <td> {{$seller->name}}</td>
                                    <td>{{$order->platform}}</td>
                                    @php
                                        $address = json_decode($order->shipping_address);
                                        $product = json_decode($order->product);
                                    @endphp
                                    <td>{{$address->address_1}}</td>
                                    <td>{{$order->company_name}}</td>
                                    <td>{{$order->sub_total}}</td>
                                    <td>{{json_decode($order->shipping_fee)->price}}</td>
                                    <td>{{$order->total}}</td>
                                        {{--                                    <td>--}}
                                        {{--                                        @php--}}
                                        {{--                                            $SMSA_order = \App\Models\SMSAorder::where('order_id',$order->id)->first();--}}
                                        {{--                                            if ($SMSA_order != null)--}}
                                        {{--                                            {--}}
                                        {{--                                                $AWB_no = $SMSA_order->AWB_no;--}}
                                        {{--                                                $status = Smsa::getStatus($AWB_no,env('SMSA_PASSKEY'));--}}
                                        {{--                                                $getstatus = $status->getGetStatusResult();--}}
                                        {{--                                            }--}}
                                        {{--                                        @endphp--}}
                                        {{--                                        @if($order->order_status == "Canceled by Admin" && $SMSA_order == null)--}}
                                        {{--                                            <span title="@if($order->order_status == "Canceled by Admin" && $SMSA_order == null) Our system Status @endif" class="font-weight-bold  ms-auto @if($order->order_status == "Canceled by Admin" && $SMSA_order == null) {{invoice_status($order->order_status)}} @endif ">@if($order->order_status == "Canceled by Admin" && $SMSA_order == null) {{invoice_status_lang($order->order_status)}} @endif</span>--}}
                                        {{--                                        @elseif($order->order_status == "Canceled by Admin" && $SMSA_order != null)--}}
                                        {{--                                            <span title="Our system Status" class="font-weight-bold  ms-auto {{invoice_status($order->order_status)}} ">{{invoice_status_lang($order->order_status)}}</span>--}}
                                        {{--                                            <br>--}}
                                        {{--                                            <span title="Shipping Company Status" class="font-weight-bold  ms-auto {{invoice_status($getstatus)}} ">{{invoice_status_lang($getstatus)}}</span>--}}
                                        {{--                                        @elseif(($order->order_status == "Packing" || $order->order_status == "Accept" || $order->order_status == "Shipping Process" || $order->order_status == "Reject") && $SMSA_order == null )--}}
                                        {{--                                            <span title="Our system Status" class="font-weight-bold  ms-auto {{invoice_status($order->order_status)}} ">{{invoice_status_lang($order->order_status)}}</span>--}}
                                        {{--                                        @else--}}
                                        {{--                                            <span title="@if($order->order_status == "Order Canceled by Seller" && $SMSA_order == null) Our System Status @elseif($order->order_status == "Order Canceled by Seller" && $SMSA_order != null) Shipping Company Status @elseif($order->order_status == "Shipping Process" && $SMSA_order != null) Shipping Company Status @endif" class="font-weight-bold  ms-auto @if($order->order_status == "Order Canceled by Seller" && $SMSA_order == null){{invoice_status($order->order_status)}} @elseif($order->order_status == "Order Canceled by Seller" && $SMSA_order != null) {{invoice_status($getstatus)}}@elseif($order->order_status == "Shipping Process" && $SMSA_order != null) {{invoice_status($getstatus)}} @endif ">@if($order->order_status == "Order Canceled by Seller" && $SMSA_order == null){{invoice_status_lang($order->order_status)}} @elseif($order->order_status == "Order Canceled by Seller" && $SMSA_order != null) {{invoice_status_lang($getstatus)}}@elseif($order->order_status == "Shipping Process" && $SMSA_order != null) {{invoice_status_lang($getstatus)}} @else {{invoice_status_lang($getstatus)}} @endif</span>--}}
                                        {{--                                        @endif--}}
                                        {{--                                    </td>--}}
                                                                            <td><span class="font-weight-bold  ms-auto {{invoice_status($order->status)}}">{{invoice_status_lang($order->status)}}</span></td>
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
    <!-- CONTAINER END -->
@endsection
