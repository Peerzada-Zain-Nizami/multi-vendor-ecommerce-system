@extends('W_admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.stock-in')}}</h4>
                    <h6 class="page-title mb-0 text-muted">{{$shelf->id}}/{{$shelf->shelf_code}}</h6>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div>
                <div id="error">

                </div>
                <div id="success">

                </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <p id="cam_success" class="text-success"></p>
                            <div id="camera_list">
                            </div>
                            <p id="cam_error" class="text-danger"></p>
                            <video id="preview"></video>
                        </div>
                        <div class="col-sm-4">
                        <div class="form-group">
                            <label>{{__('messages.product-barcode-search')}}</label>
                            <input type="text" id="product_barcode" list="shelf_code" onchange="selected_item({{$shelf->warehouse_id}})" class="form-control" autofocus>
                            <input type="hidden" id="stockins_list_id" value="???" autofocus>
                            <input type="hidden" id="order_id" value="{{$order_id}}" autofocus>
                            <input type="hidden" id="warehouse_id" value="{{$shelf->warehouse_id}}" autofocus>
                            <datalist id="shelf_code">
                                @foreach($stocks as $stock)
                                    <option value="{{$stock->stock_ins_id}}">{{$stock->stock_ins_id}}</option>
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                        <div class="col-sm-2">
                        <div class="form-group">
                            <label>{{__('messages.product-quantity')}}</label>
                            <input type="number" id="quantity" value="0" max="???" min="???" class="form-control" autofocus>
                        </div>
                    </div>
                        <div class="col-sm-1">
                            <label>{{__('messages.auto-sync')}}</label>
                            <br>
                            @php
                                $user = \Illuminate\Support\Facades\Auth::user();
                            @endphp
                            <label class="custom-switch">
                                <input value="{{$user->id}}" id="stock_in_check" type="checkbox" class="custom-switch-input on_off" @if($user->stock_in_check == "true")checked @endif>
                                <span class="custom-switch-indicator"></span>
                            </label>
                        </div>
                        <div class="col-sm-2">
                            <label>{{__('messages.action')}}</label>
                            <br>
                            <button id="stock_in" class="btn btn-primary">{{__('messages.stock-in')}}</button>
                        </div>
                    </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{__('messages.stock-in')}}</div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{__('messages.product-image')}}</th>
                                    <th>{{__('messages.product-name')}}</th>
                                    <th>{{__('messages.quantity')}}</th>
                                    <th>{{__('messages.action')}}</th>
                                </tr>
                            </thead>
                            <tbody id="ins_body">
                            </tbody>
                        </table>
                        <div class="text-center" id="loader" style="display: none;">
                            <div class="spinner-grow text-muted"></div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <form action="{{route('wadmin.stockIn.place.stock.update.order.return',$shelf->id)}}" method="post">
                        @csrf
                        <input id="update_btn" type="submit" class="btn btn-primary" value="{{__('messages.update')}}" disabled>
                    </form>
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

        /*For Select the Stock*/
        function selected_item(warehouse_id)
        {
            var id = document.getElementById("product_barcode").value;
            var order_id = $('#order_id').val();
            var old_qty = $('#quantity').val();
            var data = {
                'id':id,
                'order_id':order_id,
                'old_qty':old_qty,
                'warehouse_id':warehouse_id,
            };
            console.log(data);
            $.ajax({
                url:'{{route('wadmin.stockIn.get.stock.length.order.return')}}',
                type:'POST',
                data:data,
                success:function(response){
                    $("#quantity").attr({
                        "max" : response.final_stock,
                        "min" : 0
                    });
                    console.log(response.final_stock);
                    $("#stockins_list_id").attr({
                        "value" : response.data.id,
                    });
                }
            });
        }

        function load_data()
        {
            $('#loader').show();
            var route = "{{route('wadmin.stockIn.place.data')}}";
            $.get(route,function (data) {
                console.log(data);
                if(data.stock_in.length == 0)
                {
                    $('#update_btn').attr('disabled','disabled');
                }
                $('#ins_body').html('');
                var ins_body = '';
                var i = 1;
                var asset = "{{asset('uploads/featured_images/')}}";
                $.each(data.stock_in , function (key,value) {
                    var st = '';
                    if (value.quantity == 1)
                    {
                        st = 'disabled';
                    }
                    ins_body +='<tr><td>'+i+++'</td><td><img class="avatar avatar-lg br-7" src="' +asset+'/'+value.product.featured_image +'"></td><td>'+value.product.product_name +'</td><td><button id="'+value.barcode_id +'" class="btn btn-sm btn-primary minus" '+st+'><i class="fe fe-minus"></i></button><input style="outline: none; border: none;background-color: transparent;width: 100px;" class="ms-2 me-2 fs-15 text-center" value="'+value.quantity +'" disabled/><button id="'+value.barcode_id +'" class="btn btn-sm btn-primary plus"><i class="fe fe-plus"></i></button></td><td><button id="'+value.barcode_id +'" class="btn btn-sm btn-outline-danger del"><i class="fe fe-trash"></i></button></td></tr>';
                });
                $('#ins_body').append(ins_body);
                $('#loader').hide();
            });
        }
        function auto_sync() {
            var pro_id = $('#product_barcode').val();
            var stockins_list_id = $('#stockins_list_id').val();
            var qty = $('#quantity').val();
            var order_id = $('#order_id').val();
            var old_qty = $('#quantity').val();
            var data = {
                'product_barcode':pro_id,
                'stockins_list_id':stockins_list_id,
                'product_quantity':qty,
                'order_id':order_id,
                'old_qty':old_qty,
            };
            console.log("quantity" , qty);
            $.ajax({
                url:'{{route('wadmin.stockIn.place.update.order.return')}}',
                type:'POST',
                data:data,
                success:function(response){
                    if (response.status == "fail")
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
                        $('#update_btn').removeAttr('disabled');
                        $('#product_barcode').val('');
                        $('#quantity').val('1');
                        $('#success').addClass('alert alert-light-success');
                        $('#success').html('Record Added Successful.');
                        setInterval(function () {
                            $('#success').removeClass('alert alert-light-success');
                            $('#success').html('');
                        },2000);
                        load_data();
                    }
                }
            });
        }
        $('.on_off').click(function () {
            var id = $(this).attr("id");
            var user_id = $(this).val();
            if ($(this).is(':checked') == true)
            {
                var data = {
                    'user_id': user_id,
                    'tab': id,
                    'value': "true",
                };
                $.ajax({
                    url:"{{route('wadmin.stock.placement.on_off')}}",
                    type:"POST",
                    dataType:"json",
                    data: data,
                });
                $('#stock_in').attr('disabled','disabled');
                $('#quantity').attr('disabled','disabled');
                $('#quantity').val('1');
            }
            else{
                var data = {
                    'user_id': user_id,
                    'tab': id,
                    'value': "false",
                };
                $.ajax({
                    url:"{{route('wadmin.stock.placement.on_off')}}",
                    type:"POST",
                    dataType:"json",
                    data: data,
                });
                $('#stock_in').removeAttr('disabled');
                $('#quantity').removeAttr('disabled');
                $('#quantity').val('0');
            }
        });
        $('#stock_in').click(function(){
            var pro_id = $('#product_barcode').val();
            var stockins_list_id = $('#stockins_list_id').val();
            var order_id = $('#order_id').val();
            var qty = $('#quantity').val();
            var data = {
                'product_barcode':pro_id,
                'stockins_list_id':stockins_list_id,
                'product_quantity':qty,
                'order_id':order_id,
            };
            $.ajax({
                url:'{{route('wadmin.stockIn.place.update.order.return')}}',
                type:'POST',
                data:data,
                success:function(response){
                    if (response.status == "fail")
                    {
                        $('#error').html('');
                        $('#error').addClass('alert alert-light-danger');
                        $.each(response.errors,function (key,error) {
                            $('#error').append('<li>'+error+'</li>');
                        });
                        setInterval(function () {
                            $('#error').removeClass('alert alert-light-danger');
                            $('#error').html('');
                        },9000);
                    }
                    else{
                        $('#error').html('');
                        $('#error').removeClass('alert alert-light-danger');
                        $('#update_btn').removeAttr('disabled');
                        $('#product_barcode').val('');
                        $('#quantity').val('0');
                        $('#success').addClass('alert alert-light-success');
                        $('#success').html('Record Added Successful.');
                        setInterval(function () {
                            $('#success').removeClass('alert alert-light-success');
                            $('#success').html('');
                        },2000);
                        load_data();
                    }
                }
            });
        });

        $(document).on('click','.minus',function () {
            var id = $(this).attr("id");
            $.ajax({
                url:'{{route('wadmin.stockIn.place.stock.minus.order.return')}}',
                type:'post',
                data:{id:id},
                success:function(data){
                    load_data();
                }
            });
        });
        $(document).on('click','.plus',function () {
            var id = $(this).attr("id");
            $.ajax({
                url:'{{route('wadmin.stockIn.place.stock.plus.order.return')}}',
                type:'post',
                data:{id:id},
                success:function(data){

                    if (data.status == "fail")
                    {
                        $('#error').html('');
                        $('#error').addClass('alert alert-light-danger');
                        $.each(data.errors,function (key,error) {
                            $('#error').append('<li>'+error+'</li>');
                        })
                    }
                    setInterval(function () {
                        $('#error').removeClass('alert alert-light-danger');
                        $('#error').html('');
                    },9000);
                    load_data();
                }
            });
        });
        $(document).on('click','.del',function(){
            var th = $(this);
            var id = $(this).attr("id");

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this Product!",
                icon:"warning",
                showCancelButton: true,
                dangerMode: true,
                confirmButtonText: "Ok, Delete it!",
                showConfirmButton: true,

            })
                .then((willDelete) => {

                    if (willDelete) {
                        $.ajax({
                            url:'{{route('wadmin.stockIn.place.stock.del.order.return')}}',
                            type:'POST',
                            data:{id:id},
                            success:function(data){
                                load_data();
                            }
                        });


                    } else {
                        Swal.fire({
                            title: 'Your Product is Safe!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                });


        });
    </script>


    <script type="text/javascript">
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror: false, });
        scanner.addListener('scan', function (content) {
            var warehouse_id = $('#warehouse_id').val();
            var order_id = $('#order_id').val();
            var old_qty = $('#quantity').val();

            var data = {
                'id':content,
                'order_id':order_id,
                'old_qty':old_qty,
                'warehouse_id':warehouse_id,
            };
            console.log(data);
            $.ajax({
                url:'{{route('wadmin.stockIn.get.stock.length.order.return')}}',
                type:'POST',
                data:data,
                success:function(response){
                    if (response.status == "fail")
                    {
                        $('#error').html('');
                        $('#error').addClass('alert alert-light-danger');
                        $.each(response.errors,function (key,error) {
                            $('#error').append('<li>'+error+'</li>');
                        });
                        setInterval(function () {
                            $('#error').removeClass('alert alert-light-danger');
                            $('#error').html('');
                        },9000);
                    }
                    $("#quantity").attr({
                        "max" : response.final_stock,
                        "min" : 0
                    });
                    console.log(response.final_stock);
                    $("#stockins_list_id").attr({
                        "value" : response.data.id,
                    });

                    var old_qty = $('#quantity').val();
                    console.log(old_qty);
                    var new_qty = null;
                    if(response.final_stock > parseInt(old_qty))
                    {
                        new_qty = parseInt(old_qty)+1;
                    }
                    else {
                        new_qty = parseInt(old_qty);
                    }
                    $('#product_barcode').val(content);

                    document.getElementById('beep_sound').play();
                    if ($('.on_off').is(':checked') == true)
                    {
                        auto_sync();
                    }
                    else{
                        $('#quantity').val(new_qty);
                    }
                }
            });

        });
        Instascan.Camera.getCameras().then(function (cameras) {
            if (cameras.length > 0) {
                var i = 0;
                $.each(cameras,function (key,name) {
                    var class_name = '';
                    if (i == key)
                    {
                        class_name = "text-success";
                    }
                    else{
                        class_name = "text-muted";
                    }
                    $('#camera_list').append('<li onclick="change('+key+')" class="'+class_name+'">'+name.name+'</li>');
                });
                scanner.start(cameras[0]);
            } else {
                $('#cam_error').html('No cameras found.');
            }
        }).catch(function (e) {
            console.error(e);
        });
        function change(key) {
            $('#camera_list').html('');
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[key]);
                    var i = key;
                    $.each(cameras,function (key,name) {
                        var class_name = '';
                        if (i == key)
                        {
                            class_name = "text-success";
                        }
                        else{
                            class_name = "text-muted";
                        }
                        $('#camera_list').append('<li onclick="change('+key+')" class="'+class_name+'">'+name.name+'</li>');
                    });
                } else {
                    $('#cam_error').html('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });
        }
    </script>


@endsection
