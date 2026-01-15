@extends('W_admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.stock-move')}}
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="row">
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
                <div class="col-sm-8">
                    <form id="pass" method="GET">
                        @csrf
                        <div id="add_f_error"></div>
                        <div class="form-group">
                            <label>{{__('messages.Shelf-barcode-search')}}</label>
                            <input id="code_shelf" type="text" name="shelf_code" list="shelf_code" class="form-control" autofocus>
                            <datalist id="shelf_code">
                                @foreach($results as $result)
                                    @php
                                        $warehouse = \App\Models\Warehouse::find($result->warehouse_id);
                                    @endphp
                                    <option value="{{$result->id}}">{{$warehouse->warehouse_name}}/{{$result->id}}/{{$result->shelf_code}}</option>
                                @endforeach
                            </datalist>
                            @if ($errors->has('shelf_code'))
                                <span class="text-danger">{{ $errors->first('shelf_code') }}</span>
                            @endif
                        </div>
                        <input type="button" class="btn btn-primary pass" value="{{__('messages.Go')}}">
                    </form>
                </div>
                <div class="col-sm-4">
                    <p id="cam_success" class="text-success"></p>
                    <div id="camera_list">

                    </div>
                    <p id="cam_error" class="text-danger"></p>
                    <video id="preview"></video>
                </div>
            </div>
        </div>
        <!--/div-->


    </div>
    <!-- CONTAINER END -->

@endsection
@section('query')
    <script type="text/javascript">


 $(Document).ready(function() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                            $('.pass').click(function(){
                $.ajax({
                    url:'{{route('wadmin.stockMove.place.add')}}',
                    type:'GET',
                    data:$('#pass').serialize(),
                    success:function(response){
                        console.log(response);
                        if (response.status == "fail")
                        {
                            $('#add_f_error').html('');
                            $('#add_f_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_f_error').append('<li>'+error+'</li>');
                            })
                        }
                        else if (response.status == "danger") {
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning',
                                showConfirmButton: true
                            });
                        }
                         else {
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
        let scanner = new Instascan.Scanner({ video: document.getElementById('preview'), mirror: false, });
        scanner.addListener('scan', function (content) {
            $('#code_shelf').val(content);
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