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
       }elseif($st == "Onway")
       {
        echo __('messages.onway');
       }
    else{
        echo __('messages.reject');
    }
}
@endphp
@extends('W_admin.base')

@section('content')

<!--app-content open-->
<div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.company-invoices')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            @if (Session::has('success'))
            <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Well done!</strong> {{Session::get('success')}}
                </div>
            @endif
            @if (Session::has('danger'))
                <div class="alert alert-light-danger" role="alert">
                    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Oopps!</strong> {{Session::get('danger')}}
                </div>
            @endif
            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.invoices-details')}}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                        <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                            <thead class="border-bottom-0 pt-3 pb-3">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{__('messages.invoice-no')}}</th>
                                    <th>{{__('messages.supplier')}}</th>
                                    <th>{{__('messages.invoice-amount')}}</th>
                                    <th>{{__('messages.total-return')}}</th>
                                    <th>{{__('messages.invoice-status')}}</th>
                                    <th>{{__('messages.created-by')}}</th>
                                    <th>{{__('messages.received-by')}}</th>
                                    <th>{{__('messages.created-at')}}</th>
                                    <th>{{__('messages.action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                            @php
                                $total_return = \App\Models\CompanyReturn::where('invoice_no',$result->invoice_no)->where('status',"Complete")->sum('total');
                            @endphp
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$result->invoice_no}}</td>
                                <td>{{$result->suppliers_name[0]->name}}</td>
                                <td>{{$result->total}}</td>
                                <td>{{$total_return}}</td>
                                <td class="{{invoice_status($result->status)}}">{{invoice_status_lang($result->status)}}</td>
                                <td>
                                    @php
                                        $user = \App\Models\User::find($result->user_id);
                                        echo $user->name;
                                    @endphp
                                </td>
                                <td>
                                    @if(!empty($result->receiver_admin))
                                    @php
                                        $user1 = \App\Models\User::find($result->receiver_admin);
                                        echo $user1->name;
                                    @endphp
                                        @endif
                                </td>
                                <td>{{$result->created_at}}</td>

                                <td>
                                    <a class="btn btn-primary" href="{{route('wadmin.company.invoice.view',['id'=> $result->invoice_no])}}"><i class="fa fa-eye"></i></a>
                                </td>
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
