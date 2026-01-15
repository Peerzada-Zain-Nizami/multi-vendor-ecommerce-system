@php
    function payment_status($st)
   {
       if($st == "Paid")
       {
           echo "text-success";
       }elseif($st == "Pending")
       {
           echo "text-warning";
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
       if($st == "Complete")
       {
           echo "text-success";
       }elseif($st == "Reject" || $st == "Cancel")
       {
           echo "text-danger";
       }elseif($st == "Received" || $st == "Accepted")
       {
        echo "text-info";
       }elseif($st == "Onway")
       {
        echo "text-muted";
       }
       else{
           echo "text-warning";
       }
   }
function invoice_status_lang($st)
{
    if($st == "Complete")
    {
        echo __('messages.complete');
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
       }elseif($st == "Process")
    {
        echo __('messages.process');
    }elseif($st == "Onway")
       {
        echo __('messages.onway');
       }
    else{
        echo __('messages.reject');
    }
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
                    <h4 class="page-title mb-0 text-primary">{{__('messages.supplier-dashboard')}}</h4>
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
                    <div class="card overflow-hidden dash1-card border-0 dash3">
                        <div class="card-body">
                            <span class="fs-14">{{__('messages.cancel/rejected')}}</span>
                            <h2 class="mb-2 mt-1 number-font carn2 font-weight-bold">{{$cancel_reject}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row-1 -->

            <!-- Row-2 -->
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
            <!-- End Row-2 -->

            <!--Row-->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('messages.invoice-summary')}}</h3>
                            <div class="card-options">
                                <a href="{{route('supplier.myorder')}}" class="btn btn-sm btn-primary">{{__('messages.view-all')}}</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table id="" class="table table-hover table-bordered text-nowrap key-buttons">
                                    <thead class="border-bottom-0 pt-3 pb-3">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>{{__('messages.invoice-no')}}</th>
                                        <th>{{__('messages.sub-total')}}</th>
                                        <th>{{__('messages.fee')}}</th>
                                        <th>{{__('messages.total')}}</th>
                                        <th>{{__('messages.payment-status')}}</th>
                                        <th>{{__('messages.remaining')}}</th>
                                        <th>{{__('messages.paid')}}</th>
                                        <th>{{__('messages.invoice-status')}}</th>
                                        <th>{{__('messages.created-at')}}</th>
                                        <th>{{__('messages.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach($results as $result)
                                        <tr>
                                            <td class="text-center">{{$i++}}</td>
                                            <td>{{$result->invoice_no}}</td>
                                            <td>{{$result->sub_total}}</td>
                                            <td>{{$result->shipping_fee}}</td>
                                            <td>{{$result->total}}</td>
                                            <td class="{{payment_status($result->payment)}}">{{payment_status_lang($result->payment)}}</td>
                                            <td>{{$result->remaining}}</td>
                                            <td>{{$result->paid}}</td>
                                            <td class="{{invoice_status($result->status)}}">{{invoice_status_lang($result->status)}}</td>
                                            <td>{{$result->created_at}}</td>
                                            <td>
                                                <a href="{{route('supplier.myorder.view',['id'=> $result->invoice_no])}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>
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
