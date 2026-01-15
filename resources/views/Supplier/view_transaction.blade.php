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
@extends('Supplier.base')

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
