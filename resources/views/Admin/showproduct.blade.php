@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.product-lists')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.product-detail')}}</div>
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
                    <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{__('messages.product-category')}}</th>
                            <th>{{__('messages.product-name')}}</th>
                            <th>{{__('messages.status')}}</th>
                            <th>{{__('messages.image')}}</th>
                            <th>{{__('messages.languages')}}</th>
                            <th>{{__('messages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach($results as $result)
                        <tr>
                            <td class="text-center">{{$i++}}</td>
                                @php
                                    $catagory_data = \App\MyClasses\Helpers::get_lang_cat($result->category,"category",App::getLocale());
                                @endphp
                            <td class="fs-13">@if(App::getLocale() == "en"){{$result->category}} @elseif($catagory_data) {{$catagory_data}} @else {{$result->category}} @endif</td>
                            @php
                                $data = \App\MyClasses\Helpers::get_lang($result->product_name,$result->id,"product",App::getLocale());
                                $product_data = json_decode($data);
                            @endphp
                            <td>@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$result->product_name}} @endif</td>
                            <td><h3><span class="badge @if($result->status == "Active") bg-success-transparent @else bg-danger-transparent @endif">@if($result->status == "Active") {{__('messages.active')}} @else {{__('messages.deactivate')}} @endif</span></h3></td>
                            <td><img class="avatar avatar-xxl" src="{{asset('uploads/featured_images/'.$result->featured_image)}}"></td>
                            <td>
                                <div class="w-100" style="overflow: auto; height: 50px;">
                                    @php
                                        $langs = \App\MyClasses\Helpers::act_lang($result->id,"product");
                                    @endphp
                                    @if(count($langs) > 0)
                                        <table class="table-striped table-hover table-sm">
                                            @foreach($langs as $lang)
                                                <tr>
                                                    <td><img src="{{asset ('assets/images/flags/'.Config::get('languages')[$lang->language]['flag-icon'].'.svg')}}" width="30px" height="20px" class="me-2">{{Config::get('languages')[$lang->language]['display']}}</td>
                                                    <td>
                                                        <a href="{{route('admin.product.lang.add.edit',$lang->id)}}" class="btn text-warning btn-sm"><i class="fa fa-edit"></i></a>
                                                        <a href="{{route('admin.warehouse.lang.del',$lang->id)}}" class="btn text-danger btn-sm"><i class="fa fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @else
                                        <p class="text-danger">{{__('messages.language-not-set')}}</p>
                                    @endif

                                </div>
                            </td>
                            <td>
                                <a href="{{route('admin.product.manage')."/view/".$result->id}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                <a href="{{route('admin.product.manage')."/edit/".$result->id}}" class="btn btn-warning"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0);" id="{{$result->id}}" class="btn btn-success add_lang_model"><i class="fa fa-language"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
            <!--/div-->


        </div>
    <!-- CONTAINER END -->
    <div class="modal fade" id="lang_model">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.add-language')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="add_error"></div>
                    <input type="text" id="ref_id" hidden>
                    <div class="form-group">
                        <label> {{__('messages.product-name')}}</label>
                        <input type="text" id="product_name" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label> {{__('messages.short-description')}}</label>
                        <input type="text" id="short_description" class="form-control"/>
                    </div>
                    <div class="form-group card-body">
                        <label> {{__('messages.brief-description')}}</label>
                        <textarea type="text" class="content form-control" id="brief_description"></textarea>
                    </div>
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.language-type')}}</label>
                        <select id="lang_type" class="form-control">
                            <option value="">{{__('messages.please-select')}}</option>
                            <option value="ar">{{__('messages.arabic')}}</option>
                            <option value="ur">{{__('messages.urdu')}}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary add_lang">{{__('messages.add')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('query')
    <script type="text/javascript">
        $(document).on('click','.add_lang_model',function () {
            $('#lang_type').html('');
            $('#add_error').html('');
            $('#add_error').removeClass('alert alert-light-danger');

            var id = $(this).attr('id');
            var data = {
                'ref_id': id,
                'ref_type': "product",
            };
            $.ajax({
                url: "{{route('admin.get.languages')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    var lang_list = "";
                    lang_list+= "<option value=''>Please Select</option>";
                    $.each(response,function (value,key) {
                        if (key.sort != "en")
                        {
                            lang_list+= "<option value='"+key.sort+"'>"+key.display+"</option>";
                        }
                    });
                    $('#lang_type').append(lang_list);
                    $('#ref_id').val(id);
                    $('#lang_model').modal('toggle');
                }
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.add_lang').click(function () {
            var data = {
                'product_name': $('#product_name').val(),
                'short_description': $('#short_description').val(),
                'brief_description': $('#brief_description').val(),
                'lang_type': $('#lang_type').val(),
                'ref_id': $('#ref_id').val(),
            };
            console.log(data);
            $.ajax({
                url: "{{route('admin.product.lang.add')}}",
                type:"POST",
                dataType: "json",
                data: data,
                success: function(response){
                    if (response.status == "fail")
                    {
                        $('#add_error').html('');
                        $.each(response.errors,function (key,error) {
                            $('#add_error').addClass('alert alert-light-danger');
                            $('#add_error').append('<li>'+error+'</li>');
                        })
                    }
                    else{
                        $('#lang_model').modal('hide');
                        Swal.fire({
                            title: 'Congratulations!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(loct, 1500);
                    }
                }
            })
        });
        function loct() {
            window.location.href = '{{route('admin.product.manage')}}';
        }
        function show_hide()
        {
            var x = document.getElementById("add_new");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>

@endsection