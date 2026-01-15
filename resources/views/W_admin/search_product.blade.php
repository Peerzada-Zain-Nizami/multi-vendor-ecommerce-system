@extends('W_admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.product-search')}}</h4>
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
                    <div class="col-sm-8">
                            <div class="form-group">
                                <label>{{__('messages.product-barcode-search')}}</label>
                                <input type="text" id="product_barcode" list="shelf_code" class="form-control" autofocus>
                            </div>
                        <button id="go_btn" class="btn btn-primary" >{{__('messages.Go')}}</button>
                    </div>
                    <div class="col-sm-4">
                        <p id="cam_success" class="text-success"></p>
                        <div id="camera_list">

                        </div>
                        <p id="cam_error" class="text-danger"></p>
                        <video id="preview"></video>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{__('messages.product-detail')}}</div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('messages.product-image')}}</th>
                                <th>{{__('messages.product-name')}}</th>
                                <th>{{__('messages.warehouse-name')}}</th>
                                <th>{{__('messages.block/room')}}</th>
                                <th>{{__('messages.racks')}}</th>
                                <th>{{__('messages.shelf')}}</th>
                                <th>{{__('messages.quantity')}}</th>
                            </tr>
                            </thead>
                            <tbody id="product_detail_body">

                            </tbody>
                        </table>
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
        $('#go_btn').click(function(){
            var pro_id = $('#product_barcode').val();
            var data = {
                'product_barcode':pro_id,
            };
            $.ajax({
                url:'{{route('wadmin.product.place.tracking')}}',
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
                        $('#success').addClass('alert alert-light-success');
                        $('#success').html('Record Search Successful.');
                        setInterval(function () {
                            $('#success').removeClass('alert alert-light-success');
                            $('#success').html('');
                        },2000);
                        $('#product_detail_body').html('');
                        var ins_body = '';
                        var i = 1;
                        var asset = "{{asset('uploads/featured_images/')}}";
                        $.each(response.data , function (key,value) {
                            ins_body += '<tr>';
                            ins_body += '<td>'+i+++'</td>';
                            ins_body += '<td><img class="avatar avatar-lg br-7" src="' +asset+'/'+value.product.featured_image +'"></td>';
                            ins_body += '<td>'+value.product.product_name+'</td>';
                            ins_body += '<td>'+value.warehouse.warehouse_name+'</td>';
                            ins_body += '<td>'+value.block.block_code+'</td>';
                            ins_body += '<td>'+value.rack.rack_code+'</td>';
                            ins_body += '<td>'+value.shelf.shelf_code+'</td>';
                            ins_body += '<td>'+value.stock.quantity+'</td>';
                            ins_body += '</tr>';
                        });
                        $('#product_detail_body').append(ins_body);
                    }
                }
            });
        });
    </script>
    <script type="text/javascript">
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror: false, });
        scanner.addListener('scan', function (content) {
            var old_qty = $('#quantity').val();
            var new_qty = parseInt(old_qty)+1;
            $('#product_barcode').val(content);
            $('#quantity').val(new_qty);
            document.getElementById('beep_sound').play();
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