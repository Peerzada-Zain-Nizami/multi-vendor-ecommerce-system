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
       }elseif($st == "New Return")
       {
        echo __('messages.new-return');
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
                            <h5>{{__('messages.additional-details')}}</h5>
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
                                @if($result->status == "Onway")
                                <form action="{{route('wadmin.invoice.status',['id'=>$result->id])}}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                    <label class="form-label">{{__('messages.change-status')}}</label>
                                    <select name="status" class="form-control select2 @if($errors->has('status'))is-invalid @endif">
                                        <option value="">{{__('messages.please-select')}}</option>
                                            <option value="Received" @if($result->status == "Received") selected @endif>{{__('messages.received')}}</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">{{__('messages.warehouse')}}</label>
                                        <select name="warehouse_id" class="form-control select2 select2-show-search @if($errors->has('warehouse_id'))is-invalid @endif">
                                            <option value="">{{__('messages.please-select')}}</option>
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{$warehouse->id}}">{{$warehouse->warehouse_name}}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('warehouse_id'))
                                            <span class="text-danger">{{ $errors->first('warehouse_id') }}</span>
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
                                            <li class=""><a href="#bar_code" class="active" data-bs-toggle="tab">{{__('messages.barcode')}}</a></li>
                                            <li class=""><a href="#returns" data-bs-toggle="tab">{{__('messages.returns')}}</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="bar_code">
                                            <div class="table-responsive-lg">
                                                <div class="text-end mb-2">
                                                    <a href="{{route('wadmin.all.barcode.download',['id'=>$result->invoice_no])}}" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> {{__('messages.print-all')}}</a>
                                                </div>
                                                    <table class="table table-responsive-lg-sm table-hover table-bordered text-nowrap">
                                                    <thead class="border-bottom-0 pt-3 pb-3">
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>{{__('messages.product')}}</th>
                                                        <th>{{__('messages.product-name')}}</th>
                                                        <th>{{__('messages.barcode')}}</th>
                                                        <th>{{__('messages.QRcode')}}</th>
                                                        <th>{{__('messages.action')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                        $i = 1;
                                                        $warehouses = \App\Models\Warehouse::where('id',$result->warehouse_id)->with('blocks','racks','shelfs')->first();
                                                    @endphp
                                                    @foreach($results as $result)
                                                        @php
                                                            $cp = \App\Models\Product::find($result->product_id);
                                                        @endphp
                                                        <tr>
                                                            <td class="text-center">{{$i++}}</td>
                                                            <td><img class='avatar avatar-lg br-7' src="{{asset('uploads/featured_images/'.$cp->featured_image)}}"></td>
                                                            @php
                                                                $data = \App\MyClasses\Helpers::get_lang($cp->product_name,$cp->id,"product",App::getLocale());
                                                                $product_data = json_decode($data);
                                                            @endphp
                                                            <td>@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$cp->product_name}} @endif</td>
                                                            <td>
                                                                <img src="data:image/png;base64,{{ DNS1D::setStorPath(__DIR__.'/cache/')->getBarcodePNG((string)$result->product_id, 'C128')}}" alt="sultan">
                                                            </td>
                                                            <td>
                                                                <img src="data:image/png;base64,{{ DNS2D::setStorPath(__DIR__.'/cache/')->getBarcodePNG((string)$result->product_id, 'QRCODE')}}" alt="sultan">
                                                            </td>
                                                            <td>
                                                                <a href="{{route('wadmin.barcode.download',['type'=>"product",'id'=>$result->stock_ins_id,'qty'=>$result->stock])}}" target="_blank" class="btn btn-warning"><i class="fa fa-print"></i></a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="returns">
                                            <div class="table-responsive-lg">
                                                <table class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                                                    <thead class="border-bottom-0 pt-3 pb-3">
                                                    <tr>
                                                        <th class="text-center">#</th>
                                                        <th>{{__('messages.return-total')}}</th>
                                                        <th>{{__('messages.return-status')}}</th>
                                                        <th>{{__('messages.return-type')}}</th>
                                                        <th>{{__('messages.created-by')}}</th>
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
                                                            <td class="{{invoice_status($result->status)}}">{{invoice_status_lang($result->status)}}</td>
                                                            <td>{{$result->type}}</td>
                                                            <td>
                                                                @php
                                                                    $user = \App\Models\User::find($result->user_id);
                                                                    echo $user->name;
                                                                @endphp
                                                            </td>
                                                            <td>{{$result->created_at}}</td>
                                                            <td>
                                                                <a class="btn btn-primary" href="{{route('wadmin.company.return.view',['id'=> $result->invoice_no])}}"><i class="fa fa-eye"></i></a>
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
                </div>
            </div>
        </div>
        <!--/div-->


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
        $(document).ready(function () {
            $('#placement_edit').click(function () {
                var id = '{{$result->warehouse_id}}';
                var route = "{{route('wadmin.warehouse.overview')}}";
                $(this).attr('disabled','disabled');
                $('.block_list').html('');
                $.get(route+'-get_block_list/'+ id, function (blocks) {
                    $('.block_list').append('<option value="" disabled selected>Please Select</option>');
                    $.each(blocks,function (key,rack) {
                        $('.block_list').append('<option value="'+rack.id+'">'+rack.block_code+'</option>');
                    })
                });
                $('.rack_list').html('');
                $('.shelf_list').html('');
                $('#up_btn').removeAttr('disabled');
                $('#placement_cancel').removeAttr('disabled');
                $('#placement_form select').removeAttr('disabled');
            });
            $('#placement_cancel').click(function () {
                location.reload();
            });
            $('.block_list').change(function () {
                var tr = $(this).closest('tr').attr('id');
                var value = $(this).val();
                var route = "{{route('wadmin.warehouse.overview')}}";
                var select = '#'+tr+' .rack_list';
                $(select).html('');
                $.get(route+'-get_rack_list/'+ value, function (blocks) {
                    $(select).append('<option value="" disabled selected>Please Select</option>');
                    $.each(blocks,function (key,rack) {
                        $(select).append('<option value="'+rack.id+'">'+rack.rack_code+'</option>');
                    })
                });
            });
            $('.rack_list').change(function () {
                var tr = $(this).closest('tr').attr('id');
                var value = $(this).val();
                var route = "{{route('wadmin.warehouse.overview')}}";
                var select = '#'+tr+' .shelf_list';
                $(select).html('');
                $.get(route+'-get_shelf_list/'+ value, function (blocks) {
                    $(select).append('<option value="" disabled selected>Please Select</option>');
                    $.each(blocks,function (key,rack) {
                        $(select).append('<option value="'+rack.id+'">'+rack.shelf_code+'</option>');
                    })
                });
            });
            $('.update').click(function () {
                var data ={
                    'id':$('#p_id').val(),
                    'selling_price':$('#selling_price').val(),
                    'discount':$('#discount').val(),
                    'fee':$('#fee').val(),
                    'status':$('#status').val(),
                    'retail_price':$('#rp').val(),
                    'suggested_price':$('#sp').val(),
                };
                $.ajax({
                    url:'{{route('stock.catalog.update')}}',
                    type:'post',
                    data:data,
                    success:function(response){
                        if (response.status == 400)
                        {
                            $('#error').html('');
                            $('#error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#error').append('<li>'+error+'</li>');
                            })
                        }
                        else{
                            $('#error').html('');
                            $('#error').removeClass('alert alert-light-danger');
                            $('#success').html(
                                '<div class="alert alert-light-success" role="alert">\n' +
                                '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Well done! </strong>'+response.message+'\n' +
                                '</div>');
                            $('#edit').modal('hide');
                            location.href = '{{route('stock.catalog')}}';
                        }
                    }
                });
            });

        });
    </script>
@endsection
