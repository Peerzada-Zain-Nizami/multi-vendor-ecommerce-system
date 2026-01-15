@extends('Supplier.base')

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
            @if (Session::has('success'))
                <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                </div>
            @endif
            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.product-details')}}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                        <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                            <thead class="border-bottom-0 pt-3 pb-3">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('messages.product-category')}}</th>
                                <th>{{__('messages.product-name')}}</th>
                                <th>{{__('messages.product-status')}}</th>
                                <th>{{__('messages.selling-price')}}</th>
                                <th>{{__('messages.shipping-charges')}}</th>
                                <th>{{__('messages.available-stock')}}</th>
                                <th>{{__('messages.my-status')}}</th>
                                <th>{{__('messages.image')}}</th>
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
                                        $data = \App\MyClasses\Helpers::get_lang($result->products->product_name,$result->products->id,"product",App::getLocale());
                                        $product_data = json_decode($data);
                                        $catagory_data = \App\MyClasses\Helpers::get_lang_cat($result->products->category,"category",App::getLocale());
                                    @endphp
                                    <td class="fs-13">@if(App::getLocale() == "en"){{$result->products->category}} @elseif($catagory_data) {{$catagory_data}} @else {{$result->products->category}} @endif</td>
                                    <td>@if(App::getLocale() == "en"){{$result->products->product_name}} @elseif($product_data) {{$product_data->product_name}} @else {{$result->products->product_name}} @endif</td>
                                    <td><h3><span class="badge @if($result->products->status == "Active") bg-success-transparent @else bg-danger-transparent @endif">@if($result->products->status == "Active") {{__('messages.active')}} @else {{__('messages.deactive')}} @endif</span></h3></td>
                                    <td>{{$result->selling_price}}</td>
                                    <td>{{$result->shipping_charges}}</td>
                                    <td>{{$result->stock}}</td>
                                    <td><h3><span class="badge @if($result->status == "Available") bg-success-transparent @else bg-danger-transparent @endif">@if($result->status == "Available") {{__('messages.available')}} @else {{__('messages.not-available')}} @endif</span></h3></td>
                                    <td><img class="avatar avatar-xxl" src="{{asset('uploads/featured_images/'.$result->products->featured_image)}}"></td>
                                    <td>
                                        <a href="javascript:void(0)" id="{{$result->id}}" class="btn btn-warning edit"><i class="fa fa-edit"></i></a>
                                        <a href="{{route('supplier.catalog.delete',$result->id)}}" class="btn btn-danger delete-confirm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->
    </div>
    </div>
    <!-- CONTAINER END -->
    <div class="modal fade" id="edit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.edit-product-in-my-catalog')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="error">
                    </div>
                    <input type="text" id="list_id" hidden>
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.selling-price')}}</label>
                        <input type="text" id="selling_price"  class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-left">{{__('messages.stock')}}</label>
                        <input type="text" id="stock" class="form-control" disabled />
                    </div>
                    <div class="form-group">
                        <label class="form-label text-left">{{__('messages.shipping-charges-in-SAR')}}</label>
                        <input type="text" id="shipping_charges" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-left">{{__('messages.status')}}</label>
                        <select class="form-control" id="status">
                            <option value="Available">{{__('messages.available')}}</option>
                            <option value="Not Available">{{__('messages.not-available')}}</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update">{{__('messages.update')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('query')
    <script type="text/javascript">
     $('.delete-confirm').on('click', function (event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'This Product and it`s details will be permanantly deleted!',
                icon: 'warning',
                showCancelButton: true,
                dangerMode: true,
                confirmButtonText: "Ok, Delete it!",
                showConfirmButton: true,
            }).then(function (value) {
                if (value) {
                    window.location.href = url;
                }
                else {
                        Swal.fire({
                            title: 'Your Product is Safe!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
            });
        });
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
           $('.edit').click(function () {
               var id = $(this).attr("id");
               var route = "{{route('supplier.catalog')}}";
               $.get(route+'/edit/'+ id, function (product) {

                   $('#list_id').val(product.id);
                   $('#selling_price').val(product.selling_price);
                   $('#stock').val(product.stock);
                   $('#shipping_charges').val(product.shipping_charges);
                   var opt = $('option').val();
                   $("select option").each(function(){
                       if ($(this).text() == product.status)
                           $(this).attr("selected","selected");
                   });
                   $('#edit').modal('toggle');
               });
           });
           $('.update').click(function () {
               var data = {
                   'id': $('#list_id').val(),
                   'selling_price': $('#selling_price').val(),
                   'shipping_charges': $('#shipping_charges').val(),
                   'status': $('#status').val(),
               };
               $.ajax({
                   url: "{{route('supplier.catalog.update')}}",
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
                       else{
                           $('#edit').modal('hide');
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
                window.location.href = '{{route('supplier.catalog')}}';
            }
        });
    </script>
@endsection
