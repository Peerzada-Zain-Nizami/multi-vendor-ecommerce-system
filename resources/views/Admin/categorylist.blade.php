@php
use App\Models\Category;
class categories
{
    public function format($id)
    {
        $data = Category::where('parent_id', $id)->with('children')->get();
        foreach ($data as $category) {
            echo '<li><a href="javascript:void(0);" id="' .
                $category->id .
                '" class="cat_click" name="' .
                \App\MyClasses\Helpers::get_lang(
                    $category->category_name,
                    $category->id,
                    'category',
                    App::getLocale(),
                ) .
                '">' .
                \App\MyClasses\Helpers::get_lang(
                    $category->category_name,
                    $category->id,
                    'category',
                    App::getLocale(),
                ) .
                '</a>';
            if ($category->children->isNotEmpty()) {
                echo '<ul>';
                self::format($category->id);
                echo '</ul>';
            }
            echo '</li>';
        }
    }
}
$chaild = new categories();
@endphp
@extends('Admin.base')

@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.categories-list') }}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.category-action') }}</h3>
                        </div>
                        @if (Session::has('success'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                                    aria-hidden="true">×</button>
                                <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <form id="add_category">
                                <div id="add_error"></div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.new-category-name') }}</label>
                                    <input type="text" class="form-control" name="category_name">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.add-parent-category') }}</label>
                                    <select name="parent_category" class="form-control select2-show-search">
                                        <option value="">{{ __('messages.please-select') }}</option>
                                        @foreach ($results as $result)
                                            <option value="{{ $result->id }}">
                                                {{ \App\MyClasses\Helpers::get_lang($result->category_name, $result->id, 'category', App::getLocale()) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-12">
                                    <button class="btn btn-primary add_submit"
                                        type="button">{{ __('messages.add-new') }}</button>
                                </div>
                            </form>
                        </div>
                        <div id="form" style="display: none;">
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="text" id="id" name="id" hidden>
                                    <label class="form-label">{{ __('messages.selected-category') }}</label>
                                    <input id="name" type="text" class="form-control" disabled>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-danger del">{{ __('messages.delete') }}</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <button
                                    class="btn btn-primary btn-sm add_lang_model">{{ __('messages.add-language') }}</button>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.languages') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="result_langs">

                                    </tbody>
                                </table>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="form-group">
                                        <label class="form-label">{{ __('messages.change-parent-category') }}</label>
                                        <select id="parent_category" class="form-control select2-show-search">
                                            <option value="">{{ __('messages.please-select') }}</option>
                                            @foreach ($results as $result)
                                                <option value="{{ $result->id }}">
                                                    {{ \App\MyClasses\Helpers::get_lang($result->category_name, $result->id, 'category', App::getLocale()) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="button"
                                            class="btn btn-primary update">{{ __('messages.update') }}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-body">
                                <div id="sub_error"></div>
                                <form>
                                    <div class="form-group">
                                        <label class="form-label">{{ __('messages.add-sub-category') }}</label>
                                        <input type="text" class="form-control" id="category_name">
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary add_sub"
                                            type="button">{{ __('messages.add-sub-category') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.category-list') }}</h3>
                        </div>
                        <div class="card-body">
                            <ul id="tree1">
                                @foreach ($categories as $category)
                                    <li><a href="javascript:void(0);" id="{{ $category->id }}" class="cat_click"
                                            name="{{ \App\MyClasses\Helpers::get_lang($category->category_name, $category->id, 'category', App::getLocale()) }}">{{ \App\MyClasses\Helpers::get_lang($category->category_name, $category->id, 'category', App::getLocale()) }}</a>
                                        @if ($category->children->isNotEmpty())
                                            <ul>
                                                {{ $chaild->format($category->id) }}
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Row-->


        </div>
    </div>
    <!-- CONTAINER END -->
    <div class="modal fade" id="lang_model">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('messages.add-language') }}</h6><button aria-label="Close"
                        class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="add_lang_error"></div>
                    <input type="text" id="ref_id" hidden>
                    <div class="form-group">
                        <label> {{ __('messages.category-name') }}</label>
                        <input type="text" id="lang_category_name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class=" text-left">{{ __('messages.language-type') }}</label>
                        <select id="lang_type" class="form-control">
                            <option value="">{{ __('messages.please-select') }}</option>
                            <option value="ar">{{ __('messages.arabic') }}</option>
                            <option value="ur">{{ __('messages.urdu') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary add_lang">{{ __('messages.add') }}</button> <button
                        class="btn btn-secondary" data-bs-dismiss="modal"
                        type="button">{{ __('messages.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="lang_model_edit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('messages.update-language') }}</h6><button aria-label="Close"
                        class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="update_error"></div>
                    <input type="text" id="update_id" hidden>
                    <div class="form-group">
                        <label> {{ __('messages.category-name') }}</label>
                        <input type="text" id="update_category" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update_lang">{{ __('messages.update') }}</button> <button
                        class="btn btn-secondary" data-bs-dismiss="modal"
                        type="button">{{ __('messages.close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('query')
    <script type="application/javascript">
        $(document).on('click','.add_lang_model',function () {
            $('#lang_type').html('');
            $('#add_lang_error').html('');
            $('#add_lang_error').removeClass('alert alert-light-danger');
            var id = $('#id').val();
            var data = {
                'ref_id': id,
                'ref_type': "category",
            };
            $.ajax({
                url: "{{route('admin.get.languages')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    var lang_list = "";
                    lang_list+= "<option value=''>Please Select</option>";
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
            $('#update_error').html('');
            $('#update_error').removeClass('alert alert-light-danger');
            var id = $(this).attr('id');
            var route = "{{route('admin.category.lang.add')}}";
            $.get(route+'/edit/'+ id, function (lang) {
                $('#update_id').val(lang.id);
                $('#update_category').val(lang.lang_data);
                $('#lang_model_edit').modal('toggle');
            });
        });
        $(Document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.add_submit').click(function(){
                $.ajax({
                    url:'{{route('admin.category.add')}}',
                    type:'POST',
                    data:$('#add_category').serialize(),
                    success:function(response){
                        if (response.status == "fail")
                        {
                            $('#add_error').html('');
                            $('#add_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_error').append('<li>'+error+'</li>');
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
            $('.cat_click').click(function () {
                var id = $(this).attr("id");
                var name = $(this).attr("name");
                $('#result_langs').html('');
                var data = {
                    'ref_id': id,
                    'ref_type': "category",
                };
                $.ajax({
                    url: "{{route('admin.get.languages_2')}}",
                    type:"POST",
                    dataType: "json",
                    data: data,
                    success: function(response){
                        $('#form').show();
                        $('#id').val(id);
                        $('#name').val(name);
                        $('#result_langs').append(response);
                    }
                });
            });
            $('.del').click(function() {
                var id = $('#id').val();
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Category!",
                    icon: "warning",
                    showCancelButton: true,
                    dangerMode: true,
                    confirmButtonText: "Ok, Delete it!",
                    showConfirmButton: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: '{{route('admin.category.del')}}',
                            type: 'POST',
                            data: {
                                id: id,
                                _token: '{{ csrf_token() }}'
                            },
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
                    } else {
                        Swal.fire({
                            title: 'Your Category is Safe!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
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
                window.location.href = '{{route('admin.category.list')}}';
            }

        });
    </script>
@endsection
