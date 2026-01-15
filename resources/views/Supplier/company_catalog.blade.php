<?php
    use App\Models\Category;
    class categories{
        public function format($id)
        {
            $data = Category::where('parent_id',$id)->with('children')->get();
            foreach ($data as $category)
            {
                echo '<li>';
                ?>
                <input type="checkbox" name="category[]" value="{{$category->id}}" {{ (is_array(old('category')) && in_array($category->id, old('category'))) ? ' checked' : '' }}>
                                                <a href="javascript:void(0);">{{\App\MyClasses\Helpers::get_lang($category->category_name,$category->id,"category",App::getLocale())}}</a>

                <?php
                if ($category->children->isNotEmpty())
                {
                    echo "<ul>";
                    self::format($category->id);
                    echo "</ul>";
                }
                echo "</li>";
            }
        }
    }
$chaild = new categories();
?>
@extends('Supplier.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.company-catalog')}}</h4>
                </div>
                <div class="page-rightheader">
                    <div class="btn-list">
                        <button onclick="show_hide()" class="btn btn-primary"><i class="fa fa-filter"></i>
                            {{__('messages.filter')}}</button>
                    </div>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="row">
                <div id="success"></div>
                <div style="display: none;" id="filter" class="col-sm-12">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 ">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <div class="card-title">{{__('messages.categories-&amp;-fliters')}}</div>
                                </div>
                                @if (Session::has('danger'))
                                    <div class="alert alert-light-danger" role="alert">
                                        <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                        <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}}
                                    </div>
                                @endif
                                <form action="{{route('supplier.company.catalog.filter')}}" method="post">
                                    @csrf
                                <div class="card-body">
                                    <ul id="tree1">
                                        @foreach($categories as $category)
                                            <li>
                                                <input type="checkbox" name="category[]" value="{{$category->id}}" {{ (is_array(old('category')) && in_array($category->id, old('category'))) ? ' checked' : '' }}>
                                                <a href="javascript:void(0);">{{\App\MyClasses\Helpers::get_lang($category->category_name,$category->id,"category",App::getLocale())}}</a>
                                                @if ($category->children->isNotEmpty())
                                                    <ul>
                                                        {{$chaild->format($category->id)}}
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="form-group">
                                        <label class="font-weight-bold mt-2">{{__('messages.business-model')}}</label>
                                        <select name="business_model[]" class="form-control select2 select2-hidden-accessible @if($errors->has('business_model'))is-invalid @endif" data-placeholder=" {{__('messages.choose-model')}}" multiple="" tabindex="-1" aria-hidden="true">
                                            @foreach($models as $model)
                                                <option value="{{$model->name}}" {{ (is_array(old('business_model')) && in_array($model->name, old('business_model'))) ? ' selected' : '' }}>{{\App\MyClasses\Helpers::get_lang($model->name,$model->id,"business_model",App::getLocale())}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                               <div class="card-footer">
                                    <input type="submit" class="btn btn-primary" value="{{__('messages.apply-filter')}}">
                                   <a href="{{route('supplier.company.catalog')}}" class="btn btn-gray">{{__('messages.clear-filter')}}</a>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        @if($results->IsNotEmpty())
                             @foreach($results as $result)
                                <div class="col-sm-3 alert">
                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $data = $result->created_at->diffInDays($now);;
                                        $class = null;
                                        $new = false;
                                    if ($data <= 30)
                                    {
                                        $class = "offer offer-radius offer-primary";
                                        $new = true;
                                    }
                                    else{
                                        $class = "card item-card";
                                    }
                                    @endphp
                                    <div class="{{$class}}">
                                        @if($new == true)
                                            <div class="shape">
                                                <div class="shape-text">
                                                    {{__('messages.new')}}
                                                </div>
                                            </div>
                                        @endif
                                        <div class="card-body pb-0">
                                            <div class="text-center zoom">
                                                <img src="{{asset('uploads/featured_images/'.$result->featured_image)}}" class="img-fluid w-100 br-7">
                                            </div>
                                            <div class="card-body px-0 pb-3">
                                                <div class="row">
                                                    <div>
                                                        @php
                                                            $data = \App\MyClasses\Helpers::get_lang($result->product_name,$result->id,"product",App::getLocale());
                                                            $product_data = json_decode($data);
                                                            $catagory_data = \App\MyClasses\Helpers::get_lang_cat($result->category,"category",App::getLocale());
                                                        @endphp
                                                        <h3>@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$result->product_name}} @endif</h3>
                                                        <h6>{{__('messages.category')}}: @if(App::getLocale() == "en"){{$result->category}} @elseif($catagory_data) {{$catagory_data}} @else {{$result->category}} @endif</h6>
                                                        <p class="shop-description fs-13 text-muted mb-0">@if(App::getLocale() == "en"){{$result->short_description}} @elseif($product_data) {{$product_data->short_description}} @else {{$result->short_description}} @endif</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center pb-4 ps-2 pe-2">
                                            <a href="{{route('supplier.company.catalog').'/view/'.$result->id}}" class="btn bg-primary-transparent text-primary mb-2 border-primary"><i class="fe fe-eye me-1 font-weight-bold"></i>{{__('messages.view')}}</a>
                                            <?php
                                                $data = \App\Models\SupplierProduct::where('user_id',Auth::user()->id)->where('product_id',$result->id)->first();
                                                if ($data)
                                                {
                                                    echo '<span class="text-success">'.__("messages.already-added").'</span>';
                                                }
                                            else{
                                            ?>
                                            <button onclick="model('{{$result->id}}')" id="{{$result->id}}" class="btn btn-primary mb-2"><i class="fe fe-shopping-cart me-2"></i>{{__('messages.add-to-my-list')}}</button>
                                            <?php
                                            }
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h3 class="text-muted pt-3">{{__('messages.no-products-found')}}</h3>
                        @endif
                    </div>
                    @if($filter == 0)
                    <div class="d-flex justify-content-end">
                        {{$results->links('pagination')}}
                    </div>
                    @endif
                </div>
            </div>
            <!--/div-->


        </div>
    </div>
    <!-- CONTAINER END -->
    <div class="modal fade" id="add_list">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.add-product-to-my-catalog')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="error">
                    </div>
                    <input type="text" id="product_id" hidden>
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.selling-price')}}</label>
                        <input type="text" id="selling_price" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-left">{{__('messages.stock')}}</label>
                        <input type="text" id="stock" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-left">{{__('messages.shipping-charges-in-SAR')}}</label>
                        <input type="text" id="shipping_charges" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary add_list">{{__('messages.add-in-my-list')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('query')
    <script type="text/javascript">
        function tax_hide()
        {
            var x = document.getElementById("tax_body");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
        function show_hide()
        {
            var x = document.getElementById("filter");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
        function model(id)
        {
            $('#error').html('');
            $('#product_id').val(id);
            $('#add_list').modal('show');
        }
        $(document).ready(function () {
            var counter = 0;
            $(document).on("click",'.add_tax',function(){
                var whole_extra_item_add = $('.extra_item_add').html();
                $(this).closest("#add_item").append(whole_extra_item_add);
                counter++;
            });
            $(document).on("click",'.del_tax',function(event){
                $(this).closest(".extra_item_del").remove();
                counter -= 1

            });
            $(document).on('click','.add_list',function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var id = $('#product_id').val();
                var data = {
                    'product_id': $('#product_id').val(),
                    'selling_price': $('#selling_price').val(),
                    'stock': $('#stock').val(),
                    'shipping_charges': $('#shipping_charges').val(),
                };
                $.ajax({
                    url: "{{route('supplier.company.catalog.addlist')}}",
                    type:"POST",
                    dataType: "json",
                    data: data,
                    success: function(response){
                        if (response.status == 400)
                        {
                            $('#error').html('');
                            $('#error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#error').append('<li>'+error+'</li>');
                            })
                        }
                        else if (response.status == 200) {
                            $('#add_list').modal('hide');
                               Swal.fire({                     
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function () {
                                 $('#add_list').modal('hide');
                                location.reload();
                            }, 1500);
                        }
                        else{
                            $('#error').html('');
                            $('#error').removeClass('alert alert-light-danger');
                            $('#success').html(
                                '<div class="alert alert-light-success my-alert" role="alert">\n' +
                                '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Well done! </strong>'+response.message+'\n' +
                                '</div>');
                            var newmsg = document.createElement("span");
                            newmsg.className = "text-success";
                            newmsg.innerHTML = "Added in list";
                            document.getElementById(id).replaceWith(newmsg);
                            $('#add_list').modal('hide');
                            $('#add_list').find('input').val("");
                            setTimeout(function() {
                                $(".my-alert").alert('close');
                            }, 2000);
                        }
                    }
                })
            });
        });
    </script>
@endsection