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
       }elseif($st == "Received" || $st == "Accepted" || $st == "New Order")
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
       }elseif($st == "New Order")
       {
        echo __('messages.new-order');
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
                                    <th>{{__('fee')}}</th>
                                    <th>{{__('messages.order-quantity')}}</th>
                                    <th>{{__('messages.total')}}</th>
                                    <th>{{__('messages.returned')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                    $original_order = json_decode($result->original_order);
                                    $returns = json_decode($result->products);
                                @endphp
                                @foreach($original_order as $row)
                                    @php
                                        $cp = \App\Models\Product::find($row->product_id);
                                        $return = $returns[$loop->index];
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
                                        <td>{{$row->shipping_charges}}</td>
                                        <td>{{$row->quantity}}</td>
                                        <td>{{$row->rate*$row->quantity}}</td>
                                        <td>{{$row->quantity-$return->quantity}}</td>
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
                                        <td class="text-start">{{__('messages.sub-total')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$result->sub_total}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.shipping-charges')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$result->shipping_fee}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start fs-18">{{__('messages.total-bill')}}</td>
                                        <td class="text-end"><span class="ms-2 font-weight-bold  fs-22">{{$result->total}}</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h5>Additional Details</h5>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                    <tr>
                                        <td class="text-start">{{__('messages.payment')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto {{payment_status($result->payment)}}">{{payment_status_lang($result->payment)}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.invoice-status')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto {{invoice_status($result->status)}}">{{invoice_status_lang($result->status)}}</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                                @if($result->status == "Accepted" || $result->status == "Reject" || $result->status == "Process" || $result->status == "New Order")
                                <form id="change_status_form">
                                    <div id="add_g_error"></div>
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.change-status')}}</label>
                                        <select name="status" class="form-control select2 @if($errors->has('status'))is-invalid @endif">
                                            <option value="">{{__('messages.please-select')}}</option>
                                            @if($result->status == "New Order")
                                            <option value="Accepted" @if($result->status == "Accepted") selected @endif>{{__('messages.accepted')}}</option>
                                            @endif
                                            @if($result->status == "Accepted" || $result->status == "Process" || $result->status == "Onway")
                                                @else
                                            <option value="Reject" @if($result->status == "Reject") selected @endif>{{__('messages.reject')}}</option>
                                            @endif
                                            @if($result->status == "Accepted")
                                            <option value="Process" @if($result->status == "Process") selected @endif>{{__('messages.process')}}</option>
                                            @endif
                                            @if($result->status == "Process")
                                            <option value="Onway" @if($result->status == "Onway") selected @endif>{{__('messages.onway')}}</option>
                                            @endif
                                        </select>
                                        @if ($errors->has('status'))
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group text-center">
                                        {{-- <button type="button" data-id="{{ $result->id }}" class="btn btn-primary change_status">{{__('messages.save')}}</button> --}}
                                         <button class="btn btn-primary change_status" data-id="{{ $result->id }}" type="button">{{ __('messages.save') }}</button>

                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End Row-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="ms-3">{{__('messages.invoice-history')}}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="panel panel-primary tabs-style-3">
                                <div class="tab-menu-heading">
                                    <div class="tabs-menu ">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs">
                                            <li class=""><a href="#returns" class="active" data-bs-toggle="tab">{{__('messages.returns')}}</a></li>
                                            <li><a href="#transactions" data-bs-toggle="tab" class="">{{__('messages.transactions')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="returns">
                                            <div class="table-responsive-lg">
                                                <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                                                    <thead class="border-bottom-0 pt-3 pb-3">
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>{{__('messages.total')}}</th>
                                                        <th>{{__('messages.status')}}</th>
                                                        <th>{{__('messages.remaining')}}</th>
                                                        <th>{{__('messages.paid')}}</th>
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
                                                    @foreach($invoices as $result)
                                                        <tr>
                                                            <td class="text-center">{{$i++}}</td>
                                                            <td>{{$result->total}}</td>
                                                            <td class="{{payment_status($result->payment)}}">{{payment_status_lang($result->payment)}}</td>
                                                            <td>{{$result->remaining}}</td>
                                                            <td>{{$result->total-$result->remaining}}</td>
                                                            <td class="{{invoice_status($result->status)}}">{{invoice_status_lang($result->status)}}</td>
                                                            <td>@if($result->type == "Partial Return") {{__('messages.partial-return')}} @else {{__('messages.full-return')}} @endif</td>
                                                            <td>{{$result->created_at}}</td>
                                                            <td>
                                                                <a class="btn btn-primary" href="{{route('supplier.return.view',['id'=> $result->id])}}"><i class="fa fa-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="transactions">
                                            <h3>{{__('messages.transactions-comment')}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
   $(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.change_status').click(function(){
        var id = $(this).data('id'); // Assuming the button has a data-id attribute
        var route = '{{ route("supplier.invoice.status", ":id") }}';
        route = route.replace(':id', id);
                $.ajax({
                    url:route,
                    type:'POST',
                    data:$('#change_status_form').serialize(),
                    success:function(response){
                        console.log(response);
                        if (response.status == "fail")
                        {
                            $('#add_g_error').html('');
                            $('#add_g_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_g_error').append('<li>'+error+'</li>');
                            })
                        }
                         else{
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                            location.reload();
                        }, 1500);
                        }
                    }
                });
            });
});
</script>
@endsection

