@php
    function invoice_status($st)
   {
    if($st == "New Order")
        {
            echo "text-warning";
        }
        elseif($st == "Pending")
        {
            echo "text-secondary";
        }
        elseif($st == "Processing")
        {
            echo "text-success";
        }
        elseif($st == "Cancelled")
        {
            echo "text-secondary";
        }
        elseif($st == "Refund Requested")
        {
            echo "text-secondary";
        }
        elseif($st == "Refund Approved")
        {
            echo "text-secondary";
        }
        elseif($st == "Refunded")
        {
            echo "text-secondary";
        }
        elseif($st == "Complete" || $st == "Completed")
        {
            echo "text-info";
        }
   }
function invoice_status_lang($st)
{
    if($st == "Processing")
    {
        echo __('messages.processing');
    }
    elseif($st == "Complete")
    {
        echo __('messages.complete');
    }
    else{
        echo __('messages.reject');
    }
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
                        <h4 class="page-title mb-0 text-primary">{{__('messages.refunded-orders')}}</h4>
                    </div>
                </div>
                <!--End Page header-->
                <!--div-->
                <div class="card">
                    <div class="card-body">
                        <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                            <thead class="border-bottom-0 pt-3 pb-3">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('messages.order-no')}}</th>
                                <th>{{__('messages.warehouse-name')}}</th>
                                <th>{{__('messages.platform')}}</th>
                                <th>{{__('messages.shipping-company')}}</th>
                                <th>{{__('messages.total')}}</th>
                                <th>{{__('messages.status')}}</th>
                                <th>{{__('messages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach($orders as $order)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$order['order'][0]->order_no}}</td>
                                    @php
                                        $warehouse = \App\Models\Warehouse::where('id',$order['order'][0]->order_warehouse_id)->first();
                                    @endphp
                                    <td> @if($warehouse){{$warehouse->warehouse_id}}/{{$warehouse->warehouse_name}}@else @endif</td>
                                    <td>{{ucfirst($order['order'][0]->platform)}}</td>
                                    <td>{{$order['order'][0]->company_name}}</td>
                                    <td>{{$order['order'][0]->total}}</td>
                                    <td>
                                        <span class="font-weight-bold  ms-auto {{invoice_status($order->status)}}">{{invoice_status_lang($order->status)}}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <div class="" style="">
                                                <a href="{{route('admin.refunded.order.view',$order->id)}}"><i class="fa fa-eye"></i> {{__('messages.view')}}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        <!--/div-->
    </div>
    <!-- CONTAINER END -->
@endsection
