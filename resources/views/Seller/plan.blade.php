@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.select-plan') }}</h4>
                </div>
            </div>
            <!--End Page header-->
            <div id="success">

            </div>
            @if (Session::has('Success'))
                <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                        aria-hidden="true">×</button>
                    <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('Success') }}
                </div>
            @endif
            <!-- Row -->
            <div class="row">
                @if ($plans->IsNotEmpty())
                    @foreach ($plans as $plan)
                        <div class="col-sm-6 col-xl-4">
                            <div class="panel price panel-color">
                                <div class="panel-heading bg-primary text-white p-0 pb-0 text-center">
                                    <h3>{{ $plan->name }}</h3>
                                </div>
                                @php
                                    $plan_price = json_decode($plan->plan_price);
                                @endphp
                                <div class="bg-primary-transparent">
                                    <div class="panel-body text-center mb-3">
                                        <h4 class="lead text-primary"><strong>
                                                @if ($plan->name == 'Free')
                                                    0
                                                @else
                                                    {{ $plan_price->Monthly }}
                                                @endif
                                            </strong>{{ $plan->currency }} / {{ __('messages.month') }}</h4>
                                    </div>
                                    <div class="row p-5">
                                        <div class="col-sm-8">
                                            <ul class="text-start">
                                                @if ($plan->name == 'Free')
                                                @else
                                                    <li class="mb-4">
                                                        <strong>{{ __('messages.plan-yearly-price') }}</strong></li>
                                                @endif
                                                <li class="mb-4"> <strong>{{ __('messages.listing-product') }}</strong>
                                                </li>
                                                <li class="mb-4">
                                                    <strong>{{ __('messages.no-of-push-product-by-hour') }}</strong></li>
                                                <li class="mb-4">
                                                    <strong>{{ __('messages.no-of-push-product-by-day') }}</strong></li>
                                                <li class="mb-4"><strong>{{ __('messages.platform-sync') }}</strong></li>
                                                {{-- <li class="mb-4"><strong>{{__('messages.shipping-price-discount')}}</strong></li>
                                                <li class="mb-4"><strong>{{__('messages.order-cancellation-discount')}}</strong></li> --}}
                                            </ul>
                                        </div>
                                        @php
                                            $product = json_decode($plan->product_price);
                                            $shipping = json_decode($plan->shipping_price);
                                            $cancellation = json_decode($plan->order_cancellation);
                                            $push_product = json_decode($plan->push_product);
                                        @endphp
                                        <div class="col-sm-4">
                                            <ul class="text-start">
                                                @if ($plan->name == 'Free')
                                                @else
                                                    <li class="mb-4"><strong>{{ $plan_price->Yearly }}</strong></li>
                                                @endif
                                                <li class="mb-4"><strong>{{ $plan->listing_product }}</strong></li>
                                                <li class="mb-7">
                                                    <strong>{{ $push_product->push_product_by_hour }}</strong></li>
                                                <li class="mb-5">
                                                    <strong>{{ $push_product->push_product_by_day }}</strong></li>
                                                <li class="mb-4"><strong>{{ $plan->plateform_sync }}</strong></li>
                                                <li class="mb-4"><strong>{{ $shipping->discount }}@if ($shipping->method == 'percentage')
                                                            %
                                                        @else
                                                            FP
                                                        @endif
                                                    </strong>
                                                </li>
                                                <li class="mb-4"><strong>{{ $cancellation->discount }}@if ($cancellation->method == 'percentage')
                                                            %
                                                        @else
                                                            FP
                                                        @endif
                                                    </strong></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="panel-footer bg-primary-transparent text-center border-top-0">
                                        @if (!empty($plan_subscriber) && $plan_subscriber->plan_id == $plan->id)
                                            <a href="{{ route('seller.plan.unsubscribe', $plan->id) }}"
                                                class="btn btn-lg btn-dark delete-confirm">{{ __('messages.unsubscribe-plan') }}</a>
                                        @elseif(empty($plan_subscriber))
                                            @if ($plan->name == 'Free')
                                                @php
                                                    $user = \Illuminate\Support\Facades\Auth::user()->id;
                                                    $free_plan = \App\Models\FreePlan::where('user_id', $user)->first();
                                                @endphp
                                                @if ($free_plan)
                                                    <a href="{{ route('seller.plan.subscribe', $plan->id) }}"
                                                        class="btn btn-lg btn-primary disabled subscribe-plan">{{ __('messages.subscribe-plan') }}</a>
                                                @else
                                                    <a href="{{ route('seller.plan.subscribe', $plan->id) }}"
                                                        class="btn btn-lg btn-primary subscribe-plan">{{ __('messages.subscribe-plan') }}</a>
                                                @endif
                                            @else
                                                <button id="{{ $plan->id }}"
                                                    class="btn btn-lg btn-primary click-modal">{{ __('messages.subscribe-plan') }}</button>
                                            @endif
                                        @elseif(!empty($plan_subscriber) && $plan_subscriber->plan_id != $plan->id)
                                            <a
                                                class="btn btn-lg btn-primary disabled">{{ __('messages.subscribe-plan') }}</a>
                                        @endif
                                        <a href="{{ route('seller.plan.view', $plan->id) }}"
                                            class="btn bg-primary-transparent text-primary border-primary"><i
                                                class="fe fe-eye me-1 font-weight-bold"></i>{{ __('messages.view') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h3 class="text-muted pt-3">{{ __('messages.no-plan-found') }}</h3>
                @endif
            </div>
            <!-- End Row -->
        </div>
    </div>
    <!-- CONTAINER END -->
    <div class="modal fade" id="edit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('messages.plan-price-type') }}</h6><button aria-label="Close"
                        class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="error">
                    </div>
                    <input id="p_id" type="number" hidden />
                    <label class="form-label">{{ __('messages.subscribe-plan') }}</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="Monthly">
                        <label class="form-check-label" for="flexRadioDisabled">
                            {{ __('messages.subscribe-for-month') }}.
                        </label>
                        <label class="form-check-label" id="monthly">
                            Rs:
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="type" value="Yearly">
                        <label class="form-check-label" for="flexRadioCheckedDisabled">
                            {{ __('messages.subscribe-for-Year') }}.
                        </label>
                        <label class="form-check-label" id="yearly">
                            Rs:
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update">{{ __('messages.update') }}</button> <button
                        class="btn btn-secondary" data-bs-dismiss="modal"
                        type="button">{{ __('messages.close') }}</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('query')
    <script type="text/javascript">
        $('.delete-confirm').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this Plan!',
                icon: 'warning',
                showCancelButton: true,
                dangerMode: true,
                confirmButtonText: "Ok, Delete it!",
                showConfirmButton: true,
            }).then(function(value) {
                if (value) {
                    window.location.href = url;
                } else {
                    Swal.fire({
                        title: 'Your Plan is Safe!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.click-modal', function() {
            $('#error').html('');
            $('#monthly').html('');
            $('#yearly').html('');
            $('#error').removeClass('alert alert-light-danger');
            var id = $(this).attr("id");
            var route = "{{ route('seller.plan.index') }}";
            $.get(route + '/get/' + id, function(reponse) {
                console.log(reponse);
                $('#monthly').append(reponse.data.Monthly);
                $('#yearly').append(reponse.data.Yearly);
                $('#p_id').val(id);
                $('#edit').modal('toggle');
            });
        });
        $(document).ready(function() {
            $('.update').click(function() {
                var data = {
                    'id': $('#p_id').val(),
                    'type': $('input[name="type"]:checked').val(),
                };
                console.log(data.type);

                $.ajax({
                    url: '{{ route('seller.plan.subscribe.with.type') }}',
                    type: 'post',
                    data: data,
                    success: function(response) {
                        console.log(response);
                        if (response.status == 400) {
                            $('#error').html('');
                            $('#error').addClass('alert alert-light-danger');
                            $.each(response.errors, function(key, error) {
                                $('#error').append('<li>' + error + '</li>');
                            })
                        } else if (response.status == 401) {
                            $('#error').html('');
                            $('#error').addClass('alert alert-light-danger');
                            $('#error').append('<li>' + response.message + '</li>');
                        } else {
                            $('#error').html('');
                            $('#error').removeClass('alert alert-light-danger');
                            $('#edit').modal('hide');
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(loct, 1500);
                        }
                    }
                });
            });

            function loct() {
                window.location.href = '{{ route('seller.plan.index') }}';
            }
        });

        $(document).ready(function() {
            $('.subscribe-plan').on('click', function(event) {
                event.preventDefault(); // Prevent default action
                var url = $(this).attr('href');

                // Send an AJAX request to subscribe
                $.ajax({
                    url: url,
                    type: 'GET', // Use 'POST' if required by your backend
                    success: function(response) {
                        Swal.fire({
                            title: 'Congratulations!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });

                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong!',
                            icon: 'error',
                            showConfirmButton: true
                        });
                    }
                });
            });
        });
    </script>
@endsection
