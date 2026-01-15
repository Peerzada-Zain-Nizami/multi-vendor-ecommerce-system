{{--Functions--}}
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
        elseif($st == "Complete" || $st == "Completed" || $st == "return-approved" )
       {
           echo "text-success";
       }elseif($st == "Reject" || $st == "Cancel" || $st == "Cancelled" || $st == "cancelled" || $st == "return-cancelled" || $st == "Cancelled by Seller" || $st == "Order Cancelled")
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
    }elseif($st == "New Order")
    {
        echo __('messages.new-order');
    }elseif($st == "Packed")
    {
        echo __('messages.packed');
    }elseif($st == "Dispatched")
    {
        echo __('messages.dispatched');
    }elseif($st == "refunded")
    {
        echo __('messages.refunded');
    }elseif($st == "return-cancelled")
    {
        echo __('messages.return-cancelled');
    }elseif($st == "cancelled"|| $st == "Cancelled" )
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
    }elseif($st == "Return and Refund")
    {
        echo __('messages.return-and-refund');
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
{{--end Functions--}}
@extends('W_admin.base')
{{--start main content--}}
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

                <!--Message-->
                @if (Session::has('success'))
                        <div class="alert alert-light-success" role="alert">
                            <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                            <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                        </div>
                @endif
                @if(Session::has('danger'))
                        <div class="alert alert-light-danger" role="alert">
                            <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                            <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}}
                        </div>
                @endif
                <div id="msg">

                </div>
                <!--end Message-->

                <!--Card-->
                <div class="card">
                    <div class="card-body">
                        <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                            <thead class="border-bottom-0 pt-3 pb-3">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('messages.order-no')}}</th>
                                <th>{{__('messages.admin')}}</th>
                                <th>{{__('messages.warehouse-name')}}</th>
                                <th>{{__('messages.shipping-address')}}</th>
                                <th>{{__('messages.shipping-company')}}</th>
                                <th>{{__('messages.order-status')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = 1;
                            @endphp
                            {{--Dynamic data--}}
                            @foreach($orders as $order)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$order->order_no}}</td>
                                    @php
                                        $admin = \App\Models\User::where('id',$order->admin_id)->first();
                                        $receiver_admin = \App\Models\User::where('id',$order->receiver_admin)->first();
                                        $warehouse = \App\Models\Warehouse::where('id',$order->order_warehouse_id)->first();
                                    @endphp
                                    <td>{{$admin->name}}</td>
                                    <td>{{$warehouse->warehouse_id}}/{{$warehouse->warehouse_name}}</td>
                                    @php
                                        $address = json_decode($order->shipping_address);
                                        $product = json_decode($order->product);
                                    @endphp
                                    <td>{{$address->address_1}}</td>
                                    <td>{{$order->company_name}}</td>
                                    <td>
                                        <span class="font-weight-bold  ms-auto {{invoice_status($order->status)}}">{{invoice_status_lang($order->status)}}</span>
                                    </td>
                                </tr>
                            @endforeach
                            {{--end Dynamic data--}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end Card-->
        </div>
    </div>
    <!-- app-content END -->
@endsection
{{--end main content--}}

{{--java & jquery--}}
