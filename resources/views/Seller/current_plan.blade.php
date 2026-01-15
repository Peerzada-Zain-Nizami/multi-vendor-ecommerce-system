@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">
            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.current-plan')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                {{__('messages.plan-detail')}}
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($plan->name == "Free")
                                <div class="d-flex mb-3">
                                    <div class="d-flex align-items-center justify-content-center w-30 h-9 bg-light ms-4">
                                        <span class="fs-5 mb-4">{{__('messages.name')}}: </span>
                                        <span class="fs-4">{{$plan->name}}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center w-70 h-9 bg-light ms-4">
                                        <span class="fs-5 mb-4">{{__('messages.created-at')}}: </span>
                                        <span class="fs-4">{{$plan_subscriber->created_at}}</span>
                                    </div>
                                </div>
                            @else
                                <div class="d-flex mb-3">
                                    <div class="d-flex align-items-center justify-content-center w-40 h-9 bg-light ms-4">
                                        <span class="fs-5 mb-4">{{__('messages.name')}}: </span>
                                        <span class="fs-4">{{$plan->name}}</span>
                                    </div>
                                    @php
                                        $plan_price = json_decode($plan->plan_price);
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-center w-30 h-9 bg-light ms-4">
                                        <span class="fs-5 mb-4">{{__('messages.type')}}: </span>
                                        <span class="fs-4">{{$plan_subscriber->plan_type}}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center w-30 h-9 bg-light ms-4">
                                        <span class="fs-5 mb-4">{{__('messages.price')}}: </span>
                                        <span class="fs-4">{{$plan_subscriber->plan_type == "Monthly"? $plan_price->Monthly:$plan_price->Yearly}}</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="d-flex align-items-center justify-content-center w-100 h-9 bg-light ms-4">
                                        <span class="fs-5 mb-4">{{__('messages.created-at')}}: </span>
                                        <span class="fs-4">{{$plan_subscriber->created_at}}</span>
                                    </div>
                                </div>
                            @endif
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
        </div>
    </div>
    <!-- CONTAINER END -->
@endsection

@section('query')
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
