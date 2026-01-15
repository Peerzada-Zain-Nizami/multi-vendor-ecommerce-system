@extends('W_admin.base')


@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{$warehouse->warehouse_id}}/{{$warehouse->warehouse_name}}</h4>
                    <h6 class="page-title mb-0 text-muted">{{$warehouse->address}}</h6>
                </div>
                <div class="page-rightheader">
                    <div class="btn-list">
                        <button onclick="show_hide('block')" class="btn btn-primary"><i class="fa fa-plus"></i>
                            Block/Room</button>
                        <button onclick="show_hide('rack')" class="btn btn-primary"><i class="fa fa-plus"></i>
                            Rack</button>
                        <button onclick="show_hide('shelf')" class="btn btn-primary"><i class="fa fa-plus"></i>
                            Shelf</button>
                    </div>
                </div>
            </div>
            @if (Session::has('success'))
                <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>Well done!</strong> {{Session::get('success')}}
                </div>
            @endif
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div id="block_room" style="@if(!empty(old('block_tab'))) display: block; @else display: none; @endif" class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Block/Room Add</div>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-6 offset-3">
                                <form action="{{route('admin.warehouse.block.add',$id)}}" method="POST">
                                    @csrf
                                    <input type="text" name="block_tab" value="block_tab" hidden>
                                    <div class="form-group">
                                        <label class=" text-left">Block/Room Code</label>
                                        <input type="text" name="block_code" value="{{old('block_code')}}" class="form-control"/>
                                    </div>
                                    @if ($errors->has('block_code'))
                                        <span class="text-danger">{{ $errors->first('block_code') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <label class=" text-left">Status</label>
                                        <select name="status" class="form-control select2">
                                            <option disabled selected>Please Select Status</option>
                                            <option>Active</option>
                                            <option>Deactive</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Add Block/Room">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="rack" style="@if(!empty(old('rack_tab'))) display: block; @else display: none; @endif" class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Rack Add</div>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-6 offset-3">
                                <form action="{{route('admin.warehouse.rack.add',$id)}}" method="POST">
                                    @csrf
                                    <input type="text" name="rack_tab" value="rack_tab" hidden>
                                    <div class="form-group">
                                        <label class=" text-left">Block/Room Code</label>
                                        <select name="block_id" class="form-control select2">
                                            <option disabled selected>Please Select</option>
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
                                        <label class=" text-left">Rack Code</label>
                                        <input type="text" name="rack_code" value="{{old('rack_code')}}" class="form-control"/>
                                    </div>
                                    @if ($errors->has('rack_code'))
                                        <span class="text-danger">{{ $errors->first('rack_code') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <label class=" text-left">Status</label>
                                        <select name="status" class="form-control select2">
                                            <option disabled selected>Please Select Status</option>
                                            <option>Active</option>
                                            <option>Deactive</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Add Rack">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="shelf" style="@if(!empty(old('shelf_tab'))) display: block; @else display: none; @endif" class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Shelf Add</div>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-6 offset-3">
                                <form action="{{route('admin.warehouse.shelf.add',$id)}}" method="POST">
                                    @csrf
                                    <input type="text" name="shelf_tab" value="shelf_tab" hidden>
                                    <div class="form-group">
                                        <label class=" text-left">Block/Room Code</label>
                                        <select name="block_id" id="block_list" class="form-control select2">
                                            <option disabled selected>Please Select</option>
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
                                        <label class=" text-left">Rack Code</label>
                                        <select name="rack_id" id="rack_list" class="form-control select2">

                                        </select>
                                    </div>
                                    @if ($errors->has('rack_id'))
                                        <span class="text-danger">{{ $errors->first('rack_id') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <label class=" text-left">Shelf Code</label>
                                        <input type="text" name="shelf_code" value="{{old('shelf_code')}}" class="form-control"/>
                                    </div>
                                    @if ($errors->has('shelf_code'))
                                        <span class="text-danger">{{ $errors->first('shelf_code') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <label class=" text-left">Status</label>
                                        <select name="status" class="form-control select2">
                                            <option disabled selected>Please Select Status</option>
                                            <option>Active</option>
                                            <option>Deactive</option>
                                        </select>
                                    </div>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary" value="Add Shelf">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Blocks/Rooms List</div>
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive table-bordered text-nowrap">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Block/Room Code</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
                                    <td class="@if($block->status == "Active") text-success @else text-danger @endif">{{$block->status}}</td>
                                    <td>
                                        <button id="{{$block->id}}" class="btn btn-primary block_edit"><i class="fa fa-edit"></i></button>
                                        <a href="{{route('admin.warehouse.block.delete',$block->id)}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Rack's List</div>
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive table-bordered text-nowrap">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Block/Room Code</th>
                                    <th>Rack Code</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
                                        <td class="@if($rack->status == "Active") text-success @else text-danger @endif">{{$rack->status}}</td>
                                        <td>
                                            <button id="{{$rack->id}}" class="btn btn-primary rack_edit"><i class="fa fa-edit"></i></button>
                                            <a href="{{route('admin.warehouse.rack.delete',$rack->id)}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Shelf's List</div>
                        </div>
                        <div class="card-body">
                            <table class="table table-responsive table-bordered text-nowrap">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Block/Room Code</th>
                                    <th>Rack Code</th>
                                    <th>Shelf Code</th>
                                    <th>Products</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
                                        <td class="@if($shelf->status == "Active") text-success @else text-danger @endif">{{$shelf->status}}</td>
                                        <td>
                                            <button id="{{$shelf->id}}" class="btn btn-primary shelf_edit"><i class="fa fa-edit"></i></button>
                                            <a href="{{route('admin.warehouse.shelf.delete',$shelf->id)}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
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
            <!--/Row-->
        <div class="modal fade" id="block_edit">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">Edit Block/Rooms</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Block/Room Code</label>
                            <input class="form-control" type="text" id="block_room_code" disabled>
                            <input class="form-control" type="text" id="block_room_code_id" hidden>
                        </div>
                        <div class="form-group">
                            <label class=" text-left">Status</label>
                            <select id="block_status" class="form-control">
                                <option>Active</option>
                                <option>Deactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary block_update">Update</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="rack_edit">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">Edit Rack</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Rack Code</label>
                            <input class="form-control" type="text" id="rack_code" disabled>
                            <input class="form-control" type="text" id="rack_code_id" hidden>
                        </div>
                        <div class="form-group">
                            <label class=" text-left">Status</label>
                            <select id="rack_status" class="form-control">
                                <option>Active</option>
                                <option>Deactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary rack_update">Update</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="shelf_edit">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">Edit Shelf</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Shelf Code</label>
                            <input class="form-control" type="text" id="shelf_code" disabled>
                            <input class="form-control" type="text" id="shelf_code_id" hidden>
                        </div>
                        <div class="form-group">
                            <label class=" text-left">Status</label>
                            <select id="shelf_status" class="form-control">
                                <option>Active</option>
                                <option>Deactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary shelf_update">Update</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                    </div>

                </div>
            </div>
        </div>
        </div>
    <!-- CONTAINER END -->

@endsection
@section('query')
    <script type="text/javascript">
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
