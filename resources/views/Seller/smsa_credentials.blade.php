@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">

            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.SMSA-Credentials')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            @if (empty($passkey))
<div class="d-flex justify-content-center align-items-center mt-8">
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <button type="button" class="btn btn-primary btn-lg shadow-sm d-flex align-items-center" data-bs-toggle="modal"
                        data-bs-target="#addPasskeyModal">
                        <i class="fas fa-key me-2"></i> {{ __('Add SMSA Passkey') }}
                    </button>
                </div>
        @endif
            <!-- Flash Messages -->
            <div class="row">
                @if (Session::has('success'))
                    <div id="success-alert2" class="alert alert-light-success" role="alert">
                        <strong>{{__('messages.well-done')}}</strong> {{ session('success') }}
                    </div>
                @endif

                @if (Session::has('danger'))
                    <div id="success-alert" class="alert alert-light-danger" role="alert">
                        <strong>{{__('messages.oopps')}}</strong> {{ session('danger') }}
                    </div>
                @endif
            </div>

            <!-- Centered Add Passkey Button -->


            <!-- Credentials Section -->
            @if ($passkey)

            <div class="row mt-4">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.SMSA-Credentials')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table id="example1" class="table table-bordered text-nowrap key-buttons dataTable no-footer">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('messages.smsa-user')}}</th>
                                        <th>{{__('messages.passkey')}}</th>
                                        <th>{{__('messages.action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$SMSAuser = Illuminate\Support\Facades\Auth::user()->role;}}</td>
                                            <td>{{ $passkey->passkey }}</td>
                                            <td>
                                                <div class="btn-group mt-2 mb-2">
                                                    <button type="button" class="btn btn-warning btn-pill edit" id="{{$passkey->id}}">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Add Passkey Modal -->
    <div class="modal fade" id="addPasskeyModal" tabindex="-1" aria-labelledby="addPasskeyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPasskeyModalLabel">{{ __('Add passkey') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="smsa_credentials_form" method="post">
                    <div class="modal-body">
                        <div id="city_add_error"> </div>
                        <div class="form-group">
                            <label class="form-label">{{__('messages.passkey')}}</label>
                            <input name="passkey" class="form-control @if($errors->has('passkey'))is-invalid @endif" value="{{old('passkey')}}" type="text" placeholder="{{__('messages.passkey')}}">
                        </div>
                        @if ($errors->has('passkey'))
                            <span class="text-danger">{{ $errors->first('passkey') }}</span>
                            <br>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary" id="add_new">{{ __('messages.add-new') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

{{-- edit model --}}
    <div class="modal fade" id="edit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.Edit-SMSA-Credentials')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="errors">
                    </div>
                    <input id="id" type="number" hidden />
                    <div class="form-group">
                        <label class="form-label">{{__('messages.passkey')}}</label>
                        <input type="text" id="passkey" value="{{old('passkey')}}" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update">{{('update')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{('close')}}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTAINER END -->
@endsection
@section('query')
    <script type="text/javascript">
        // Edit SMSA Credentials
        $(document).on('click','.edit',function () {
            var id = $(this).attr("id");
            var route = "{{route('seller.edit.credentials', ':id')}}".replace(':id', id);

            $.get(route, function (credential) {
                $('#id').val(credential.id);
                $('#passkey').val(credential.passkey);
                $('#edit').modal('toggle');
            });
        });

        // Setup CSRF Token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Update SMSA Credentials
        $('.update').click(function () {
            var data = {
                'id': $('#id').val(),
                'passkey': $('#passkey').val(),
            };

            $.ajax({
                url: "{{route('seller.update.credentials')}}",
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
                        setTimeout(function() {
                            window.location.href = '{{route('seller.smsa.credentials')}}';
                        }, 1500);
                    } else {
                        $('#errors').html('');
                        $('#errors').addClass('alert alert-light-danger');
                        $.each(response.errors, function(key, error) {
                            $('#errors').append('<li>' + error + '</li>');
                        });
                    }
                }
            });
        });
    </script>

    <script>
    // Hide success alert after 3 seconds
    $(document).ready(function () {
        if ($('#success-alert').length) {
            setTimeout(function () {
                $('#success-alert').fadeOut('slow', function () {
                    $(this).remove();
                });
            }, 3000);
        }
        if ($('#success-alert2').length) {
            setTimeout(function () {
                $('#success-alert2').fadeOut('slow', function () {
                    $(this).remove();
                });
            }, 3000);
        }
    });

      $(document).ready(function() {
    $('#add_new').on('click', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = $('#smsa_credentials_form').serialize(); // Serialize the form data

        $.ajax({
            url: '{{ route("seller.store.credentials") }}', // Adjust the route as necessary
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'fail') {
                     $('#city_add_error').html('');
                            $('#city_add_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#city_add_error').append('<li>'+error+'</li>');
                            })
                } else if (response.status === 'danger') {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        showConfirmButton: true
                    });
                } else if (response.status === 'success') {
                     $('#addPasskeyModal').modal('hide');
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
                }
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    icon: 'error',
                    showConfirmButton: true
                });
            }
        });
    });
});
    </script>
@endsection


