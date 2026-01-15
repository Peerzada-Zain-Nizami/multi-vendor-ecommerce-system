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
       }elseif($st == "Received" || $st == "Accepted" || $st == "New Return")
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
    if ($st == 'New Order') {
            echo __('messages.new-order');
        } elseif ($st == 'Pending') {
            echo __('messages.pending');
        } elseif ($st == 'Processing') {
            echo __('messages.processing');
        }
        elseif($st == "Refunded")
        {
            echo __('messages.refunded');
        }
        elseif ($st == 'Complete' || $st == 'Completed') {
            echo __('messages.complete');
        } elseif ($st == 'New Order') {
            echo __('messages.new-order');
        } elseif ($st == 'Packed') {
            echo __('messages.packed');
        } elseif ($st == 'refunded') {
            echo __('messages.refunded');
        } elseif ($st == 'return-received') {
            echo __('messages.return-received');
        } elseif ($st == 'Order Returned') {
            echo __('messages.order-returned');
        } elseif ($st == 'return-cancelled') {
            echo __('messages.return-cancelled');
        }
        elseif($st == "Onway")
       {
        echo __('messages.onway');
       } elseif ($st == 'cancelled' || $st == 'Cancelled') {
            echo __('messages.cancelled');
        } elseif ($st == 'Order Cancelled') {
            echo __('messages.order-canceled');
        } elseif ($st == 'DATA RECEIVED') {
            echo __('messages.DATA-RECEIVED');
        } elseif ($st == 'Cancelled by Seller') {
            echo __('messages.cancelled-by-seller');
        } elseif ($st == 'return-approved') {
            echo __('messages.return-approved');
        } elseif ($st == 'Return and Refund') {
            echo __('messages.return-and-refund');
        } elseif ($st == 'Return Received') {
            echo __('messages.return-received');
        } elseif ($st == 'return-requested') {
            echo __('messages.return-requested');
        } elseif ($st == 'Dispatch') {
            echo __('messages.dispatch');
        } elseif ($st == 'Reject') {
            echo __('messages.reject');
        } elseif ($st == 'Cancel') {
            echo __('messages.cancel');
        } elseif ($st == 'Dispatched') {
            echo __('messages.dispatched');
        } elseif ($st == 'Received') {
            echo __('messages.received');
        } elseif ($st == 'Accepted') {
            echo __('messages.accepted');
        } elseif ($st == 'DEPARTED FORM ORIGIN') {
            echo __('messages.DEPARTED-FORM-ORIGIN');
        } elseif ($st == 'Collected from Retail') {
            echo __('messages.COLLECTED-FROM-RETAIL');
        } elseif($st == "Awaiting Collection")
       {
        echo __('messages.Awaiting-Collection');
       }elseif($st == "In Transit")
       {
        echo __('messages.in-transit');
       }elseif($st == "Delivery Attempted")
       {
        echo __('messages.Delivery-Attempted');
       }elseif($st == "SMSA Processing")
       {
        echo __('messages.SMSA-Processing');
       }elseif($st == "Return Initiated")
       {
        echo __('messages.Return-Initiated');
       }elseif ($st == 'PROOF OF DELIVERY CAPTURED') {
            echo __('messages.PROOF-OF-DELIVERY-CAPTURED');
        } elseif ($st == 'Out for Delivery') {
            echo __('messages.OUT-FOR-DELIVERY');
        } elseif ($st == 'PICKED UP') {
            echo __('messages.PICKED-UP');
        } elseif ($st == 'Process') {
            echo __('messages.process');
        } elseif ($st == 'CANCELLED ON CLIENTS REQUEST') {
            echo __('messages.CANCELLED-ON-CLIENTS-REQUEST');
        } elseif ($st == 'Resend') {
            echo __('messages.resend');
        } elseif ($st == 'Packing' || $st == 'Accept' || $st == 'Shipping Process') {
            echo __('messages.packing');
        } else {
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
                            <strong>Well done!</strong> {{Session::get('success')}}
                        </div>
                    @endif
                    @if (Session::has('danger'))
                    <div class="alert alert-light-danger" role="alert">
                        <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                        <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}} <a href="{{ route('wadmin.stockOut.place.view') }}" class="alert-link"><b> <u> Stock Out </u> </b></a>
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
                                            <td class="text-end"><span class="font-weight-bold  ms-auto {{payment_status($result->payment)}}">{{__('messages.No-need-to-receive')}}</span></td>
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
                                            <label class="form-label">Change Status</label>
                                            <select name="status" class="form-control select2 @if($errors->has('status'))is-invalid @endif">
                                                <option value="">Please Select</option>
                                                <option value="Reject" @if($result->status == "Reject") selected @endif>Approve Rejection</option>
                                                <option value="Resended" @if($result->status == "Resended") selected @endif>Resend</option>
                                                <option value="Cancel" @if($result->status == "Cancel") selected @endif>Cancel</option>

                                            </select>
                                            @if ($errors->has('status'))
                                                <span class="text-danger">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group text-center">
                                            <input type="submit" class="btn btn-primary" value="Save">
                                        </div>
                                    </form>
                                @else
                                    <form action="{{route('wadmin.invoice.return.status',['id'=>$result->id])}}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label class="form-label">Change Status</label>
                                            <select name="status" class="form-control select2 @if($errors->has('status'))is-invalid @endif">
                                                <option value="">Please Select</option>
                                                @if($result->status == "Accepted")
                                                    <option value="Process" @if($result->status == "Process") selected @endif>Process</option>
                                                @endif
                                                @if($result->status == "Process")
                                                    <option value="Onway" @if($result->status == "Onway") selected @endif>Onway</option>
                                                @endif
                                                {{-- @if($result->status == "Received")
                                                    <option value="Complete" @if($result->status == "Complete") selected @endif>Complete</option>
                                                @endif
                                                @if($result->status == "Process"|| $result->status == "Onway"|| $result->status == "Received" || $result->status == "Complete")
                                                @else
                                                    <option value="Cancel" @if($result->status == "Cancel") selected @endif>Cancel</option>
                                                @endif --}}
                                            </select>
                                            @if ($errors->has('status'))
                                                <span class="text-danger">{{ $errors->first('status') }}</span>
                                            @endif
                                        </div>
                                        <div class="form-group text-center">
                                            <input type="submit" class="btn btn-primary" value="Save">
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
    <!-- CONTAINER END -->

@endsection
