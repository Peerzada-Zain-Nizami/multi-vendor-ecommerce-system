@extends('Admin.base')


@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">

            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.import-file') }}</h4>
                </div>
                <div class="page-rightheader">
                    <a href="{{ route('admin.download.city.file.format') }}" class="btn btn-primary"><i
                            class="fa fa-download"></i>
                        {{ __('messages.format-file') }}</a>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.shipping-company-cities') }}</h3>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger">{{ $error }}</li>
                                @endforeach
                            @endif
                            @if (Session::has('success'))
                                <div class="alert alert-light-success" role="alert">
                                    <button type="button" class="btn-close text-success mr-negative-16"
                                        data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                                </div>
                            @endif
                            @if (Session::has('danger'))
                                <div class="alert alert-light-danger" role="alert">
                                    <button type="button" class="btn-close text-success mr-negative-16"
                                        data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{{ __('messages.oopps') }}</strong> {{ Session::get('danger') }}
                                </div>
                            @endif
                            <form id="import_file" enctype="multipart/form-data">
                                <div id="add_g_error"></div>
                                <div class="row">
                                    <div class="form-group">
                                        <label>{{ __('messages.please-select') }}</label>
                                        <input type="file" name="excel_file" class="dropify" onchange="checkfile(this);"
                                            data-height="180" />
                                    </div>
                                    <div class="form-group text-center">
                                        <button class="btn btn-primary import_file"
                                            type="button">{{ __('messages.upload') }}</button>
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
    <script type="text/javascript">
        $(Document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.import_file').click(function() {
                var formData = new FormData($('#import_file')[0]);
                $.ajax({
                    url: '{{ route('admin.add.city.file') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        console.log(response);
                        if (response.status == "fail") {
                            $('#add_g_error').html('');
                            $('#add_g_error').addClass('alert alert-light-danger');
                            $.each(response.errors, function(key, error) {
                                $('#add_g_error').append('<li>' + error + '</li>');
                            })
                        } else if (response.status == "danger") {
                            Swal.fire({
                                title: 'Warning!',
                                text: response.message,
                                icon: 'warning',
                                showConfirmButton: true
                            });
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

        function checkfile(sender) {
            var validExts = new Array(".xlsx", ".xls");
            var fileExt = sender.value;
            fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
            if (validExts.indexOf(fileExt) < 0) {
                alert("Invalid file selected, valid files are of " +
                    validExts.toString() + " types.");
                sender.value = '';
                return false;
            } else return true;
        }
        $(document).ready(function() {
            document.getElementById('pro-image').addEventListener('change', readImage, false);
            $(".preview-images-zone");

        });
        var num = 1;

        function readImage() {
            $("#hide").show();
            if (window.File && window.FileList && window.FileReader) {
                var files = event.target.files; //FileList object
                var output = $(".preview-images-zone");

                for (let i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (!file.type.match('image')) continue;

                    var picReader = new FileReader();

                    picReader.addEventListener('load', function(event) {
                        var picFile = event.target;
                        var html = '<div class="preview-image preview-show-' + num + '">' +
                            '<div class="image-zone"><img id="pro-img-' + num + '" src="' + picFile.result +
                            '"></div>' +
                            '</div>';
                        output.append(html);
                        num = num + 1;
                    });

                    picReader.readAsDataURL(file);
                }
            } else {
                console.log('Browser not support');
            }
        }
    </script>
@endsection
