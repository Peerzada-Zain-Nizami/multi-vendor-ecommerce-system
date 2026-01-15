@extends('Admin.base')


@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.add-sub-admin') }}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.sub-admin-information') }}</h3>
                        </div>
                        <div class="card-body">
                            @if (Session::has('subadmin'))
                                <div class="alert alert-light-success" role="alert">
                                    <button type="button" class="btn-close text-success mr-negative-16"
                                        data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('subadmin') }}
                                </div>
                            @endif

                            <form id="subadmin" class="row g-3">
                                <div id="add_g_error"></div>
                                <div class="col-md-7 position-relative">
                                    <label for="validationTooltip02"
                                        class="form-label">{{ __('messages.subAdmin-name') }}</label>
                                    <input type="text" placeholder="Name"
                                        class="form-control @if ($errors->has('name')) is-invalid @endif"
                                        value="{{ old('name') }}" name="name" id="validationTooltip02" required>
                                </div>
                                <div class="col-md-7 position-relative">
                                    <label for="validationTooltipUsername"
                                        class="form-label">{{ __('messages.subAdmin-email') }}</label>
                                    <input type="email" placeholder="Email"
                                        class="form-control @if ($errors->has('email')) is-invalid @endif"
                                        value="{{ old('email') }}" name="email" id="validationTooltipUsername" required>
                                </div>
                                <div class="col-md-7 position-relative">
                                    <label for="validationTooltip03"
                                        class="form-label">{{ __('messages.password') }}</label>
                                    <input type="password" placeholder="Password" name="password"
                                        class="form-control @if ($errors->has('password')) is-invalid @endif"
                                        id="validationTooltip03" required>
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary subadmin"
                                        type="button">{{ __('messages.add-subadmin') }}</button>
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
    <script type="text/javascript">
        $(Document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.subadmin').click(function() {
                $.ajax({
                    url: '{{ route('admin.subadmin.add') }}',
                    type: 'POST',
                    data: $('#subadmin').serialize(),
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
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    }
                });
            });
        });
    </script>
@endsection
