@php
    $profile = null;
    if (!empty(Auth::user()->profile_img))
    {
        $profile = asset ('uploads/profiles/'.Auth::user()->profile_img);
    }
    else{
        $profile = asset ('assets/user_avator.png');
    }
@endphp
@extends('Subadmin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">Transaction Detail</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="card">
                    <div class="card-header justify-content-end">
                        <button id="print" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
                    </div>
                    <div class="card-body">
                        <div class="row" id="printdata">
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">Transaction ID</th>
                                        <td>{{$trs['transaction_id']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Transaction Type</th>
                                        <td>{{$trs['type']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Date</th>
                                        <td>{{date('d-m-Y h:i:sa',strtotime($trs['created_at']))}}</td>
                                    </tr>
                                </table>
                            </div>
                            @if($trs['method'] == "Bank Deposit" && $trs['type'] == "Cash Deposit")
                            {{--For bank deposit--}}
                                <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">Payment Method</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Bank Slip ID</th>
                                        <td>{{$trs['method_trs_id']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Deposit Amount</th>
                                        <td>{{Crypt::decrypt($trs['cash_in'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Status</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4 pt-3">
                                <p class="font-weight-bold">Proof Imgage</p>
                                <a href="{{asset('uploads/deposit_proof/'.$trs['attach'])}}" target="_blank"><img src="{{asset('uploads/deposit_proof/'.$trs['attach'])}}" width="250px" height="100px"></a>
                            </div>
                            {{-- end bank deposit--}}
                            @endif

                            @if($trs['method'] == "PayTabs" && $trs['type'] == "Cash Deposit")
                            {{--For paytab deposit--}}
                                <div class="col-sm-4 offset-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">Payment Method</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Paytab Transaction ID</th>
                                        <td>{{$trs['method_trs_id']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Deposit Amount (SAR)</th>
                                        <td>{{Crypt::decrypt($trs['cash_in'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Status</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            {{-- end paytab deposit--}}
                            @endif

                            @if($trs['method'] == "PayPal" && $trs['type'] == "Cash Deposit")
                            {{--For paypal deposit--}}
                            <div class="col-sm-5">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">Payment Method</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Paypal Transaction ID</th>
                                        <td>{{$trs['method_trs_id']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Deposit Amount (USD)</th>
                                        <td>{{Crypt::decrypt($trs['deposit_amount'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Status</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-3">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">Exchange Rate</th>
                                        <td>{{$trs['exchange_rate']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Fees</th>
                                        <td>{{$trs['fees']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Received Amount (SAR)</th>
                                        <td>{{Crypt::decrypt($trs['cash_in'])}}</td>
                                    </tr>
                                </table>
                            </div>
                            {{-- end paypal deposit--}}
                            @endif

                            @if($trs['type'] == "Transfered")
                            {{--For transfar--}}
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        @if(!empty($trs['transfar_to']))
                                            <th class="font-weight-bold">Transfar To</th>
                                            <td>{{$trs['transfar_to']}}</td>
                                        @endif
                                        @if(!empty($trs['transfar_from']))
                                            <th class="font-weight-bold">Transfar From</th>
                                            <td>{{$trs['transfar_from']}}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Transfar Amount</th>
                                        @if(!empty($trs['transfar_to']))
                                            <td>{{Crypt::decrypt($trs['cash_out'])}}</td>
                                        @endif
                                        @if(!empty($trs['transfar_from']))
                                            <td>{{Crypt::decrypt($trs['cash_in'])}}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Status</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4 pt-3">
                                <p class="font-weight-bold">Note</p>
                                <p>{{$trs['note']}}</p>
                            </div>
                            {{-- end transfar--}}
                            @endif

                            @if($trs['method'] == "Bank Transfar" && $trs['type'] == "Withdraw")
                            {{--For bank withdraw--}}
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">Withdraw Method</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Withdraw Amount</th>
                                        <td>{{Crypt::decrypt($trs['cash_out'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Status</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Note</th>
                                        <td>{{$trs['note']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">Bank Name</th>
                                        <td>{{$trs['bank_name']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Account Name</th>
                                        <td>{{$trs['account_name']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">IBAN No</th>
                                        <td>{{$trs['iban_no']}}</td>
                                    </tr>
                                </table>
                            </div>
                            {{-- end bank withdraw--}}
                            @endif

                            @if($trs['method'] == "PayPal" && $trs['type'] == "Withdraw")
                            {{--For paypal withdraw--}}
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">Withdraw Method</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Paypal Email</th>
                                        <td>{{$trs['paypal_email']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Withdraw Amount (SAR)</th>
                                        <td>{{Crypt::decrypt($trs['cash_out'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Status</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">Exchange Rate</th>
                                        <td>{{$trs['exchange_rate']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Fees</th>
                                        <td>{{$trs['fees']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Received Amount (USD)</th>
                                        <td>{{Crypt::decrypt($trs['total_recive'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Note</th>
                                        <td>{{$trs['note']}}</td>
                                    </tr>
                                </table>
                            </div>
                            {{-- end paypal withdraw--}}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-->


        </div>
    </div>
    <!-- CONTAINER END -->

@endsection
@section('query')

    <script type="text/javascript">
        $('#print').click(function () {
            $('#printdata').printThis({
                debug: false,
                importCSS: true,
                importStyle: true,
                printContainer: true,
                loadCSS: "",
                pageTitle: "Bills Printing",
                removeInline: false,
                removeInlineSelector: "*",
                printDelay: 333,
                header: "",
                footer: null,
                base: false,
                formValues: true,
                canvas: false,
                doctypeString: '<!DOCTYPE html',
                removeScripts: false,
                copyTagClasses: false,
                beforePrintEvent: null,
                beforePrint: null,
                afterPrint: null
            });
        })
    </script>
@endsection