@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.new-invoice')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div>
                <div class="form-group">
                    <label>{{__('messages.search-product')}}</label>
                    <select name="product" id="query" class="form-control select2-show-search">
                        <option value="">{{__('messages.please-select')}}</option>
                        @foreach($results as $result)
                            @php
                                $data = \App\MyClasses\Helpers::get_lang($result->product_name,$result->id,"product",App::getLocale());
                                $product_data = json_decode($data);
                            @endphp
                            <option value="{{$result->id}}">@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$result->product_name}} @endif</option>
                        @endforeach
                    </select>
                </div>
                <div id="success">

                </div>
            </div>
            <div class="card" id="blcok" style="display: none;">
                <div class="card-body">
                    <table class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{__('messages.supplier')}}</th>
                            <th>{{__('messages.selling-price')}}</th>
                            <th>{{__('messages.shipping-charges')}}</th>
                            <th>{{__('messages.stock')}}</th>
                            <th>{{__('messages.status')}}</th>
                            <th>{{__('messages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody id="result">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/div-->


    </div>
    <div class="modal fade" id="shop">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.add-in-list')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="error">
                    </div>
                    <input type="text" id="p_id" hidden>
                    <input type="text" id="button" hidden>
                    <div class="form-group">
                        <label class=" text-left">{{__('messages.quantity')}}</label>
                        <input type="number" placeholder="Quantity" id="quantity" class="form-control"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary add">{{__('messages.add')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>

            </div>
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
           $('#query').on('change',function () {
               var value = $(this).val();
               $.ajax({
                   method:"POST",
                   url:"{{route('admin.invoice.show.list')}}",
                   data:{id:value},
                   dataType:"json",
                   success:function(response){
                       $("#result").html('');
                       $("#blcok").show();
                       var i = 1;
                       var bodyData = '';
                       $.each(response.result,function(key,index){

                           bodyData+="<tr>";
                           bodyData+="<td>"+ i++ +"</td><td>"+index.suppliers_name[0].name+"</td><td>"+index.selling_price+"</td><td>"+index.shipping_charges+"</td>"
                               +"<td>"+index.stock+"</td><td>"+index.status+"</td>";
                           var row = '';
                           var add_button = "{{__('messages.add-in-list')}}";
                           $.ajax({
                               method:"POST",
                               url:"{{route('admin.invoice.add.list.check')}}",
                               data:{user:index.user_id,p_id:index.product_id},
                               async: false,
                               dataType:"json",
                               success:function(check){
                                   if (check.status == 400)
                                   {
                                       row+= "<td class='text-success'>Already added in list</td>";
                                   }
                                   else{
                                       row+= "<td><button class='btn btn-primary click_modal' id='"+index.id+"' value='"+index.id+"'><i class='fe fe-shopping-cart me-2'></i>"+add_button+"</button></td>";
                                   }
                               }
                           });
                           bodyData+= row;
                           bodyData+="</tr>";
                       });
                       console.log(bodyData);
                       $("#result").append(bodyData);
                   }
               })
           });
            $(document).on('click','.click_modal',function () {
                var id = $(this).val();
                var button = $(this).attr("id");
                $('#p_id').val(id);
                $('#button').val(button);
                $('#shop').modal('toggle');
            });
            $('.add').click(function () {
                var id = $('#p_id').val();
                var button = $('#button').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var data = {
                    'id':id,
                    'quantity': $('#quantity').val(),
                };
                $.ajax({
                    url: "{{route('admin.invoice.add.list')}}",
                    method:"POST",
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
                            // $('#error').html('');
                            // $('#error').removeClass('alert alert-light-danger');
                            // $('#success').html(
                            //     '<div class="alert alert-light-success" role="alert">\n' +
                            //     '    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>\n' +
                            //     '    <strong>Well done! </strong>'+response.message+'\n' +
                            //     '</div>');
                              Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                              $('#shop').modal('hide');
                            setTimeout(function() {
                              location.reload();
                             }, 1500);
                            // $('#shop').modal('hide');
                            var newmsg = document.createElement("p");
                            newmsg.className = "text-success";
                            newmsg.innerHTML = "Added in list";
                            document.getElementById(button).replaceWith(newmsg);
                            $('#shop').find('input').val("");
                            setTimeout(function() {
                                $(".alert").alert('close');
                            }, 2000);
                        }
                    }
                })
            });
        });
    </script>
@endsection