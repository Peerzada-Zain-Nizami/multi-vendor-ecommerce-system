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
       if($st == "Complete"||$st == "New Return"||$st == "Resended")
       {
           echo "text-success";
       }elseif($st == "Reject" || $st == "Cancel"|| $st == "Reject Request")
       {
           echo "text-danger";
       }elseif($st == "Received" || $st == "Accepted"  || $st == "New Order")
       {
        echo "text-info";
       }elseif($st == "Onway"||$st == "Process")
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
    }elseif($st == "Reject Request")
    {
        echo __('messages.reject');
    }elseif($st == "Reject")
    {
        echo __('messages.reject');
    }elseif($st == "Cancel")
    {
        echo __('messages.cancel');
    }elseif($st == "Received")
       {
        echo __('messages.received');
       }
       elseif($st == "New Order")
       {
        echo __('messages.new-order');
       }elseif($st == "New Return")
       {
        echo __('messages.new-return');
       }elseif($st == "Resended")
       {
        echo __('messages.return-resended');
       }elseif($st == "Accepted")
       {
        echo __('messages.accepted');
       }elseif($st == "Onway")
       {
        echo __('messages.onway');
       }elseif($st == "Process")
       {
        echo __('messages.process');
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
                    <h4 class="page-title mb-0 text-primary">#{{$result->invoice_no}} {{__('messages.invoice-details')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-8">
                    @if (Session::has('success'))
                        <div class="alert alert-light-success" role="alert">
                            <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                            <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="ms-3">{{__('messages.invoice-items')}}</span>
                            </div>
                        </div>
                        <div class="table-responsive-lg">
                            <table class="table card-table table-vcenter text-nowrap shopping-carttable">
                                <thead class="border-bottom-0 pt-3 pb-3 ">
                                <tr>
                                    <th>#</th>
                                    <th class="fs-15">{{__('messages.product')}}</th>
                                    <th>{{__('messages.product-name')}}</th>
                                    <th>{{__('messages.price')}}</th>
                                    <th>{{__('messages.old-quantity')}}</th>
                                    <th>{{__('messages.return-quantity')}}</th>
                                    <th>{{__('messages.total')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                    $product = json_decode($result->products);
                                @endphp
                                @foreach($product as $row)
                                    @php
                                        $cp = \App\Models\Product::find($row->product_id);
                                    @endphp
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td><img class='avatar avatar-lg br-7' src="{{asset('uploads/featured_images/'.$cp->featured_image)}}"></td>
                                        @php
                                            $data = \App\MyClasses\Helpers::get_lang($cp->product_name,$cp->id,"product",App::getLocale());
                                            $product_data = json_decode($data);
                                        @endphp
                                        <td>@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$cp->product_name}} @endif</td>
                                        <td>{{$row->rate}}</td>
                                        <td>{{$row->quantity}}</td>
                                        <td>{{$row->return_quantity}}</td>
                                        <td>{{$row->rate*$row->return_quantity}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
                <div class="col-lg-12 col-xl-4 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.invoice-summary')}}</div>
                        </div>
                        <div class="card-body">
                            <h5>{{__('messages.amount-details')}}</h5>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                    <tr>
                                        <td class="text-start fs-18">{{__('messages.total-bill')}}</td>
                                        <td class="text-end"><span class="ms-2 font-weight-bold  fs-22">{{$result->total}}</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h5>{{__('messages.additional-details')}}</h5>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                    <tr>
                                        <td class="text-start">{{__('messages.payment')}}</td>
                                        @if($result->payment == "Return")
                                            <td class="text-end"><span class="font-weight-bold  ms-auto {{payment_status($result->payment)}}">{{__('messages.no-need-to-receive')}}</span></td>
                                        @else
                                            <td class="text-end"><span class="font-weight-bold  ms-auto {{payment_status($result->payment)}}">{{payment_status_lang($result->payment)}}</span></td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.invoice-status')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto {{invoice_status($result->status)}}">{{invoice_status_lang($result->status)}}</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                                @if($result->status == "Cancel" || $result->status == "Reject" || $result->status == "Onway" || $result->status == "Complete")
                                @elseif($result->status == "Reject Request")
                                    <form action="{{route('admin.invoice.return.status',['id'=>$result->id])}}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.change-status')}}</label>
                                            <select name="status" class="form-control select2 @if($errors->has('status'))is-invalid @endif">
                                                <option value="">{{__('messages.please-select')}}</option>
                                                <option value="Reject" @if($result->status == "Reject") selected @endif>{{__('messages.approve-rejection')}}</option>
                                                <option value="Resended" @if($result->status == "Resended") selected @endif>{{__('messages.resend')}}</option>
                                                <option value="Cancel" @if($result->status == "Cancel") selected @endif>{{__('messages.cancel')}}</option>

                                            </select>
                                            @if ($errors->has('status'))
                                                <span class="text-danger">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group text-center">
                                            <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                                        </div>
                                    </form>
                                @else
                                    <form action="{{route('admin.invoice.return.status',['id'=>$result->id])}}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.change-status')}}</label>
                                            <select name="status" class="form-control select2 @if($errors->has('status'))is-invalid @endif">
                                                <option value="">{{__('messages.please-select')}}</option>
                                                @if($result->status == "Accepted")
                                                    <option value="Process" @if($result->status == "Process") selected @endif>{{__('messages.process')}}</option>
                                                @endif
                                                @if($result->status == "Process")
                                                    <option value="Onway" @if($result->status == "Onway") selected @endif>{{__('messages.onway')}}</option>
                                                @endif
                                                @if($result->status == "Received")
                                                    <option value="Complete" @if($result->status == "Complete") selected @endif>{{__('messages.complete')}}</option>
                                                @endif
                                                @if($result->status == "Process"|| $result->status == "Onway"|| $result->status == "Received" || $result->status == "Complete")
                                                @else
                                                    <option value="Cancel" @if($result->status == "Cancel") selected @endif>{{__('messages.cancel')}}</option>
                                                @endif
                                            </select>
                                            @if ($errors->has('status'))
                                                <span class="text-danger">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group text-center">
                                            <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End Row-->
        </div>
        <!--/div-->


    </div>
    </div>
    <!-- CONTAINER END -->

@endsection
