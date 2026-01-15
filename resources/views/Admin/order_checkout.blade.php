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
        elseif ($st == 'Cancellation Request') {
                echo __('messages.cancellation-request');
        }elseif ($st == 'Cancel Approved') {
                echo __('messages.cancel-approved');
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
        }elseif($st == "Cancel Approved")
        {
            echo __('messages.cancel-approved');
        }elseif($st == "Cancel and Refund Approved")
        {
            echo __('messages.cancel-and-refund-approved');
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
        }elseif ($st == 'Cancel and Refund') {
                echo __('messages.Cancel-and-Refund');
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
                    <h4 class="page-title mb-0 text-primary">{{__('messages.invoice-checkout')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <li class="text-danger">{{$error}}</li>
                @endforeach
            @endif
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
                                    <th>{{__('messages.product-discount')}}</th>
                                    <th>{{__('messages.plan-discount')}}</th>
                                    <th>{{__('messages.sub-total')}}</th>
                                    <th>{{__('messages.order_quantity')}}</th>
                                    <th>{{__('messages.available_quantity')}}</th>
                                    <th>{{__('messages.tax-price')}}</th>
                                    <th>{{__('messages.discount-price')}}</th>
                                    <th>{{__('messages.total')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $flag = false;
                                    $stock_count = 0;
                                @endphp
                                @foreach(json_decode($order->product) as $json_product)
                                    <tr style="@if($order->is_confirm != 1 && $json_product->order_qty > $json_product->available_qty) @endif" >
                                        @php
                                            if ($json_product->order_qty > $json_product->available_qty)
                                                {
                                                    $flag = true;
                                                }
                                            $stock_count += $json_product->available_qty;
                                            $product = \App\Models\Product::where('id',$json_product->p_id)->first();
                                            $address = json_decode($order->shipping_address);
                                        @endphp
                                        <td class="fs-15"><img class="avatar avatar-lg br-7" src="{{asset('uploads/featured_images/'.$product->featured_image)}}"></td>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$json_product->p_price}}</td>
                                        <td>{{$json_product->p_tax}}</td>
                                        <td>{{$json_product->p_disc}}</td>
                                        <td>{{$json_product->p_plan_disc}}<span class="font-weight-bold  ms-auto">@if($json_product->p_plan_disc_method == "percentage") % @else SAR @endif</span></td>
                                        <td>{{$json_product->sub_total}}</td>
                                        <td>{{$json_product->order_qty}}</td>
                                        <td>{{$json_product->available_qty}}</td>
                                        <td>{{$json_product->tax_price}}</td>
                                        <td>{{$json_product->dis_price}}</td>
                                        <td>{{$json_product->total}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>



                    @if (!empty(json_decode($order->refund_items)) && $order->refund_status != null)

                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="ms-3">{{__('messages.refund-items')}}</span>
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
                                    <th>{{__('messages.product-discount')}}</th>
                                    <th>{{__('messages.plan-discount')}}</th>
                                    <th>{{__('messages.sub-total')}}</th>
                                    <th>{{__('messages.refund-quantity')}}</th>
                                    <th>{{__('messages.tax-price')}}</th>
                                    <th>{{__('messages.discount-price')}}</th>
                                    <th>{{__('messages.total')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $stock_count = 0;
                                @endphp
                                @foreach(json_decode($order->product) as $json_product)
                                @foreach (json_decode($order->refund_items) as $refund_item)
                                <?php
                                if ($order->status == 'DELIVERED' && $order->refund_status == 'Refund Approved') {
                                    $return_price = \App\Models\ShippingPrice::where('group_id',$order->shipping_group)->first();
                                    $refundAmount= $order->paid-$order->shipping_fee-$return_price->return_price;
                                }
                                elseif (in_array($order->status,["Collected from Retail","Dispatched","In Transit","Out for Delivery","Delivery Attempted"]) && $order->refund_status == 'Cancel and Refund Approved') {
                                    $return_price = \App\Models\ShippingPrice::where('group_id',$order->shipping_group)->first();
                                    $refundAmount = $order->paid-$order->shipping_fee;
                                }
                                else {
                                    $refundAmount = $order->paid;
                                }
                                ?>
                               @if ($json_product->p_id == $refund_item->p_id)
                                <tr style="@if($order->is_confirm != 1 && $json_product->order_qty > $json_product->available_qty) background-color: #FFCCCC @endif" >
                                    @php
                                            $stock_count += $json_product->available_qty;
                                            $product = \App\Models\Product::where('id',$refund_item->p_id)->first();
                                            $address = json_decode($order->shipping_address);

                                            $product_price = $json_product->total - ($json_product->sub_total * $refund_item->p_qty);
                                        @endphp
                                        <td class="fs-15"><img class="avatar avatar-lg br-7" src="{{asset('uploads/featured_images/'.$product->featured_image)}}"></td>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$json_product->p_price}}</td>
                                        <td>{{$json_product->p_tax}}</td>
                                        <td>{{$json_product->p_disc}}</td>
                                        <td>{{$json_product->p_plan_disc}}<span class="font-weight-bold  ms-auto">@if($json_product->p_plan_disc_method == "percentage") % @else SAR @endif</span></td>
                                        <td>{{$json_product->sub_total}}</td>
                                        <td>{{$refund_item->p_qty}}</td>
                                        <td>{{$json_product->tax_price}}</td>
                                        <td>{{$json_product->dis_price}}</td>
                                        <td>{{$product_price < 0 ? $json_product->total :$json_product->sub_total * $refund_item->p_qty }}</td>
                                    </tr>
                                    @endif
                                @endforeach
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    @endif

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
                                        $json_address = json_decode($order->shipping_address);
                                        $output = \App\MyClasses\Helpers::get_shipping_address($json_address->country_code);
                                        $shipping_group = \App\Models\ShippingGroup::where('id',$order->shipping_group)->first();
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
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$order->company_name}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.shipping-group')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{(isset($shipping_group->name))?$shipping_group->name:__('messages.record-not-found')}}</span></td>
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
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$order->sub_total}}</span></td>
                                    </tr>
                                    @php
                                        $shipping = json_decode($order->shipping_fee);
                                    @endphp
                                    <tr>
                                        <td class="text-start">{{__('messages.shipping-price')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$shipping->price}}</span></td>
                                    </tr>
                                    @php
                                    $return_price = App\Models\ShippingPrice::where('group_id', $order->shipping_group)->first();
                                @endphp

                                    <tr>
                                        <td class="text-start">{{__('messages.discount')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$shipping->discount}}{{$shipping->discount_method}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.discount-price')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$shipping->discount_price}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start fs-18">{{__('messages.total-bill')}}</td>
                                        <td class="text-end"><span class="ms-2 font-weight-bold  fs-22">{{$order->total}}</span></td>
                                    </tr>
                                    @if (!empty(json_decode($order->refund_items)) && $order->refund_status != null && (!empty($order->return_payment)) )
                                    <tr>
                                        <td class="text-start">{{__('messages.refund-amount')}}</td>
                                        <td class="text-end"><span class="ms-2 font-weight-bold">{{$order->return_payment}}</span></td>
                                    </tr>
                                    @endif
                                    @if ($order->refund_status == 'Refunded' && $order->status == 'Packed' && $order->status == 'DELIVERED')
                                    <tr>
                                        <td class="text-start">{{__('messages.return-price-/-cancellation-fee')}}</td>
                                        <td class="text-end"><span class="ms-2 font-weight-bold">{{$return_price->return_price}}</span></td>
                                    </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <h4>{{__('messages.additional-details')}}</h4>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                    <tr>
                                        <td class="text-start">{{__('messages.payment')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto {{payment_status($order->payment)}}">{{payment_status_lang($order->payment)}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.order-status')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto {{invoice_status($order->status)}}">{{invoice_status_lang($order->status)}} </span></td>
                                    </tr>
                                    @if(in_array($order->api_status,["return-requested","return-cancelled","return-approved"]) && $order->refund_status == null )
                                        <tr>
                                            <td class="text-start">{{__('messages.refund-status')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto {{invoice_status($order->api_status)}}">{{invoice_status_lang($order->api_status)}}</span></td>
                                        </tr>
                                    @endif


                                    @if(!empty($order->order_warehouse_id))
                                        @php
                                            $warehouse = \App\Models\Warehouse::where('id',$order->order_warehouse_id)->first();
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{__('messages.warehouse')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto">{{ucfirst($warehouse->warehouse_name)}}</span></td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                                @php
                                $users = App\Models\User::where('role',"SuperAdmin")->first();
                           @endphp
                        @if($users->order_process_status == 0 && in_array($order->status,["Pending","New Order"]) && $order->is_confirm == 1)
                        <form action="{{ route('admin.order.approved', $order->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                @if ($order->api_status != 'Cancelled')
                                <h4>{{__('messages.further-processing')}}</h4>
                                <select name="order_approval" class="form-control select2 @if($errors->has('status'))is-invalid @endif">
                                    <option value="">{{__('messages.please-select')}}</option>
                                   @if (array_sum(array_column(json_decode($order->product), 'available_qty')) > 0 && !empty($order->order_warehouse_id))
                                    <option value="Accepted">{{__('messages.accepted')}}</option>
                                    @else
                                    <option value="Reject">{{__('messages.reject')}}</option>
                                    @endif
                                </select>
                                @if ($errors->has('status'))
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                @endif
                                @endif
                            </div>
                            <div class="form-group text-center">
                                <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                            </div>
                        </form>
                        @endif
                            </div>
                            {{--Refund Order payment--}}
                            @if($order->refund_status != null)
                                <h4>{{__('messages.refund-order')}}</h4>
                                <div class="table-responsive-lg">
                                    <table class="table table-borderless text-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="text-start">{{__('messages.refund-status')}}</td>
                                                <td class="text-end"><span class="font-weight-bold  ms-auto {{invoice_status($order->refund_status)}}">{{invoice_status_lang($order->refund_status)}}</span></td>
                                            </tr>
                                            @if($order->return_payment != 0)
                                                <tr>
                                                    <td class="text-start">{{__('messages.refund-payment')}}</td>
                                                    <td class="text-end"><span class="font-weight-bold  ms-auto">{{$order->return_payment}}</span></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            {{--If order not Delivered and not picked up only paid--}}

                            @if(in_array($order->refund_status,["Cancellation Request","Cancel and Refund","Return and Refund"]) && $order->payment == 'Paid')
                                <form action="{{route('admin.refund.order.status.send',['id'=>$order->id])}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.refund-order')}}</label>
                                        <select name="status" class="form-control select2 @if($errors->has('status'))is-invalid @endif">
                                            <option value="">{{__('messages.please-select')}}</option>
                                            @if ($order->refund_status == "Cancellation Request")
                                            <option value="cancel-approved">{{__('messages.approved')}}</option>
                                            @elseif ($order->refund_status == "Cancel and Refund")
                                            <option value="Cancel-and-refund-approved">{{__('messages.approved')}}</option>
                                            @elseif ($order->refund_status == "Return and Refund")
                                            <option value="return-approved">{{__('messages.approved')}}</option>
                                            @endif
                                            <option value="refund-cancel">{{__('messages.cancelled')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group text-center">
                                        <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                                    </div>
                                </form>
                            @endif
                            <!--If order Delivered or picked up-->
                            {{-- @if($order->api_status == "return-requested" && $order->refund_status == "Return Received" && in_array($order->status,["DELIVERED","PICKED UP"]))
                                <form action="{{route('admin.refund.order.status.send',['id'=>$order->id])}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.refund-order')}}</label>
                                        <select name="status" class="form-control select2 @if($errors->has('status'))is-invalid @endif">
                                            <option value="">{{__('messages.please-select')}}</option>
                                            <option value="return-approved">{{__('messages.return-approved')}}</option>
                                            <option value="return-cancelled">{{__('messages.return-cancelled')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group text-center">
                                        <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                                    </div>
                                </form>
                            @endif --}}
                            <!--Cancelled order when order shipped means the order is Picked up on warehouse-->
                            {{-- @if($order->api_status == "Refund Requested" && $order->refund_status == "Return and Refund" && $order->status == "Dispatched" )
                                @php
                                    $SMSA_order = \App\Models\SMSAorder::where('order_id',$order->id)->first();
                                @endphp
                                @if ($SMSA_order != null)
                                    <div class="form-group text-center">
                                        <span class="font-weight-bold  ms-auto fs-6 fw-bold">{{__('messages.cancelled-from-shipping-company')}}</span>
                                        <a href="{{route('admin.send.order.status.to.w_admin',$order->id)}}" class="btn btn-primary">{{__('messages.cancel-order')}}</a>
                                    </div>
                                @endif
                            @endif --}}
                            <!--Send return request to shipping company-->
                            {{-- @if($order->api_status == "Refund Requested" && $order->refund_status == "Return and Refund" && $order->status == "DELIVERED")
                                <label class="form-label fs-6 fw-bold">{{__('messages.return-request-to-shipping-company')}}</label>
                                <div class="form-group text-center">
                                    <a class="btn btn-primary" href="{{route('admin.return.request.to.shipping.company',$order->id)}}">{{__('messages.send-request')}}</a>
                                </div>
                            @endif --}}

                             <!--Order Payment Return-->
                            <!--If order not Delivered and not picked up-->
                            @if(in_array($order->api_status,["Cancel Approved","Cancel and Refund Approved","Refund Approved"]) && in_array($order->refund_status,["Cancel Approved","Cancel and Refund Approved","Refund Approved"]) && $order->payment == "Paid")
                                <label class="form-label fs-5">{{__('messages.refund-payment')}}</label>
                                <div class="form-group text-center">
                                    <a class="btn btn-primary" href="javascript:void(0);" onclick="refund({{$order->id}},{{$refundAmount}})">{{__('messages.pay')}}</a>
                                </div>
                            @endif
                            <!--If order is Shipped means Picked Up then cancelled-->
                            @if($order->delivery_status == "Order Returned" && $order->refund_status == "return-approved" && $order->api_status == "return-approved" && $order->status == "Dispatched" && $order->payment == "Paid")
                                <label class="form-label fs-5">{{__('messages.refund-payment')}}</label>
                                <div class="form-group text-center">
                                    @php
                                        $return_price = \App\Models\ShippingPrice::where('group_id',$order->shipping_group)->first();
                                        $return_amount = $order->paid-$order->shipping_fee;
                                    @endphp
                                    <a class="btn btn-primary" href="javascript:void(0);" onclick="model3({{$order->id}},{{$datas->paid}},{{$datas->shipping_fee}},{{$return_amount}})">{{__('messages.pay')}}</a>
                                </div>
                            @endif
                            <!--If order return received in warehoouse means picked from customer and return to warehouse-->
                            @if($order->delivery_status == "Order Returned" && $order->status == "DELIVERED" && $order->refund_status == "return-approved" && $order->payment == "Paid")
                                <label class="form-label fs-5">{{__('messages.refund-payment')}}</label>
                                <div class="form-group text-center">
                                    @php
                                        $return_price = \App\Models\ShippingPrice::where('group_id',$order->shipping_group)->first();
                                        $return_amount = $order->paid-$order->shipping_fee-$return_price->return_price;
                                    @endphp
                                    <a class="btn btn-primary" href="javascript:void(0);" onclick="model2({{$order->id}},{{$order->paid}},{{$order->shipping_fee}},{{$return_price->return_price}},{{$return_amount}})">{{__('messages.pay')}}</a>
                                </div>
                            @endif
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
                            <span >{{__('messages.refund-amount')}} (<span class="fw-bold" id="paid"></span>)</span>
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
                                window.location.href = '{{route('admin.order.invoice.checkout.view',$order->id)}}';
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
                                window.location.href = '{{route('admin.order.invoice.checkout.view',$order->id)}}';
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
                                window.location.href = '{{route('admin.order.invoice.checkout.view',$order->id)}}';
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
