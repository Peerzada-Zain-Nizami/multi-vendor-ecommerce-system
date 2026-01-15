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
    elseif($st == "Cancelled")
    {
        echo __('messages.cancelled');
    }
    elseif($st == "Refund Requested")
    {
        echo __('messages.return-requested');
    }
    elseif($st == "Refund Approved")
    {
        echo __('messages.return-approved');
    }
    elseif($st == "Refund Cancelled")
    {
        echo __('messages.refund-cancel');
    }
    elseif($st == "Complete" || $st == "Completed")
    {
        echo __('messages.complete');
    }
    elseif($st == "Complete" || $st == "Completed")
    {
        echo __('messages.complete');
    }elseif($st == "Pending")
    {
        echo __('messages.pending');
    }elseif($st == "Requested")
    {
        echo __('messages.requested');
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
    }
    elseif($st == "Refunded")
    {
        echo __('messages.refunded');
    }

    elseif($st == "DATA RECEIVED")
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
                    <h4 class="page-title mb-0 text-primary">#{{$result->invoice_no}} {{__('messages.invoice-details')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-12">
                    @if (Session::has('success'))
                        <div class="alert alert-light-success" role="alert">
                            <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                            <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                        </div>
                    @endif
                        @if (Session::has('danger'))
                            <div class="alert alert-light-danger" role="alert">
                                <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}}
                            </div>
                        @endif
                        @if(Session::has('errors'))
                            <div class="alert alert-light-danger" role="alert">
                                <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                @foreach(Session::get('errors') as $error)
                                    <strong>{{__('messages.oopps')}}</strong> {{$error[0]}} <br>
                                @endforeach
                            </div>
                        @endif
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="ms-3">{{__('messages.invoice-items')}}</span>
                            </div>
                        </div>
                        <div class="table-responsive-lg">
                            <table class="table card-table table-vcenter text-nowrap shopping-carttable">
                                <thead class="border-bottom-0 pt-3 pb-3 ">
                                <tr>
                                    <th>#</th>
                                    <th class="fs-15">{{__('messages.product')}}</th>
                                    <th>{{__('messages.product-name')}}</th>
                                    <th>{{__('messages.price')}}</th>
                                    <th>{{__('fee')}}</th>
     fee                            <th>{{__('messages.order-quantity')}}</th>
                                    <th>{{__('messages.total')}}</th>
                                    <th>{{__('messages.already-return')}}</th>
                                    <th>{{__('messages.already-sold')}}</th>
                                    <th>{{__('messages.return-values')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                    $original_order = json_decode($result->original_order);
                                    $returns = json_decode($result->products);
                                @endphp
                                @foreach($original_order as $row)
                                    @php
                                        $cp = \App\Models\Product::find($row->product_id);
                                        $return = $returns[$loop->index];
                                    $sold = \App\Models\CompanyOrder::sold($result->invoice_no,$result->supplier_id,$row->product_id);
                                    @endphp
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td><img class='avatar avatar-lg br-7' src="{{asset('uploads/featured_images/'.$cp->featured_image)}}"></td>
                                        @php
                                            $data = \App\MyClasses\Helpers::get_lang($cp->product_name,$cp->id,"product",App::getLocale());
                                            $product_data = json_decode($data);
                                        @endphp
                                        <td>@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$cp->product_name}} @endif</td>
                                        <td>{{$row->rate}}</td>
                                        <td>{{$row->shipping_charges}}</td>
                                        <td>{{$row->quantity}}</td>
                                        <td>{{$row->rate*$row->quantity}}</td>
                                        <td>{{$row->quantity-$return->quantity}}</td>
                                        <td>{{$sold}}</td>
                                        <td>
                                            <div class="form-group">
                                                <input class="form-control" type="number" value="{{$return->quantity-$sold}}" disabled>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="form-group text-center">
                    <a href="{{route('admin.manage.invoice.return.full.request',$result->invoice_no)}}" class="btn btn-primary">{{__('messages.send-request')}}</a>
                </div>
            </div>
            <!-- End Row-->
        </div>
        <!--/div-->


    </div>
    </div>
    <!-- CONTAINER END -->

@endsection
