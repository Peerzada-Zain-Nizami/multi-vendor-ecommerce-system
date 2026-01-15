{{-- Functions --}}
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
        } elseif ($st == 'Pending') {
            echo __('messages.pending');
        } elseif ($st == 'Return Received') {
            echo __('messages.return-received');
        } else {
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
{{-- end Functions --}}
@extends('W_admin.base')
{{-- start main content --}}
@section('content')

    @php

        $jsonData = json_decode($datas->product);


        if (is_array($jsonData)) {
            $firstItem = reset($jsonData);
            $p_id = $firstItem->p_id;
        } else {
        }
    @endphp

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

            <!--Messages-->
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <li class="text-danger">{{ $error }}</li>
                @endforeach
            @endif
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
            <!--end Messages-->

            <!--Row-->
            <div class="row">
                {{-- first part --}}
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
                                        <th>{{ __('Packed Quantity') }}</th>
                                        <th>{{ __('messages.tax-price') }}</th>
                                        <th>{{ __('messages.discount-price') }}</th>
                                        <th>{{ __('messages.total') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (json_decode($datas->product) as $json_product)
                                        <tr>
                                            @php
                                                $product = \App\Models\Product::where('id', $json_product->p_id)->first();
                                                $address = json_decode($datas->shipping_address);
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
                                            <td>{{ $json_product->packed_qty }}</td>
                                            <td>{{ $json_product->tax_price }}</td>
                                            <td>{{ $json_product->dis_price }}</td>
                                            <td>{{ $json_product->total }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- end first part --}}

                {{-- Second part --}}
                <div class="col-lg-12 col-xl-4 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{ __('messages.invoice-summary') }}</div>
                        </div>
                        <div class="card-body">
                            {{-- Shipping detail --}}
                            <h5>{{ __('messages.shipping-details') }}</h5>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                        @php
                                            $json_address = json_decode($datas->shipping_address);
                                            $output = \App\MyClasses\Helpers::get_shipping_address($json_address->country_code);
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
                                                    class="font-weight-bold  ms-auto">{{ ucfirst($datas->company_name) }}</span>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div><br />
                            {{-- end Shipping detail --}}

                            {{-- Amount detail --}}
                            <h4>{{ __('messages.amount-details') }}</h4>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">{{ __('messages.sub-total') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto">{{ $datas->sub_total }}</span></td>
                                        </tr>
                                        @php
                                            $shipping = json_decode($datas->shipping_fee);
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
                                                    class="ms-2 font-weight-bold  fs-22">{{ $datas->total }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{-- end Amount detail --}}

                            {{-- additional detail --}}
                            <h4>{{ __('messages.additional-details') }}</h4>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-start">{{ __('messages.payment') }}</td>
                                            <td class="text-end"><span
                                                    class="font-weight-bold  ms-auto {{ payment_status($datas->payment) }}">{{ payment_status_lang($datas->payment) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{ __('messages.order-status') }}</td>
                                            <td class="text-end">
                                                <span
                                                    class="font-weight-bold  ms-auto {{ invoice_status($datas->status) }}">{{ invoice_status_lang($datas->status) }}</span>
                                            </td>
                                        </tr>
                                        {{-- Warehouse tag --}}
                                        @if (!empty($datas->order_warehouse_id))
                                            @php
                                                $warehouse = \App\Models\Warehouse::where('id', $datas->order_warehouse_id)->first();
                                            @endphp
                                            <tr>
                                                <td class="text-start">{{ __('messages.warehouse') }}</td>
                                                <td class="text-end"><span
                                                        class="font-weight-bold  ms-auto">{{ ucfirst($warehouse->warehouse_name) }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        {{-- end Warehouse tag --}}

                                        {{-- Checking order exist or not --}}
                                        @php
                                            $ok = \App\Models\SMSAorder::where('order_id', $datas->id)->first();
                                        @endphp
                                        {{-- end Checking order exist or not --}}

                                        {{-- order tag --}}
                                        @if (!empty($ok->AWB_no))
                                            <tr>
                                                <td class="text-start">{{ __('messages.air-way-bill') }}</td>
                                                <td class="text-end"><span class="font-weight-bold  ms-auto text-info">
                                                        @if ($datas->status == 'CANCELLED ON CLIENTS REQUEST')
                                                            {{ __('messages.CANCELLED-ON-CLIENTS-REQUEST') }}
                                                        @elseif(!empty($ok->AWB_no))
                                                            {{ __('messages.generated') }}
                                                        @endif
                                                    </span></td>
                                            </tr>
                                        @endif
                                        {{-- end order tag --}}

                                        {{-- for refund order --}}
                                        @if (in_array($datas->refund_status, ['return-requested', 'return-approved', 'return-cancelled']))
                                            <tr>
                                                <td class="text-start">{{ __('messages.refund-status') }}</td>
                                                <td class="text-end">
                                                    <span
                                                        class="font-weight-bold  ms-auto {{ invoice_status($datas->refund_status) }}">{{ invoice_status_lang($datas->refund_status) }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{-- Changing status before order --}}
                                @if (in_array($datas->order_status, ['Accept', 'Resend', 'Packing']) &&
                                        !in_array($datas->api_status, ['return-requested', 'return-approved', 'return-cancelled']))
                                    <form action="{{ route('wadmin.order.status.set', ['id' => $datas->id]) }}"
                                        method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-label">{{ __('messages.change-status') }}</label>
                                            <select name="status"
                                                class="form-control select2 @if ($errors->has('status')) is-invalid @endif">
                                                <option value="">{{ __('messages.please-select') }}</option>
                                                @if ($datas->order_status == 'Packing' || $datas->order_status == 'Resend')
                                                    <option value="Accept"
                                                        @if ($datas->status == 'Accept') selected @endif>
                                                        {{ __('messages.accepted') }}</option>
                                                    <option value="Reject"
                                                        @if ($datas->order_status == 'Reject') selected @endif>
                                                        {{ __('messages.reject') }}</option>
                                                @endif
                                                @if ($datas->order_status == 'Accept')
                                                    <option value="Shipping Process"
                                                        @if ($datas->order_status == 'Shipping Process') selected @endif>
                                                        {{ __('messages.process') }}</option>
                                                @endif
                                            </select>
                                            @if ($errors->has('status'))
                                                <span class="text-danger">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group text-center">
                                            <input type="submit" class="btn btn-primary"
                                                value="{{ __('messages.save') }}">
                                        </div>
                                    </form>
                                @endif
                            </div>
                            {{-- end additional detail --}}

                             {{--add shipping button--}}
                             @if($datas->status == "Packed" && !in_array($datas->refund_status,["return-requested","return-approved","return-cancelled"]))
                             <div class="form-group text-center">
                                 @php
                                     $check = \App\Models\SMSAorder::where('order_id',$datas->id)->first();
// dd($check);
                                 @endphp
                                 @if ($check)
                                 <a href="{{Route('wadmin.add.shipping.page',$check->id)}}" class="btn btn-primary" @if(!empty($check->AWB_no)) hidden @else @endif>{{__('messages.add-shipping')}}</a>
                                 @endif
                             </div>
                         @endif
                         {{--end add shipping button--}}

                            {{-- @if (!in_array($order->status, ['Packed','Cancelled','Refunded','DELIVERED','Dispatched'])) --}}
                            @if ($datas->status != 'Packed' && $datas->status != 'Cancelled' && $datas->status != 'Refunded' && $datas->status != 'Delivered' && $datas->status != 'Dispatched')
                                <div class="panel panel-default">
                                    <div class="panel-body p-0">
                                        <div class="btn-group mt-2 mb-2" style="left: 35%">
                                            <button type="button" class="btn btn-primary btn-md  dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                Scan Here<span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li><a id="mySelect" class="modal-effect" data-bs-effect="effect-scale"
                                                        data-bs-toggle="modal" href="javascript:void(0);"
                                                        data-bs-target="#modaldemo8">By Camera</a></li>
                                                <li><a href="javascript:void(0);">By Scanner</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- end Second part --}}
            </div>
            <!-- End Row-->

            <div class="modal fade" id="modaldemo8">
                <div class="modal-dialog modal-dialog-centered text-center" role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                            <h6 class="modal-title">Scan Product</h6><button aria-label="Close" id="modalCloseButton"
                                class="btn-close" data-bs-dismiss="modal" type="button"><span
                                    aria-hidden="true">&times;</span></button>
                        </div>

                        <div class="col-sm-12">
                            <p id="cam_success" class="text-success"></p>
                            <div id="camera_list">
                            </div>
                            <p id="cam_error" class="text-danger"></p>
                            <video id="preview"></video>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" id="modalCloseButton"
                                type="button">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="shelf_scanner_modal">
                <div class="modal-dialog modal-dialog-centered text-center" role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                            <h6 class="modal-title">Scan Shelf</h6>
                            <button aria-label="Close" id="shelfScannerModalCloseButton" class="btn-close"
                                data-bs-dismiss="modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="col-sm-12">
                            <p id="cam_success1" class="text-success"></p>
                            <div id="camera_list1"></div>
                            <p id="cam_error1" class="text-danger"></p>
                            <video id="preview1"></video>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal" id="shelfScannerModalCloseButton"
                                type="button">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--/div-->
        </div>
        <!-- app-content end -->
    @endsection
    {{-- end main content --}}

    {{-- java & jquery --}}


    @section('query')
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        //     $('#select2').change(function(){
        //     var id = $(this).val();
        //     console.log(id);
        //     $.ajax({
        //         url:'{{route('seller.invoice.shipping.get')}}',
        //         type:'post',
        //         data:{id:id},
        //         success:function(data){
        //             $('#company_id').val(data.data.id);
        //             $("#shipping_price1").val(data.data.price);
        //             $("#shipping_price").html(data.data.price);
        //             var a = parseInt(data.data.price);
        //             var b = parseInt($('#my_value').val());
        //             var sum = a + b;
        //             console.log(sum);
        //             $("#total1").val(sum);
        //             $("#total").html(sum);
        //         }
        //     });
        // });

            let scanner;

            $('#mySelect').on('click', function() {
                $('#camera_list').html('');
                scanner = new Instascan.Scanner({
                    video: document.getElementById('preview'),
                    mirror: false,
                });
                scanner.addListener('scan', function(content) {

                    var shelf_id = $('#shelf_id').val();
                    var data = {
                        'p_id': content,
                        'order_id': {{ $datas->id }},
                    };
                    $.ajax({
                        url: '{{ route('wadmin.order.scanner') }}',
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.status == 'danger') {
                                $("#modaldemo8").modal('hide');
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops...',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            } else if (response.status == 'success') {
                                $("#modaldemo8").modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                                setTimeout(() => {
                                    openShelfScannerModal(data);
                                }, 2500);
                            }

                        }
                    });
                });
                Instascan.Camera.getCameras().then(function(cameras) {
                    if (cameras.length > 0) {
                        var i = 0;
                        $.each(cameras, function(key, name) {
                            var class_name = '';
                            if (i == key) {
                                class_name = "text-success";
                            } else {
                                class_name = "text-muted";
                            }
                            $('#camera_list').append('<li onclick="change(' + key + ')" class="' +
                                class_name + '">' + name.name + '</li>');
                        });
                        scanner.start(cameras[0]);
                    } else {
                        $('#cam_error').html('No cameras found.');
                    }
                }).catch(function(e) {
                    console.error(e);
                });
            });

            function openShelfScannerModal(p_data) {
                scanner = new Instascan.Scanner({
                    video: document.getElementById('preview1'),
                    mirror: false
                });
                scanner.addListener('scan', function(content) {
                    $('#code_shelf').val(content);
                    var data = {
                        'shelf_id': content,
                        'order_id': p_data.order_id,
                        'p_id': p_data.p_id
                    };
                    $.ajax({
                        url: '{{ route('wadmin.order.scanner') }}',
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.status == 'success') {
                                $("#shelf_scanner_modal").modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'success',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                                setTimeout(function() {
                                location.reload();
                                }, 1000);

                            } else if (response.status == 'danger') {
                                $("#shelf_scanner_modal").modal('hide');
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops...',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }

                        }
                    });
                });

                Instascan.Camera.getCameras().then(function(cameras) {
                    if (cameras.length > 0) {
                        var i = 0;
                        $.each(cameras, function(key, name) {
                            var class_name = '';
                            if (i == key) {
                                class_name = "text-success";
                            } else {
                                class_name = "text-muted";
                            }
                            $('#camera_list1').append('<li onclick="changeShelfScanner(' + key + ')" class="' +
                                class_name + '">' + name.name + '</li>');
                        });
                        scanner.start(cameras[0]);
                    } else {
                        $('#cam_error1').html('No cameras found.');
                    }
                }).catch(function(e) {
                    console.error(e);
                });

                $('#modaldemo8').modal('hide'); // Close the product scanner modal
                $('#shelf_scanner_modal').modal('show'); // Open the shelf scanner modal
            }

            $('#modaldemo8').on('hidden.bs.modal', function() {
                if (scanner) {
                    scanner.stop();
                }
            });
            $('#shelf_scanner_modal').on('hidden.bs.modal', function() {
                if (scanner) {
                    scanner.stop();
                }
            });
        </script>
    @endsection
