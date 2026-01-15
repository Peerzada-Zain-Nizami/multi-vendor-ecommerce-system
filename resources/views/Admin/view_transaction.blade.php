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
@extends('Admin.base')

@section('content')


    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.Transaction-detail')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="card">
                    <div class="card-header justify-content-end">
                        <button id="print" class="btn btn-primary"><i class="fa fa-print"></i> {{__('messages.print')}}</button>
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
                    <div class="card-body">
                        <div class="row" id="printdata">
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.transaction-ID')}}</th>
                                        <td>{{$trs['transaction_id']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.transaction-type')}}</th>
                                        <td>{{$trs['type']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.date')}}</th>
                                        <td>{{date('d-m-Y h:i:sa',strtotime($trs['created_at']))}}</td>
                                    </tr>
                                </table>
                            </div>
                            @if($trs['method'] == "Bank Deposit" && $trs['type'] == "Cash Deposit")
                            {{--For bank deposit--}}
                                <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.payment-method')}}</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.bank-slip-ID')}}</th>
                                        <td>{{$trs['method_trs_id']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.deposit-amount')}}</th>
                                        <td>{{Crypt::decrypt($trs['cash_in'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.status')}}</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4 pt-3">
                                <p class="font-weight-bold">{{__('messages.proof-images')}}</p>
                                <a href="{{asset('uploads/deposit_proof/'.$trs['attach'])}}" target="_blank"><img src="{{asset('uploads/deposit_proof/'.$trs['attach'])}}" width="250px" height="100px"></a>
                            </div>
                            {{-- end bank deposit--}}
                            @endif
                            @if($trs['method'] == "Manual" && $trs['type'] == "Cash Deposit")
                            {{--For bank deposit--}}
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.payment-method')}}</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.deposit-amount')}}</th>
                                        <td>{{Crypt::decrypt($trs['cash_in'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.status')}}</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4 pt-3">
                                <a class="btn btn-primary" href="{{asset('uploads/proof_slips/'.$trs['attach'])}}" target="_blank">{{__('messages.view-attach-image')}}</a>
                            </div>
                            {{-- end bank deposit--}}
                            @endif

                            @if($trs['method'] == "PayTabs" && $trs['type'] == "Cash Deposit")
                            {{--For paytab deposit--}}
                            <div class="col-sm-4 offset-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.payment-method')}}</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.paytab-transaction-ID')}}</th>
                                        <td>{{$trs['method_trs_id']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.deposit-amount-(SAR)')}}</th>
                                        <td>{{Crypt::decrypt($trs['cash_in'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.status')}}</th>
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
                                        <th class="font-weight-bold">{{__('messages.payment-method')}}</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.paypal-transaction-ID')}}</th>
                                        <td>{{$trs['method_trs_id']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.deposit-amount-(USD)')}}</th>
                                        <td>{{Crypt::decrypt($trs['deposit_amount'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.status')}}</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-3">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.exchange-rate')}}</th>
                                        <td>{{$trs['exchange_rate']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.fees')}}</th>
                                        <td>{{$trs['fees']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.received-amount-(SAR)')}}</th>
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
                                        @php
                                                $user = \App\Models\User::find($trs['transfar_to']);

                                                @endphp
                                            <th class="font-weight-bold">{{__('messages.transfar-to')}}</th>
                                           @if ($user)
                                             <td>{{$user->name}}</td>
                                            @endif
                                        @endif
                                            @if(!empty($trs['transfar_from']))
                                            @php
                                                    $user = \App\Models\User::find($trs['transfar_from']);
                                                @endphp
                                            <th class="font-weight-bold">{{__('messages.transfar-from')}}</th>
                                            @if ($user)
                                            <td>{{$user->name}}</td>
                                            @endif
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.transfar-amount')}}</th>
                                        @if(!empty($trs['transfar_to']))
                                            <td>{{Crypt::decrypt($trs['cash_out'])}}</td>
                                        @endif
                                        @if(!empty($trs['transfar_from']))
                                            <td>{{Crypt::decrypt($trs['cash_in'])}}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.status')}}</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4 pt-3">
                                <p class="font-weight-bold">{{__('messages.note')}}</p>
                                <p>{{$trs['note']}}</p>
                                <a class="btn btn-primary" href="{{asset('uploads/proof_slips/'.$trs['attach'])}}" target="_blank">{{__('messages.view-attach-image')}}</a>
                            </div>
                            {{-- end transfar--}}
                            @endif

                            @if($trs['type'] == "Invoice Payment")
                            {{--For transfar--}}
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        @if(!empty($trs['transfar_to']))
                                            <th class="font-weight-bold">{{__('messages.transfar-to')}}</th>
                                            <td>{{$trs['transfar_to']}}</td>
                                        @endif
                                        @if(!empty($trs['transfar_from']))
                                            <th class="font-weight-bold">{{__('messages.transfar-from')}}</th>
                                            <td>{{$trs['transfar_from']}}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.transfar-amount')}}</th>
                                        @if(!empty($trs['transfar_to']))
                                            <td>{{Crypt::decrypt($trs['cash_out'])}}</td>
                                        @endif
                                        {{-- @if(!empty($trs['transfar_from']))
                                            <td>{{Crypt::decrypt($trs['cash_out'])}}</td>
                                        @endif --}}
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.status')}}</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4 pt-3">
                                <p class="font-weight-bold">{{__('messages.note')}}</p>
                                <p>{{$trs['note']}}</p>
                                <a class="btn btn-primary" href="{{asset('uploads/proof_slips/'.$trs['attach'])}}" target="_blank">{{__('messages.view-attach-image')}}</a>
                            </div>
                            {{-- end transfar--}}
                            @endif

                            @if($trs['type'] == "Invoice Return Payment")
                            {{--For transfar--}}
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        @if(!empty($trs['transfar_to']))
                                            <th class="font-weight-bold">{{__('messages.transfar-to')}}</th>
                                            <td>{{$trs['transfar_to']}}</td>
                                        @endif
                                        @if(!empty($trs['transfar_from']))
                                            <th class="font-weight-bold">{{__('messages.transfar-from')}}</th>
                                            <td>{{$trs['transfar_from']}}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.transfar-amount')}}</th>
                                        @if(!empty($trs['transfar_to']))
                                            <td>{{Crypt::decrypt($trs['cash_in'])}}</td>
                                        @endif
                                        @if(!empty($trs['transfar_from']))
                                            <td>{{Crypt::decrypt($trs['cash_in'])}}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.status')}}</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4 pt-3">
                                <p class="font-weight-bold">{{__('messages.note')}}</p>
                                <p>{{$trs['note']}}</p>
                                <a class="btn btn-primary" href="{{asset('uploads/proof_slips/'.$trs['attach'])}}" target="_blank">{{__('messages.view-attach-image')}}</a>
                            </div>
                            {{-- end transfar--}}
                            @endif

                            @if($trs['method'] == "Bank Transfar" && $trs['type'] == "Withdraw")
                            {{--For bank withdraw--}}
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.withdraw-method')}}</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.withdraw-amount')}}</th>
                                        <td>{{Crypt::decrypt($trs['cash_out'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.status')}}</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.note')}}</th>
                                        <td>{{$trs['note']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.bank-name')}}</th>
                                        <td>{{$trs['bank_name']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.account-name')}}</th>
                                        <td>{{$trs['account_name']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.IBAN-No')}}</th>
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
                                        <th class="font-weight-bold">{{__('messages.withdraw-method')}}</th>
                                        <td>{{$trs['method']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.paypal-email')}}</th>
                                        <td>{{$trs['paypal_email']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.withdraw-amount-(SAR)')}}</th>
                                        <td>{{Crypt::decrypt($trs['cash_out'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.status')}}</th>
                                        <td>{{$trs['status']}}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-sm-4">
                                <table class="table table-borderless">
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.exchange-rate')}}</th>
                                        <td>{{$trs['exchange_rate']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">Fees</th>
                                        <td>{{$trs['fees']}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.received-amount-(USD)')}}</th>
                                        <td>{{Crypt::decrypt($trs['total_recive'])}}</td>
                                    </tr>
                                    <tr>
                                        <th class="font-weight-bold">{{__('messages.note')}}</th>
                                        <td>{{$trs['note']}}</td>
                                    </tr>
                                </table>
                            </div>
                            {{-- end paypal withdraw--}}
                            @endif
                        </div>
                    </div>
                    @if($trs['status'] == "Review")
                        <div class="card-footer text-end">
                            <button id="{{$trs['id']}}" class="btn btn-danger reject-confirm"><i class="fa fa-ban"></i> {{__('messages.reject')}}</button>
                            <a href="{{ route('admin.users.balance.approved',$trs['id']) }}" class="btn btn-primary"><i class="fa fa-check"></i> {{__('messages.approve')}}</a>
                        </div>
                    @endif
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div>
    <div class="modal fade" id="reason">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.add-rejection-reason')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="error">
                    </div>
                    <input id="trs_id" type="number" hidden />
                    <div class="col-md-12 position-relative">
                        <label class="form-label">{{__('messages.rejection-reason')}}</label>
                        <textarea id="myTextarea" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update">{{__('messages.submit')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('query')
    <script type="text/javascript">
        $('.reject-confirm').on('click', function (event) {
            var id = $(this).attr("id");
            swal({
                title: 'Are you sure?',
                text: 'You want to reject this Payment request!',
                icon: 'warning',
                buttons: ["Cancel", "Yes!"],
            }).then(function(value) {
                if (value) {
                    $('#error').html('');
                    $('#error').removeClass('alert alert-light-danger');
                    $('#trs_id').val(id);
                    $('#reason').modal('toggle');
                }
            });
        });

        $(document).ready(function () {
            $('.update').click(function () {
                $('#error').html('');
                $('#error').removeClass('alert alert-light-danger');
                var trs_id = $('#trs_id').val();
                var textareaValue = $('#myTextarea').val();

                if (textareaValue == '') {
                    $('#error').addClass('alert alert-light-danger');
                    $('#error').append('<li>'+"Please Add the reason of rejection."+'</li>');
                    return false;
                }
                if (textareaValue.length < 10) {
                    $('#error').addClass('alert alert-light-danger');
                    $('#error').append('<li>'+"Please type minimum 10 letters."+'</li>');
                    return false;
                }
                var data = {
                    id : trs_id,
                    reason : textareaValue,
                };

                console.log(data);
                $.ajax({
                    url:'{{route('admin.users.request.reject')}}',
                    type:'post',
                    data:data,
                    success:function(response){
                        console.log(response);
                        if(response.status == 200){
                            $('#reason').modal('hide');
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            location.reload(true);
                        }
                    }
                });
            });
        });

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
