
@extends('Seller.base')


@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.product-edit')}}</h4>
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

                            <form id="edit_product" enctype="multipart/form-data">
                               <div id="add_g_error"></div>
                                <input type="text" name="id" value="{{ $id }}" hidden>
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.product-name')}}</label>
                                            <input type="text" class="form-control" name="product_name" value="{{$product->product_name}}">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.category')}}</label>
                                            <select name="category" class="form-control select2-show-search">
                                                <option value="">{{__('messages.please-select')}}</option>
                                                @foreach($results as $result)
                                                    @php
                                                        $catagory_data = \App\MyClasses\Helpers::get_lang($result->category_name,$result->id,"seller_category",App::getLocale());
                                                    @endphp
                                                    <option value="{{$result->category_name}}" @if($result->category_name == $product->category) selected @endif>{{$catagory_data}}</option>

                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.short-description')}}</label>
                                            <textarea type="text" class="form-control" name="short_description">@if(old('short_description')) {{old('short_description')}} @else {{$product->short_description}} @endif</textarea>
                                        </div>
                                        <label class="form-label">{{__('messages.brief-description')}}</label>
                                        <div class="form-group card-body">
                                            <textarea type="text" class="content form-control" name="brief_description">@if(old('brief_description')) {{old('brief_description')}} @else {{$product->brief_description}} @endif</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>{{__('messages.tags')}}</label>
                                            <select name="tags[]" class="form-control select2 select2-hidden-accessible" data-placeholder="Choose Tags" multiple="" tabindex="-1" aria-hidden="true">
                                                @foreach($tags as $tag)
                                                    @php
                                                        $data = \App\MyClasses\Helpers::get_lang($tag->name,$tag->id,"Seller_tag",App::getLocale());
                                                    @endphp
                                                    @if(!empty($product->tags))
                                                        <option  @if(in_array($tag->name,json_decode($product->tags))) selected @endif value="{{$tag->name}}">{{$data}}</option>
                                                    @else
                                                        <option value="{{$tag->name}}">{{$data}}</option>
                                                    @endif
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="mb-3 border-dark border-wd-3">
                                            @php
                                                $rate = $product->get_stock[0]->selling_price;
                                                $dis = $product->get_stock[0]->discount;
                                                $fee = $product->get_stock[0]->fee;
                                                $net = $rate-$dis+$fee;
                                            @endphp
                                            <ul class="list-group">
                                                <li class="list-group-item list-group-item-secondary">{{__('messages.company-rate')}}: {{$rate}}</li>
                                                <li class="list-group-item list-group-item-success">{{__('messages.discount')}}: @if(empty($dis)) 0 @else {{$dis}} @endif</li>
                                                <li class="list-group-item list-group-item-danger">{{__('fee')}}: @if(empty($fee)) 0 @else {{$fee}} @endif</li>
                                                <li class="list-group-item list-group-item-info">{{__('messages.net-rate')}}: {{$net}}</li>
                                                <li class="list-group-item list-group-item-warning">{{__('messages.suggested-price')}}: {{$product->get_stock[0]->suggested_price}}</li>
                                                <li class="list-group-item list-group-item-primary">{{__('messages.retail-price')}}: {{$product->get_stock[0]->retail_price}}</li>
                                            </ul>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.selling-price')}}</label>
                                            <input type="number" class="form-control" name="selling_price" value="@if(old('selling_price')){{old('selling_price')}}@else{{$product->selling_price}}@endif">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.discount')}}</label>
                                            <input type="number" class="form-control" name="discount" value="@if(old('discount')){{old('discount')}}@else{{$product->discount}}@endif">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.fee')}}</label>
                                            <input type="number" class="form-control" name="fee" value="@if(old('fee')){{old('fee')}}@else{{$product->fee}}@endif">
                                        </div>
                                        <div class="form-group">
                                            <label>{{__('messages.featured-image')}}</label>
                                            <input type="file" name="featured_image" class="dropify" @if(!empty($product->featured_image)) value="{{asset('uploads/seller_products/featured_images/'.$product->featured_image)}}" data-default-file="{{asset('uploads/seller_products/featured_images/'.$product->featured_image)}}" @endif data-height="180"/>
                                        </div>
                                        <div class="form-group">
                                            <label>{{__('messages.product-images')}}</label>
                                            <input id="pro-image" type="file" name="product_images[]" class="form-control" multiple>
                                        </div>
                                        @php
                                            $images = $product->product_images;
                                            $new_images = explode("|",$images);
                                        $i = 1;
                                        @endphp
                                        @if(!empty($images))
                                            <div class="preview-images-zone">
                                                @foreach($new_images as $img)
                                                    @if(!empty($img))
                                                        <div class="preview-image preview-show-{{$i++}}">
                                                            <div id="{{$img}}" class="image-cancel" data-no="{{$i++}}">x</div>
                                                            <div class="image-zone"><img id="pro-img-{{$i++}}" src="{{asset('uploads/seller_products/product_images/'.$img)}}"></div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                        <div id="hide" style="display: none;">
                                            <div class="preview-images-zone"></div>
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label>{{__('messages.status')}}</label>
                                            <select name="status" class="form-control select2">
                                                <option value="">{{__('messages.please-select')}}</option>
                                                <option value="Active" @if($product->status == "Active") selected @endif>{{__('messages.active')}}</option>
                                                <option value="Deactive" @if($product->status == "Deactive") selected @endif>{{__('messages.deactive')}}</option>
                                            </select>
                                        </div>
                                        <div class="form-group mt-5">
                                            <button class="btn btn-primary edit_product" type="button">{{__('messages.update-product')}}</button>
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
            document.getElementById('pro-image').addEventListener('change', readImage, false);
            $( ".preview-images-zone");
            $('.image-cancel').click(function(){
                var image = $(this).attr("id");
                var id = "{{$id}}";

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Image!",
                    icon:"warning",
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
                                url:'{{route('seller.drop.catalog.delete.img')}}',
                                type:'post',
                                data:{id:id,image:image},
                                success:function(data){
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

         $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.edit_product').click(function() {
                let formData = new FormData($('#edit_product')[0]); // Include files in the form
                $.ajax({
                    url: '{{ route("seller.drop.catalog.update") }}',
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
                                window.location.href = '{{ route("seller.drop.catalog") }}';
                            }, 1500);
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
@endsection
