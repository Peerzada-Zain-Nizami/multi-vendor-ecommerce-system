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
@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.transaction-detail')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!-- Row -->
            <div class="row">
                <div class="card">
                    <div class="card-header justify-content-end">
                        <button id="print" class="btn btn-primary"><i class="fa fa-print"></i> {{__('messages.print')}}</button>
                    </div>
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
                                        <th class="font-weight-bold">{{__('messages.fees')}}</th>
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
                        @if($trs['status'] == "Rejected")
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>
                                                <label class="font-weight-bold form-label">{{__('messages.rejection-reason')}}</label>
                                                @php
                                                    $trs_reason = \App\Models\TrsReason::where('trs_id',$trs['id'])->first();
                                                @endphp
                                                <p>{{ucwords(strtolower($trs_reason->reason))}}</p>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                            </div>
                        @endif
                    </div>
                    @if($trs['status'] == "Rejected")
                        <div class="card-footer text-end">
                            <button id="{{$trs['id']}}" class="btn btn-primary click-modal"><i class="fa fa-edit"></i> {{__('messages.edit')}}</button>
                        </div>
                    @endif
                </div>
            </div>
            <!-- End Row-->
        </div>
    </div>
    <!-- CONTAINER END -->
    <div class="modal fade" id="edit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.update-deposit-details')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="error">
                    </div>
                    <input id="transaction_id" type="number" hidden />
                    <div class="col-md-12 position-relative">
                        <label class="form-label">{{__('messages.transaction-ID')}}</label>
                        <input type="text" id="trs_id" class="form-control" readonly />
                    </div>
                    <div class="col-md-12 position-relative">
                        <label class="form-label">{{__('messages.attach-proof-of-deposit-slip')}}</label>
                        <input type="file" class="form-control" name="file" id="myfile" />
                    </div>
                    <div class="col-md-12 position-relative">
                        <label class="form-label">{{__('messages.deposit-amount')}}</label>
                        <input type="number" id="deposit_amount" class="form-control" readonly />
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update">{{__('messages.update')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('query')

    <script type="text/javascript">

        $(document).on('click','.click-modal',function () {
            $('#error').html('');
            $('#error').removeClass('alert alert-light-danger');
            var id = $(this).attr("id");
            $.ajax({
                url:"{{route('seller.trans.get')}}",
                type:"POST",
                data:{id},
                success:function(data){
                    console.log(data);
                    $('#transaction_id').val(data.record.transaction_id);
                    $('#trs_id').val(data.record.method_trs_id);
                    $.ajax({
                        url:"{{route('seller.decrypt.value')}}",
                        type:"POST",
                        data:{val : data.record.cash_in},
                        success:function(value){
                            $('#deposit_amount').val(value.data);
                        }
                    })
                    $('#edit').modal('toggle');
                }
            })
        });
        $(document).ready(function () {
            // Variable to store the selected file
            var selectedFile = '';
            $('#myfile').change(function(e) {
                var file_data = getFileNameWithExt(e);
                selectedFile = e.target.files[0];
                const ext = file_data.ext.toUpperCase();
                if (ext != 'PDF' && ext != 'JPG' && ext != 'JPEG' && ext != 'PNG') {
                    alert('Please choose only required file type');
                    e.target.value = null;
                    return;
                }
            });
            function getFileNameWithExt(event) {
                if (!event || !event.target || !event.target.files || event.target.files.length === 0) {
                    return;
                }
                const name = event.target.files[0].name;
                const lastDot = name.lastIndexOf('.');
                const fileName = name.substring(0, lastDot);
                const ext = name.substring(lastDot + 1);
                var data = {name: fileName, ext: ext};
                return data;
            }

            $('.update').click(function () {
                $('#error').html('');
                $('#error').removeClass('alert alert-light-danger');

                var transaction_id = $('#transaction_id').val();

                if (selectedFile === '') {
                    $('#error').addClass('alert alert-light-danger');
                    $('#error').append('<li>'+"Please select a file"+'</li>');
                    return false;
                }
                var formData = new FormData();
                formData.append('myfile', selectedFile);
                formData.append('id', transaction_id);

                $.ajax({
                    url:'{{route('seller.deposit.update')}}',
                    type:'post',
                    data:formData,
                    processData: false,
                    contentType: false,
                    success:function(response){
                        if(response.status == 200){
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
