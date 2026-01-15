@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.tags-management')}}</h4>
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
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.add-new')}}</div>
                        </div>
                        <div class="card-body">
                            <form id="add_tag">
                               <div id="add_g_error"></div>
                                <div class="form-group">
                                    <input name="name" class="form-control @if($errors->has('name'))is-invalid @endif" type="text" placeholder="{{__('messages.tag-name')}}">
                                </div>
                                @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    <br>
                                @endif
                                <div class="form-group">
                                    <select name="status" class="form-control select2">
                                        <option disabled selected>{{__('messages.please-select-status')}}</option>
                                        <option>{{__('messages.active')}}</option>
                                        <option>{{__('messages.deactive')}}</option>
                                    </select>
                                </div>
                                @if ($errors->has('status'))
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                    <br>
                                @endif
                                <div class="form-group">
                                    <input class="btn btn-primary add_tag" value="{{__('messages.add-new')}}" type="button" >
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.tags-list')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered text-nowrap key-buttons dataTable no-footer" role="grid" aria-describedby="example1_info">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('messages.tag-names')}}</th>
                                        <th>{{__('messages.status')}}</th>
                                        <th>{{__('messages.languages')}}</th>
                                        <th>{{__('messages.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach($results as $result)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{\App\MyClasses\Helpers::get_lang($result->name,$result->id,"Seller_tag",App::getLocale())}}</td>
                                            <td class="@if($result->status == "Active") text-success @else text-danger @endif">@if($result->status == "Active") {{__('messages.active')}} @else {{__('messages.deactive')}} @endif</td>
                                            <td>
                                                <div class="w-100" style="overflow: auto; height: 50px;">
                                                    @php
                                                        $langs = \App\MyClasses\Helpers::act_lang($result->id,"Seller_tag");
                                                    @endphp
                                                    @if(count($langs) > 0)
                                                        <table class="table-striped table-hover table-sm">
                                                            @foreach($langs as $lang)
                                                                <tr>
                                                                    <td><img src="{{asset ('assets/images/flags/'.Config::get('languages')[$lang->language]['flag-icon'].'.svg')}}" width="30px" height="20px" class="me-2">{{Config::get('languages')[$lang->language]['display']}}</td>
                                                                    <td>
                                                                        <button id="{{$lang->id}}" class="btn text-warning btn-sm update_lang_model"><i class="fa fa-edit"></i></button>
                                                                        <a href="javascript:void(0);" class="btn text-danger btn-sm delete-btn" data-url="{{ route('seller.tag.lang.del', $lang->id) }}">
                                                                            <i class="fa fa-trash"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    @else
                                                        <p class="text-danger">{{__('messages.language-not-set')}}</p>
                                                    @endif

                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group mt-2 mb-2">
                                                    <button type="button" class="btn btn-light btn-pill dropdown-toggle" data-bs-toggle="dropdown">
                                                        {{__('messages.action')}} <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu">

                                                        <li><a href="javascript:void(0);" id="{{$result->id}}" class="edit">{{__('messages.edit')}}</a></li>
                                                        <li><a  href="javascript:void(0);" id="{{ $result->id }}" class="del">{{__('messages.delete')}}</a></li>
                                                        <li><a href="javascript:void(0);" id="{{$result->id}}" class="add_lang_model">{{__('messages.add-language')}}</a></li>

                                                    </ul>
                                                </div>


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
        <!--/div-->


    </div>
    </div>
    <div class="modal fade" id="edit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.edit-tag')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <input id="id" type="number" hidden />
                    <div class="form-group">
                        <input type="text" id="name" class="form-control" disabled/>
                    </div>
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.status')}}</label>
                        <select id="status" class="form-control">
                            <option>{{__('messages.active')}}</option>
                            <option>{{__('messages.deactive')}}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update">{{__('messages.update')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- CONTAINER END -->
    <div class="modal fade" id="lang_model">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.add-language')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="add_error"></div>
                    <input type="text" id="ref_id" hidden>
                    <div class="form-group">
                        <label> {{__('messages.tag-name')}}</label>
                        <input type="text" id="tag_name" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.language-type')}}</label>
                        <select id="lang_type" class="form-control">
                            <option value="">{{__('messages.please-select')}}</option>
                            <option value="ar">{{__('messages.arabic')}}</option>
                            <option value="ur">{{__('messages.urdu')}}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary add_lang">{{__('messages.add')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="lang_model_edit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.update-language')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="update_error"></div>
                    <input type="text" id="update_id" hidden>
                    <div class="form-group">
                        <label> {{__('messages.business-model-name')}}</label>
                        <input type="text" id="update_tag_name" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update_lang">{{__('messages.update')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('query')
    <script type="text/javascript">
        $(document).on('click','.edit',function () {
            var id = $(this).attr("id");
            var route = "{{route('seller.tags.list')}}";
            $.get(route+'/edit/'+ id, function (product) {
                $('#id').val(product.id);
                $('#name').val(product.name);
                $("select option").each(function(){
                    if ($(this).text() == product.status)
                        $(this).attr("selected","selected");
                });
                $('#edit').modal('toggle');
            });
        });
        $(document).on('click','.add_lang_model',function () {
            $('#lang_type').html('');
            var id = $(this).attr('id');
            var data = {
                'ref_id': id,
                'ref_type': "Seller_tag",
            };
            $.ajax({
                url: "{{route('seller.tag.get.languages')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    var lang_list = "";
                    var Please_Select = "{{__('messages.please-select')}}";
                    lang_list+= "<option value=''>"+Please_Select+"</option>";
                    $.each(response,function (value,key) {
                        if (key.sort != "en")
                        {
                            lang_list+= "<option value='"+key.sort+"'>"+key.display+"</option>";
                        }
                    });
                    $('#lang_type').append(lang_list);
                    $('#ref_id').val(id);
                    $('#lang_model').modal('toggle');
                }
            });
        });
        $(document).on('click','.update_lang_model',function () {
            var id = $(this).attr('id');
            var route = "{{route('seller.tag.lang.add')}}";
            $.get(route+'/edit/'+ id, function (lang) {
                $('#update_id').val(lang.id);
                $('#update_tag_name').val(lang.lang_data);
                $('#lang_model_edit').modal('toggle');
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.update').click(function () {
            var data = {
                'id': $('#id').val(),
                'status': $('#status').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{route('seller.tag.update')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    if (response.status == 200)
                    {
                        console.log(response.message);
                        $('#edit').modal('hide');
                        Swal.fire({
                            title: 'Congratulations!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(loct, 1500);
                    }
                }
            })
        });
        $('.add_lang').click(function () {
            var data = {
                'tag_name': $('#tag_name').val(),
                'lang_type': $('#lang_type').val(),
                'ref_id': $('#ref_id').val(),
            };
            $.ajax({
                url: "{{route('seller.tag.lang.add')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    console.log(response);
                    if (response.status == "fail")
                    {
                        $('#add_error').html('');
                        $('#add_error').addClass('alert alert-light-danger');
                        $.each(response.errors,function (key,error) {
                            $('#add_error').append('<li>'+error+'</li>');
                        })
                    }
                    else{
                        $('#lang_model').modal('hide');
                        Swal.fire({
                            title: 'Congratulations!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(loct, 1500);
                    }
                }
            })
        });
        $('.update_lang').click(function () {
            var data = {
                'tag_name': $('#update_tag_name').val(),
                'id': $('#update_id').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{route('seller.tag.lang.update')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    if (response.status == "fail")
                    {
                        $('#update_error').html('');
                        $('#update_error').addClass('alert alert-light-danger');
                        $.each(response.errors,function (key,error) {
                            $('#update_error').append('<li>'+error+'</li>');
                        })
                    }
                    else{
                        $('#lang_model_edit').modal('hide');
                        Swal.fire({
                            title: 'Congratulations!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(loct, 1500);
                    }
                }
            })
        });
        function loct() {
            window.location.href = '{{route('seller.tags.list')}}';
        }
  $(Document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
       $('.add_tag').click(function(){
                $.ajax({
                    url:'{{route('seller.tag.add')}}',
                    type:'POST',
                    data:$('#add_tag').serialize(),
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
                            setTimeout(loct, 1500);
                        }
                    }
                });
            });

             $('.del').click(function() {
            var id = $(this).attr('id');
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Tax!",
                    icon: "warning",
                    showCancelButton: true,
                    dangerMode: true,
                    confirmButtonText: "Ok, Delete it!",
                    showConfirmButton: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: '{{ route('seller.tag.del') }}',
                            type: 'POST',
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response){
                                console.log(response);
                                if (response.status === 'success') {
                                    Swal.fire({
                                        title: 'Congratulations!',
                                        text: response.message,
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    setTimeout(function() {
                                        location
                                            .reload();
                                    }, 1500);
                                } else if (response.status === 'error') {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.message,
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
                    } else {
                        Swal.fire({
                            title: 'Your Tax is Safe!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
        });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let deleteUrl = this.getAttribute('data-url');

                Swal.fire({
                    icon: "warning",
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Language!",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Delete",
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    </script>
@endsection
