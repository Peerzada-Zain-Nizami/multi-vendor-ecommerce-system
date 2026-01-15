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
    elseif($st == "Complete" || $st == "Completed" || $st == "return-approved" )
       {
           echo "text-success";
       }
       elseif($st == "Pending")
       {
           echo "text-warning";
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
    }elseif($st == "Complete" || $st == "Completed")
    {
        echo __('messages.complete');
    }elseif($st == "Pending")
    {
        echo __('messages.pending');
    }elseif($st == "New Order")
    {
        echo __('messages.new-order');
    }elseif($st == "refunded")
    {
        echo __('messages.refunded');
    }elseif($st == "return-cancelled")
    {
        echo __('messages.return-cancelled');
    }elseif($st == "cancelled")
    {
        echo __('messages.cancelled');
    }elseif($st == "Packed")
    {
        echo __('messages.packed');
    }elseif($st == "Dispatched")
    {
        echo __('messages.dispatched');
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
@extends('Seller.base')


@section('content')

       <!--app-content open-->
       <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            @if (Session::has('danger'))
                <div class="alert alert-light-danger" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}}
                </div>
            @endif
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.seller-dashboard')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row-1 -->
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden dash1-card border-0 dash1">
                        <div class="card-body">
                            <span class="fs-14 font-weight-normal">{{__('messages.new-orders')}}</span>
                            <h2 class="mb-2 number-font carn1 font-weight-bold">{{$new_orders}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden dash1-card border-0 dash4">
                        <div class="card-body">
                            <span>{{__('messages.process-orders')}}</span>
                            <h2 class="mb-2 mt-1 number-font carn2 font-weight-bold">{{$process}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden dash1-card border-0 dash2">
                        <div class="card-body">
                            <span class="fs-14">{{__('messages.completed-orders')}}</span>
                            <h2 class="mb-2 mt-1 number-font carn2 font-weight-bold">{{$completed}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden dash1-card border-0 dash2">
                        <div class="card-body">
                            <span class="fs-14">{{__('messages.refund-completed')}}</span>
                            <h2 class="mb-2 mt-1 number-font carn2 font-weight-bold">{{$refund_completed}}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
                    <div class="card overflow-hidden dash1-card border-0 dash3">
                        <div class="card-body">
                            <span class="fs-14">{{__('messages.cancel/rejected')}}</span>
                            <h2 class="mb-2 mt-1 number-font carn2 font-weight-bold">{{$cancel_reject}}</h2>
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
                            <h3 class="card-title">{{__('messages.sales-summary')}}</h3>
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('messages.plan-counter')}}</h3>
                        </div>
                        <div class="card-body text-center">
                            <div class="under-countdown row">
                                <div class="col-lg-3 mb-2">
                                    <div class="countdown bg-primary-transparent">
                                        <span class="days" id="days"></span>
                                        <span class="">{{__('messages.days')}}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-2">
                                    <div class="countdown bg-success-transparent">
                                        <span class="hours" id="hours"></span>
                                        <span class="">{{__('messages.hours')}}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-2">
                                    <div class="countdown bg-danger-transparent">
                                        <span class="minutes" id="minutes"></span>
                                        <span class="">{{__('messages.minutes')}}</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 mb-2">
                                    <div class="countdown bg-warning-transparent">
                                        <span class="seconds" id="seconds"></span>
                                        <span class="">{{__('messages.seconds')}}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('messages.new-order')}}</h3>
                            <div class="card-options">
                                <a href="{{route('seller.woo.order.list')}}" class="btn btn-sm btn-primary">{{__('messages.view-all')}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                            <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                                <thead class="border-bottom-0 pt-3 pb-3">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{__('messages.order-no')}}</th>
                                    <th>{{__('messages.shipping-address')}}</th>
                                    <th>{{__('messages.platforms')}}</th>
                                    <th>{{__('messages.sub-total')}}</th>
                                    <th>{{__('messages.total')}}</th>
                                    <th>{{__('messages.payment')}}</th>
                                    <th>{{__('messages.status')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($results as $order)
                                    <tr>
                                        <td class="text-center">{{$i++}}</td>
                                        <td>{{$order->order_no}}</td>
                                        @php
                                                $address = json_decode($order->shipping_address);
                                        @endphp
                                        <td>{{(isset($address->address_1))?$address->address_1:$address->address1}}</td>
                                        <td>{{$order->platform}}</td>
                                        <td>{{$order->sub_total}}</td>
                                        <td>{{$order->total}}</td>
                                        <td class="fw-bold {{payment_status($order->payment)}}">{{payment_status_lang($order->payment)}}</td>
                                        <td class="fw-bold {{invoice_status($order->status)}}">{{invoice_status_lang($order->status)}}</td>
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
                            <h3 class="card-title">{{__('messages.my-catalog')}}</h3>
                            <div class="card-options">
                                <a href="{{route('seller.drop.catalog')}}" class="btn btn-sm btn-primary">{{__('messages.view-all')}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                            <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                                <thead class="border-bottom-0 pt-3 pb-3">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{__('messages.image')}}</th>
                                    <th>{{__('messages.product-category')}}</th>
                                    <th>{{__('messages.product-name')}}</th>
                                    <th>{{__('messages.company-product-status')}}</th>
                                    <th>{{__('messages.company-price')}}</th>
                                    <th>{{__('messages.my-prices')}}</th>
                                    <th>{{__('messages.status')}}</th>
                                    <th>{{__('messages.listed-platforms')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($drop_shipping as $result)
                                    <tr>
                                        <td class="text-center">{{$i++}}</td>
                                        <td><img class="avatar avatar-xxl" src="@if(empty($result->featured_image) && empty($result->product_images)){{asset('uploads/featured_images/'.$result->get_products[0]->featured_image)}} @else {{asset('uploads/seller_products/featured_images/'.$result->featured_image)}}@endif"></td>
                                        <td class="fs-13">{{$result->category}}</td>
                                        <td>{{$result->product_name}}</td>
                                        <td><h3><span class="badge @if($result->get_stock[0]->status == "Listed") bg-success-transparent @else bg-danger-transparent @endif">@if($result->get_stock[0]->status == "Listed") {{__('messages.listed')}} @else {{__('messages.unlisted')}} @endif</span></h3></td>
                                        <td>
                                            @php
                                                $rate = $result->get_stock[0]->selling_price;
                                                $dis = $result->get_stock[0]->discount;
                                                $net = $rate-$dis;
                                            @endphp
                                            <span class='text-secondary'>{{__('messages.company-rate')}}: {{$rate}}</span><br>
                                            <span class='text-success'>{{__('messages.discount')}}: @if(empty($dis)) 0 @else {{$dis}} @endif</span><br>
                                            <span class='text-info'>{{__('messages.net-rate')}}: {{$net}}</span><br>
                                            <span class='text-warning'>{{__('messages.suggested-price')}}: {{$result->get_stock[0]->suggested_price}}</span><br>
                                            <span class='text-muted'>{{__('messages.retail-price')}}: {{$result->get_stock[0]->retail_price}}</span>
                                        </td>
                                        <td>
                                            @php
                                                $rate = $result->selling_price;
                                                $dis = $result->discount;
                                                $net = $rate-$dis;
                                            @endphp
                                            <span class='text-secondary'>{{__('messages.my-rate')}}: @if(empty($rate)) 0 @else {{$rate}} @endif</span><br>
                                            <span class='text-success'>{{__('messages.discount')}}: @if(empty($dis)) 0 @else {{$dis}} @endif</span><br>
                                            <span class='text-info'>{{__('messages.net-rate')}}: {{$net}}</span>
                                        </td>
                                        <td><h3><span class="badge @if($result->status == "Active") bg-success-transparent @else bg-danger-transparent @endif">@if($result->status == "Active") {{__('messages.active')}} @else {{__('messages.deactive')}} @endif</span></h3></td>
                                        <td>
                                            @php
                                                $platforms = json_decode($result->platforms);
                                            @endphp
                                            @if(!empty($platforms))
                                                @foreach($platforms as $platform)
                                                    <span class="badge rounded-pill bg-primary mt-2">{{$platform}}</span>
                                                    @if($loop->even == true)
                                                        <br>
                                                    @endif
                                                @endforeach
                                            @else
                                                {{__('messages.not-listed')}}
                                            @endif
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
@section('query')
    {{-- <script>
        @php
            if ($plan->name == "Free")
            {
                $new_date = date_add($plan_subscriber->updated_at,date_interval_create_from_date_string("30 days"));
            }
            else{
                if ($plan_subscriber->plan_type == "Monthly")
                {
                    $new_date = date_add($plan_subscriber->updated_at,date_interval_create_from_date_string("1 month"));
                }
                else{
                    $new_date = date_add($plan_subscriber->updated_at,date_interval_create_from_date_string("1 year"));
                }
            }
            $getDateTime = $new_date->format("M d, Y H:i:s");
        @endphp
        // Set the date we're counting down to
        var countDownDate = new Date("<?php echo "$getDateTime"; ?>").getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            console.log(countDownDate);
            // Get today's date and time
            var now = new Date().getTime();
            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in an element with id="demo"
            document.getElementById("days").innerHTML = days;
            document.getElementById("hours").innerHTML = hours;
            document.getElementById("minutes").innerHTML = minutes;
            document.getElementById("seconds").innerHTML = seconds;

            // If the count down is over, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("demoa").innerHTML = "EXPIRED";
            }
        }, 1000);
    </script> --}}

    <script>
        @php
            use Carbon\Carbon;

            if ($plan->name == "Free") {
                $new_date = date_add($plan_subscriber->updated_at, date_interval_create_from_date_string("30 days"));
            } else {
                if ($plan_subscriber->plan_type == "Monthly") {
                    // Adjust the month countdown to start from 29 days
                    $new_date = date_add($plan_subscriber->updated_at, date_interval_create_from_date_string("29 days"));
                } else {
                    $new_date = date_add($plan_subscriber->updated_at, date_interval_create_from_date_string("1 year"));
                }
            }
            $getDateTime = $new_date->format("M d, Y H:i:s");
            // Get the timestamp (in milliseconds) of the date object
            $timestamp = $new_date->timestamp * 1000;

            // Calculate the timestamp for the deadline (24 hours after the new date)
            $deadlineTimestamp = $new_date->addHours(24)->timestamp * 1000;
        @endphp

        // Set the deadline timestamp
        var deadline = <?php echo $deadlineTimestamp; ?>;

        // Define a function to update the countdown timer
        function updateTimer() {
            // Get the current timestamp
            var now = new Date().getTime();
            // Calculate the remaining time
            var distance = deadline - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Output the result in corresponding elements
            document.getElementById("days").innerHTML = days;
            document.getElementById("hours").innerHTML = hours;
            document.getElementById("minutes").innerHTML = minutes;
            document.getElementById("seconds").innerHTML = seconds;

            // If the countdown is over, display "EXPIRED"
            if (distance < 0) {
                document.getElementById("demoa").innerHTML = "EXPIRED";
            } else {
                // Call the function again after one second
                setTimeout(updateTimer, 1000);
            }
        }

        // Call the function for the first time
        updateTimer();
    </script>

@endsection
