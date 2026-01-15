{{--Functions--}}
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
        }elseif($st == "SMSA Processing")
       {
        echo "text-muted";
       }
        elseif($st == "Cancelled")
        {
            echo "text-secondary";
        }elseif($st == "Returned")
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
        }elseif($st == "Delivered")
        {
            echo "text-secondary";
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
    elseif($st == "Refunded")
    {
        echo __('messages.refunded');
    }
    elseif($st == "Complete" || $st == "Completed")
    {
        echo __('messages.complete');
    }elseif($st == "Delivered")
    {
        echo __('messages.DELIVERED');
    }elseif($st == "New Order")
    {
        echo __('messages.new-order');
    }elseif($st == "refunded")
    {
        echo __('messages.refunded');
    }elseif($st == "return-cancelled")
    {
        echo __('messages.return-cancelled');
    }elseif($st == "return-received")
       {
        echo __('messages.return-received');
       }elseif($st == "Order Returned")
       {
        echo __('messages.order-returned');
       }elseif($st == "cancelled"|| $st == "Cancelled" )
    {
        echo __('messages.cancelled');
    }elseif($st == "Dispatched")
    {
        echo __('messages.dispatched');
    }elseif($st == "Returned")
    {
        echo __('messages.returned');
    }elseif($st == "Return and Refund")
    {
        echo __('messages.return-and-refund');
    }elseif($st == "Return Received")
    {
        echo __('messages.return-received');
    }elseif($st == "Order Cancelled")
    {
        echo __('messages.order-canceled');
    }elseif($st == "DATA RECEIVED")
    {
        echo __('messages.DATA-RECEIVED');
    }elseif($st == "Cancelled by Seller")
    {
        echo __('messages.cancelled-by-seller');
    }elseif($st == "Packed")
    {
        echo __('messages.packed');
    }elseif($st == "approved")
    {
        echo __('messages.approved');
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
                        <h4 class="page-title mb-0 text-primary">{{__('messages.manage-orders')}}</h4>
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
                        <div class="table-responsive-lg">
                        <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                            <thead class="border-bottom-0 pt-3 pb-3">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('messages.order-no')}}</th>
                                <th>{{__('messages.admin')}}</th>
                                <th>{{__('messages.warehouse-name')}}</th>
                                <th>{{__('messages.receiver-wadmin')}}</th>
                                <th>{{__('messages.shipping-address')}}</th>
                                <th>{{__('messages.shipping-company')}}</th>
                                <th>{{__('messages.order-status')}}</th>
                                <th>{{__('messages.action')}}</th>
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
                                        $receiver_admin = \App\Models\User::where('id',$order->receiver_wadmin)->first();
                                        $warehouse = \App\Models\Warehouse::where('id',$order->order_warehouse_id)->first();
                                    @endphp
                                    <td>{{$admin->name}}</td>
                                    <td>{{$warehouse->warehouse_id}}/{{$warehouse->warehouse_name}}</td>
                                    <td>@if($receiver_admin){{$receiver_admin->name}}@endif</td>
                                    @php
                                        $address = json_decode($order->shipping_address);
                                        $product = json_decode($order->product);
                                    @endphp
                                    <td>{{$address->address_1}}</td>
                                    <td>{{$order->company_name}}</td>
                                    <td>
                                        <span class="font-weight-bold  ms-auto {{invoice_status($order->status)}}">{{invoice_status_lang($order->status)}}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-light btn-pill dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('messages.action')}}</button>
                                            <div class="dropdown-menu" style="">
                                                <a href="{{route('wadmin.order.checkout.view',$order->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> {{__('messages.view')}}</a>
                                                @php
                                                    $smsa_order = \App\Models\SMSAorder::where('order_id',$order->id)->first();
                                                    $seller = \App\Models\User::where('id',$order->user_id)->first();
                                                @endphp

                                                @if($order->status == 'Packed')
                                                        {{-- <a href="{{route('wadmin.order.tracking',$order->id)}}" class="dropdown-item"><i class="fa fa-bus"></i> {{__('messages.order-tracking')}}</a> --}}
                                                        <a href="{{route('wadmin.get.pdf',['order_id'=>$order->id])}}" target="_blank" class="dropdown-item"><i class="fa fa-print"></i> {{__('messages.print')}}</a>
                                                {{-- @elseif ($seller->shipping_from_us == 0 && $order->company_name != "SMSA" && $order->status == 'Packed')
                                                <a href="{{route('wadmin.get.system.waybill',['id'=>$order->id])}}" target="_blank" class="dropdown-item"><i class="fa fa-print"></i> {{__('messages.print')}}</a> --}}
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                                {{--end Dynamic data--}}
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                <!--end Card-->
        </div>
    </div>
    <!-- app-content END -->
@endsection
{{--end main content--}}
