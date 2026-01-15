@extends('Admin.base')


@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.add-seller') }}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.seller-information') }}</h3>
                        </div>
                        <div class="card-body">
                            @if (Session::has('seller'))
                                <div class="alert alert-light-success" role="alert">
                                    <button type="button" class="btn-close text-success mr-negative-16"
                                        data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('seller') }}
                                </div>
                            @endif

                            <form id="seller" class="row g-3 needs-validation">
                                <div id="add_g_error"></div>
                                <div class="col-md-7 position-relative">
                                    <label for="validationTooltip02"
                                        class="form-label">{{ __('messages.seller-name') }}</label>
                                    <input type="text" placeholder="Name"
                                        class="form-control @if ($errors->has('name')) is-invalid @endif"
                                        name="name" id="validationTooltip02" required>
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>

                                <div class="col-md-7 position-relative">
                                    <label for="validationTooltipUsername"
                                        class="form-label">{{ __('messages.seller-email') }}</label>
                                    <input type="email" placeholder="Email"
                                        class="form-control @if ($errors->has('email')) is-invalid @endif"
                                        name="email" id="validationTooltipUsername"
                                        aria-describedby="validationTooltipUsernamePrepend" required>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

                                <div class="col-md-7 position-relative">
                                    <label for="validationTooltip03"
                                        class="form-label">{{ __('messages.password') }}</label>
                                    <input type="password" placeholder="Password" name="password"
                                        class="form-control @if ($errors->has('password')) is-invalid @endif"
                                        id="validationTooltip03" required>
                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary seller"
                                        type="button">{{ __('messages.add-seller') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Row-->
            <!-- Row -->
            <div class="row ">
                <div class="col-md-12">

                </div>
            </div>
            <!-- /Row -->


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
            $('.seller').click(function() {
                $.ajax({
                    url: '{{ route('admin.seller.add') }}',
                    type: 'POST',
                    data: $('#seller').serialize(),
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
