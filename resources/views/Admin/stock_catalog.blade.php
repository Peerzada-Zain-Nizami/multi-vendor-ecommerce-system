@php
 $user1 = Illuminate\Support\Facades\Auth::user();
 $smsa = App\Models\SMSACredential::where('user_id',$user1->id)->first();
    function rates($id)
    {
    $invoices = DB::table("company_orders")
                    ->where('status', 'Complete')
                    ->where('created_at', '>', now()->subDays(30)->endOfDay())
                    ->get();
                    $data = array();
                    foreach ($invoices as $invoice)
                    {
                        $var = json_decode($invoice->original_order);
                        foreach ($var as $pro)
                        {
                            if ($pro->product_id == $id)
                            {
                                $data[] = $pro->rate;
                            }
                        }
                    }
                    if (!empty($data))
                    {
                        return $array = array('min'=>min($data),'max'=>max($data),'avg'=>array_sum($data)/count($data));
                    }
                    else{
                        return $array = array('min'=>0,'max'=>0,'avg'=>0);
                    }
    }
    function stock($id)
    {
        $array = array();
        $stock_ins = DB::table("stock_ins")->where('product_id',$id)->get();
        foreach ($stock_ins as $stock_in)
        {
            $array[] = $stock_in->id;
        }
        $stock_ins_list = DB::table("stock_ins_list")->whereIn('stock_ins_id',$array)->sum('stock');
        return $stock_ins_list;
    }
@endphp
@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.stock-catalog')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            <div id="success">

            </div>
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
                            <th>{{__('messages.stock')}}</th>
                            <th>{{__('messages.purchasing-price')}}</th>
                            <th>{{__('messages.selling-price')}}</th>
                            <th>{{__('messages.retail-price')}}</th>
                            <th>{{__('messages.suggested-price')}}</th>
                            <th>{{__('messages.status')}}</th>
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

                                <td class="fs-13">{{$result->get_products[0]->category}}</td>

                                @php
                                    $data = \App\MyClasses\Helpers::get_lang($result->get_products[0]->product_name,$result->get_products[0]->id,"product",App::getLocale());
                                    // $data = \App\MyClasses\Helpers::get_lang('Michael Joseph',3,"product",App::getLocale());
                                    $product_data = json_decode($data);
                                @endphp
                                <td>@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$result->get_products[0]->product_name}} @endif</td>
                                <td>{{stock($result->product_id)}}</td>
                                <td>
                                    @php
                                        $rate = rates($result->product_id);
                                    @endphp
                                    <span class='text-info'>{{__('messages.minimum')}}: {{(int)$rate['min']}}</span><br>
                                    <span class='text-danger'>{{__('messages.maximum')}}: {{(int)$rate['max']}}</span><br>
                                    <span class='text-success'>{{__('messages.average')}}: {{(int)$rate['avg']}}</span>
                                </td>
                                <td>
                                    <span class="@php if((int)$rate['avg'] >= $result->selling_price){echo 'text-danger';} @endphp">{{__('messages.selling-rate')}}: {{$result->selling_price}}</span><br>
                                    <span class="text-info">{{__('messages.discount')}}: @php if($result->discount == null){ echo 0;}else{ echo $result->discount; } @endphp</span><br>
                                    <span class="text-success">{{__('messages.net-rate')}}: {{$result->selling_price+$result->fee-$result->discount}}</span>
                                </td>
                                <td>{{$result->retail_price}}</td>
                                <td>{{$result->suggested_price}}</td>
                                <td><h3><span class="badge @if($result->status == "Listed") bg-success-transparent @else bg-danger-transparent @endif">@if($result->status == "Listed") {{__('messages.listed')}} @else {{__('messages.unlisted')}} @endif</span></h3></td>
                                <td><img class="avatar avatar-xxl" src="{{asset('uploads/featured_images/'.$result->get_products[0]->featured_image)}}"></td>
                                <td>
                                    <button id="{{$result->id}}" class="btn btn-warning click-modal"><i class="fa fa-edit"></i></button>
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
    <div class="modal fade" id="edit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.edit-product')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="error">
                    </div>
                    <input id="p_id" type="number" hidden />
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.selling-price')}}</label>
                        <input type="number" placeholder="Selling Price" id="selling_price" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.discount')}}</label>
                        <input type="number" placeholder="Discount" id="discount" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.retail-price')}}</label>
                        <input type="number" placeholder="Retail Price" id="rp" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.suggested-price')}}</label>
                        <input type="number" placeholder="Suggested Price" id="sp" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.status')}}</label>
                        <select id="status" class="form-control">

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update">{{__('messages.update')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>

            </div>
        </div>
    </div>
    <!-- CONTAINER END -->

