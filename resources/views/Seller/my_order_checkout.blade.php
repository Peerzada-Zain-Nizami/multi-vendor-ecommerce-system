@php
    function payment_status($st)
    {
        if ($st == 'Paid' || $st == 'Return Received') {
            echo 'text-success';
        } elseif ($st == 'Pending') {
            echo 'text-warning';
        } elseif ($st == 'Return') {
            echo 'text-info';
        } else {
            echo 'text-danger';
        }
    }
    function payment_status_lang($st)
    {
        if ($st == 'Paid') {
            echo __('messages.paid');
        } elseif ($st == 'Return Received') {
            echo __('messages.return-received');
        } elseif ($st == 'Pending') {
            echo __('messages.pending');
        } else {
            echo __('messages.unpaid');
        }
    }
    function invoice_status($st)
    {
        if ($st == 'New Order') {
            echo 'text-warning';
        } elseif ($st == 'Pending') {
            echo 'text-secondary';
        } elseif ($st == 'Processing') {
            echo 'text-success';
        } elseif ($st == 'Cancelled') {
            echo 'text-secondary';
        } elseif ($st == 'Refund Requested') {
            echo 'text-secondary';
        } elseif ($st == 'Refund Approved') {
            echo 'text-secondary';
        } elseif ($st == 'Refunded') {
            echo 'text-secondary';
        } elseif ($st == 'Complete' || $st == 'Completed') {
            echo 'text-info';
        } elseif ($st == 'Complete' || $st == 'Completed' || $st == 'return-approved') {
            echo 'text-info';
        } elseif ($st == 'Pending') {
            echo 'text-secondary';
        } elseif ($st == 'Processing') {
            echo 'text-success';
        } elseif ($st == 'Reject' || $st == 'Cancel' || $st == 'return-cancelled' || $st == 'Cancelled by Seller' || $st == 'Order Cancelled') {
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
        } elseif ($st == 'Cancelled') {
            echo __('messages.cancelled');
        } elseif ($st == 'Refund Requested') {
            echo __('messages.return-requested');
        } elseif ($st == 'Cancel and Refund') {
            echo __('messages.Cancel-and-Refund');
        } elseif ($st == 'Refund Cancelled') {
            echo __('messages.refund-cancel');
        }elseif ($st == 'Cancellation Request') {
            echo __('messages.cancellation-request');
        }elseif ($st == 'Cancel Approved') {
            echo __('messages.cancel-approved');
        }elseif($st == "Cancel and Refund Approved")
        {
            echo __('messages.cancel-and-refund-approved');
        }
        elseif($st == "Refund Approved")
        {
            echo __('messages.return-approved');
        }
       elseif ($st == 'Complete' || $st == 'Completed') {
            echo __('messages.complete');
        } elseif ($st == 'Requested') {
            echo __('messages.requested');
        } elseif ($st == 'Refunded') {
            echo __('messages.refunded');
        } elseif ($st == 'Return and Refund') {
            echo __('messages.return-and-refund');
        } elseif ($st == 'return-cancelled') {
            echo __('messages.return-cancelled');
        }
        // elseif($st == "cancelled" || $st == "Cancelled")
        // {
        //     echo __('messages.cancelled');
        // }
        elseif ($st == 'Packed') {
            echo __('messages.packed');
        } elseif ($st == 'Dispatched') {
            echo __('messages.dispatched');
        } elseif ($st == 'Return Received') {
            echo __('messages.return-received');
        } elseif ($st == 'Return and Refund') {
            echo __('messages.return-and-refund');
        } elseif ($st == 'Order Cancelled') {
            echo __('messages.order-canceled');
        } elseif ($st == 'DATA RECEIVED') {
            echo __('messages.DATA-RECEIVED');
        } elseif ($st == 'Delivered') {
            echo __('messages.DELIVERED');
        } elseif ($st == 'Cancelled by Seller') {
            echo __('messages.cancelled-by-seller');
        } elseif ($st == 'return-approved') {
            echo __('messages.return-approved');
        } elseif ($st == 'Dispatch') {
            echo __('messages.dispatch');
        } elseif ($st == 'Out of Stock') {
            echo __('messages.out-of-stock');
        } elseif ($st == 'Order Returned') {
            echo __('messages.order-returned');
        } elseif ($st == 'return-received') {
            echo __('messages.return-received');
        } elseif ($st == 'Reject') {
            echo __('messages.reject');
        } elseif ($st == 'Cancel') {
            echo __('messages.cancel');
        } elseif ($st == 'Received') {
            echo __('messages.received');
        } elseif ($st == 'Accepted') {
            echo __('messages.accepted');
        } elseif ($st == 'DEPARTED FORM ORIGIN') {
            echo __('messages.DEPARTED-FORM-ORIGIN');
        } elseif ($st == 'Collected from Retail') {
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
       } elseif ($st == 'PROOF OF DELIVERY CAPTURED') {
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
@extends('Seller.base')
@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.invoice-checkout') }}</h4>
                </div>
            </div>
            <!--End Page header-->
            @if (Session::has('success'))
                <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                        aria-hidden="true">×</button>
                    <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::has('danger'))
                <div class="alert alert-light-danger" role="alert">
                    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert"
                        aria-hidden="true">×</button>
                    <strong>{{ __('messages.oopps') }}</strong> {{ Session::get('danger') }}
                </div>
            @endif

            <!--div-->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="ms-3">{{ __('messages.invoice-items') }}</span>
                            </div>
                        </div>
                        <div class="table-responsive-lg">
                            <table
                                class="table card-table table-responsive-lg-sm table-vcenter text-nowrap shopping-carttable">
                                <thead class="border-bottom-0 pt-3 pb-3 ">
                                    <tr>
                                        <th class="fs-15">{{ __('messages.product') }}</th>
                                        <th>{{ __('messages.product-name') }}</th>
                                        <th>{{ __('messages.price') }}</th>
                                        <th>{{ __('messages.tax') }}%</th>
                                        <th>{{ __('messages.product-discount') }}</th>
                                        <th>{{ __('messages.plan-discount') }}</th>
                                        <th>{{ __('messages.sub-total') }}</th>
                                        <th>{{ __('messages.order_quantity') }}</th>
                                        <th>{{ __('messages.available_quantity') }}</th>
                                        <th>{{ __('messages.tax-price') }}</th>
                                        <th>{{ __('messages.discount-price') }}</th>
                                        <th>{{ __('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $stock_count = 0;

                                    @endphp

                                    @foreach (json_decode($order->product) as $json_product)
                                        <tr style="@if ($order->is_confirm != 1 && $json_product->order_qty > $json_product->available_qty) background-color: #FFCCCC @endif">
                                            @php
                                                $stock_count += $json_product->available_qty;
                                                $product = \App\Models\Product::where('id', $json_product->p_id)->first();
                                                $address = json_decode($order->shipping_address);
                                               $users = \App\Models\User::where('role', "Seller")->with('seller_wallet')->get();
                                            @endphp
                                            <td class="fs-15"><img class="avatar avatar-lg br-7"
                                                    src="{{ asset('uploads/featured_images/' . $product->featured_image) }}">
                                            </td>
                                            <td>{{ $product->product_name }}</td>
                                            <td>{{ $json_product->p_price }}</td>
                                            <td>{{ $json_product->p_tax }}</td>
                                            <td>{{ $json_product->p_disc }}</td>
                                            <td>{{ $json_product->p_plan_disc }}<span class="font-weight-bold  ms-auto">
                                                    @if ($json_product->p_plan_disc_method == 'percentage')
                                                        %
                                                    @else
                                                        SAR
                                                    @endif
                                                </span></td>
                                            <td>{{ $json_product->sub_total }}</td>
                                            <td>{{ $json_product->order_qty }}</td>
                                            <td>{{ $json_product->available_qty }}</td>
                                            <td>{{ $json_product->tax_price }}</td>
                                            <td>{{ $json_product->dis_price }}</td>
                                            <td>{{ $json_product->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>

                    @if (!empty(json_decode($order->refund_items)))
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <span class="ms-3">{{ __('messages.refund-items') }}</span>
                                </div>
                            </div>
                            <div class="table-responsive-lg">
                                <table
                                    class="table card-table table-responsive-lg-sm table-vcenter text-nowrap shopping-carttable">
                                    <thead class="border-bottom-0 pt-3 pb-3 ">
                                        <tr>
                                            <th class="fs-15">{{ __('messages.product') }}</th>
                                            <th>{{ __('messages.product-name') }}</th>
                                            <th>{{ __('messages.price') }}</th>
                                            <th>{{ __('messages.tax') }}%</th>
                                            <th>{{ __('messages.product-discount') }}</th>
                                            <th>{{ __('messages.plan-discount') }}</th>
                                            <th>{{ __('messages.sub-total') }}</th>
                                            <th>{{ __('messages.refund-quantity') }}</th>
                                            <th>{{ __('messages.tax-price') }}</th>
                                            <th>{{ __('messages.discount-price') }}</th>
                                            <th>{{ __('messages.total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $stock_count = 0;
                                        @endphp
                                        @foreach (json_decode($order->product) as $json_product)
                                            @foreach (json_decode($order->refund_items) as $refund_item)
                                                @if ($json_product->p_id == $refund_item->p_id)
                                                    <tr
                                                        style="@if ($order->is_confirm != 1 && $json_product->order_qty > $json_product->available_qty) background-color: #FFCCCC @endif">
                                                        @php
                                                            $stock_count += $json_product->available_qty;
                                                            $product = \App\Models\Product::where('id', $refund_item->p_id)->first();
                                                            $address = json_decode($order->shipping_address);

                                                            $product_price = $json_product->total - $json_product->sub_total * $refund_item->p_qty;
                                                        @endphp
                                                        <td class="fs-15"><img class="avatar avatar-lg br-7"
                                                                src="{{ asset('uploads/featured_images/' . $product->featured_image) }}">
                                                        </td>
                                                        <td>{{ $product->product_name }}</td>
                                                        <td>{{ $json_product->p_price }}</td>
                                                        <td>{{ $json_product->p_tax }}</td>
                                                        <td>{{ $json_product->p_disc }}</td>
                                                        <td>{{ $json_product->p_plan_disc }}<span
                                                                class="font-weight-bold  ms-auto">
                                                                @if ($json_product->p_plan_disc_method == 'percentage')
                                                                    %
                                                                @else
                                                                    SAR
                                                                @endif
                                                            </span></td>
                                                        <td>{{ $json_product->sub_total }}</td>
                                                        <td>{{ $refund_item->p_qty }}</td>
                                                        <td>{{ $json_product->tax_price }}</td>
                                                        <td>{{ $json_product->dis_price }}</td>
                                                        <td>{{ $product_price < 0 ? $json_product->total : $json_product->sub_total * $refund_item->p_qty }}
                                                        </td>
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
                            <div class="card-title">{{ __('messages.invoice-summary') }}</div>
                        </div>
                        <div class="card-body">
                            <h5>{{ __('messages.shipping-details') }}</h5>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                        @php
                                            $json_address = json_decode($order->shipping_address);
                                            $output = \App\MyClasses\Helpers::get_shipping_address($json_address->country_code);
                                            $shipping_group = \App\Models\ShippingGroup::where('id', $order->shipping_group)->first();
                                           $return_price = App\Models\ShippingPrice::where('group_id', $order->shipping_group)->first();

                                        @endphp
                                        <tr>
                                            <td class="text-start">{{ __('messages.shipping-address') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $json_address->address_1 }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{ __('messages.city') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $json_address->city }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{ __('messages.state') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $json_address->state }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{ __('messages.country') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $output }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{ __('messages.shipping-company') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $order->company_name }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{ __('messages.shipping-group') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ isset($shipping_group->name) ? $shipping_group->name : __('messages.record-not-found') }}</span>
                                            </td>
                                        </tr>

                            @if (empty($order->company_name) && $users[0]->shipping_from_us == 1)
                                        <tr>
                                            <td class="text-center h5 text-danger " colspan="2">{{ __('messages.city-dismatch-deliver-byself') }}</td>

                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div><br />
                            <h4>{{ __('messages.amount-details') }}</h4>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">{{ __('messages.sub-total') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $order->sub_total }}</span></td>
                                        </tr>
                                        @php
                                            $shipping = json_decode($order->shipping_fee);
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{ __('messages.shipping-price') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $shipping->price }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{ __('messages.discount') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $shipping->discount }}{{ $shipping->discount_method }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{ __('messages.discount-price') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $shipping->discount_price }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start fs-18">{{ __('messages.total-bill') }}</td>
                                            <td class="text-end"><span
                                                    class="ms-2 font-weight-bold  fs-22">{{ $order->total }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h4>{{ __('messages.amount-done') }}</h4>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">{{ __('messages.paid-amount') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $order->paid }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{ __('messages.remaining-amount') }}</td>
                                            <td class="text-end"><span
                                                    class="ms-2 font-weight-bold">{{ $order->remaining }}</span></td>
                                        </tr>
                                        @if (!empty(json_decode($order->refund_items)) && (!empty($order->return_payment)))
                                            <tr>
                                                <td class="text-start">{{ __('messages.refund-amount') }}</td>
                                                <td class="text-end"><span
                                                        class="ms-2 font-weight-bold">{{ $order->return_payment }}</span>
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($order->refund_status == 'Refunded'  && $order->status == 'Delivered')
                                    <tr>
                                        <td class="text-start">{{__('messages.return-price-/-cancellation-fee')}}</td>
                                        <td class="text-end"><span class="ms-2 font-weight-bold">{{$return_price->return_price}}</span></td>
                                    </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <h4>{{ __('messages.additional-details') }}</h4>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">{{ __('messages.payment') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto {{ payment_status($order->payment) }}">{{ payment_status_lang($order->payment) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{ __('messages.order-status') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto {{ invoice_status($order->status) }}">{{ invoice_status_lang($order->status) }}
                                                </span></td>
                                        </tr>
                                        @if (!empty(json_decode($order->refund_items)))
                                            <tr>
                                                <td class="text-start">{{ __('messages.customer-status') }}</td>
                                                <td class="text-end"><span
                                                        class="font-weight-bold  ms-auto {{ invoice_status($order->api_status) }}">{{ $order->api_status }}
                                                    </span></td>
                                            </tr>
                                        @endif
                                        @if (in_array($order->api_status, ['return-requested', 'return-cancelled', 'return-approved']) &&
                                                $order->refund_status == null)
                                            <tr>
                                                <td class="text-start">{{ __('messages.refund-status') }}</td>
                                                <td class="text-end"><span
                                                        class="font-weight-bold  ms-auto {{ invoice_status($order->api_status) }}">{{ invoice_status_lang($order->api_status) }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            {{-- Refund Order payment --}}
                            @if ($order->refund_status != null)
                                <h4>{{ __('messages.refund-order') }}</h4>
                                <div class="table-responsive-lg">
                                    <table class="table table-borderless text-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                <td class="text-start">{{ __('messages.refund-status') }}</td>
                                                <td class="text-end"><span
                                                        class="font-weight-bold  ms-auto {{ invoice_status($order->refund_status) }}">{{ invoice_status_lang($order->refund_status) }}</span>
                                                </td>
                                            </tr>
                                            @if ($order->return_payment != 0)
                                                <tr>
                                                    <td class="text-start">{{ __('messages.refund-payment') }}</td>
                                                    <td class="text-end"><span
                                                            class="font-weight-bold  ms-auto">{{ $order->return_payment }}</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                            @if ($order->is_confirm == false && $order->status != 'Order Cancelled')
                                <h4>{{ __('messages.further-processing') }}</h4>
                                <div class="table-responsive-lg">
                                    <table class="table table-borderless text-nowrap mb-0">
                                        <tbody>
                                            <tr>
                                                @if ($stock_count > 0)
                                                    <td class="text-start">
                                                        <a href="{{ route('seller.invoice.confirm', $order->id) }}"
                                                            class="btn btn-primary">{{ __('messages.confirm-order') }} <i
                                                                class="fa fa-check"></i></a>
                                                    </td>
                                                @endif
                                                <td
                                                    class="@if ($stock_count > 0) text-end @else text-center @endif">
                                                    <a href="{{ route('seller.invoice.cancel', $order->id) }}"
                                                        class="btn btn-danger cancel-order">
                                                        {{ __('messages.cancel-order') }} <i class="fa fa-times"></i></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                            @endif

                            {{-- Refund Order --}}
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                        @if ($order->api_status == 'Refund Requested' && $order->refund_status == null && $order->payment == 'Paid')
                                            <h4>{{ __('messages.further-processing') }}</h4>
                                            <form id="refund-order-form"
                                                action="{{ route('seller.send.refund.status.to.admin', $order->id) }}"
                                                method="post">
                                                @csrf
                                                @if ($order->refund_status != 'Refund Requested')
                                                    <tr>
                                                        <td colspan="2">
                                                            <label
                                                                class="form-label">{{ __('messages.change-status') }}</label>
                                                            <select name="status_value"
                                                                class="form-control select2 @if ($errors->has('status_value')) is-invalid @endif">
                                                                <option value="">{{ __('messages.please-select') }}
                                                                </option>
                                                                @if (in_array($order->status, ['Processing', 'Pending','New Order']) )
                                                                <option value="Cancellation Request">{{ __('messages.order-canceled') }}</option>
                                                                @elseif (in_array($order->status, ['SMSA Processing', 'Packed']))
                                                                <option value="Cancel and Refund">{{ __('messages.Cancel-and-Refund') }}</option>
                                                                @elseif (in_array($order->status, ['Collected from Retail','In Transit','Out for Delivery','Dispatched','Delivered']))
                                                                    <option value="Return and Refund">
                                                                        {{ __('messages.return-and-refund') }}</option>
                                                                @endif
                                                            </select>
                                                            @if ($errors->has('status_value'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('status_value') }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-center">
                                                            <button type="submit"
                                                                class="btn btn-primary refund_request btn-md mt-3"
                                                                @if ($order->refund_status == 'Send To Admin') disabled @else @endif>
                                                                {{ __('messages.sent-order') }}</button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </form>
                                        @endif
                                        {{-- New Order Send to Admin --}}
                                        {{-- @if ($order->status != 'Out of Stock')
                                            <form action="{{ route('seller.send.order.invoice', $order->id) }}"
                                                method="get">
                                                @csrf
                                                @if (!empty($shipping_data))
                                                    @if (!in_array($order->api_status, ['return-requested', 'return-cancelled', 'return-approved']))
                                                        <tr>
                                                            <td colspan="2" class="text-center">
                                                                <input type="submit"
                                                                    value="{{ __('messages.sent-order') }}"
                                                                    class="btn btn-primary btn-md"
                                                                    @if ($order->status == 'Send') hidden @else @endif>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            </form>
                                        @endif --}}
                                    </tbody>
                                </table>
                            </div>
                            {{-- If order not match to seller record --}}
                            {{-- @if (empty($shipping_data))
                                <h4 style="text-align: center;color:red;">{{ __('messages.the-order-not-done') }}</h4>
                                <h5 style="text-align: center;color:red;">{{ __('messages.city-not-matched') }}</h5>
                            @endif --}}
                            </tbody>
                            </table>
                        </div>
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

@endsection
@section('query')
    <script type="text/javascript">
        $('.cancel-order').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            console.log(url);
            swal({
                title: 'Are you sure?',
                text: 'You want to cancel this order permanently!',
                icon: 'warning',
                buttons: ["Cancel", "Yes!"],
            }).then(function(value) {
                if (value) {
                    window.location.href = url;
                }
            });
        });

        $('.refund_request').on('click', function(event) {
            event.preventDefault();
            const form = $('#refund-order-form');
            swal({
                title: 'Are you sure?',
                text: 'You want to Refund this Order!',
                icon: 'warning',
                buttons: ["Cancel", "Yes!"],
            }).then(function(value) {
                if (value) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
