@php
    $usd_to_sar = DB::table('settings')->where('option_name','usd_to_sar')->first();
    $paypal_deposit_fees = DB::table('settings')->where('option_name','paypal_deposit_fees')->first();
    $sar_to_usd = DB::table('settings')->where('option_name','sar_to_usd')->first();
    $paypal_withdraw_fee = DB::table('settings')->where('option_name','paypal_withdraw_fee')->first();
@endphp
@extends('Admin.base')
@section('content')

<!--app-content open-->
<div class="app-content main-content">
    <div class="side-app">
        <!-- Row -->
        <div class="row mt-5">
            <div class="col-xl-5 col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            {{__('messages.wallet-balance')}}
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <p class="display-2">SAR.{{$balance}}</p>
                    </div>
                </div>
            </div>
            <!-- End Row -->

            <div class="col-md-12 col-lg-6">
                <div class="card" id="tabs-style3">
                    <div class="card-header">
                        <div class="card-title">
                            {{__('messages.payment-action')}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="bank_success">

                        </div>
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
                        <div class="panel panel-primary tabs-style-3">
                            <div class="tab-menu-heading">
                                <div class="tabs-menu">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs">
                                        <li><a href="#tab11" class="@if(!empty(old('deposit'))) active @endif" data-bs-toggle="tab">{{__('messages.payment-deposit')}}</a></li>
                                        <li><a href="#tab12" class="@if(!empty(old('transfar'))) active @endif" data-bs-toggle="tab">{{__('messages.transfer-payment')}}</a></li>
                                        <li><a href="#tab13" class="@if(!empty(old('withdraw'))) active @endif" data-bs-toggle="tab">{{__('messages.withdraw-payment')}}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body">
                                <div class="tab-content">
                                    <div class="tab-pane @if(!empty(old('deposit'))) active @endif" id="tab11">
                                        <!--Row-->
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">{{__('messages.deposit-details')}}</h3>
                                                    </div>
                                                    <div class="card-body">

                                                        <form id="a_deposit" class="row g-3" enctype="multipart/form-data">
                                                            <div id="add_g_error"></div>
                                                            <input type="text" name="deposit" value="{{__('messages.deposit')}}" hidden>
                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">{{__('messages.deposit-amount')}}</label>
                                                                <input type="number" id="amount" class="form-control @if($errors->has('amount'))is-invalid @endif" value="{{old('amount')}}" name="amount" />
                                                            </div>
                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">{{__('messages.attach-proof')}}</label>
                                                                <input type="file" class="form-control @if($errors->has('deposit_proof'))is-invalid @endif" name="deposit_proof" />
                                                            </div>
                                                            <div class="col-md-12 position-relative card-body">
                                                                <label for="validationTooltip02" class="form-label">{{__('messages.deposit-note')}}</label>
                                                                <textarea class="form-control @if($errors->has('deposit_note'))is-invalid @endif" name="deposit_note">{{old('deposit_note')}}</textarea>
                                                            </div>
                                                            <div class="col-12">
                                                                <button class="btn btn-primary a_deposit"type="button">{{ __('messages.deposit') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/Row-->
                                    </div>
                                    <div class="tab-pane @if(!empty(old('transfar'))) active @endif" id="tab12">
                                        <!--Row-->
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">{{__('messages.transfer-details')}}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <form id="a_transfer">
                                                            <div id="add_f_error"></div>
                                                            <input type="text" name="transfar" value="transfar" hidden>
                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">{{__('messages.transfer-amount')}}</label>
                                                                <input type="number" class="form-control @if($errors->has('transfar_amount'))is-invalid @endif" value="{{old('transfar_amount')}}" name="transfar_amount"/>
                                                            </div>

                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">{{__('messages.transfar-to-(Email)')}}</label>
                                                                <select class="form-control select2-show-search @if($errors->has('email'))is-invalid @endif" value="{{old('email')}}" name="email">
                                                                    <option value="">{{__('messages.please-select')}}</option>
                                                                    @foreach($subadmins as $admin)
                                                                        <option value="{{$admin->email}}">{{$admin->email}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">{{__('messages.attach-proof')}}</label>
                                                                <input type="file" class="form-control @if($errors->has('transfar_proof'))is-invalid @endif" name="transfar_proof" />
                                                            </div>
                                                            <div class="col-md-12 position-relative card-body">
                                                                <label class="form-label">{{__('messages.note')}}</label>
                                                                <textarea class="form-control @if($errors->has('note'))is-invalid @endif" name="note">{{old('note')}}</textarea>
                                                            </div>

                                                            <div class="col-12">
                                                                <button class="btn btn-primary a_transfer"type="button">{{ __('messages.transfar') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/Row-->
                                    </div>
                                    <div class="tab-pane @if(!empty(old('withdraw'))) active @endif" id="tab13">
                                        <!--Row-->
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title">{{__('messages.withdraw-details')}}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <form id="withdraw" class="row g-3" enctype="multipart/form-data">
                                                            <div id="add_w_error"></div>
                                                            <input type="text" name="withdraw" value="withdraw" hidden>
                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">{{__('messages.withdraw-amount')}}</label>
                                                                <input type="number" id="withdraw" class="form-control @if($errors->has('withdraw_amount'))is-invalid @endif" value="{{old('withdraw_amount')}}" name="withdraw_amount"/>
                                                                @if ($errors->has('withdraw_amount'))
                                                                <span class="text-danger">{{ $errors->first('withdraw_amount') }}</span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">{{__('messages.attach-proof')}}</label>
                                                                <input type="file" class="form-control @if($errors->has('withdraw_proof'))is-invalid @endif" name="withdraw_proof" />
                                                                @if ($errors->has('withdraw_proof'))
                                                                    <span class="text-danger">{{ $errors->first('withdraw_proof') }}</span>
                                                                @endif
                                                            </div>
                                                            <div class="col-md-12 position-relative card-body">
                                                                <label for="validationTooltip02" class="form-label">{{__('messages.withdraw-note')}}</label>
                                                                <textarea class="form-control @if($errors->has('withdraw_note'))is-invalid @endif" name="withdraw_note">{{old('withdraw_note')}}</textarea>
                                                                @if ($errors->has('withdraw_note'))
                                                                <span class="text-danger">{{ $errors->first('withdraw_note') }}</span>
                                                                @endif
                                                            </div>

                                                            <div class="col-12">
                                                                <button class="btn btn-primary withdraw"type="button">{{ __('messages.withdraw') }}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/Row-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection

@section('query')
    <script type="text/javascript">
    $(Document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        $('.a_deposit').click(function () {
            let formData = new FormData($('#a_deposit')[0]); // Use FormData instead of serialize()
            $.ajax({
                url: '{{route('admin.deposit')}}',
                type: 'POST',
                data: formData,
                contentType: false, // Important for file uploads
                processData: false, // Prevent jQuery from automatically processing data
                success: function (response) {
                    console.log(response);
                    if (response.status == "fail") {
                        $('#add_g_error').html('');
                        $('#add_g_error').addClass('alert alert-light-danger');
                        $.each(response.errors, function (key, error) {
                            $('#add_g_error').append('<li>' + error + '</li>');
                        });
                    } else if (response.status == "danger") {
                        Swal.fire({
                            title: 'Warning!',
                            text: response.message,
                            icon: 'warning',
                            showConfirmButton: true,
                        });
                    } else {
                        Swal.fire({
                            title: 'Congratulations!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500,
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    }
                },
            });
    });

             $('.a_transfer').click(function(){
                let formData = new FormData($('#a_transfer')[0]); 
                $.ajax({
                    url:'{{route('admin.transfar')}}',
                    type:'POST',
                    data: formData,
                    contentType: false, // Required for file uploads
                    processData: false, // 
                    success:function(response){
                        console.log(response);
                        if (response.status == "fail")
                        {
                            $('#add_f_error').html('');
                            $('#add_f_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_f_error').append('<li>'+error+'</li>');
                            })
                        }
                          else if (response.status == "danger") {
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning',
                                showConfirmButton: true
                            });
                        }
                         else {
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
                        }
                    }
                });
            });

            $('.withdraw').click(function () {
                var formData = new FormData($('#withdraw')[0]);
                $.ajax({
                    url: '{{ route("admin.withdraw") }}',
                    type: 'POST',
                    data: formData,
                    contentType: false, // Important for FormData
                    processData: false, // Important for FormData
                    success: function (response) {
                        console.log(response);
                        if (response.status === "fail") {
                            $('#add_w_error').html('');
                            $('#add_w_error').addClass('alert alert-light-danger');
                            $.each(response.errors, function (key, error) {
                                $('#add_w_error').append('<li>' + error + '</li>');
                            });
                        } else if (response.status === "danger") {
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning',
                                showConfirmButton: true,
                            });
                        } else {
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500,
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText); // Debug server-side errors
                    },
                });
            });
        });
    </script>
@endsection
