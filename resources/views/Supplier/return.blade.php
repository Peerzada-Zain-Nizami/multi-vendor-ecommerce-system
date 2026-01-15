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
    }elseif($st == "Process")
    {
        echo __('messages.process');
    }elseif($st == "Cancel")
    {
        echo __('messages.cancel');
    }elseif($st == "Received")
       {
        echo __('messages.received');
       }elseif($st == "Accepted")
       {
        echo __('messages.accepted');
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
                    <h4 class="page-title mb-0 text-primary">{{__('messages.return-invoices-management')}}</h4>
                </div>
            </div>
            <!--End Page header-->
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
        <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.invoices-details')}}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                        <table id="example" class="table table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{__('messages.invoice-no')}}</th>
                            <th>{{__('messages.total')}}</th>
                            <th>{{__('messages.refund-status')}}</th>
                            <th>{{__('messages.remaining')}}</th>
                            <th>{{__('messages.refund')}}</th>
                            <th>{{__('messages.return-status')}}</th>
                            <th>{{__('messages.return-type')}}</th>
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
                                <td>{{$result->total}}</td>
                                <td class="{{payment_status($result->payment)}}">{{payment_status_lang($result->payment)}}</td>
                                <td>{{$result->remaining}}</td>
                                <td>{{$result->total-$result->remaining}}</td>
                                <td class="{{invoice_status($result->status)}}">{{invoice_status_lang($result->status)}}</td>
                                <td>@if($result->type == "Partial Return") {{__('messages.partial-return')}} @else {{__('messages.full-return')}} @endif</td>
                                <td>{{$result->created_at}}</td>
                                <td><a href="{{route('supplier.return.view',['id'=> $result->id])}}" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->


    </div>
    </div>
    <!-- CONTAINER END -->

@endsection
