@extends('Admin.base')
@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">
            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.edit-product') }}</h4>
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

                            <form id="edit_product" enctype="multipart/form-data">
                                <div id="add_g_error"></div>
                                <input type="text" name="id" value="{{ $result->id }}" hidden>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('messages.product-name') }}</label>
                                            <input type="text" class="form-control" placeholder="Product Name"
                                                name="product_name" value="{{ $result->product_name }}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{ __('messages.category') }}</label>
                                            <select name="category" class="form-control select2-show-search">
                                                <option value="">{{ __('messages.please-select') }}</option>
                                                @foreach ($results as $value)
                                                    <option value="{{ $value->category_name }}"
                                                        @if ($result->category == $value->category_name) selected @endif>
                                                        {{ \App\MyClasses\Helpers::get_lang($value->category_name, $value->id, 'category', App::getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{ __('messages.short-description') }}</label>
                                            <textarea type="text" class="form-control" placeholder="Short Description" name="short_description">{{ $result->short_description }}</textarea>
                                        </div>
                                        <label class="form-label">{{ __('messages.brief-description') }}</label>
                                        <div class="form-group card-body">
                                            <textarea type="text" class="content form-control" name="brief_description">{{ $result->brief_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>{{ __('messages.status') }}</label>
                                            <select name="status" class="form-control select2">
                                                <option value="">{{ __('messages.please-select') }}</option>
                                                <option value="Active" @if ($result->status == 'Active') selected @endif>
                                                    {{ __('messages.active') }}</option>
                                                <option value="Deactivate"
                                                    @if ($result->status == 'Deactivate') selected @endif>
                                                    {{ __('messages.deactivate') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('messages.business-model') }}</label>
                                            <select name="business_model[]"
                                                class="form-control select2 select2-hidden-accessible @if ($errors->has('business_model')) is-invalid @endif"
                                                data-placeholder="Choose Model" multiple="" tabindex="-1"
                                                aria-hidden="true">
                                                @foreach ($models as $model)
                                                    <option value="{{ $model->name }}"
                                                        @if (in_array($model->name, json_decode($result->business_model))) selected @endif>
                                                        {{ \App\MyClasses\Helpers::get_lang($model->name, $model->id, 'business_model', App::getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            .
                                        </div>

                                        <div class="form-group">
                                            <label>{{ __('messages.tax') }}</label>
                                            <select name="taxes[]"
                                                class="form-control select2 select2-hidden-accessible @if ($errors->has('taxes')) is-invalid @endif"
                                                data-placeholder="Choose Tax" multiple="" tabindex="-1"
                                                aria-hidden="true">
                                                @foreach ($taxes as $tax)
                                                    <option value="{{ $tax->id }}"
                                                        @if ($result->taxes != null) @if (in_array($tax->id, json_decode($result->taxes))) selected @endif
                                                        @endif
                                                        >{{ \App\MyClasses\Helpers::get_lang($tax->name, $tax->id, 'tax', App::getLocale()) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('messages.featured-image') }}</label>
                                            <input type="file" name="featured_image" class="dropify"
                                                value="{{ asset('uploads/featured_images/' . $result->featured_image) }}"
                                                data-default-file="{{ asset('uploads/featured_images/' . $result->featured_image) }}"
                                                data-height="180" />
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('messages.product-images') }}</label>
                                            <input id="pro-image" type="file" name="product_images[]"
                                                class="form-control @if ($errors->has('product_images')) is-invalid @endif"
                                                multiple>
                                        </div>
                                        @php
                                            $images = $result->product_images;
                                            $new_images = explode('|', $images);
                                            $i = 1;
                                        @endphp
                                        <div class="preview-images-zone">
                                            @foreach ($new_images as $img)
                                                @if (!empty($img))
                                                    <div class="preview-image preview-show-{{ $i++ }}">
                                                        <div id="{{ $img }}" class="image-cancel"
                                                            data-no="{{ $i++ }}">x</div>
                                                        <div class="image-zone"><img id="pro-img-{{ $i++ }}"
                                                                src="{{ asset('uploads/product_images/' . $img) }}"></div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="form-group mt-5">
                                            <button class="btn btn-primary edit_product"
                                                type="button">{{ __('messages.update-product') }}</button>
                                        </div>
                                    </div>
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
            $('.edit_product').click(function() {
                $.ajax({
                    url: '{{ route('admin.product.update') }}',
                    type: 'POST',
                    data: $('#edit_product').serialize(),
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
                                window.location.href =
                                    '{{ route('admin.product.manage') }}';
                            }, 1500);
                        }
                    }
                });
            });
        });
        $(document).ready(function() {
            document.getElementById('pro-image').addEventListener('change', readImage, false);
            $(".preview-images-zone");
            $('.image-cancel').click(function() {
                var image = $(this).attr("id");
                var id = "{{ $result->id }}";

                swal({
                        title: "Are you sure?",
                        text: "Once deleted, you will not be able to recover this Image!",
                        icon: "warning",
                        showCancelButton: true,
                        dangerMode: true,
                        confirmButtonText: "Ok, Delete it!",
                        showConfirmButton: true,

                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                url: '{{ route('admin.product.image.delete') }}',
                                type: 'post',
                                data: {
                                    id: id,
                                    image: image
                                },
                                success: function(data) {
                                    Swal.fire({
                                        title: 'Congratulations!',
                                        text: "Product Image has been successfully deleted",
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                    setTimeout(location.reload(), 1500);
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Product Image is Safe!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    });
            });
        });



        var num = 1;

        function readImage() {
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
