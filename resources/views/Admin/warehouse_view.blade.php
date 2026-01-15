@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">

            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    @php
                        $data = \App\MyClasses\Helpers::get_lang($warehouse->warehouse_name,$warehouse->id,"warehouse",App::getLocale());
                        $warehouse_data = json_decode($data);
                    @endphp
                    <h4 class="page-title mb-0 text-primary">{{$warehouse->warehouse_id}}/@if(App::getLocale() == "en"){{$data}} @elseif($warehouse_data) {{$warehouse_data->name}} @else {{$warehouse->warehouse_name}} @endif</h4>
                    <h6 class="page-title mb-0 text-muted">@if(App::getLocale() == "en"){{$warehouse->address}} @elseif($warehouse_data) {{$warehouse_data->address}} @else {{$warehouse->address}} @endif</h6>
                </div>
                <div class="page-rightheader">
                    <div class="btn-list">
                        <button onclick="show_hide('block')" class="btn btn-primary"><i class="fa fa-plus"></i>
                            {{__('messages.blocks/rooms')}}</button>
                        <button onclick="show_hide('rack')" class="btn btn-primary"><i class="fa fa-plus"></i>
                            {{__('messages.racks')}}</button>
                        <button onclick="show_hide('shelf')" class="btn btn-primary"><i class="fa fa-plus"></i>
                            {{__('messages.shelf')}}</button>
                    </div>
                </div>
            </div>
            @if (Session::has('success'))
                <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                </div>
            @endif
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div id="block_room" style="@if(!empty(old('block_tab'))) display: block; @else display: none; @endif" class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.block/room-add')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-6 offset-3">
                                <form id="add_block">
                                   <div id="add_g_error"></div>
                                    <input type="text" name="block_tab" value="block_tab" hidden>
                                    <div class="form-group">
                                        <label class=" text-left">{{__('messages.block/room-code')}}</label>
                                        <input type="text" name="block_code" placeholder="Block Code" value="{{old('block_code')}}" class="form-control"/>
                                    </div>
                                    {{-- @if ($errors->has('block_code'))
                                        <span class="text-danger">{{ $errors->first('block_code') }}</span>
                                    @endif --}}
                                    <div class="form-group">
                                        <label class=" text-left">{{__('messages.status')}}</label>
                                        <select name="status" class="form-control select2">
                                            <option disabled selected>{{__('messages.please-select-status')}}</option>
                                            <option>{{__('messages.active')}}</option>
                                            <option>{{__('messages.deactive')}}</option>
                                        </select>
                                    </div>
                                    {{-- @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif --}}
                                    <div class="form-group">
                                         <button class="btn btn-primary add_block" data-id="{{ $id }}" type="button">{{ __('messages.add-block/room') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="rack" style="@if(!empty(old('rack_tab'))) display: block; @else display: none; @endif" class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.rack-add')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-6 offset-3">
                                <form id="add_rack">
                                   <div id="add_r_error"></div>
                                    <input type="text" name="rack_tab" value="rack_tab" hidden>
                                    <div class="form-group">
                                        <label class=" text-left">{{__('messages.block/room-code')}}</label>
                                        <select name="block_id" class="form-control select2">
                                            <option disabled selected>{{__('messages.please-select')}}</option>
                                            @foreach($blocks as $block)
                                                @if($block->status == "Active")
                                                <option value="{{$block->id}}">{{$block->block_code}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('block_id'))
                                        <span class="text-danger">{{ $errors->first('block_id') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <label class=" text-left">{{__('messages.rack-code')}}</label>
                                        <input type="text" name="rack_code" placeholder="Rack Code" value="{{old('rack_code')}}" class="form-control"/>
                                    </div>
                                    @if ($errors->has('rack_code'))
                                        <span class="text-danger">{{ $errors->first('rack_code') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <label class=" text-left">{{__('messages.status')}}</label>
                                        <select name="status" class="form-control select2">
                                            <option disabled selected>{{__('messages.please-select-status')}}</option>
                                            <option>{{__('messages.active')}}</option>
                                            <option>{{__('messages.deactive')}}</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <button class="btn btn-primary add_rack" data-id="{{ $id }}" type="button">{{ __('messages.add-rack') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="shelf" style="@if(!empty(old('shelf_tab'))) display: block; @else display: none; @endif" class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.shelf-add')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-6 offset-3">
                                <form id="add_shelf">
                                    <div id="add_s_error"></div>
                                    <input type="text" name="shelf_tab" value="shelf_tab" hidden>
                                    <div class="form-group">
                                        <label class=" text-left">{{__('messages.block/room-code')}}</label>
                                        <select name="block_id" id="block_list" class="form-control select2">
                                            <option disabled selected>{{__('messages.please-select')}}</option>
                                            @foreach($blocks as $block)
                                                @if($block->status == "Active")
                                                    <option value="{{$block->id}}">{{$block->block_code}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('block_id'))
                                        <span class="text-danger">{{ $errors->first('block_id') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <label class=" text-left">{{__('messages.rack-code')}}</label>
                                        <select name="rack_id" id="rack_list" class="form-control select2">

                                        </select>
                                    </div>
                                    @if ($errors->has('rack_id'))
                                        <span class="text-danger">{{ $errors->first('rack_id') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <label class=" text-left">{{__('messages.shelf-code')}}</label>
                                        <input type="text" name="shelf_code" placeholder="Shelf Code" value="{{old('shelf_code')}}" class="form-control"/>
                                    </div>
                                    @if ($errors->has('shelf_code'))
                                        <span class="text-danger">{{ $errors->first('shelf_code') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <label class=" text-left">{{__('messages.status')}}</label>
                                        <select name="status" class="form-control select2">
                                            <option disabled selected>{{__('messages.please-select-status')}}</option>
                                            <option>{{__('messages.active')}}</option>
                                            <option>{{__('messages.deactive')}}</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                    <div class="form-group">
                                         <button class="btn btn-primary add_shelf" data-id="{{ $id }}" type="button">{{ __('messages.add-shelf') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.blocks/rooms-list')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table class="table table-responsive-lg-sm table-bordered text-nowrap">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('messages.block/room-code')}}</th>
                                        <th>{{__('messages.status')}}</th>
                                        <th>{{__('messages.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach($blocks as $block)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$block->block_code}}</td>
                                        <td class="@if($block->status == "Active") text-success @else text-danger @endif">@if($block->status == "Active") {{__('messages.active')}} @else {{__('messages.deactivate')}} @endif</td>
                                        <td>
                                            <button id="{{$block->id}}" class="btn btn-primary block_edit"><i class="fa fa-edit"></i></button>
                                            <a href="{{route('admin.warehouse.block.delete',$block->id)}}" class="btn btn-danger delete-confirm @if($block->count_rack->count() > 0) disabled @endif" ><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.rack-list')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                            <table class="table table-responsive-lg-sm table-bordered text-nowrap">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('messages.block/room-code')}}</th>
                                    <th>{{__('messages.rack-code')}}</th>
                                    <th>{{__('messages.status')}}</th>
                                    <th>{{__('messages.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($racks as $rack)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>
                                            @php
                                                $block = \App\Models\Room_Block::find($rack->block_id);
                                            @endphp
                                            {{$block->block_code}}
                                        </td>
                                        <td>{{$rack->rack_code}}</td>
                                        <td class="@if($rack->status == "Active") text-success @else text-danger @endif">@if($rack->status == "Active") {{__('messages.active')}} @else {{__('messages.deactivate')}} @endif</td>
                                        <td>
                                            <button id="{{$rack->id}}" class="btn btn-primary rack_edit"><i class="fa fa-edit"></i></button>
                                            <a href="{{route('admin.warehouse.rack.delete',$rack->id)}}" class="btn btn-danger delete-confirm1 @if($rack->count_shelf->count() > 0) disabled @endif"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.shelf-list')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                            <table id="example" class="table table-responsive-lg-sm table-bordered text-nowrap">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('messages.block/room-code')}}</th>
                                    <th>{{__('messages.rack-code')}}</th>
                                    <th>{{__('messages.shelf-code')}}</th>
                                    <th>{{__('messages.products')}}</th>
                                    <th>{{__('messages.status')}}</th>
                                    <th>{{__('messages.barcode')}}</th>
                                    <th>{{__('messages.QRcode')}}</th>
                                    <th>{{__('messages.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($shelfs as $shelf)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>
                                            @php
                                                $block = \App\Models\Room_Block::find($shelf->block_id);
                                            @endphp
                                            {{$block->block_code}}
                                        </td>
                                        <td>
                                            @php
                                                $rack = \App\Models\Rack::find($shelf->rack_id);
                                            @endphp
                                            {{$rack->rack_code}}
                                        </td>
                                        <td>{{$shelf->shelf_code}}</td>
                                        <td>qty</td>
                                        <td class="@if($shelf->status == "Active") text-success @else text-danger @endif">@if($shelf->status == "Active") {{__('messages.active')}} @else {{__('messages.deactivate')}} @endif</td>
                                        <td>
                                            <img src="data:image/png;base64,{{ DNS1D::setStorPath(__DIR__.'/cache/')->getBarcodePNG((string)$shelf->id, 'C128')}}" alt="sultan">
                                        </td>
                                        <td>
                                            <img src="data:image/png;base64,{{ DNS2D::setStorPath(__DIR__.'/cache/')->getBarcodePNG((string)$shelf->id, 'QRCODE')}}" alt="sultan">
                                        </td>
                                        <td>
                                            <button id="{{$shelf->id}}" class="btn btn-primary shelf_edit"><i class="fa fa-edit"></i></button>
                                            <a  href="{{route('admin.warehouse.shelf.delete',$shelf->id)}}" class="btn btn-danger delete-confirm2 @if($shelf->count_product->count() > 0) disabled @endif"><i class="fa fa-trash"></i></a>
                                            <a href="{{route('admin.barcode.download',['type'=>"shelf",'id'=>$shelf->id,'qty'=>1])}}" target="_blank" class="btn btn-warning"><i class="fa fa-print"></i></a>
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
            <!--/Row-->
        <div class="modal fade" id="block_edit">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{__('messages.edit-block/rooms')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{__('messages.block/room-code')}}</label>
                            <input class="form-control" type="text" id="block_room_code" disabled>
                            <input class="form-control" type="text" id="block_room_code_id" hidden>
                        </div>
                        <div class="form-group">
                            <label class=" text-left">{{__('messages.status')}}</label>
                            <select id="block_status" class="form-select">
                                <option>{{__('messages.active')}}</option>
                                <option>{{__('messages.deactive')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary block_update">{{__('messages.update')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="rack_edit">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{__('messages.edit-rack')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{__('messages.rack-code')}}</label>
                            <input class="form-control" type="text" id="rack_code" disabled>
                            <input class="form-control" type="text" id="rack_code_id" hidden>
                        </div>
                        <div class="form-group">
                            <label class=" text-left">{{__('messages.status')}}</label>
                            <select id="rack_status" class="form-select">
                                <option>{{__('messages.active')}}</option>
                                <option>{{__('messages.deactive')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary rack_update">{{__('messages.update')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="shelf_edit">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">{{__('messages.edit-shelf')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{__('messages.shelf-code')}}</label>
                            <input class="form-control" type="text" id="shelf_code" disabled>
                            <input class="form-control" type="text" id="shelf_code_id" hidden>
                        </div>
                        <div class="form-group">
                            <label class=" text-left">{{__('messages.status')}}</label>
                            <select id="shelf_status" class="form-select">
                                <option>{{__('messages.active')}}</option>
                                <option>{{__('messages.deactive')}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary shelf_update">{{__('messages.update')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                    </div>

                </div>
            </div>
        </div>
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
       $('.add_block').click(function(){
        var id = $(this).data('id'); // Assuming the button has a data-id attribute
        var route = '{{ route("admin.warehouse.block.add", ":id") }}';
        route = route.replace(':id', id);
                $.ajax({
                    url:route,
                    type:'POST',
                    data:$('#add_block').serialize(),
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

        $('.add_rack').click(function(){
        var id = $(this).data('id'); // Assuming the button has a data-id attribute
        var route = '{{ route("admin.warehouse.rack.add", ":id") }}';
        route = route.replace(':id', id);
                $.ajax({
                    url:route,
                    type:'POST',
                    data:$('#add_rack').serialize(),
                    success:function(response){
                        console.log(response);
                        if (response.status == "fail")
                        {
                            $('#add_r_error').html('');
                            $('#add_r_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_r_error').append('<li>'+error+'</li>');
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

        $('.add_shelf').click(function(){
        var id = $(this).data('id'); // Assuming the button has a data-id attribute
        var route = '{{ route("admin.warehouse.shelf.add", ":id") }}';
        route = route.replace(':id', id);
                $.ajax({
                    url:route,
                    type:'POST',
                    data:$('#add_shelf').serialize(),
                    success:function(response){
                        console.log(response);
                        if (response.status == "fail")
                        {
                            $('#add_s_error').html('');
                            $('#add_s_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_s_error').append('<li>'+error+'</li>');
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

     $('.delete-confirm').on('click', function (event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this Room! ',
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
                            title: 'Your Room/Block is Safe!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
            });
        });

         $('.delete-confirm1').on('click', function (event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this Rack!',
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
                            title: 'Your Rack is Safe!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
            });
        });

         $('.delete-confirm2').on('click', function (event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this Shelf!',
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
                            title: 'Your Shelf is Safe!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
            });
        });
  
  
        function show_hide(id)
        {
            if (id == "block")
            {
                var x = document.getElementById("block_room");
                var y = document.getElementById("rack");
                var z = document.getElementById("shelf");
                y.style.display = "none";
                z.style.display = "none";
                if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
            }
            if (id == "rack")
            {
                var x = document.getElementById("rack");
                var y = document.getElementById("block_room");
                var z = document.getElementById("shelf");
                y.style.display = "none";
                z.style.display = "none";
                if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
            }
            if (id == "shelf")
            {
                var x = document.getElementById("shelf");
                var y = document.getElementById("rack");
                var z = document.getElementById("block_room");
                y.style.display = "none";
                z.style.display = "none";
                if (x.style.display === "none") {
                    x.style.display = "block";
                } else {
                    x.style.display = "none";
                }
            }
        }
        $(document).on('change','#block_list',function () {
            var id = $(this).val();
            $('#rack_list').html('');
            var route = "{{route('admin.warehouse')}}";
            $.get(route+'-show-racks/'+ id, function (racks) {
                $('#rack_list').append('<option disabled selected>Please Select</option>');
                $.each(racks,function (key,rack) {
                    $('#rack_list').append('<option value="'+rack.id+'">'+rack.rack_code+'</option>');
                });
            });
        });
        $(document).on('click','.block_edit',function () {
            var id = $(this).attr("id");
            var route = "{{route('admin.warehouse')}}";
            $.get(route+'-block-room/edit/'+ id, function (block) {
                $('#block_room_code_id').val(block.id);
                $('#block_room_code').val(block.block_code);
                $("#block_edit select option").each(function(){
                    if ($(this).text() == block.status)
                        $(this).attr("selected","selected");
                });
                $('#block_edit').modal('toggle');
            });
        });
        $(document).on('click','.block_update',function () {
            var data = {
                'id': $('#block_room_code_id').val(),
                'status': $('#block_status').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{route('admin.warehouse.block.update')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    if (response.status == 200)
                    {
                        $('#block_edit').modal('hide');
                        Swal.fire({
                            title: 'Congratulations!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(location.reload(), 1500);
                    }
                }
            })
        });

        $(document).on('click','.rack_edit',function () {
            var id = $(this).attr("id");
            var route = "{{route('admin.warehouse')}}";
            $.get(route+'-rack/edit/'+ id, function (block) {
                $('#rack_code_id').val(block.id);
                $('#rack_code').val(block.rack_code);
                $("#rack_edit select option").each(function(){
                    if ($(this).text() == block.status)
                        $(this).attr("selected","selected");
                });
                $('#rack_edit').modal('toggle');
            });
        });
        $(document).on('click','.rack_update',function () {
            var data = {
                'id': $('#rack_code_id').val(),
                'status': $('#rack_status').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{route('admin.warehouse.rack.update')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    if (response.status == 200)
                    {
                        $('#rack_edit').modal('hide');
                        Swal.fire({
                            title: 'Congratulations!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(location.reload(), 1500);
                    }
                }
            })
        });

        $(document).on('click','.shelf_edit',function () {
            var id = $(this).attr("id");
            var route = "{{route('admin.warehouse')}}";
            $.get(route+'-shelf/edit/'+ id, function (block) {
                $('#shelf_code_id').val(block.id);
                $('#shelf_code').val(block.shelf_code);
                $("#shelf_edit select option").each(function(){
                    if ($(this).text() == block.status)
                        $(this).attr("selected","selected");
                });
                $('#shelf_edit').modal('toggle');
            });
        });
        $(document).on('click','.shelf_update',function () {
            var data = {
                'id': $('#shelf_code_id').val(),
                'status': $('#shelf_status').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{route('admin.warehouse.shelf.update')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    if (response.status == 200)
                    {
                        $('#shelf_edit').modal('hide');
                        Swal.fire({
                            title: 'Congratulations!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(location.reload(), 1500);
                    }
                }
            })
        });
    </script>
@endsection
