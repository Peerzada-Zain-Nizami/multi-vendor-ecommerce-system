@extends('Admin.base')


@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.update-language') }}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.product-detail') }}</h3>
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
                            <form method="POST" action="{{ route('admin.product.lang.update', $data->id) }}">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('messages.product-name') }}</label>
                                            <input type="text" class="form-control" name="product_name"
                                                value="{{ $decode_data->product_name }}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{ __('messages.short-description') }}</label>
                                            <textarea type="text" class="form-control" name="short_description">{{ $decode_data->short_description }}</textarea>
                                        </div>
                                        <label class="form-label">{{ __('messages.brief-description') }}</label>
                                        <div class="form-group card-body">
                                            <textarea type="text" class="content form-control" name="brief_description">{{ $decode_data->brief_description }}</textarea>
                                        </div>
                                        <div class="form-group mt-5">
                                            <button class="btn btn-primary"
                                                type="submit">{{ __('messages.update-language') }}</button>
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
    <script type="text/javascript">
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
