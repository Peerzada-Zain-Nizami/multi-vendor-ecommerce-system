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
        } elseif ($st == 'Complete' || $st == 'completed' || $st == 'Completed' || $st == 'Shipping Process') {
            echo 'text-success';
        } elseif (
            $st == 'Reject' ||
            $st == 'Cancel' ||
            $st == 'cancelled' ||
            $st == 'Cancelled' ||
            $st == 'Order Cancelled' ||
            $st == 'Cancelled by Seller'
        ) {
            echo 'text-danger';
        } elseif (
            $st == 'Received' ||
            $st == 'Accepted' ||
            $st == 'DATA RECEIVED' ||
            $st == 'Accept' ||
            $st == 'Resend'
        ) {
            echo 'text-info';
        } elseif ($st == 'Packing') {
            echo 'text-muted';
        } elseif ($st == 'CANCELLED ON CLIENTS REQUEST') {
            echo 'text-muted';
        } elseif ($st == 'Dispatch' || $st == 'DATA RECEIVED') {
            echo 'text-success';
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
        } elseif ($st == 'Complete' || $st == 'completed' || $st == 'Completed') {
            echo __('messages.complete');
        } elseif ($st == 'New Order') {
            echo __('messages.new-order');
        } elseif ($st == 'Dispatch') {
            echo __('messages.dispatch');
        } elseif ($st == 'return-requested') {
            echo __('messages.return-requested');
        } elseif ($st == 'return-cancelled') {
            echo __('messages.return-cancelled');
        } elseif ($st == 'return-approved') {
            echo __('messages.return-approved');
        } elseif ($st == 'Delivered') {
            echo __('messages.DELIVERED');
        } elseif ($st == 'Reject') {
            echo __('messages.reject');
        } elseif ($st == 'Cancel') {
            echo __('messages.cancel');
        } elseif ($st == 'cancelled' || $st == 'Cancelled') {
            echo __('messages.cancelled');
        } elseif ($st == 'Packed') {
            echo __('messages.packed');
        } elseif ($st == 'Cancelled by Seller') {
            echo __('messages.cancelled-by-seller');
        } elseif ($st == 'Return Received') {
            echo __('messages.return-received');
        } elseif ($st == 'Cancelled by Admin') {
            echo __('messages.canceled-by-admin');
        } elseif ($st == 'Resend') {
            echo __('messages.resend');
        } elseif ($st == 'Received') {
            echo __('messages.received');
        } elseif ($st == 'DATA RECEIVED') {
            echo __('messages.DATA-RECEIVED');
        } elseif ($st == 'Accepted') {
            echo __('messages.accepted');
        } elseif ($st == 'Order Returned') {
            echo __('messages.order-returned');
        } elseif ($st == 'return-received') {
            echo __('messages.return-received');
        } elseif ($st == 'Accept') {
            echo __('messages.accepted-on-warehouse');
        } elseif ($st == 'Shipping Process') {
            echo __('messages.processing-in-warehouse');
        } elseif ($st == 'Return and Refund') {
            echo __('messages.return-and-refund');
        } elseif ($st == 'Process') {
            echo __('messages.process');
        } elseif ($st == 'Packing') {
            echo __('messages.packing');
        } elseif ($st == 'Order Cancelled') {
            echo __('messages.order-canceled');
        } elseif ($st == 'CANCELLED ON CLIENTS REQUEST') {
            echo __('messages.CANCELLED-ON-CLIENTS-REQUEST');
        } elseif ($st == 'DATA RECEIVED') {
            echo __('messages.DATA-RECEIVED');
        } else {
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
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.admin-dashboard') }}</h4>
                </div>
            </div>
            @php
                $user = Illuminate\Support\Facades\Auth::user();
                $smsaCredentials = App\Models\SMSACredential::where('user_id', $user->id)->first();
            @endphp
            @if ($smsaCredentials == null)
                <div class="alert alert-light-danger" role="alert">
                    <strong>{{ __('messages.oopps') }}</strong> It seems your SMSA credentials are missing. Please <a
                        href="{{ route('admin.smsa.credentials') }}" class="alert-link">add the credentials</a> Thanks.
                </div>
            @endif


            <!--End Page header-->

            <!-- Row-1 -->
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden dash1-card border-0 dash1">
                        <div class="card-body">
                            <span class="fs-14 font-weight-normal">{{ __('messages.new-orders') }}</span>
                            <h2 class="mb-2 number-font carn1 font-weight-bold">{{ $new_orders }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden dash1-card border-0 dash4">
                        <div class="card-body">
                            <span>{{ __('messages.process-orders') }}</span>
                            <h2 class="mb-2 mt-1 number-font carn2 font-weight-bold">{{ $process }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden dash1-card border-0 dash2">
                        <div class="card-body">
                            <span class="fs-14">{{ __('messages.completed-orders') }}</span>
                            <h2 class="mb-2 mt-1 number-font carn2 font-weight-bold">{{ $completed }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden dash1-card border-0 dash2">
                        <div class="card-body">
                            <span class="fs-14">{{ __('messages.refund-completed') }}</span>
                            <h2 class="mb-2 mt-1 number-font carn2 font-weight-bold">{{ $refund_completed }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden dash1-card border-0 dash3">
                        <div class="card-body">
                            <span class="fs-14">{{ __('messages.cancel/rejected') }}</span>
                            <h2 class="mb-2 mt-1 number-font carn2 font-weight-bold">{{ $cancel_reject }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-1 -->

            <!--Row-->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header border-bottom-0">
                            <h3 class="card-title">{{ __('messages.sales-summary') }}</h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="chart-wrapper">
                                <div id="chart-area-spline" class="chartsh"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.new-order') }}</h3>
                            <div class="card-options">
                                <a href="{{ route('admin.woo.order.management') }}"
                                    class="btn btn-sm btn-primary">{{ __('messages.view-all') }}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table id="example"
                                    class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                                    <thead class="border-bottom-0 pt-3 pb-3">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>{{ __('messages.order-no') }}</th>
                                            <th>{{ __('messages.warehouse-name') }}</th>
                                            <th>{{ __('messages.shipping-address') }}</th>
                                            <th>{{ __('messages.shipping-company') }}</th>
                                            <th>{{ __('messages.order-status') }}</th>
                                            <th>{{ __('messages.payment-status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($results as $order)
                                            <tr>
                                                <td class="text-center">{{ $i++ }}</td>
                                                <td>{{ $order->order_no }}</td>
                                                @php
                                                    $warehouse = \App\Models\Warehouse::where(
                                                        'id',
                                                        $order->order_warehouse_id,
                                                    )->first();
                                                    $receiver_admin = \App\Models\User::where(
                                                        'id',
                                                        $order->receiver_admin,
                                                    )->first();
                                                @endphp
                                                <td>
                                                    @if ($warehouse)
                                                        {{ $warehouse->warehouse_id }}/{{ $warehouse->warehouse_name }}
                                                    @else
                                                    @endif
                                                </td>
                                                @php
                                                    $address = json_decode($order->shipping_address);
                                                    $product = json_decode($order->product);
                                                @endphp
                                                <td>{{ $address->address_1 }}</td>
                                                <td>{{ $order->company_name }}</td>
                                                <td>
                                                    <span
                                                        class="font-weight-bold  ms-auto {{ invoice_status($order->status) }}">{{ invoice_status_lang($order->status) }}</span>
                                                </td>
                                                <td class="font-weight-bold {{ payment_status($order->payment) }}">
                                                    {{ payment_status_lang($order->payment) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.invoice-summary') }}</h3>
                            <div class="card-options">
                                <a href="{{ route('admin.manage.invoice') }}"
                                    class="btn btn-sm btn-primary">{{ __('messages.view-all') }}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table id="" class="table table-hover table-bordered text-nowrap key-buttons">
                                    <thead class="border-bottom-0 pt-3 pb-3">
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>{{ __('messages.invoice-no') }}</th>
                                            <th>{{ __('messages.sub-total') }}</th>
                                            <th>{{ __('messages.fee') }}</th>
                                            <th>{{ __('messages.total') }}</th>
                                            <th>{{ __('messages.payment-status') }}</th>
                                            <th>{{ __('messages.remaining') }}</th>
                                            <th>{{ __('messages.paid') }}</th>
                                            <th>{{ __('messages.invoice-status') }}</th>
                                            <th>{{ __('messages.created-at') }}</th>
                                            <th>{{ __('messages.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($companyorder as $result)
                                            <tr>
                                                <td class="text-center">{{ $i++ }}</td>
                                                <td>{{ $result->invoice_no }}</td>
                                                <td>{{ $result->sub_total }}</td>
                                                <td>{{ $result->shipping_fee }}</td>
                                                <td>{{ $result->total }}</td>
                                                <td class="{{ payment_status($result->payment) }}">
                                                    {{ payment_status_lang($result->payment) }}</td>
                                                <td>{{ $result->remaining }}</td>
                                                <td>{{ $result->paid }}</td>
                                                <td class="{{ invoice_status($result->status) }}">
                                                    {{ invoice_status_lang($result->status) }}</td>
                                                <td>{{ $result->created_at }}</td>
                                                <td>
                                                    <a href="{{ route('supplier.myorder.view', ['id' => $result->invoice_no]) }}"
                                                        class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!-- CONTAINER END -->
@endsection
