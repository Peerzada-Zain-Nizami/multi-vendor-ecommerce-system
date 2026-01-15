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
    }elseif($st == "Cancel")
    {
        echo __('messages.cancel');
    }elseif($st == "Process")
    {
        echo __('messages.process');
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
{{-- this is reports side --}}
@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.return-reports')}}</h4>
                </div>
                <div class="page-rightheader">
                    <div class="btn-list">
                        <button onclick="show_hide()" class="btn btn-primary"><i class="fa fa-filter"></i>
                            {{__('messages.filter')}}</button>
                    </div>
                </div>
            </div>
            <!--End Page header-->
            <!--div-->
            <div class="card" id="filter" @if($filter != 1)style="display: none;"@endif>
                <div class="card-header">
                    <div class="card-title">{{__('messages.filter-data')}}</div>
                </div>
                <form action="{{route('supplier.return.reports.filter')}}" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('messages.payment-status')}}</label>
                                    <select class="form-control select2-show-search" name="payment">
                                        <option value="" selected disabled>{{__('messages.please-select')}}</option>
                                        <option value="Paid" {{(old('payment') == "Paid")?'selected':''}}>{{__('messages.paid')}}</option>
                                        <option value="Unpaid" {{(old('payment') == "Unpaid")?'selected':''}}>{{__('messages.unpaid')}}</option>
                                        <option value="Pending" {{(old('payment') == "Pending")?'selected':''}}>{{__('messages.pending')}}</option>
                                        <option value="Return" {{(old('payment') == "Return")?'selected':''}}>{{__('messages.return')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('messages.date-range')}}</label>
                                    <input placeholder="Please Select" id="date" name="date" type="text" value="{{(old('date'))?old('date'):''}}" class="form-control" autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-primary " value="{{__('messages.search')}}">
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.return-invoices-details')}}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                    <table id="example" class="table  table-hover table-bordered text-nowrap">
                        <thead class="border-bottom-0 pt-3 pb-3">
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{__('messages.invoice-no')}}</th>
                            <th>{{__('messages.total')}}</th>
                            <th>{{__('messages.payment-status')}}</th>
                            <th>{{__('messages.remaining')}}</th>
                            <th>{{__('messages.paid')}}</th>
                            <th>{{__('messages.return-status')}}</th>
                            <th>{{__('messages.created-at')}}</th>
                        </tr>
                        </thead>
                        <tbody id="result">
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
                                <td>{{date('d-m-Y',strtotime($result->created_at))}}</td>
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
@section('query')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        function show_hide()
        {
            var x = document.getElementById("filter");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
        $(function() {

            $('#date').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' / ' + picker.endDate.format('DD-MM-YYYY'));
            });

            $('#date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

        });
    </script>
@endsection
