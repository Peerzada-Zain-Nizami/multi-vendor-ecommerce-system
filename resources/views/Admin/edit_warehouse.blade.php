@extends('Admin.base')


@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.warehouse-edit') }}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        {{-- <div class="card-header d-flex justify-content-between align-items-center"> --}}
                        <div class="card-header">
                            <div class="card-title">{{ __('messages.edit-warehouse') }}</div>
                            {{-- <a href="{{ route('admin.warehouse') }}" class="btn btn-primary"  type="button">Back</a> --}}
                        </div>
                        <div class="card-body">
                            <form id="edit_warehouse">
                                <div id="add_g_error"></div>
                                <input type="text" name="form" value="true" hidden>
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ __('messages.warehouse-name') }}</label>
                                            <input name="warehouse_name"
                                                class="form-control @if ($errors->has('warehouse_name')) is-invalid @endif"
                                                value="{{ old() ? old('warehouse_name') : $warehouse->warehouse_name }}"
                                                type="text">
                                        </div>
                                        @if ($errors->has('warehouse_name'))
                                            <span class="text-danger">{{ $errors->first('warehouse_name') }}</span>
                                            <br>
                                        @endif
                                        <div class="form-group">
                                            <label>{{ __('messages.warehouse-code') }}</label>
                                            <input name="warehouse_code"
                                                class="form-control @if ($errors->has('warehouse_code')) is-invalid @endif"
                                                value="{{ old() ? old('warehouse_code') : $warehouse->warehouse_id }}"
                                                type="text" disabled>
                                        </div>
                                        @if ($errors->has('warehouse_code'))
                                            <span class="text-danger">{{ $errors->first('warehouse_code') }}</span>
                                            <br>
                                        @endif
                                        <div class="form-group">
                                            <label>{{ __('messages.address') }}</label>
                                            <textarea name="address" class="form-control">{{ old() ? old('address') : $warehouse->address }}</textarea>
                                        </div>
                                        @if ($errors->has('address'))
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                            <br>
                                        @endif
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>{{ __('messages.responsible') }}</label>
                                            <select name="responsible[]"
                                                class="form-control select2 select2-hidden-accessible"
                                                data-placeholder="Choose Admins" multiple="" tabindex="-1"
                                                aria-hidden="true">
                                                @foreach ($admins as $admin)
                                                    <option value="{{ $admin->id }}"
                                                        @if (in_array($admin->id, json_decode($warehouse->responsible))) selected @endif>
                                                        {{ $admin->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                                    <option value="{{ $city->id }}"
                                                        @if ($city->id == $warehouse->city) ) selected @endif>
                                                        {{ $city->our_system_cities }}</option>
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
                                                <option @if ($warehouse->status == 'Active') selected @endif>
                                                    {{ __('messages.active') }}</option>
                                                <option @if ($warehouse->status == 'Deactive') selected @endif>
                                                    {{ __('messages.deactive') }}</option>
                                            </select>
                                        </div>
                                        @if ($errors->has('status'))
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                            <br>
                                        @endif
                                        <div class="form-group">
                                            <button class="btn btn-primary edit_warehouse" data-id="{{ $warehouse->id }}"
                                                type="button">{{ __('messages.update') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Row-->


        </div>
    </div>
    <!-- CONTAINER END -->
@endsection
@section('query')
    <script>
        $(Document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.edit_warehouse').click(function() {
                var id = $(this).data('id'); // Assuming the button has a data-id attribute
                var route = '{{ route('admin.warehouse.update', ':id') }}';
                route = route.replace(':id', id);
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: $('#edit_warehouse').serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response.status == "fail") {
                            $('#add_g_error').html('');
                            $('#add_g_error').addClass('alert alert-light-danger');
                            $.each(response.errors, function(key, error) {
                                $('#add_g_error').append('<li>' + error + '</li>');
                            })
                        } else {
                            location.href = "{{ route('admin.warehouse') }}"
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
