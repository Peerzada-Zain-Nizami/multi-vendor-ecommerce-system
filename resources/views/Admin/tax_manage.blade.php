@extends('Admin.base')

@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.tax-management') }}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="row">
                @if (Session::has('success'))
                    <div id="success-alert2" class="alert alert-light-success" role="alert">
                        <strong>{{ __('messages.well-done') }}</strong> {{ session('success') }}
                    </div>
                @endif

                @if (Session::has('danger'))
                    <div id="success-alert" class="alert alert-light-danger" role="alert">
                        <strong>{{ __('messages.oopps') }}</strong> {{ session('danger') }}
                    </div>
                @endif

                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{ __('messages.add-type') }}</div>
                        </div>
                        <div class="card-body">
                            <form id="add_tax">
                                 <div id="add_g_error"></div>
                                <div class="form-group">
                                    <input name="name"
                                        class="form-control"
                                        value="{{ old('name') }}" type="text"
                                        placeholder="{{ __('messages.tax-name') }}">
                                </div>
                                {{-- @if ($errors->has('name'))
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                    <br>
                                @endif --}}
                                <div class="form-group">
                                    <input name="percent"
                                        class="form-control"
                                        value="{{ old('percent') }}" type="text"
                                        placeholder="{{ __('messages.Rate') }}">
                                </div>
                                {{-- @if ($errors->has('percent'))
                                    <span class="text-danger">{{ $errors->first('percent') }}</span>
                                    <br>
                                @endif --}}
                                <div class="form-group">
                                    <select name="status" class="form-control select2">
                                        <option disabled selected>{{ __('messages.please-select-status') }}</option>
                                        <option>{{ __('messages.active') }}</option>
                                        <option>{{ __('messages.deactive') }}</option>
                                    </select>
                                </div>
                                {{-- @if ($errors->has('status'))
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                    <br>
                                @endif --}}
                                <div class="form-group">
                                    <button class="btn btn-primary add_submit"type="button">{{ __('messages.add-new') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{ __('messages.tax-list') }}</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table id="example1"
                                    class="table table-responsive-lg-sm table-bordered text-nowrap key-buttons dataTable no-footer"
                                    role="grid" aria-describedby="example1_info">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.tax-name') }}</th>
                                            <th>{{ __('messages.Rate') }}</th>
                                            <th>{{ __('messages.status') }}</th>
                                            <th>{{ __('messages.languages') }}</th>
                                            <th>{{ __('messages.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
$i = 1;
                                        @endphp
                                        @foreach ($results as $result)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ \App\MyClasses\Helpers::get_lang($result->name, $result->id, 'tax', App::getLocale()) }}
                                                </td>
                                                <td>{{ $result->percent }}</td>
                                                <td
                                                    class="@if ($result->status == 'Active') text-success @else text-danger @endif">
                                                    @if ($result->status == 'Active')
                                                        {{ __('messages.active') }}
                                                    @else
                                                        {{ __('messages.deactivate') }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="w-100" style="overflow: auto; height: 50px;">
                                                        @php
    $langs = \App\MyClasses\Helpers::act_lang(
        $result->id,
        'tax',
    );
                                                        @endphp
                                                        @if (count($langs) > 0)
                                                            <table class="table-striped table-hover table-sm">
                                                                @foreach ($langs as $lang)
                                                                    <tr>
                                                                        <td><img src="{{ asset('assets/images/flags/' . Config::get('languages')[$lang->language]['flag-icon'] . '.svg') }}"
                                                                                width="30px" height="20px"
                                                                                class="me-2">{{ Config::get('languages')[$lang->language]['display'] }}
                                                                        </td>
                                                                        <td>
                                                                            <button id="{{ $lang->id }}"
                                                                                class="btn text-warning btn-sm update_lang_model"><i
                                                                                    class="fa fa-edit"></i></button>
                                                                            <a href="{{ route('admin.tax.lang.del', $lang->id) }}"
                                                                                class="btn text-danger btn-sm"><i
                                                                                    class="fa fa-trash"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        @else
                                                            <p class="text-danger">{{ __('messages.language-not-set') }}
                                                            </p>
                                                        @endif

                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="btn-group mt-2 mb-2">
                                                        <button type="button"
                                                            class="btn btn-light btn-pill dropdown-toggle"
                                                            data-bs-toggle="dropdown">
                                                            {{ __('messages.action') }} <span class="caret"></span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu">

                                                            <li><a href="javascript:void(0);" id="{{ $result->id }}"
                                                                    class="edit">{{ __('messages.edit') }}</a></li>
                                                            <li><a href="javascript:void(0);" id="{{ $result->id }}"
                                                                    class="del">{{ __('messages.delete') }}</a></li>
                                                            <li><a href="javascript:void(0);" id="{{ $result->id }}"
                                                                    class="add_lang_model">{{ __('messages.add-language') }}</a>
                                                            </li>

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
    <div class="modal fade" id="edit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('messages.edit-tax') }}</h6><button aria-label="Close" class="btn-close"
                        data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="errors">
                    </div>
                    <input id="id" type="number" hidden />
                    <div class="form-group">
                        <input type="text" id="name" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <input type="text" id="percent" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label class=" text-left">{{ __('messages.status') }}</label>
                           <select id="status" class="form-select">
                            <option>{{ __('messages.active') }}</option>
                            <option>{{ __('messages.deactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update">{{ __('messages.update') }}</button> <button
                        class="btn btn-secondary" data-bs-dismiss="modal"
                        type="button">{{ __('messages.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="lang_model">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('messages.add-language') }}</h6><button aria-label="Close"
                        class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="add_error"></div>
                    <input type="text" id="ref_id" hidden>
                    <div class="form-group">
                        <label> {{ __('messages.tax-name') }}</label>
                        <input type="text" id="tax_name" class="form-control" />
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
                        <label> {{ __('messages.tax-name') }}</label>
                        <input type="text" id="update_tax" class="form-control" />
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
       $('.add_submit').click(function(){
                $.ajax({
                    url:'{{route('admin.tax.add')}}',
                    type:'POST',
                    data:$('#add_tax').serialize(),
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
        });

        $(document).on('click', '.edit', function() {
            var id = $(this).attr("id");
            var route = "{{ route('admin.tax.index') }}";
            $.get(route + '/edit/' + id, function(product) {
                $('#id').val(product.id);
                $('#name').val(product.name);
                $('#percent').val(product.percent);
                $("select option").each(function() {
                    if ($(this).text() == product.status)
                        $(this).attr("selected", "selected");
                });
                $('#edit').modal('toggle');
            });
        });
        $(document).on('click', '.add_lang_model', function() {
            $('#lang_type').html('');
            $('#add_error').html('');
            $('#add_error').removeClass('alert alert-light-danger');
            var id = $(this).attr('id');
            var data = {
                'ref_id': id,
                'ref_type': "tax",
            };
            $.ajax({
                url: "{{ route('admin.get.languages') }}",
                type: "POST",
                dataType: "json",
                data: data,
                success: function(response) {
                    var lang_list = "";
                    lang_list += "<option value=''>Please Select</option>";
                    $.each(response, function(value, key) {
                        if (key.sort != "en") {
                            lang_list += "<option value='" + key.sort + "'>" + key.display +
                                "</option>";
                        }
                    });
                    $('#lang_type').append(lang_list);
                    $('#ref_id').val(id);
                    $('#lang_model').modal('toggle');
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
                            url: '{{ route('admin.tax.delete') }}',
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
        $(document).on('click', '.update_lang_model', function() {
            $('#update_error').html('');
            $('#update_error').removeClass('alert alert-light-danger');
            var id = $(this).attr('id');
            var route = "{{ route('admin.tax.lang.add') }}";
            $.get(route + '/edit/' + id, function(lang) {
                $('#update_id').val(lang.id);
                $('#update_tax').val(lang.lang_data);
                $('#lang_model_edit').modal('toggle');
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.update').click(function() {
            var data = {
                'id': $('#id').val(),
                'percent': $('#percent').val(),
                'status': $('#status').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{ route('admin.tax.update') }}",
                type: "POST",
                dataType: "json",
                data: data,
                success: function(response) {
                    if (response.status == 200) {
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
                    } else {
                        $('#errors').html('');
                        $('#errors').addClass('alert alert-light-danger');
                        $.each(response.errors, function(key, error) {
                            $('#errors').append('<li>' + error + '</li>');
                        })
                    }
                }
            })
        });
        $('.add_lang').click(function() {
            var data = {
                'tax_name': $('#tax_name').val(),
                'lang_type': $('#lang_type').val(),
                'ref_id': $('#ref_id').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{ route('admin.tax.lang.add') }}",
                type: "POST",
                dataType: "json",
                data: data,
                success: function(response) {
                    if (response.status == "fail") {
                        $('#add_error').html('');
                        $('#add_error').addClass('alert alert-light-danger');
                        $.each(response.errors, function(key, error) {
                            $('#add_error').append('<li>' + error + '</li>');
                        })
                    } else {
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
        $('.update_lang').click(function() {
            var data = {
                'tax_name': $('#update_tax').val(),
                'id': $('#update_id').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{ route('admin.tax.lang.update') }}",
                type: "POST",
                dataType: "json",
                data: data,
                success: function(response) {
                    if (response.status == "fail") {
                        $('#update_error').html('');
                        $('#update_error').addClass('alert alert-light-danger');
                        $.each(response.errors, function(key, error) {
                            $('#update_error').append('<li>' + error + '</li>');
                        })
                    } else {
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
            window.location.href = '{{ route('admin.tax.index') }}';
        }
    </script>

    <script>
        // Wait for the document to fully load
        $(document).ready(function() {
            // Check if the success alert is present
            if ($('#success-alert').length) {
                // Set a timeout to hide the alert after 5 seconds
                setTimeout(function() {
                    $('#success-alert').fadeOut('slow', function() {
                        // Remove the alert from the DOM after fading out
                        $(this).remove();
                    });
                }, 3000); // 5000 milliseconds = 5 seconds
            }
        });
    </script>

    <script>
        // Wait for the document to fully load
        $(document).ready(function() {
            // Check if the success alert is present
            if ($('#success-alert2').length) {
                // Set a timeout to hide the alert after 5 seconds
                setTimeout(function() {
                    $('#success-alert2').fadeOut('slow', function() {
                        // Remove the alert from the DOM after fading out
                        $(this).remove();
                    });
                }, 3000); // 5000 milliseconds = 5 seconds
            }
        });
    </script>
@endsection