@endsection
@section('query')
    {{-- <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click','.click-modal',function () {
            var id = $(this).attr("id");
            $('#p_id').val(id);
            $.ajax({
                url:'{{route('stock.catalog.edit')}}',
                type:'post',
                data:{id:id},
                success:function(data){
                    console.log(data);
                    $('#selling_price').val(data.selling_price);
                    $('#discount').val(data.discount);
                    $('#rp').val(data.retail_price);
                    $('#sp').val(data.suggested_price);
                    var options = '';
                    var listed = '';
                    var unlisted = '';
                    if(data.status == "Listed"){
                        listed = "selected";
                    }
                    if(data.status == "Unlisted"){
                        unlisted = "selected";
                    }
                    var listed_text = "{{__('messages.listed')}}";
                    var unlisted_text = "{{__('messages.unlisted')}}";
                    var please_select = "{{__('messages.please-select')}}";
                    options+="<option value=''>"+please_select+"</option>";
                    options+="<option value='Listed' "+listed+">"+listed_text+"</option>";
                    options+="<option value='Unlisted' "+unlisted+">"+unlisted_text+"</option>";
                    $('select').html(options);
                    $('#edit').modal('toggle');
                }
            });
        });
        $(document).ready(function () {
            $('.update').click(function () {
                var data ={
                    'id':$('#p_id').val(),
                    'selling_price':$('#selling_price').val(),
                    'discount':$('#discount').val(),
                    'status':$('#status').val(),
                    'retail_price':$('#rp').val(),
                    'suggested_price':$('#sp').val(),
                };
                console.log(data.id);

                $.ajax({
                    url:'{{route('stock.catalog.update')}}',
                    type:'post',
                    data:data,
                    success:function(response){
                        if (response.status == 400)
                        {
                            $('#error').html('');
                            $('#error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#error').append('<li>'+error+'</li>');
                            })
                        }
                        else{
                            $('#error').html('');
                            $('#error').removeClass('alert alert-light-danger');
                            $('#success').html(
                                '<div class="alert alert-light-success" role="alert">\n' +
                                '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Well done! </strong>'+response.message+'\n' +
                                '</div>');
                            $('#edit').modal('hide');
                            location.href = '{{route('stock.catalog')}}';
                        }
                    }
                });
            });

        });
    </script> --}}


<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Function to check if all cities are selected before allowing "Listed" status
    function checkCitiesBeforeListing() {
     var status = $('#status').val();

     if (status === "Listed") {
        $.ajax({
            url: '{{ route('stock.check.all.cities.selected') }}',
            type: 'get',
            success: function(response) {
                if (response.status === 'success') {
                    // If all cities are selected, check SMSA credentials
                    checkSmsaCredentials();
                } else {
                    $("#edit").modal('hide');
                    Swal.fire({
                        title: '<strong>Incomplete City Grouping</strong>',
                        icon: 'info',
                        html: 'Please visit the, ' +
                              '<a href="' + response.blade_link + '"><span style="font-weight:bold">City Management/City Price</span></a> ' +
                              'page to associate the cities with the appropriate groups.',
                    });
                }
            }
        });
     } else {
        // If status is not "Listed," proceed with the update
        updateProduct();
     }
}

function checkSmsaCredentials() {
    var smsa = '{{ $smsa }}'; // Passing SMSA credentials from backend

    if (!smsa) {
        $("#edit").modal('hide');
        Swal.fire({
            title: '<strong>Empty SMSA Credentials</strong>',
            icon: 'info',
            html: 'Please fill in the <a href="{{ route('admin.smsa.credentials') }}"><span style="font-weight:bold">SMSA Credentials</span></a> first.'
        });
    } else {
        updateProduct();
    }
}


    function updateProduct() {
        var data = {
            'id': $('#p_id').val(),
            'selling_price': $('#selling_price').val(),
            'discount': $('#discount').val(),
            'status': $('#status').val(),
            'retail_price': $('#rp').val(),
            'suggested_price': $('#sp').val(),
        };

        $.ajax({
            url: '{{ route('stock.catalog.update') }}',
            type: 'post',
            data: data,
            success: function (response) {
                if (response.status == 400) {
                    $('#error').html('');
                    $('#error').addClass('alert alert-light-danger');
                    $.each(response.errors, function (key, error) {
                        $('#error').append('<li>' + error + '</li>');
                    })
                }
                //  else {
                //     $('#error').html('');
                //     $('#error').removeClass('alert alert-light-danger');
                //     $('#success').html(
                //         '<div class="alert alert-light-success" role="alert">\n' +
                //         '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                //         '    <strong>Well done! </strong>' + response.message + '\n' +
                //         '</div>');
                //     $('#edit').modal('hide');
                //     location.href = '{{ route('stock.catalog') }}';
                // }
                else {
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                              $('#edit').modal('hide');
                            setTimeout(function() {
                                window.location.href = '{{ route("stock.catalog") }}';
                            }, 2500);
                    }
            }
        });
    }

    $(document).on('click', '.click-modal', function () {
        var id = $(this).attr("id");
        $('#p_id').val(id);
        $.ajax({
            url: '{{ route('stock.catalog.edit') }}',
            type: 'post',
            data: { id: id },
            success: function (data) {
                console.log(data);
                $('#selling_price').val(data.selling_price);
                $('#discount').val(data.discount);
                $('#rp').val(data.retail_price);
                $('#sp').val(data.suggested_price);
                var options = '';
                var listed = '';
                var unlisted = '';
                if (data.status == "Listed") {
                    listed = "selected";
                }
                if (data.status == "Unlisted") {
                    unlisted = "selected";
                }
                var listed_text = "{{__('messages.listed')}}";
                var unlisted_text = "{{__('messages.unlisted')}}";
                var please_select = "{{__('messages.please-select')}}";
                options += "<option value=''>" + please_select + "</option>";
                options += "<option value='Listed' " + listed + ">" + listed_text + "</option>";
                options += "<option value='Unlisted' " + unlisted + ">" + unlisted_text + "</option>";
                $('select').html(options);
                $('#edit').modal('toggle');
            }
        });
    });

     $('#editModal').on('hidden.bs.modal', function () {
        Swal.fire({
            title: 'Success!',
            text: 'The item has been successfully updated.',
            icon: 'success',
            showConfirmButton: false,
            timer: 1500
        });
    });

    $(document).ready(function () {
        $('.update').click(function () {
            // Check if all cities are selected before allowing "Listed" status
            checkCitiesBeforeListing();
        });
    });
</script>
@endsection
