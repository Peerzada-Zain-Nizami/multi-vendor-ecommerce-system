@extends('Admin.base')
@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">

            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.shipping-plans')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('messages.update-plan')}}</h3>
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

                            <form id="edit_plan">
                             <div id="add_g_error"></div>
                                @php
                                    $plan_price = json_decode($data->plan_price);
                                    $products = json_decode($data->product_price);
                                    $shipping = json_decode($data->shipping_price);
                                    $cancellation = json_decode($data->order_cancellation);
                                    $push_product = json_decode($data->push_product);
                                @endphp
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.plan-name')}}</label>
                                            <input type="text" class="form-control" placeholder="{{__('messages.plan-name')}}" name="plan_name" @if($data->id == 1 || $data->name == "Free") readonly @endif value="{{$data->name}}">
                                            <input type="hidden" value="{{$data->id}}" name="id">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.plan-monthly-price')}}</label>
                                            <input type="text" class="form-control" placeholder="{{__('messages.plan-monthly-cost')}}" name="plan_monthly_price" @if($data->id == 1 || $data->name == "Free") readonly @else value="{{$plan_price->Monthly}}" @endif>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.plan-yearly-price')}}</label>
                                            <input type="text" placeholder="{{__('messages.plan-yearly-price')}}" class="form-control" name="plan_yearly_price" @if($data->id == 1 || $data->name == "Free") readonly @else value="{{$plan_price->Yearly}}" @endif >
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.no-of-product-add-in-list')}}</label>
                                            <input type="text" placeholder="{{__('messages.enter-number-of-product')}}" class="form-control" name="no_P_list" value="{{$data->listing_product}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.no-of-push-product-by-hour')}}</label>
                                            <input type="text" class="form-control" name="push_product_by_hour" placeholder="{{__('messages.enter-size-that-the-seller-push-product-a-hour')}}" value="{{$push_product->push_product_by_hour}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.no-of-push-product-by-day')}}</label>
                                            <input type="text" class="form-control" name="push_product_by_day" placeholder="{{__('messages.enter-size-that-the-seller-push-product-a-day')}}" value="{{$push_product->push_product_by_day}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.no-of-platform-sync')}}</label>
                                            <input type="text" class="form-control" name="plateform_sync" placeholder="{{__('messages.no-of-platform-sync')}}" value="{{$data->plateform_sync}}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.shipping-price-discount')}}</label>
                                            <div class="input-group">
                                                <input type="text" name="shipping_price_disc" placeholder="{{__('messages.shipping-price-discount')}}" value="{{$shipping->discount}}" class="form-control">
                                                {{-- <div class="input-group-append">
                                                    <select class="form-control select2" name="shipping_price_disc_method" data-placeholder="{{__('messages.choose-method')}}">
                                                        <option value="percentage" @if($shipping->method == "percentage") selected @endif class="dropdown-item" >{{__('messages.by-percentage')}}%</option>
                                                        <option value="fixed_price" @if($shipping->method == "fixed_price") selected @endif class="dropdown-item" >{{__('messages.by-fixed-price')}}</option>
                                                    </select>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.cancellation-price-discount')}}</label>
                                            <div class="input-group">
                                                <input type="text" name="cancel_price_discount" placeholder="{{__('messages.cancellation-price-discount')}}" value="{{$cancellation->discount}}" class="form-control">
                                                {{-- <div class="input-group-append">
                                                    <select class="form-control select2" name="cancel_price_discount_method" data-placeholder="{{__('messages.choose-method')}}">
                                                        <option value="percentage" @if($cancellation->method == "percentage") selected @endif class="dropdown-item" >{{__('messages.by-percentage')}}%</option>
                                                        <option value="fixed_price" @if($cancellation->method == "fixed_price") selected @endif class="dropdown-item" >{{__('messages.by-fixed-price')}}</option>
                                                    </select>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.status')}}</label>
                                            <select class="form-control select2" name="status" @if($plan || $data->name == "Free" || $data->id == 1) disabled @endif data-placeholder="{{__('messages.choose-method')}}">
                                                <option value="Active" @if($data->status == "Active") selected @endif class="dropdown-item" >{{__('messages.active')}}</option>
                                                <option value="Deactive" @if($data->status == "Deactive") selected @endif class="dropdown-item" >{{__('messages.deactive')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.customs-currency')}}</label>
                                            <select name="currency" class="form-control custom-select select2">
                                                <option @if($data->currency == "SAR") selected @endif value="SAR">SAR</option>
                                                <option @if($data->currency == "USD") selected @endif value="USD">USD</option>
                                                <option @if($data->currency == "AED") selected @endif value="AED">AED</option>
                                                <option @if($data->currency == "BHD") selected @endif value="BHD">BHD</option>
                                                <option @if($data->currency == "EGP") selected @endif value="EGP">EGP</option>
                                                <option @if($data->currency == "KWD") selected @endif value="KWD">KWD</option>
                                                <option @if($data->currency == "OMR") selected @endif value="OMR">OMR</option>
                                                <option @if($data->currency == "JOD") selected @endif value="JOD">JOD</option>
                                                <option @if($data->currency == "QAR") selected @endif value="QAR">QAR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <table class="table table-borderless text-nowrap">
                                                <tr>
                                                    <td class="text-start"><label class="form-label fs-4 pt-3">{{__('messages.product-price-discount')}}</label></td>
                                                    <td class="text-end align-content-right pt-5"><button id="addRow" type="button" class="btn btn-info">{{__('messages.add-row')}}</button></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div id="newRow">
                                            @if ($products)
                                            @foreach($products as $product)
                                                <div id="inputFormRow">
                                                    <label class="form-label">{{__('messages.product-price-discount')}}</label>
                                                    <div class="input-group mb-3">
                                                        @php
                                                            $j_product = json_decode($product);
                                                        @endphp
                                                        <select name="category[]" class="form-control price_discount" onfocus="set_last_val(this)" onchange="validate_select(this)" required>
                                                            <option value=""> {{__("messages.select-category")}} </option>
                                                            @foreach($category as $categori)
                                                                <option value="{{$categori->id}}" @if($categori->id == $j_product->category) selected  @endif > {{$categori->category_name}} </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text" name="price[]" class="form-control m-input" value="{{$j_product->price}}" required>
                                                        {{-- <div class="input-group-append">
                                                            <select class="form-control select2" name="product_price_disc_method[]">
                                                                <option value="percentage" @if($j_product->method == "percentage") selected @endif class="dropdown-item" >{{__('messages.by-percentage')}}%</option>
                                                                <option value="fixed_price" @if($j_product->method == "fixed_price") selected @endif class="dropdown-item" >{{__('messages.by-fixed-price')}}</option>
                                                            </select>
                                                        </div> --}}
                                                        <button id="removeRow" type="button" class="btn btn-danger">Remove</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group text-center">
                                            <button class="btn btn-primary edit_plan"type="button">{{ __('messages.update') }}</button>
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
    {{-- <script type="text/javascript">
        var category_names = JSON.parse('@php echo $category @endphp');
        var category_name_options = '<option value=""> {{__("messages.select-category")}} </option>';
        category_names.forEach(category => {
            category_name_options += `<option value="${category.id}">${category.category_name}</option>`
        });

        $("#addRow").click(function () {
            var content1 = "{{__('messages.product-price-discount')}}";
            var content2 = "{{__('messages.by-percentage')}}";
            var content3 = "{{__('messages.by-fixed-price')}}";
            var content4 = "{{__('messages.enter-price')}}";
            var html = `<div id="inputFormRow">
                            <label class="form-label">${content1}</label>
                            <div class="input-group mb-3">
                                <select name="category[]" required class="form-control price_discount" onfocus="set_last_val(this)" onchange="validate_select(this)" >
                                    ${category_name_options}
                                </select>
                                <input type="text" name="price[]" class="form-control m-input" placeholder="${content4}" required>
                                <div class="input-group-append">
                                    <select class="form-control select2" name="product_price_disc_method[]">
                                        <option value="percentage" class="dropdown-item" >${content2}%</option>
                                        <option value="fixed_price" class="dropdown-item" >${content3}</option>
                                    </select>
                                </div>
                                <button id="removeRow" type="button" class="btn btn-danger">Remove</button>
                            </div>
                        </div>`;
            $('#newRow').append(html);
        });

        function set_last_val(ele) {
            ele.setAttribute('data-value', ele.value);
            console.log(ele);
        }
        function validate_select(ele) {
            let last_val = ele.getAttribute('data-value');
            console.log(last_val);
            let equipment_values = document.querySelectorAll('.price_discount');
            equipment_values.forEach(equip => {
                if (equip != ele && equip.value == ele.value) {
                    ele.value = last_val;
                }
            });
        }
        // remove row
        $(document).on('click', '#removeRow', function () {
            $(this).closest('#inputFormRow').remove();
        });
    </script> --}}

     <script type="text/javascript">
      $(Document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
       $('.edit_plan').click(function(){
                $.ajax({
                    url:'{{route('admin.seller.plan.update')}}',
                    type:'POST',
                    data:$('#edit_plan').serialize(),
                    success:function(response){
                        console.log(response);
                        if (response.status == "fail")
                        {
                            $('#add_g_error').html('');
                            $('#add_g_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_g_error').append('<li>'+error+'</li>');
                            })
                        }
                        else{
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                           setTimeout(function() {
                            window.location.href = '{{ route("admin.seller.plan.manage") }}';
                        }, 1500);
                        }

                    }
                });
            });
        });
        var category_names = JSON.parse('@php echo $category @endphp');
        var category_name_options = '<option value=""> {{__("messages.select-category")}} </option>';
        category_names.forEach(category => {
            category_name_options += `<option value="${category.id}">${category.category_name}</option>`
        });

        $("#addRow").click(function () {
            var content1 = "{{__('messages.product-price-discount')}}";
            var content2 = "{{__('messages.by-percentage')}}";
            var content3 = "{{__('messages.by-fixed-price')}}";
            var content4 = "{{__('messages.enter-price')}}";
            var html = `<div id="inputFormRow">
                            <label class="form-label">${content1}</label>
                            <div class="input-group mb-3">
                                <select name="category[]" required class="form-control price_discount" onfocus="set_last_val(this)" onchange="validate_select(this)" >
                                    ${category_name_options}
                                </select>
                                <input type="text" name="price[]" class="form-control m-input" placeholder="${content4}" required>
                                <button id="removeRow" type="button" class="btn btn-danger">Remove</button>
                            </div>
                        </div>`;
            $('#newRow').append(html);
        });

        function set_last_val(ele) {
            ele.setAttribute('data-value', ele.value);
            console.log(ele);
        }
        function validate_select(ele) {
            let last_val = ele.getAttribute('data-value');
            console.log(last_val);
            let equipment_values = document.querySelectorAll('.price_discount');
            equipment_values.forEach(equip => {
                if (equip != ele && equip.value == ele.value) {
                    ele.value = last_val;
                }
            });
        }
        // remove row
        $(document).on('click', '#removeRow', function () {
            $(this).closest('#inputFormRow').remove();
        });
    </script>
@endsection
