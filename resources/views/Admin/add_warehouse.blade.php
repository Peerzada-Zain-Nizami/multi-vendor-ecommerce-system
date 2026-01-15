@extends('Admin.base')


@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.warehouses-setup') }}</h4>
                </div>
                <div class="page-rightheader">
                    <div class="btn-list">
                        <button onclick="show_hide()" class="btn btn-primary"><i class="fa fa-plus"></i>
                            {{ __('messages.add-warehouse') }}</button>
                    </div>
                </div>
            </div>
            @if (Session::has('success'))
                <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                        aria-hidden="true">×</button>
                    <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                </div>
            @endif
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div id="add_new" style="@if (old('form')) display: block; @else display: none; @endif"
                    class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{ __('messages.add-new') }}</div>
                        </div>
                        <div class="card-body">
                            <form id="add_warehouse">
                                <div id="add_g_error"></div>
                                <input type="text" name="form" value="true" hidden>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ __('messages.warehouse-name') }}</label>
                                            <input name="warehouse_name" placeholder="Warehouse Name"
                                                class="form-control @if ($errors->has('warehouse_name')) is-invalid @endif"
                                                value="{{ old('warehouse_name') }}" type="text">
                                        </div>
                                        {{-- @if ($errors->has('warehouse_name'))
                                            <span class="text-danger">{{ $errors->first('warehouse_name') }}</span>
                                            <br>
                                        @endif --}}
                                        <div class="form-group">
                                            <label>{{ __('messages.warehouse-code') }}</label>
                                            <input name="warehouse_code" placeholder="Warehouse Code"
                                                class="form-control @if ($errors->has('warehouse_code')) is-invalid @endif"
                                                value="{{ old('warehouse_code') }}" type="text">
                                        </div>
                                        {{-- @if ($errors->has('warehouse_code'))
                                            <span class="text-danger">{{ $errors->first('warehouse_code') }}</span>
                                            <br>
                                        @endif --}}
                                        <div class="form-group">
                                            <label>{{ __('messages.address') }}</label>
                                            <textarea name="address" placeholder="Address" class="form-control">{{ old('address') }}</textarea>
                                        </div>
                                        {{-- @if ($errors->has('address'))
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                            <br>
                                        @endif --}}
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group position-relative">
                                            <label>{{ __('messages.responsible') }}</label>

                                            <!-- Icon positioned absolutely using Bootstrap classes -->
                                            <i class="fas fa-caret-down position-absolute top-50 start-0 ms-2"
                                                style="transform: translateY(-50%); margin-left:16px"></i>

                                            <!-- Select input (Select2 will replace this with its custom container) -->
                                            <select style="margin-left:10px" name="responsible[]" class="form-control select2-show-search" multiple
                                                data-placeholder="{{ __('messages.choose-admins') }}" tabindex="-2"
                                                aria-hidden="true">
                                                <option value="">{{ __('messages.choose-admins') }}</option>
                                                @foreach ($admins as $admin)
                                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="form-group">
                                            <label for="responsible">Select responsible</label>
                                            <select name="responsible[]" id="responsible" class="form-control" multiple>
                                                @foreach ($responsible as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> --}}


                                        @if ($errors->has('responsible'))
                                            <span class="text-danger">{{ $errors->first('responsible') }}</span>

                                            <br>
                                        @endif
                                        <div class="form-group">
                                            <label>{{ __('messages.city') }}</label>
                                            <select name="city"
                                                class="form-control select2 @if ($errors->has('city')) is-invalid @endif">
                                                <option value="">{{ __('messages.please-select') }}</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}">{{ $city->our_system_cities }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($errors->has('city'))
                                            <span class="text-danger">{{ $errors->first('city') }}</span>
                                            <br>
                                        @endif
                                        <div class="form-group">
                                            <label>{{ __('messages.status') }}</label>
                                            <select name="status" class="form-control select2">
                                                <option disabled selected>{{ __('messages.please-select-status') }}
                                                </option>
                                                <option>{{ __('messages.active') }}</option>
                                                <option>{{ __('messages.deactive') }}</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('status'))
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                            <br>
                                        @endif
                                        <div class="form-group">
                                            <button
                                                class="btn btn-primary add_warehouse"type="button">{{ __('messages.add-new') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{ __('messages.warehouses-list') }}</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table id="example" class="table table-responsive-lg-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.warehouse-code') }}</th>
                                            <th>{{ __('messages.warehouse-name') }}</th>
                                            {{-- <th>{{__('messages.city')}}</th> --}}
                                            <th>{{ __('messages.address') }}</th>
                                            <th>{{ __('messages.responsible') }}</th>
                                            <th>{{ __('messages.status') }}</th>
                                            <th>{{ __('messages.languages') }}</th>
                                            <th>{{ __('messages.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($warehouses as $warehouse)
                                            @php
                                                $data = \App\MyClasses\Helpers::get_lang(
                                                    $warehouse->warehouse_name,
                                                    $warehouse->id,
                                                    'warehouse',
                                                    App::getLocale(),
                                                );
                                                $warehouse_data = json_decode($data);
                                            @endphp
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $warehouse->warehouse_id }}</td>
                                                <td>
                                                    @if (App::getLocale() == 'en')
                                                        {{ $data }}
                                                    @elseif($warehouse_data)
                                                        {{ $warehouse_data->name }}
                                                    @else
                                                        {{ $warehouse->warehouse_name }}
                                                    @endif
                                                </td>
                                                {{-- <td>@if (App::getLocale() == 'en'){{$warehouse->cityRelation->our_system_cities}} @elseif($warehouse_data) {{$warehouse->cityRelation->our_system_cities}} @else {{$warehouse->cityRelation->our_system_cities}} @endif</td> --}}
                                                <td>
                                                    @if (App::getLocale() == 'en')
                                                        {{ $warehouse->address }}
                                                    @elseif($warehouse_data)
                                                        {{ $warehouse_data->address }}
                                                    @else
                                                        {{ $warehouse->address }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @php
                                                        $users = \App\Models\User::whereIn(
                                                            'id',
                                                            json_decode($warehouse->responsible),
                                                        )->get();
                                                    @endphp
                                                    @foreach ($users as $user)
                                                        <a href="{{ route('admin.user.view', ['id' => $user->id]) }}"><span
                                                                class="badge rounded-pill bg-primary mt-2">{{ $user->name }}</span></a>
                                                        @if ($loop->even == true)
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td
                                                    class="@if ($warehouse->status == 'Active') text-success @else text-danger @endif">
                                                    {{ $warehouse->status }}</td>
                                                <td>
                                                    <div class="w-100" style="overflow: auto; height: 50px;">
                                                        @php
                                                            $langs = \App\MyClasses\Helpers::act_lang(
                                                                $warehouse->id,
                                                                'warehouse',
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
                                                                            <a href="{{ route('admin.warehouse.lang.del', $lang->id) }}"
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

                                                            <li><a
                                                                    href="{{ route('admin.warehouse.view', $warehouse->id) }}">{{ __('messages.view') }}</a>
                                                            </li>
                                                            <li><a
                                                                    href="{{ route('admin.warehouse.edit', $warehouse->id) }}">{{ __('messages.edit') }}</a>
                                                            </li>
                                                            <li><a href="javascript:void(0);" id="{{ $warehouse->id }}"
                                                                    class="add_lang_model">{{ __('messages.add-language') }}</a>
                                                            </li>
                                                            @if ($warehouse->blocks->count() > 0)
                                                            @else
                                                                <li><a class="delete-confirm"
                                                                        href="{{ route('admin.warehouse.delete', $warehouse->id) }}">{{ __('messages.delete') }}</a>
                                                                </li>
                                                            @endif

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
            <!--/Row-->


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
                        <label> {{ __('messages.warehouse-name') }}</label>
                        <input type="text" id="warehouse_name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label> {{ __('messages.address') }}</label>
                        <input type="text" id="address" class="form-control" />
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
                        <label> {{ __('messages.warehouse-name') }}</label>
                        <input type="text" id="update_warehouse_name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label> {{ __('messages.address') }}</label>
                        <input type="text" id="update_address" class="form-control" />
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
            $('.add_warehouse').click(function() {
                $.ajax({
                    url: '{{ route('admin.warehouse.add') }}',
                    type: 'POST',
                    data: $('#add_warehouse').serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.status == "fail") {
                            $('#add_g_error').html('');
                            $('#add_g_error').addClass('alert alert-light-danger');
                            $.each(response.errors, function(key, error) {
                                $('#add_g_error').append('<li>' + error + '</li>');
                            })
                        } else {
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

        $('.delete-confirm').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not able to recover this Warehouse!',
                icon: 'warning',
                showCancelButton: true,
                dangerMode: true,
                confirmButtonText: "Ok, Delete it!",
                showConfirmButton: true,
            }).then(function(value) {
                if (value) {
                    window.location.href = url;
                } else {
                    Swal.fire({
                        title: 'Your Warehouse is Safe!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
        $(document).on('click', '.add_lang_model', function() {
            $('#lang_type').html('');
            $('#add_error').html('');
            $('#add_error').removeClass('alert alert-light-danger');
            var id = $(this).attr('id');
            var data = {
                'ref_id': id,
                'ref_type': "warehouse",
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
        $(document).on('click', '.update_lang_model', function() {
            $('#update_error').html('');
            $('#update_error').removeClass('alert alert-light-danger');
            var id = $(this).attr('id');
            console.log(id);
            var route = "{{ route('admin.warehouse.lang.add') }}";
            $.get(route + '/edit/' + id, function(lang) {
                console.log(lang);
                $('#update_id').val(lang.id.id);
                $('#update_warehouse_name').val(lang.data.name);
                $('#update_address').val(lang.data.address);
                $('#lang_model_edit').modal('toggle');
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.add_lang').click(function() {
            var data = {
                'warehouse_name': $('#warehouse_name').val(),
                'address': $('#address').val(),
                'lang_type': $('#lang_type').val(),
                'ref_id': $('#ref_id').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{ route('admin.warehouse.lang.add') }}",
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
                'name': $('#update_warehouse_name').val(),
                'address': $('#update_address').val(),
                'id': $('#update_id').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{ route('admin.warehouse.lang.update') }}",
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
            window.location.href = '{{ route('admin.warehouse') }}';
        }

        function show_hide() {
            var x = document.getElementById("add_new");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
@endsection
