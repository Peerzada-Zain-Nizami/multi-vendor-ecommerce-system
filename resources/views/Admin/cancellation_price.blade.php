@extends('Admin.base')

@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.shipping-charges') }}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.group-action') }}</h3>
                        </div>
                        @if (Session::has('success'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                                    aria-hidden="true">×</button>
                                <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <form id="cancel_price">
                                <div id="add_g_error"></div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.please-select-shipping-company') }}</label>
                                    <select name="shipping_company" class="form-control select2-show-search">
                                        <option value="">{{ __('messages.please-select') }}</option>
                                        @foreach ($shipping_companies as $shipping_company)
                                            <option value="{{ $shipping_company->id }}">{{ $shipping_company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.cancellation-price') }}</label>
                                    <input type="text" class="form-control" name="cancellation_price">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary add_c_price"
                                        type="button">{{ __('messages.add-new') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.cancellation-detail') }}</h3>
                        </div>
                        @if (Session::has('Success'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                                    aria-hidden="true">×</button>
                                <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('Success') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table id="example1" class="table table-responsive-lg-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.shipping-company') }}</th>
                                            <th>{{ __('messages.cancellation-price') }}</th>
                                            <th>{{ __('messages.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($shipping_companies_show as $shipping_company_show)
                                            @if ($shipping_company_show->cancellation_price == null)
                                            @else
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{ $shipping_company_show->name }}</td>
                                                    <td>{{ $shipping_company_show->cancellation_price }}</td>
                                                    <td>
                                                        <div class="btn-group mt-2 mb-2">
                                                            <button type="button"
                                                                class="btn btn-light btn-pill dropdown-toggle"
                                                                data-bs-toggle="dropdown">
                                                                {{ __('messages.action') }} <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a
                                                                        href="{{ route('admin.warehouse.edit', $shipping_company_show->id) }}">{{ __('messages.edit') }}</a>
                                                                </li>
                                                                <li><a href="javascript:void(0);"
                                                                        id="{{ $shipping_company_show->id }}"
                                                                        class="add_lang_model">{{ __('messages.delete') }}</a>
                                                                </li>
                                                                <li><a
                                                                        href="{{ route('admin.shipping.company.delete', $shipping_company_show->id) }}">{{ __('messages.delete') }}</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Row-->
        </div>
    </div>
    <!-- CONTAINER END -->
    {{-- <div class="modal fade" id="lang_model">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.add-language')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="add_lang_error"></div>
                    <input type="text" id="ref_id" hidden>
                    <div class="form-group">
                        <label> {{__('messages.category-name')}}</label>
                        <input type="text" id="lang_category_name" class="form-control"/>
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
                        <label> {{__('messages.category-name')}}</label>
                        <input type="text" id="update_category" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update_lang">{{__('messages.update')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>
            </div>
        </div>
    </div> --}}
@endsection
@section('query')
    <script type="application/javascript">
        $(Document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.add_c_price').click(function(){
                $.ajax({
                    url:'{{route('admin.cancellation.price.add')}}',
                    type:'POST',
                    data:$('#cancel_price').serialize(),
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
            $('.del').click(function(){
                var id = $('#id').val();
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Category!",
                    icon:"warning",
                    showCancelButton: true,
                    dangerMode: true,
                    confirmButtonText: "Ok, Delete it!",
                    showConfirmButton: true,

                })
                    .then((willDelete) => {

                        if (willDelete) {
                            $.ajax({
                                url:'{{route('admin.category.del')}}',
                                type:'post',
                                data:{id:id},
                                success:function(data){
                                    Swal.fire({
                                        title: 'Congratulations!',
                                        text: "Your Category has been successfully deleted",
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    setTimeout(loct, 1500);
                                }
                            });


                        } else {
                            Swal.fire({
                                title: 'Your Category is Safe!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    });


            });
            $('.update').click(function(){
                var id = $('#id').val();
                var update = {
                    'id':id,
                    'parent_category':$('#parent_category').val(),
                };
                $.ajax({
                    url:'{{route('admin.category.update')}}',
                    type:'POST',
                    data:update,
                    success:function(response){
                        if (response.status == "pass")
                        {
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
            $('.add_sub').click(function(){
                var id = $('#id').val();
                var sub_data = {
                    'id':id,
                    'category_name':$('#category_name').val(),
                };
                console.log(sub_data);
                $.ajax({
                    url:'{{route('admin.category.sub')}}',
                    type:'POST',
                    data:sub_data,
                    success:function(response){
                        if (response.status == "fail")
                        {
                            $('#sub_error').html('');
                            $('#sub_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#sub_error').append('<li>'+error+'</li>');
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
            $('.add_lang').click(function () {
                var data = {
                    'category_name': $('#lang_category_name').val(),
                    'lang_type': $('#lang_type').val(),
                    'ref_id': $('#ref_id').val(),
                };
                console.log(data);
                $.ajax({
                    url: "{{route('admin.category.lang.add')}}",
                    type:"POST",
                    dataType: "json",
                    data: data,
                    success: function(response){
                        if (response.status == "fail")
                        {
                            $('#add_lang_error').html('');
                            $('#add_lang_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_lang_error').append('<li>'+error+'</li>');
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
                    'category_name': $('#update_category').val(),
                    'id': $('#update_id').val(),
                };
                console.log(data);
                $.ajax({
                    url: "{{route('admin.category.lang.update')}}",
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
                window.location.href = '{{route('admin.shipping.index')}}';
            }

        });
    </script>
@endsection
