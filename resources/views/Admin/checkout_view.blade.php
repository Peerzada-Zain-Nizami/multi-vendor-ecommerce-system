@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.invoice-checkout')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-8">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="ms-3">{{__('messages.invoice-items')}}</span>
                            </div>
                        </div>
                        <div id="error">

                        </div>
                        <div class="table-responsive-lg">
                            <table class="table card-table table-responsive-lg table-vcenter text-nowrap shopping-carttable">
                                <thead class="border-bottom-0 pt-3 pb-3 ">
                                <tr>
                                    <th class="fs-15">{{__('messages.product')}}</th>
                                    <th>{{__('messages.product-name')}}</th>
                                    <th>{{__('messages.price')}}</th>
                                    <th>{{__('messages.quantity')}}</th>
                                    <th>{{__('messages.total')}}</th>
                                    <th>{{__('messages.action')}}</th>
                                </tr>
                                </thead>
                                <tbody id="result">
                                </tbody>
                            </table>
                            <div class="text-center" id="loader" style="display: none;">
                                <div class="spinner-grow text-muted"></div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-4 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.invoice-summary')}}</div>
                        </div>
                        <div class="card-body">
                            <h5>{{__('messages.amount-details')}}</h5>
                            <div class="table-responsive-lg">
                                <table class="table table-borderless text-nowrap mb-0">
                                    <tbody>
                                    <tr>
                                        <td class="text-start">{{__('messages.sub-total')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto"><span id="sub_total"></span></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">{{__('messages.shipping-charges')}}</td>
                                        <td class="text-end"><span class="font-weight-bold  ms-auto" id="fee"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start fs-18">{{__('messages.total-bill')}}</td>
                                        <td class="text-end"><span class="ms-2 font-weight-bold  fs-22"><span id="total"></span></span></td>
                                    </tr>
                                    <tr>
                                        {{-- <td colspan="2" class="text-center "><a href="{{route('admin.invoice.order',['id'=>$id])}}" class="btn btn-primary btn-md mt-3" role="button">{{__('messages.sent-order')}}</a></td> --}}
                                        <td colspan="2" class="text-center"><button data-id="{{ $id }}" type="button" class="btn btn-primary btn-md mt-3 sent_order">{{ __('messages.sent-order') }}</button></td> 
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End Row-->
        </div>
        <!--/div-->


    </div>
    </div>
    <!-- CONTAINER END -->

@endsection
@section('query')
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            get_record();
            function get_record()
            {
                var id = "{{$id}}";
                var asset = "{{asset('uploads/featured_images/')}}";
                $.ajax({
                    url:'{{route('admin.invoice.checkout.get')}}',
                    type:'post',
                    data:{id:id},
                    beforeSend:function(){
                        $('#loader').show();
                    },
                    success:function(data){
                        $("#result").html('');
                        $('#loader').hide();
                        var bodyData = '';
                        var sub_total = 0;
                        var fee = 0;
                        var total = 0;
                        $.each(data.result,function (key,value) {
                            var msg = null;
                            if(value.quantity == 1)
                            {
                                msg="disabled";
                            }
                            bodyData+="<tr class='cart-table attach-supportfiles'>";
                            bodyData+="<td><img class='avatar avatar-lg br-7' src='" +asset+"/"+value.company_product[0].featured_image +"'></td>";
                            bodyData+="<td>"+ value.company_product[0].product_name +"</td>";
                            bodyData+="<td>"+ value.supplier_product[0].selling_price +"</td>";
                            bodyData+="<td><button id='"+ value.id+"' class='btn btn-sm btn-primary minus'"+ msg+"><i class='fe fe-minus'></i></button><input style='outline: none; border: none;background-color: transparent;width: 100px;' class='ms-2 me-2 fs-15 text-center' value='"+value.quantity+"' disabled/><button id='"+ value.id+"' class='btn btn-sm btn-primary plus'><i class='fe fe-plus'></i></button></td>";
                            bodyData+="<td><span>"+ value.supplier_product[0].selling_price*value.quantity +"</span></td>";
                            bodyData+="<td><button id='"+ value.id+"' class='btn btn-sm btn-outline-danger del'><i class='fe fe-trash'></i></button></td>";
                            bodyData+="</tr>";
                            sub_total += value.supplier_product[0].selling_price*value.quantity;
                            fee += value.supplier_product[0].shipping_charges*value.quantity;
                            total += value.supplier_product[0].selling_price*value.quantity+value.supplier_product[0].shipping_charges*value.quantity;
                        });
                        $("#result").append(bodyData);
                        $("#sub_total").html(sub_total);
                        $("#fee").html(fee);
                        $("#total").html(total);
                    }
                });
            }

            $(document).on('click','.minus',function () {
                var id = $(this).attr("id");
                $.ajax({
                    url:'{{route('admin.invoice.checkout.product.minus')}}',
                    type:'post',
                    data:{id:id},
                    success:function(data){
                        get_record();
                    }
                });
            });
            $(document).on('click','.plus',function () {
                var id = $(this).attr("id");
                $.ajax({
                    url:'{{route('admin.invoice.checkout.product.plus')}}',
                    type:'post',
                    data:{id:id},
                    success:function(data){
                        if (data.status == 400)
                        {
                            $('#error').html(
                                '<div class="alert alert-light-danger" role="alert">\n' +
                                '    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                                '    <strong>Oopps! </strong>'+data.message+'\n' +
                                '</div>');
                        }
                        else{
                            get_record();
                        }
                        setTimeout(function() {
                            $(".alert").alert('close');
                        }, 3000);
                    }
                });
            });
        $(document).on('click', '.del', function() {
    var id = $(this).attr("id"); // Assuming the button has an id attribute with the ID to delete

    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this Invoice!",
        icon: "warning",
        showCancelButton: true,
        dangerMode: true,
        confirmButtonText: "Ok, Delete it!",
        showConfirmButton: true,
    }).then((willDelete) => {
        if (willDelete) {
            $.ajax({
                url: '{{ route('admin.invoice.checkout.product.del') }}',
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            title: 'Congratulations!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            location.reload(); // Reload the page after the success message
                        }, 1500);
                    } 
                },
            });
        } else {
            Swal.fire({
                title: 'Your Invoice is Safe!',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
});

 $('.sent_order').click(function() {
    var id = $(this).data('id');
    var route = '{{ route("admin.invoice.order", ":id") }}';
    route = route.replace(':id', id);

    $.ajax({
        url: route,
        type: 'GET', // Use GET request here
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    title: 'Congratulations!',
                    text: response.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function() {
                   window.location.href = '{{route('admin.invoice.checkout')}}';
                }, 1500);
            }
        }
    });
});


 });
    </script>
@endsection
