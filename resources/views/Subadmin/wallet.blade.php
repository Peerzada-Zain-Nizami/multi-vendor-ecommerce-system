@php
    $usd_to_sar = DB::table('settings')->where('option_name','usd_to_sar')->first();
    $paypal_deposit_fees = DB::table('settings')->where('option_name','paypal_deposit_fees')->first();
    $sar_to_usd = DB::table('settings')->where('option_name','sar_to_usd')->first();
    $paypal_withdraw_fee = DB::table('settings')->where('option_name','paypal_withdraw_fee')->first();
@endphp
@extends('Subadmin.base')
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
                            Wallet Balance
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
                            Payment Action
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="bank_success">

                        </div>
                        @if (Session::has('success'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                <strong>Well done!</strong> {{Session::get('success')}}
                            </div>
                        @endif
                        @if (Session::has('danger'))
                            <div class="alert alert-light-danger" role="alert">
                                <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                <strong>Oppss!</strong> {{Session::get('danger')}}
                            </div>
                        @endif
                        <div class="panel panel-primary tabs-style-3">
                            <div class="tab-menu-heading">
                                <div class="tabs-menu">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs">
                                        <li><a href="#tab11" class="@if(!empty(old('deposit'))) active @endif" data-bs-toggle="tab">Payment Deposit</a></li>
                                        <li><a href="#tab12" class="@if(!empty(old('transfar'))) active @endif" data-bs-toggle="tab">Transfer Payment</a></li>
                                        <li><a href="#tab13" class="@if(!empty(old('withdraw'))) active @endif" data-bs-toggle="tab">Withdraw Request</a></li>
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
                                                        <h3 class="card-title">Deposit Details</h3>
                                                    </div>
                                                    <div class="card-body">

                                                        <form method="POST" action="{{route('subadmin.deposit')}}" class="row g-3" enctype="multipart/form-data">
                                                            @csrf
                                                            <input type="text" name="deposit" value="deposit" hidden>
                                                            <div class="form-group">
                                                                <div class="form-label">Select Payment Method</div>
                                                                <div class="custom-controls-stacked">
                                                                    <label class="custom-control custom-radio">
                                                                        <input type="radio" id="bank2" onclick="hide_1()" class="custom-control-input" name="radio" value="BankTransfar" checked />
                                                                        <span class="custom-control-label">Bank Deposit</span>
                                                                    </label>
                                                                </div>
                                                                <div class="custom-controls-stacked">
                                                                    <label class="custom-control custom-radio">
                                                                        <input type="radio" id="PayTabs" onclick="hide_2()" class="custom-control-input" name="radio" value="PayTabs" />
                                                                        <span class="custom-control-label">PayTabs</span>
                                                                    </label>
                                                                </div>
                                                                <div class="custom-controls-stacked">
                                                                    <label class="custom-control custom-radio">
                                                                        <input type="radio" id="Paypal" onclick="rate()" class="custom-control-input" name="radio" value="Paypal" />
                                                                        <span class="custom-control-label">Paypal</span>
                                                                    </label>
                                                                </div>
                                                                <div id="rate" style="display: none;" class="text-warning">Exchange Rate: 1 USD = {{$usd_to_sar->option_value}} SAR</div>
                                                            </div>
                                                            <div id="attach" style="display: block;">
                                                                <div class="col-md-12 position-relative">
                                                                    <label class="form-label">Transaction ID</label>
                                                                    <input type="text" class="form-control @if($errors->has('transaction_id'))is-invalid @endif" name="transaction_id" />
                                                                    @if ($errors->has('transaction_id'))
                                                                        <span class="text-danger">{{ $errors->first('transaction_id') }}</span>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-12 position-relative">
                                                                    <label class="form-label">Attach Proof of Deposit Slip</label>
                                                                    <input type="file" class="form-control @if($errors->has('proof'))is-invalid @endif" name="proof" />
                                                                    @if ($errors->has('proof'))
                                                                        <span class="text-danger">{{ $errors->first('proof') }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">Deposit Amount</label>
                                                                <input type="number" id="amount" class="form-control @if($errors->has('amount'))is-invalid @endif" value="{{old('amount')}}" name="amount" />
                                                                @if ($errors->has('amount'))
                                                                <span class="text-danger">{{ $errors->first('amount') }}</span>
                                                                @endif
                                                            </div>
                                                            <p class="text-danger" id="output"></p>


                                                            <div class="col-12">
                                                                <input class="btn btn-primary" type="submit" value="Deposit">
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
                                                        <h3 class="card-title">Transfer Details</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <form method="POST" action="{{route('subadmin.transfar')}}" class="row g-3">
                                                            @csrf
                                                            <input type="text" name="transfar" value="transfar" hidden>
                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">Transfer Amount</label>
                                                                <input type="number" class="form-control @if($errors->has('transfar_amount'))is-invalid @endif" value="{{old('transfar_amount')}}" name="transfar_amount"/>
                                                                @if ($errors->has('transfar_amount'))
                                                                <span class="text-danger">{{ $errors->first('transfar_amount') }}</span>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">Transfar To</label>
                                                                <input type="text" class="form-control @if($errors->has('email'))is-invalid @endif" placeholder="Enter Valid Email." value="{{old('email')}}" name="email"/>
                                                                @if ($errors->has('email'))
                                                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-12 position-relative card-body">
                                                                <label class="form-label">Note</label>
                                                                <textarea class="form-control @if($errors->has('note'))is-invalid @endif" name="note">{{old('note')}}</textarea>
                                                                @if ($errors->has('note'))
                                                                <span class="text-danger">{{ $errors->first('note') }}</span>
                                                                @endif
                                                            </div>

                                                            <div class="col-12">
                                                                <input class="btn btn-primary" type="submit" value="Transfar">
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
                                                        <h3 class="card-title">Withdraw Details</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <form method="POST" action="{{route('subadmin.withdraw')}}" class="row g-3">
                                                            @csrf
                                                            <input type="text" name="withdraw" value="withdraw" hidden>
                                                            <div class="form-group">
                                                                <div class="form-label">Select Withdraw Method</div>
                                                                @if ($errors->has('withdraw_method'))
                                                                    <span class="text-danger">{{ $errors->first('withdraw_method') }}</span>
                                                                @endif
                                                                <div class="custom-controls-stacked">
                                                                    <label class="custom-control custom-radio">
                                                                        <input id="hide_output2" type="radio" onclick="bank()" class="custom-control-input" name="withdraw_method" value="Bank" @if(old('withdraw_method') == "Bank") checked @endif/>
                                                                        <span class="custom-control-label">Bank Transfar</span>
                                                                    </label>
                                                                </div>
                                                                    <div id="bank" style="padding-left: 20px; @if(old('withdraw_method') != "Bank") display: none; @endif">
                                                                        <div class="form-group">
                                                                            <label class="form-label">Select Transfar Bank</label>
                                                                            <select id="bank_list" class="form-control select2 @if($errors->has('transfar_bank'))is-invalid @endif" name="transfar_bank">
                                                                                <option value="" selected>Please Select</option>
                                                                            </select>
                                                                            @if ($errors->has('transfar_bank'))
                                                                                <span class="text-danger">{{ $errors->first('transfar_bank') }}</span>
                                                                            @endif
                                                                        </div>
                                                                        <div class="text-end">
                                                                            <a class="modal-effect btn btn-primary" data-bs-effect="effect-scale" data-bs-toggle="modal" href="#addbank">Add new bank</a>
                                                                        </div>
                                                                    </div>
                                                                <div class="custom-controls-stacked">
                                                                    <label class="custom-control custom-radio">
                                                                        <input id="Paypal2" type="radio" onclick="paypal()" class="custom-control-input" name="withdraw_method" value="Paypal" @if(old('withdraw_method') == "Paypal") checked @endif />
                                                                        <span class="custom-control-label">Paypal</span>
                                                                    </label>
                                                                </div>
                                                                <div id="paypal" style="padding-left: 20px; @if(old('withdraw_method') != "Paypal") display: none; @endif">
                                                                    <div class="form-group">
                                                                        <label class="form-label">Select PayPal account</label>
                                                                        <select id="paypal_list" class="form-control select2 @if($errors->has('paypal_account'))is-invalid @endif" name="paypal_account">
                                                                            <option value="">Please Select</option>
                                                                        </select>
                                                                    @if ($errors->has('paypal_account'))
                                                                            <span class="text-danger">{{ $errors->first('paypal_account') }}</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="text-end">
                                                                        <a class="modal-effect btn btn-primary" data-bs-effect="effect-scale" data-bs-toggle="modal" href="#addpaypal">Add new paypal</a>
                                                                    </div>
                                                                    <div class="text-warning">Exchange Rate: 1 USD = {{$sar_to_usd->option_value}} SAR</div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-12 position-relative">
                                                                <label class="form-label">Withdraw Amount</label>
                                                                <input type="number" id="withdraw" class="form-control @if($errors->has('withdraw_amount'))is-invalid @endif" value="{{old('withdraw_amount')}}" name="withdraw_amount"/>
                                                                @if ($errors->has('withdraw_amount'))
                                                                <span class="text-danger">{{ $errors->first('withdraw_amount') }}</span>
                                                                @endif
                                                            </div>
                                                            <div id="output2" class="text-danger">

                                                            </div>
                                                            <div class="col-md-12 position-relative card-body">
                                                                <label for="validationTooltip02" class="form-label">Note</label>
                                                                <textarea class="form-control @if($errors->has('withdraw_note'))is-invalid @endif" name="withdraw_note">{{old('withdraw_note')}}</textarea>
                                                                @if ($errors->has('withdraw_note'))
                                                                <span class="text-danger">{{ $errors->first('withdraw_note') }}</span>
                                                                @endif
                                                            </div>

                                                            <div class="col-12">
                                                                <input class="btn btn-primary" type="submit" value="Withdraw Request">
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
    <!-- MODAL EFFECTS -->
    <div class="modal fade" id="addbank">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Add New Bank</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="bank_error">
                    </div>

                        <div class="form-group">
                            <label class=" text-left">Bank Name</label>
                            <input type="text" id="bank_name" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label text-left">Account Name</label>
                            <input type="text" id="account_name" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label text-left">IBAN No</label>
                            <input type="text" id="iban_no" class="form-control"/>
                        </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary add_bank">Add Bank</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="addpaypal">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Add New Paypal</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="paypal_error">
                    </div>

                    <div class="form-group">
                        <label class=" text-left">Paypal Email</label>
                        <input type="text" id="paypal_email" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary add_paypal">Add Paypal</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                </div>

            </div>
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection
@section('query')
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','.add_bank',function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = {
                'bank_name': $('#bank_name').val(),
                'account_name': $('#account_name').val(),
                'iban_no': $('#iban_no').val(),
            };
            $.ajax({
                url: "{{route('subadmin.bank.add.model')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                   if (response.status == 400)
                   {
                       $('#bank_error').html('');
                       $('#bank_error').addClass('alert alert-light-danger');
                       $.each(response.errors,function (key,error) {
                           $('#bank_error').append('<li>'+error+'</li>');
                       })
                   }
                   else{
                       $('#bank_error').html('');
                       $('#bank_error').removeClass('alert alert-light-danger');
                       $('#bank_success').html(
                           '<div class="alert alert-light-success" role="alert">\n' +
                           '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                           '    <strong>Well done! </strong>'+response.message+'\n' +
                           '</div>');
                       $('#addbank').modal('hide');
                       $('#addbank').find('input').val("");
                       get_bank();
                   }
                }
            })
        });
        $(document).on('click','.add_paypal',function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = {
                'paypal_email': $('#paypal_email').val(),
            };
            $.ajax({
                url: "{{route('subadmin.paypal.add.model')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    if (response.status == 400)
                    {
                        $('#paypal_error').html('');
                        $('#paypal_error').addClass('alert alert-light-danger');
                        $.each(response.errors,function (key,error) {
                            $('#paypal_error').append('<li>'+error+'</li>');
                        })
                    }
                    else{
                        $('#paypal_error').html('');
                        $('#paypal_error').removeClass('alert alert-light-danger');
                        $('#bank_success').html(
                            '<div class="alert alert-light-success" role="alert">\n' +
                            '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                            '    <strong>Well done! </strong>'+response.message+'\n' +
                            '</div>');
                        $('#addpaypal').modal('hide');
                        $('#addpaypal').find('input').val("");
                        get_paypal();
                    }
                }
            })
        });
        get_bank();
        function get_bank()
        {
            $('#bank_list').html('');
            $.ajax({
                url:"{{route('subadmin.banks.list')}}",
                type:"GET",
                dataType:"json",
                success:function(data){
                    $('#bank_list').append('<option value="" selected>Please Select</option>');
                    $.each(data.record,function (key,item) {
                        $('#bank_list').append('<option value="'+item.id+'">'+item.bank_name+'</option>');
                    })
                }
            })
        }
        get_paypal();
        function get_paypal()
        {
            $('#paypal_list').html('');
            $.ajax({
                url:"{{route('subadmin.paypal.list')}}",
                type:"GET",
                dataType:"json",
                success:function(data){
                    $('#paypal_list').append('<option value="" selected>Please Select</option>');
                    $.each(data.record,function (key,item) {
                        $('#paypal_list').append('<option value="'+item.id+'">'+item.paypal_email+'</option>');
                    })
                }
            })
        }
        $("#bank2").click(function () {
            $("#output").hide();
        });
        $("#PayTabs").click(function () {
            $("#output").hide();
        });
        $("#Paypal").click(function () {
            $("#amount").keyup(function(){
                var amount = $(this).val();
                var rate = '{{$usd_to_sar->option_value}}';
                var fess = '{{$paypal_deposit_fees->option_value}}';
                var total_fees = amount/100*fess;
                var newamount = amount-total_fees;
                var total_recive = newamount*rate;
                $("#output").show().html("Total Fees in USD: "+parseInt(total_fees)+"<br> You will Receive in SAR: "+parseInt(total_recive));
            });
        });
        $("#hide_output2").click(function () {
            $("#output2").hide();
        });
        $("#Paypal2").click(function () {
            $("#withdraw").keyup(function(){
                var amount = $(this).val();
                var rate = '{{$sar_to_usd->option_value}}';
                var total = amount/rate;
                var fess = '{{$paypal_withdraw_fee->option_value}}';
                var total_fees = total/100*fess;
                var total_recive = total-total_fees;
                $("#output2").show().html("Total Fees in USD: "+parseInt(total_fees)+"<br> You will Receive in USD: "+parseInt(total_recive));
            });
        });

    })
</script>
@endsection