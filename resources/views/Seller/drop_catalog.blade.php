@php
    function stock($id)
    {
        $array = array();
        $stock_ins = DB::table("stock_ins")->where('product_id',$id)->get();
        foreach ($stock_ins as $stock_in)
        {
            $array[] = $stock_in->id;
        }
        $stock_ins_list = DB::table("stock_ins_list")->whereIn('stock_ins_id',$array)->sum('stock');
        return $stock_ins_list;
    }
@endphp
@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">
                <!--Page header-->
                <div class="page-header">
                    <div class="page-leftheader">
                        <h4 class="page-title mb-0 text-primary">{{__('messages.drop-shipping-catalog')}}</h4>
                    </div>
                </div>
                <!--End Page header-->
                @if (Session::has('success'))
                        <div class="alert alert-light-success" role="alert">
                            <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                            <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                        </div>
                @endif
            <div id="msg">

            </div>
                <!--div-->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{__('messages.product-details')}}</div>
                        <div class="card-options">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    {{__('messages.list-on')}} <i class="fa fa-caret-down"></i>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @if($apis->woo == "true")
                                        <li><button id="woocommerce" class="btn">wooCommerce</button></li>
                                    @endif
                                    @if($apis->shopify == "true")
                                        <li><button id="shopify" class="btn">shopify</button></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-lg">
                        <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                            <thead class="border-bottom-0 pt-3 pb-3">
                            <tr>
                                <th><input type="checkbox" id="select_all"></th>
                                <th class="text-center">#</th>
                                <th>{{__('messages.image')}}</th>
                                <th>{{__('messages.product-category')}}</th>
                                <th>{{__('messages.product-name')}}</th>
                                <th>{{__('messages.available-stock')}}</th>
                                <th>{{__('messages.company-product-status')}}</th>
                                <th>{{__('messages.company-price')}}</th>
                                <th>{{__('messages.my-prices')}}</th>
                                <th>{{__('messages.status')}}</th>
                                <th>{{__('messages.listed-platforms')}}</th>
                                <th>{{__('messages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach($results as $result)
                                <tr>
                                    <td><input type="checkbox" class="checkbox" value="{{$result->product_id}}"></td>
                                    <td class="text-center">{{$i++}}</td>
                                {{--                                    @php--}}
                                {{--                                        dd($result);--}}
                                {{--                                    @endphp--}}
                                    <td><img class="avatar avatar-xxl" src="@if(empty($result->featured_image) && empty($result->product_images)){{asset('uploads/featured_images/'.$result->get_products[0]->featured_image)}} @else {{asset('uploads/seller_products/featured_images/'.$result->featured_image)}}@endif"></td>
                                    <td class="fs-13">{{$result->category}}</td>
                                    <td>{{$result->product_name}}</td>
                                    <td>{{stock($result->product_id)}}</td>
                                    <td><h3><span class="badge @if($result->get_stock[0]->status == "Listed") bg-success-transparent @else bg-danger-transparent @endif">@if($result->get_stock[0]->status == "Listed") {{__('messages.listed')}} @else {{__('messages.unlisted')}} @endif</span></h3></td>
                                    <td>
                                        @php
                                            $rate = $result->get_stock[0]->selling_price;
                                            $dis = $result->get_stock[0]->discount;
                                            $net = $rate-$dis;
                                        @endphp
                                        <span class='text-secondary'>{{__('messages.company-rate')}}: {{$rate}}</span><br>
                                        <span class='text-success'>{{__('messages.discount')}}: @if(empty($dis)) 0 @else {{$dis}} @endif</span><br>
                                        <span class='text-info'>{{__('messages.net-rate')}}: {{$net}}</span><br>
                                        <span class='text-warning'>{{__('messages.suggested-price')}}: {{$result->get_stock[0]->suggested_price}}</span><br>
                                        <span class='text-muted'>{{__('messages.retail-price')}}: {{$result->get_stock[0]->retail_price}}</span>
                                    </td>
                                    <td>
                                        @php
                                            $rate = $result->selling_price;
                                            $dis = $result->discount;
                                            $net = $rate-$dis;
                                        @endphp
                                        <span class='text-secondary'>{{__('messages.my-rate')}}: @if(empty($rate)) 0 @else {{$rate}} @endif</span><br>
                                        <span class='text-success'>{{__('messages.discount')}}: @if(empty($dis)) 0 @else {{$dis}} @endif</span><br>
                                        <span class='text-info'>{{__('messages.net-rate')}}: {{$net}}</span>
                                    </td>
                                    <td><h3><span class="badge @if($result->status == "Active") bg-success-transparent @else bg-danger-transparent @endif">@if($result->status == "Active") {{__('messages.active')}} @else {{__('messages.deactive')}} @endif</span></h3></td>
                                    <td>
                                        @php
                                            $platforms = json_decode($result->platforms);
                                        @endphp
                                        @if(!empty($platforms))
                                                @foreach($platforms as $platform)
                                                    <span class="badge rounded-pill bg-primary mt-2">{{$platform}}</span>
                                                    @if($loop->even == true)
                                                        <br>
                                                    @endif
                                                @endforeach
                                        @else
                                            {{__('messages.not-listed')}}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('seller.drop.catalog.edit',$result->id)}}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                        <a href="{{route('seller.drop.catalog.delete',$result->id)}}" class="btn btn-danger delete-confirm"><i class="fa fa-trash"></i></a>
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
@section('query')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //select all checkboxes
        $("#select_all").change(function(){  //"select all" change
            $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
        });
          $('.delete-confirm').on('click', function (event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this Product!',
                icon: 'warning',
                showCancelButton: true,
                dangerMode: true,
                confirmButtonText: "Ok, Delete it!",
                showConfirmButton: true,
            }).then((willDelete) => {
                if (willDelete) {
                      $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                                location.reload(); // Reload the page after the success message
                            }, 1500);
                        } else if (response.status === 'error') {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message, // Display error message returned from server
                                icon: 'error',
                                showConfirmButton: true,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong!',
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    }
                });
                }
                else {
                        Swal.fire({
                            title: 'Your Product is Safe!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
            });
        });

        //".checkbox" change
        $('.checkbox').change(function(){
            //uncheck "select all", if one of the listed checkbox item is unchecked
            if(false == $(this).prop("checked")){ //if this item is unchecked
                $("#select_all").prop('checked', false); //change "select all" checked status to false
            }
            //check "select all" if all checkbox items are checked
            if ($('.checkbox:checked').length == $('.checkbox').length ){
                $("#select_all").prop('checked', true);
            }
        });
        $(document).on('click','#woocommerce',function () {
            if ($('.checkbox:checked').length > 10)
            {
                $('#msg').html(
                    '<div class="alert alert-light-danger" role="alert">\n' +
                    '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '    <strong>Oopps! </strong>You can select only 10 products.\n' +
                    '</div>');
            }
            else{
                var ids_array = [];
                $(".checkbox:checked").each(function() {
                    ids_array.push($(this).val());
                });
                $.ajax({
                    url: "{{route('seller.woo.add')}}",
                    type:"POST",
                    dataType: "json",
                    data: {ids:ids_array},
                    beforeSend:function(){
                        $('#global-loader').show();
                    },
                    success: function(response){
                        if (response.status == 200)
                        {
                            $('#global-loader').hide();
                            $('input:checkbox').prop('checked',false);
                            $('#msg').html(
                                '<div class="alert alert-light-success" role="alert">\n' +
                                '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Well done! </strong>'+response.message+'\n' +
                                '</div>');
                            setTimeout(function() {
                                $(".alert").alert('close');
                            }, 1000);
                            location.reload(); // Reload the page after success
                        }
                        else{
                            $('#global-loader').hide();
                            $('input:checkbox').prop('checked',false);
                            $('#msg').html(
                                '<div class="alert alert-light-danger" role="alert">\n' +
                                '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Oppss! </strong>'+response.message+'\n' +
                                '</div>');
                            setTimeout(function() {
                                $(".alert").alert('close');
                            }, 2000);
                        }
                        if(response.status == 401){
                            $('#global-loader').hide();
                            $('input:checkbox').prop('checked',false);
                            $('#msg').html(
                                '<div class="alert alert-light-danger" role="alert">\n' +
                                '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Oppss! </strong>'+response.message+'\n' +
                                '</div>');
                            setTimeout(function() {
                                $(".alert").alert('close');
                            }, 4000);
                        }
                    }
                })
            }
        });
        $(document).on('click','#shopify',function () {
            if ($('.checkbox:checked').length > 10)
            {
                $('#msg').html(
                    '<div class="alert alert-light-danger" role="alert">\n' +
                    '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                    '    <strong>Oopps! </strong>You can select only 10 products.\n' +
                    '</div>');
            }
            else{
                var ids_array = [];
                $(".checkbox:checked").each(function() {
                    ids_array.push($(this).val());
                });
                $.ajax({
                    url: "{{route('seller.shopify.add')}}",
                    type:"POST",
                    dataType: "json",
                    data: {ids:ids_array},
                    beforeSend:function(){
                        $('#global-loader').show();
                    },
                    success: function(response){
                        if (response.status == 200)
                        {
                            $('#global-loader').hide();
                            $('input:checkbox').prop('checked',false);
                            $('#msg').html(
                                '<div class="alert alert-light-success" role="alert">\n' +
                                '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Well done! </strong>'+response.message+'\n' +
                                '</div>');
                            setTimeout(function() {
                                $(".alert").alert('close');
                            }, 1000);
                            location.reload(); // Reload the page after success
                        }
                        else{
                            $('#global-loader').hide();
                            $('input:checkbox').prop('checked',false);
                            $('#msg').html(
                                '<div class="alert alert-light-danger" role="alert">\n' +
                                '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Oppss! </strong>'+response.message+'\n' +
                                '</div>');
                            setTimeout(function() {
                                $(".alert").alert('close');
                            }, 2000);
                        }
                    }
                })
            }
        });

        </script>
@endsection
