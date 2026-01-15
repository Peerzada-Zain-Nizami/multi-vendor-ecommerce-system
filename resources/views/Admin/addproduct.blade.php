@extends('Admin.base')


@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.add-product')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('messages.product-detail')}}</h3>
                        </div>
                        <div class="card-body">
                            @if($errors->any())
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger">{{$error}}</li>
                                @endforeach
                            @endif
                            @if (Session::has('success'))
                                <div class="alert alert-light-success" role="alert">
                                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                                </div>
                            @endif

                            <form id="add_product" enctype="multipart/form-data">
                               <div id="add_g_error"></div>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.product-name')}}</label>
                                            <input type="text" class="form-control" placeholder="Product Name" name="product_name" value="{{old('product_name')}}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.product-SKU')}}</label>
                                            <input type="text" class="form-control" placeholder="Product SKU" name="product_sku" value="{{old('product_sku')}}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.category')}}</label>
                                            <select name="category" class="form-control select2-show-search">
                                                <option value="">{{__('messages.please-select')}}</option>
                                                @foreach($results as $result)
                                                    <option value="{{$result->category_name}}">{{\App\MyClasses\Helpers::get_lang($result->category_name,$result->id,"category",App::getLocale())}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.short-description')}}</label>
                                            <textarea type="text" class="form-control" placeholder="Short Description" name="short_description">{{old('short_description')}}</textarea>
                                        </div>
                                        <label class="form-label">{{__('messages.brief-description')}}</label>
                                        <div class="form-group card-body">
                                            <textarea type="text" class="content form-control" placeholder="Brief Description" name="brief_description">{{old('brief_description')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>{{__('messages.status')}}</label>
                                            <select name="status" class="form-control select2">
                                                    <option value="">{{__('messages.please-select')}}</option>
                                                    <option value="Active" selected>{{__('messages.active')}}</option>
                                                    <option value="Deactivate">{{__('messages.deactivate')}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>{{__('messages.business-model')}}</label>
                                                <select name="business_model[]" class="form-control select2 select2-hidden-accessible" data-placeholder="{{__('messages.choose-model')}}" multiple="" tabindex="-1" aria-hidden="true">
                                                    @foreach($models as $model)
                                                        <option>{{\App\MyClasses\Helpers::get_lang($model->name,$model->id,"business_model",App::getLocale())}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="form-group">
                                            <label>{{__('messages.tax')}}</label>
                                            <select name="taxes[]" class="form-control select2 select2-hidden-accessible" data-placeholder="{{__('messages.choose-taxes')}}" multiple="" tabindex="-1" aria-hidden="true">
                                                @foreach($taxes as $tax)
                                                    <option value="{{ $tax->id }}">{{\App\MyClasses\Helpers::get_lang($tax->name,$tax->id,"tax",App::getLocale())}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>{{__('messages.featured-image')}}</label>
                                            <input type="file" name="featured_image" class="dropify" data-height="180"/>
                                        </div>
                                        <div class="form-group">
                                            <label>{{__('messages.product-images')}}</label>
                                            <input id="pro-image" type="file" name="product_images[]" class="form-control" multiple>
                                        </div>
                                        <div id="hide" style="display: none;">
                                            <div class="preview-images-zone"></div>
                                        </div>
                                        <div class="form-group mt-5">
                                            <button class="btn btn-primary add_product" type="button">{{__('messages.add-product')}}</button>
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
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.add_product').click(function() {
                let formData = new FormData($('#add_product')[0]); // Include files in the form
                $.ajax({
                    url: '{{ route("admin.product.add") }}',
                    type: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from processing data
                    contentType: false, // Prevent jQuery from setting content type
                    success: function(response) {
                        console.log(response);
                        if (response.status === "fail") {
                            $('#add_g_error').html('');
                            $('#add_g_error').addClass('alert alert-light-danger');
                            $.each(response.errors, function(key, error) {
                                $('#add_g_error').append('<li>' + error + '</li>');
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
                                window.location.href = '{{ route("admin.product.manage") }}';
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });

        $(document).ready(function() {
            document.getElementById('pro-image').addEventListener('change', readImage, false);
            $( ".preview-images-zone");

        });
        var num = 1;
        function readImage() {
            $( "#hide").show();
            if (window.File && window.FileList && window.FileReader) {
                var files = event.target.files; //FileList object
                var output = $(".preview-images-zone");

                for (let i = 0; i < files.length; i++) {
                    var file = files[i];
                    if (!file.type.match('image')) continue;

                    var picReader = new FileReader();

                    picReader.addEventListener('load', function (event) {
                        var picFile = event.target;
                        var html =  '<div class="preview-image preview-show-' + num + '">' +
                            '<div class="image-zone"><img id="pro-img-' + num + '" src="' + picFile.result + '"></div>' +
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
