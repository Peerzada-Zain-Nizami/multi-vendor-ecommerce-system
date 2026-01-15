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
            {{--@if (Session::has('danger'))
                <div class="alert alert-light-danger" role="alert">
                    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}}
                </div>
            @endif--}}

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
                                <tbody >
                                @php
                                        $json_products = json_decode($datas->product);
                                        $sub_total = array();
                                        $tax_sub_total = array();
                                @endphp
                                @foreach($json_products as $json_product)
                                    <tr>
                                        @php
                                            $product_id = $json_product->p_id;
                                            $product = \App\Models\Product::where('id',$product_id)->first();
                                            $address = json_decode($datas->shipping_address);
                                        @endphp
                                        <td class="fs-15"><img class="avatar avatar-lg br-7" src="{{asset('uploads/featured_images/'.$product->featured_image)}}"></td>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$json_product->p_price}}</td>
                                        <td>{{$json_product->p_tax}}</td>
                                        <td>{{$json_product->tax_price}}</td>
                                        <td>{{$json_product->p_disc}}</td>
                                        <td>{{$json_product->total}}</td>
                                        <td>{{$json_product->order_qty}}</td>
                                        <td>{{$json_product->sub_total}}</td>
                                        @php
                                            $sub_total[] = $json_product->sub_total;
                                            $tax_sub_total[] = $json_product->tax_price*$json_product->order_qty;
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
                                        $shipping_company = \App\Models\shipping::where('id',$datas->shipping_id)->first();
                                        $shipping = json_decode($datas->shipping_fee);
                                    @endphp
                                    <tr>
                                        <td class="text-start">{{__('messages.shipping-address')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$json_address->address_1}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.city')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{ucfirst($json_address->city)}}</span></td>
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
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$shipping->price}}</span></td>
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
                                        <td class="text-start">{{__('messages.refund-status')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto @if(!in_array($datas->api_status,["return-cancelled","return-requested","refunded"])) {{invoice_status($datas->refund_status)}} @else {{invoice_status($datas->api_status)}} @endif" >@if(!in_array($datas->api_status,["return-requested","return-cancelled","refunded"])) {{invoice_status_lang($datas->refund_status)}} @else {{invoice_status_lang($datas->api_status)}} @endif</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.invoice-status')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto {{invoice_status($datas->status)}}">{{invoice_status_lang($datas->status)}} </span></td>
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
                            @if($datas->refund_status == "Returned" && !in_array($datas->refund_status,["Return Received","Complete"]))
                                <h4>{{__('messages.refund-order')}}</h4>
                                <form action="{{route('wadmin.refunded.order.received',['id'=>$datas->id])}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <select name="status" class="form-control select2 @if($errors->has('status'))is-invalid @endif">
                                            <option value="">{{__('messages.please-select')}}</option>
                                            <option value="Return Received">{{__('messages.return-received')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group text-center">
                                        <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                                    </div>
                                </form>
                            @endif

                            @if($datas->refund_stock_status != "True" && $datas->delivery_status == "Order Returned" && in_array($datas->refund_status,["return-approved","Return Received"]))
                                <div class="form-group text-center">
                                    <a href="{{Route('wadmin.stockIn.place.view.order.return',$datas->id)}}" class="btn btn-primary" @if(!empty($check)) hidden @else @endif>{{__('messages.stock-in')}}</a>
                                </div>
                            @endif
                            {{--end Stock In--}}

                        </div>
                    </div>

                </div>
            </div>
            <!-- End Row-->
        </div>
        <!--/div-->


    </div>
    <!-- CONTAINER END -->


@endsection
