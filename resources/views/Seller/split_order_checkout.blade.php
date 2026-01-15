@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.invoice-checkout')}}</h4>
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
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="ms-3">{{__('messages.invoice-items')}}</span>
                            </div>
                        </div>
                        <div class="table-responsive-lg">
                            <table class="table card-table table-responsive-lg-sm table-vcenter text-nowrap shopping-carttable">
                                <thead class="border-bottom-0 pt-3 pb-3 ">
                                <tr>
                                    <th class="fs-15">{{__('messages.product')}}</th>
                                    <th>{{__('messages.product-name')}}</th>
                                    <th>{{__('messages.location')}}</th>
                                    <th>{{__('messages.price')}}</th>
                                    <th>{{__('messages.quantity')}}</th>
                                    <th>{{__('messages.total')}}</th>
                                </tr>
                                </thead>
                                <tbody >
                                @php
                                        $json_products = json_decode($datas->products);
                                @endphp
                                @foreach($json_products as $json_product)
                                    <tr>
                                        @php
                                            $woo_pro_id = $json_product->product_id;
                                            $product_id = \App\Models\Wooproduct::where('woo_id',$woo_pro_id)->get('product_id');
                                            $product = \App\Models\Product::where('id',$product_id[0]->product_id)->first();
                                            $stock = \App\Models\Stock::where('product_id',$product_id[0]->product_id)->first();

                                            $rate = $stock->selling_price;
                                            $dis = $stock->discount;
                                            $fee = $stock->fee;
                                            $net = $rate-$dis+$fee;
                                        @endphp
                                        <th class="fs-15"><img class="avatar avatar-lg br-7" src="{{asset('uploads/featured_images/'.$product->featured_image)}}"></th>
                                        <th>{{$product->product_name}}</th>
                                        <th>{{$orders->shipping->address_1}}</th>
                                        <th>{{$net}}</th>
                                        <th>{{$json_product->quantity}}</th>
                                        <th>{{$net*$json_product->quantity}}</th>
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
                                        <td class="text-end"><span class="font-weight-bold  ms-auto">{{$orders->total-$orders->shipping_total-$orders->total_tax}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.shipping-charges')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto" id="fee">{{$orders->shipping_total}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.tax')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto" id="fee">{{$orders->total_tax}}</span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start fs-18">{{__('messages.total-bill')}}</td>
                                        <td class="text-end"><span class="ms-2 font-weight-bold  fs-22">{{$orders->total}}</span></td>
                                    </tr>
                                    <tr>

                                        <td colspan="2" class="text-center"><a href="{{route('seller.send.order.invoice',['id'=>$orders->id])}}" class="btn btn-primary btn-md mt-3" role="button">{{__('messages.sent-order')}}</a></td>
                                    </tr>
                                    </tbody>
                                </table>
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
{{--@section('query')--}}
{{--    <script type="text/javascript">--}}
{{--        $(document).ready(function () {--}}
{{--            $.ajaxSetup({--}}
{{--                headers: {--}}
{{--                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                }--}}
{{--            });--}}
{{--            get_record();--}}
{{--            function get_record()--}}
{{--            {--}}
{{--                var id = "{{$id}}";--}}
{{--                var asset = "{{asset('uploads/featured_images/')}}";--}}
{{--                $.ajax({--}}
{{--                    url:'{{route('admin.invoice.checkout.get')}}',--}}
{{--                    type:'post',--}}
{{--                    data:{id:id},--}}
{{--                    beforeSend:function(){--}}
{{--                        $('#loader').show();--}}
{{--                    },--}}
{{--                    success:function(data){--}}
{{--                        $("#result").html('');--}}
{{--                        $('#loader').hide();--}}
{{--                        var bodyData = '';--}}
{{--                        var sub_total = 0;--}}
{{--                        var fee = 0;--}}
{{--                        var total = 0;--}}
{{--                        $.each(data.result,function (key,value) {--}}
{{--                            var msg = null;--}}
{{--                            if(value.quantity == 1)--}}
{{--                            {--}}
{{--                                msg="disabled";--}}
{{--                            }--}}
{{--                            bodyData+="<tr class='cart-table attach-supportfiles'>";--}}
{{--                            bodyData+="<td><img class='avatar avatar-lg br-7' src='" +asset+"/"+value.company_product[0].featured_image +"'></td>";--}}
{{--                            bodyData+="<td>"+ value.company_product[0].product_name +"</td>";--}}
{{--                            bodyData+="<td>"+ value.supplier_product[0].selling_price +"</td>";--}}
{{--                            bodyData+="<td><button id='"+ value.id+"' class='btn btn-sm btn-primary minus'"+ msg+"><i class='fe fe-minus'></i></button><input style='outline: none; border: none;background-color: transparent;width: 100px;' class='ms-2 me-2 fs-15 text-center' value='"+value.quantity+"' disabled/><button id='"+ value.id+"' class='btn btn-sm btn-primary plus'><i class='fe fe-plus'></i></button></td>";--}}
{{--                            bodyData+="<td><span>"+ value.supplier_product[0].selling_price*value.quantity +"</span></td>";--}}
{{--                            bodyData+="<td><button id='"+ value.id+"' class='btn btn-sm btn-outline-danger del'><i class='fe fe-trash'></i></button></td>";--}}
{{--                            bodyData+="</tr>";--}}
{{--                            sub_total += value.supplier_product[0].selling_price*value.quantity;--}}
{{--                            fee += value.supplier_product[0].shipping_charges*value.quantity;--}}
{{--                            total += value.supplier_product[0].selling_price*value.quantity+value.supplier_product[0].shipping_charges*value.quantity;--}}
{{--                        });--}}
{{--                        $("#result").append(bodyData);--}}
{{--                        $("#sub_total").html(sub_total);--}}
{{--                        $("#fee").html(fee);--}}
{{--                        $("#total").html(total);--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}

{{--            $(document).on('click','.minus',function () {--}}
{{--                var id = $(this).attr("id");--}}
{{--                $.ajax({--}}
{{--                    url:'{{route('admin.invoice.checkout.product.minus')}}',--}}
{{--                    type:'post',--}}
{{--                    data:{id:id},--}}
{{--                    success:function(data){--}}
{{--                        get_record();--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--            $(document).on('click','.plus',function () {--}}
{{--                var id = $(this).attr("id");--}}
{{--                $.ajax({--}}
{{--                    url:'{{route('admin.invoice.checkout.product.plus')}}',--}}
{{--                    type:'post',--}}
{{--                    data:{id:id},--}}
{{--                    success:function(data){--}}
{{--                        if (data.status == 400)--}}
{{--                        {--}}
{{--                            $('#error').html(--}}
{{--                                '<div class="alert alert-light-danger" role="alert">\n' +--}}
{{--                                '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +--}}
{{--                                '    <strong>Oopps! </strong>'+data.message+'\n' +--}}
{{--                                '</div>');--}}
{{--                        }--}}
{{--                        else{--}}
{{--                            get_record();--}}
{{--                        }--}}
{{--                        setTimeout(function() {--}}
{{--                            $(".alert").alert('close');--}}
{{--                        }, 3000);--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--            $(document).on('click','.del',function(){--}}
{{--                var th = $(this);--}}
{{--                var id = $(this).attr("id");--}}

{{--                swal({--}}
{{--                    title: "Are you sure?",--}}
{{--                    text: "Once deleted, you will not be able to recover this Product!",--}}
{{--                    icon:"warning",--}}
{{--                    showCancelButton: true,--}}
{{--                    dangerMode: true,--}}
{{--                    confirmButtonText: "Ok, Delete it!",--}}
{{--                    showConfirmButton: true,--}}

{{--                })--}}
{{--                    .then((willDelete) => {--}}

{{--                        if (willDelete) {--}}
{{--                            $.ajaxSetup({--}}
{{--                                headers: {--}}
{{--                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
{{--                                }--}}
{{--                            });--}}
{{--                            $.ajax({--}}
{{--                                url:'{{route('admin.invoice.checkout.product.del')}}',--}}
{{--                                type:'post',--}}
{{--                                data:{id:id},--}}
{{--                                success:function(data){--}}
{{--                                    get_record();--}}
{{--                                }--}}
{{--                            });--}}


{{--                        } else {--}}
{{--                            Swal.fire({--}}
{{--                                title: 'Your Product is Safe!',--}}
{{--                                icon: 'success',--}}
{{--                                showConfirmButton: false,--}}
{{--                                timer: 1500--}}
{{--                            })--}}
{{--                        }--}}
{{--                    });--}}


{{--            });--}}
{{--        });--}}
{{--    </script>--}}
{{--@endsection--}}