{{--Functions--}}
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
       if($st == "Complete" || $st == "completed" || $st == "Completed")
       {
           echo "text-success";
       }elseif($st == "Reject" || $st == "Cancel"|| $st == "cancelled"|| $st == "Canceled by Admin")
       {
           echo "text-danger";
       }elseif($st == "Order Canceled by Seller")
       {
       }elseif($st == "Received" || $st == "Accepted" || $st == "DATA RECEIVED" || $st == "Accept" || $st == "Resend")
       {
        echo "text-info";
       }elseif($st == "Packing")
       {
        echo "text-muted";
       }elseif($st == "CANCELLED ON CLIENTS REQUEST")
       {
        echo "text-muted";
       }elseif($st == "Dispatch" || $st == "DATA RECEIVED")
       {
        echo "text-success";
       }
       else{
           echo "text-warning";
       }
   }
function invoice_status_lang($st)
{
    if($st == "Complete" || $st == "completed" || $st == "Completed")
    {
        echo __('messages.complete');
    }elseif($st == "New Order")
    {
        echo __('messages.new-order');
    }elseif($st == "Dispatch")
    {
        echo __('messages.dispatch');
    }elseif($st == "Reject")
    {
        echo __('messages.reject');
    }elseif($st == "Cancel")
    {
        echo __('messages.cancel');
    }elseif($st == "cancelled")
    {
        echo __('messages.cancelled');
    }elseif($st == "Order Canceled by Seller")
    {
    }elseif($st == "Canceled by Admin")
    {
        echo __('messages.canceled-by-admin');
    }elseif($st == "Resend")
       {
        echo __('messages.resend');
       }elseif($st == "Received")
       {
        echo __('messages.received');
       }elseif($st == "DATA RECEIVED")
       {
        echo __('messages.DATA-RECEIVED');
       }elseif($st == "Accepted" || $st == "Accept")
       {
        echo __('messages.accepted');
       }elseif($st == "Process" || $st == "Shipping Process")
    {
        echo __('messages.process');
    }elseif($st == "Packing")
       {
        echo __('messages.packing');
       }elseif($st == "CANCELLED ON CLIENTS REQUEST")
       {
        echo __('messages.CANCELLED-ON-CLIENTS-REQUEST');
       }
       elseif($st == "DATA RECEIVED")
       {
        echo __('messages.DATA-RECEIVED');
       }
    else{
        echo __('messages.reject');
    }
}
@endphp
{{--end Functions--}}
@extends('W_admin.base')
{{--start main content--}}
@section('content')
    <!--app-content open-->
        <div class="app-content main-content">
            <div class="side-app">
                <!--Page header-->
                <div class="page-header">
                    <div class="page-leftheader">
                        <h4 class="page-title mb-0 text-primary">{{__('messages.order-tracking')}}</h4>
                    </div>
                </div>
                <!--End Page header-->

                <!--Messages-->
                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <li class="text-danger">{{$error}}</li>
                    @endforeach
                @endif
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
            <!--end Messages-->

                <!--Row-->
                <div class="row">
                    {{--first part--}}
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xl-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <span class="ms-3">{{__('messages.order-detail')}}</span>
                                </div>
                            </div>
                            <div class="table-responsive-lg">
                                <table class="table card-table table-responsive-lg-sm table-vcenter text-nowrap shopping-carttable">
                                    <thead class="border-bottom-0 pt-3 pb-3 ">
                                    <tr>
                                        <th class="fs-15">{{__('messages.product')}}</th>
                                        <th>{{__('messages.product-name')}}</th>
                                        <th>{{__('messages.price')}}</th>
                                        <th>{{__('messages.tax')}}%</th>
                                        <th>{{__('messages.tax-price')}}</th>
                                        <th>{{__('messages.discount')}}</th>
                                        <th>{{__('messages.sub-total')}}</th>
                                        <th>{{__('messages.quantity')}}</th>
                                        <th>{{__('messages.total')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody >
                                    @php
                                        $json_products = json_decode($datas->product);
                                        $sub_total = array();
                                        $tax_sub_total = array();
                                    @endphp
                                    @foreach($json_products as $json_product)
                                        <tr>
                                            @php
                                                $product_id = $json_product->product_id;
                                                $product = \App\Models\Product::where('id',$product_id)->first();
                                                $address = json_decode($datas->shipping_address);
                                            @endphp
                                            <td class="fs-15"><img class="avatar avatar-lg br-7" src="{{asset('uploads/featured_images/'.$product->featured_image)}}"></td>
                                            <td>{{$product->product_name}}</td>
                                            <td>{{$json_product->rate}}</td>
                                            <td>{{$json_product->tax}}</td>
                                            <td>{{$json_product->tax_price}}</td>
                                            <td>{{$json_product->discount}}</td>
                                            <td>{{$json_product->total}}</td>
                                            <td>{{$json_product->quantity}}</td>
                                            <td>{{$json_product->sub_total}}</td>
                                            @php
                                                $sub_total[] = $json_product->sub_total;
                                                $tax_sub_total[] = $json_product->tax_price*$json_product->quantity;
                                            @endphp
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <span class="ms-3">{{__('messages.recent-activity')}}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                @if(count($tracking_data) == 5)
                                    <ul>
                                        <li class="mb-5 mt-5">
                                            <div>
                                                <span class="activity-timeline bg-primary text-white">1</span>
                                                <div class="activity-timeline-content">
                                                    <span class="font-weight-normal1 fs-13">{{__('messages.air-way-bill-no')}}</span>
                                                    <p class="text-info fs-12 mt-1"> {{$tracking_data['awbNo']}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-5">
                                            <div>
                                                <span class="activity-timeline bg-success text-white">2</span>
                                                <div class="activity-timeline-content">
                                                    <span class="font-weight-normal1 fs-13">{{__('messages.activity')}}</span>
                                                    <p class="text-danger fs-12 mt-1">{{$tracking_data['Activity']}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-5">
                                            <div>
                                                <span class="activity-timeline bg-warning text-white">3</span>
                                                <div class="activity-timeline-content">
                                                    <span class="font-weight-normal1 fs-13">{{__('messages.detail')}}</span>
                                                    <p class="text-success fs-12 mt-1">{{$tracking_data['Details']}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-5">
                                            <div>
                                                <span class="activity-timeline bg-info text-white">4</span>
                                                <div class="activity-timeline-content">
                                                    <span class="font-weight-normal1 fs-13">{{__('messages.date')}}</span>
                                                    <p class="text-warning fs-12 mt-1">{{$tracking_data['Date']}}</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                @else
                                    <ul>
                                        <li class="mb-5 mt-5">
                                            <div>
                                                <span class="activity-timeline bg-primary text-white">1</span>
                                                <div class="activity-timeline-content">
                                                    <span class="font-weight-normal1 fs-13">{{__('messages.air-way-bill-no')}}</span>
                                                    <p class="text-info fs-12 mt-1"> {{$tracking_data[0]['awbNo']}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-5">
                                            <div>
                                                <span class="activity-timeline bg-success text-white">2</span>
                                                <div class="activity-timeline-content">
                                                    <span class="font-weight-normal1 fs-13">{{__('messages.activity')}}</span>
                                                    <p class="text-danger fs-12 mt-1">{{$tracking_data[0]['Activity']}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-5">
                                            <div>
                                                <span class="activity-timeline bg-warning text-white">3</span>
                                                <div class="activity-timeline-content">
                                                    <span class="font-weight-normal1 fs-13">{{__('messages.detail')}}</span>
                                                    <p class="text-success fs-12 mt-1">{{$tracking_data[0]['Details']}}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="mb-5">
                                            <div>
                                                <span class="activity-timeline bg-info text-white">4</span>
                                                <div class="activity-timeline-content">
                                                    <span class="font-weight-normal1 fs-13">{{__('messages.date')}}</span>
                                                    <p class="text-warning fs-12 mt-1">{{$tracking_data[0]['Date']}}</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{--end first part--}}

                    {{--Second part--}}
                    <div class="col-lg-12 col-xl-4 col-sm-12 col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">{{__('messages.invoice-summary')}}</div>
                            </div>
                            <div class="card-body">
                                {{--Shipping detail--}}
                                <h5>{{__('messages.shipping-details')}}</h5>
                                <div class="table-responsive-lg">
                                    <table class="table table-borderless text-nowrap mb-0">
                                        <tbody>
                                        @php
                                            $json_address = json_decode($datas->shipping_address);
                                            $output = \App\MyClasses\Helpers::get_shipping_address($json_address->country);
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{__('messages.shipping-address')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto">{{$json_address->address_1}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{__('messages.city')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto">{{$json_address->city}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{__('messages.state')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto">{{$json_address->state}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{__('messages.country')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto">{{$output}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{__('messages.shipping-company')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto">{{ucfirst($datas->company_name)}}</span></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div><br/>
                                {{--end Shipping detail--}}

                                {{--additional detail--}}
                                <h4>{{__('messages.additional-details')}}</h4>
                                <div class="table-responsive-lg">
                                    <table class="table table-borderless text-nowrap mb-0">
                                        <tbody>
                                        <tr>
                                            <td class="text-start">{{__('messages.payment')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto {{payment_status($datas->payment)}}">{{payment_status_lang($datas->payment)}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start">{{__('messages.order-status')}}</td>
                                            <td class="text-end">
                                                <span class="font-weight-bold  ms-auto {{invoice_status($datas->order_status)}}">{{invoice_status_lang($datas->order_status)}}</span>
                                            </td>

                                        </tr>
                                        {{--Warehouse tag--}}
                                        @if(!empty($datas->order_warehouse_id))
                                            @php
                                                $warehouse = \App\Models\Warehouse::where('id',$datas->order_warehouse_id)->first();
                                            @endphp
                                            <tr>
                                                <td class="text-start">{{__('messages.warehouse')}}</td>
                                                <td class="text-end"><span class="font-weight-bold  ms-auto">{{ucfirst($warehouse->warehouse_name)}}</span></td>
                                            </tr>
                                        @endif
                                        {{--end Warehouse tag--}}
                                        </tbody>
                                    </table>
                                </div>
                                {{--end additional detail--}}
                            </div>
                        </div>
                    </div>
                    {{--end Second part--}}
                </div>
                <!-- End Row-->
            </div>
            <!--/div-->
        </div>
    <!-- app-content end -->
@endsection
{{--end main content--}}

{{--java & jquery--}}
{{--
@section('query')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#mySelect').change(function(){
            var id = $(this).val();
            console.log(id);
            $.ajax({
                url:'{{route('seller.invoice.shipping.get')}}',
                type:'post',
                data:{id:id},
                success:function(data){
                    $('#company_id').val(data.data.id);
                    $("#shipping_price1").val(data.data.price);
                    $("#shipping_price").html(data.data.price);
                    var a = parseInt(data.data.price);
                    var b = parseInt($('#my_value').val());
                    var sum = a + b;
                    console.log(sum);
                    $("#total1").val(sum);
                    $("#total").html(sum);
                }
            });
        });
    </script>
@endsection--}}
