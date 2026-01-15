{{-- @php
    use App\Models\ShippingPrice;
    class shipping{
        public function format($id)
        {
            $data = ShippingPrice::where('group_id',$id)->with('shipping_cities')->get();
            foreach ($data as $group)
            {
                echo '<li><a href="javascript:void(0);" id="'.$category->id.'" class="cat_click" name="'.\App\MyClasses\Helpers::get_lang($category->category_name,$category->id,"category",App::getLocale()).'">'.\App\MyClasses\Helpers::get_lang($category->category_name,$category->id,"category",App::getLocale()).'</a>';
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
$shipping_cities = new ShippingPrice();
@endphp --}}
@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.shipping-charges') }}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.group-action') }}</h3>
                        </div>
                        @if (Session::has('success'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                                    aria-hidden="true">×</button>
                                <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <form id="add_group">
                                <div id="add_g_error"></div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.new-group-name') }}</label>
                                    <input type="text" class="form-control" name="group_name">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary add_group_submit"
                                        type="button">{{ __('messages.add-new') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.shipping-company') }}</h3>
                        </div>
                        @if (Session::has('success'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                                    aria-hidden="true">×</button>
                                <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <form id="add_group_price">
                                <div id="add_p_error"></div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.select-group') }}</label>
                                    <select name="group_id" class="form-control select2-show-search">
                                        <option value="">{{ __('messages.please-select') }}</option>
                                        @foreach ($shipping_groups as $shipping_group)
                                            <option value="{{ $shipping_group->id }}">{{ $shipping_group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @php
                                    $shipping_companies = \App\Models\ShippingCompany::all();
                                @endphp
                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.please-select-shipping-company') }}</label>
                                    <select name="shipping_company" class="form-control select2-show-search">
                                        <option value="">{{ __('messages.please-select') }}</option>
                                        @foreach ($shipping_companies as $shipping_company)
                                            <option value="{{ $shipping_company->id }}">{{ $shipping_company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.shipping-price') }}</label>
                                    <input type="text" class="form-control" name="shipping_price">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.return-price') }}</label>
                                    <input type="text" class="form-control" name="return_price">
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary add_group_price_submit"
                                        type="button">{{ __('messages.add-new') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.groups-detail') }}</h3>
                        </div>
                        @if (Session::has('sUccess'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                                    aria-hidden="true">×</button>
                                <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="table-responsive-lg">
                                <table id="example1" class="table table-responsive-lg-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('messages.group-name') }}</th>
                                            <th>{{ __('messages.shipping-company') }}</th>
                                            <th>{{ __('messages.price') }}</th>
                                            <th>{{ __('messages.return-price') }}</th>
                                            <th>{{ __('messages.action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @foreach ($shipping_groups as $shipping_group)
                                            @foreach ($shipping_group->shipping_cities as $shipping_price)
                                                <tr>
                                                    <td>{{ $i++ }}</td>
                                                    @php
                                                        $i = 1;
                                                        \App\Models\ShippingCompany::find(
                                                            $shipping_price->shipping_company,
                                                        );
                                                    @endphp
                                                    <td>{{ $shipping_group->name }}</td>
                                                    <td>{{ $shipping_company->name }}</td>
                                                    <td>{{ $shipping_price->price }}</td>
                                                    <td>{{ $shipping_price->return_price }}</td>
                                                    <td>
                                                        <div class="btn-group mt-2 mb-2">
                                                            <button type="button"
                                                                class="btn btn-light btn-pill dropdown-toggle"
                                                                data-bs-toggle="dropdown">
                                                                {{ __('messages.action') }} <span class="caret"></span>
                                                            </button>
                                                            <ul class="dropdown-menu" role="menu">
                                                                <li><a href="javascript:void(0);"
                                                                        id="{{ $shipping_group->id }}"
                                                                        class="edit_shipping_model">{{ __('messages.edit') }}</a>
                                                                </li>
                                                                <li><a href="{{ route('admin.shipping.company.delete', $shipping_group->id) }}"
                                                                        class="delete-confirm">{{ __('messages.delete') }}</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.cities-action') }}</h3>
                        </div>
                        @if (Session::has('success'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16"
                                    data-bs-dismiss="alert" aria-hidden="true">×</button>
                                <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                            </div>
                        @endif
                        <div class="card-body">
                            <form id="add_group_cities">
                                <div id="city_add_error"></div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.select-group') }}</label>
                                    <select name="group_id" class="form-control select2-show-search">
                                        <option value="">{{ __('messages.please-select') }}</option>
                                        @foreach ($shipping_groups as $shipping_group)
                                            <option value="{{ $shipping_group->id }}">{{ $shipping_group->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{ __('messages.seller-cities') }}</label>

                                    <select name="group_cities[]" class="form-control select2-show-search"
                                        id="city-select" multiple>

                                        @if ($allCitiesSelected)
                                            <option disabled>{{ __('messages.all-cities-selected') }}</option>
                                        @else
                                            <option value="">{{ __('messages.please-select') }}</option>
                                            @foreach ($city_datas as $city_data)
                                                <option value="{{ $city_data->id }}">{{ $city_data->our_system_cities }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                </div>
                                <div class="col-12">
                                    @if (!$allCitiesSelected)
                                        <button type="button" class="btn btn-secondary"
                                            id="select-10">{{ __('messages.select-10') }}</button>
                                    @endif
                                    <button class="btn btn-primary add_group_cities_submit"
                                        type="button">{{ __('messages.add-new') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('messages.cities-detail') }}</h3>
                        </div>
                        <div class="card-body" style="height: 300px; overflow-y: auto;">
                            <ul id="tree1">
                                @foreach ($shipping_groups as $shipping_group)
                                    <li>
                                        <a href="javascript:void(0);"
                                            id="{{ $shipping_group->id }}">{{ $shipping_group->name }}</a>
                                        <ul>
                                            @foreach ($shipping_group->group_cities as $cities)
                                                <li>
                                                    {{ $cities->shipping_cities[0]->our_system_cities }}
                                                    <button class="delete-city"
                                                        data-city-id="{{ $cities->shipping_cities[0]->id }}"
                                                        data-group-id="{{ $shipping_group->id }}" style="float: right;">
                                                        <i class="fa-solid fa-circle-minus"></i>
                                                    </button>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                </div>

            </div>
            <!--/Row-->
        </div>
    </div>
    <div class="modal fade" id="shipping_model_edit">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('messages.update-Group-details') }}</h6><button aria-label="Close"
                        class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="update_error"></div>
                    <input type="text" id="id" hidden>
                    <div id="data">

                    </div>
                    <div class="form-group">
                        <label class="form-label">Select Shipping Company</label>
                        <select id="shipping_company" class="form-select">
                            <option disabled>{{ __('messages.please-select') }}</option>
                            <option selected value="SMSA">{{ __('messages.SMSA') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.shipping-price') }}</label>
                        <input type="text" placeholder="Shipping Price" class="form-control" id="shipping_price">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('messages.return-price') }}</label>
                        <input type="text" placeholder="Return Price" id="return_price" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary update">{{ __('messages.update') }}</button> <button
                        class="btn btn-secondary" data-bs-dismiss="modal"
                        type="button">{{ __('messages.close') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTAINER END -->
@endsection
@section('query')
    <script type="application/javascript">
        $('.delete-confirm').on('click', function (event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'This record and it`s details will be permanantly deleted!',
                icon: 'warning',
                showCancelButton: true,
                dangerMode: true,
                confirmButtonText: "Ok, Delete it!",
                showConfirmButton: true,
            }).then((willDelete) => {
                if (willDelete) {
                      $.ajax({
                    url: url,
                    type: 'GET',
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
                        } else if (response.status === 'error') {
                            Swal.fire({
                                title: 'Error!',
                                text: response.message, // Display error message returned from server
                                icon: 'error',
                                showConfirmButton: true,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong!',
                            icon: 'error',
                            showConfirmButton: true,
                        });
                    }
                });
                }
                else {
                        Swal.fire({
                            title: 'Your Group is Safe!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
            });
        });
        $(Document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.add_group_submit').click(function(){
                $.ajax({
                    url:'{{route('admin.city.group.add')}}',
                    type:'POST',
                    data:$('#add_group').serialize(),
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
                            setTimeout(loct, 1500);
                        }
                    }
                });
            });

            
            $('.add_group_price_submit').click(function(){
                console.log($('#add_group_price').serialize());
                $.ajax({
                    url:'{{route('admin.city.group.price.add')}}',
                    type:'POST',
                    data:$('#add_group_price').serialize(),
                    success:function(response){
                        console.log(response);
                        if (response.status == "fail")
                        {
                            $('#add_p_error').html('');
                            $('#add_p_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#add_p_error').append('<li>'+error+'</li>');
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
                            setTimeout(loct, 1500);
                        }
                    }
                });
            });

            $('.add_group_cities_submit').click(function(){
                console.log($('#add_group_cities').serialize());
                $.ajax({
                    url:'{{route('admin.group.cities.add')}}',
                    type:'POST',
                    data:$('#add_group_cities').serialize(),
                    success:function(response){
                        console.log(response);
                        if (response.status == "fail")
                        {
                            $('#city_add_error').html('');
                            $('#city_add_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#city_add_error').append('<li>'+error+'</li>');
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
                            setTimeout(loct, 1500);
                        }
                    }
                });
            });

            $(document).on('click', '.edit_shipping_model', function () {
                $('#update_error').html('');
                $('#update_error').removeClass('alert alert-light-danger');
                var id = $(this).attr('id');
                console.log(id);

                var route = "{{route('admin.city.group.price.add')}}";

                // Clear previous dropdown content to avoid duplication
                $('#data').html('');

                $.get(route + '/edit/' + id, function (response) {
                    console.log(response);

                    // Generate options for shipping group
                    var shipping_group_name_options = '<option value="">{{ __("messages.please-select") }}</option>';
                    response.group.forEach(data => {
                        shipping_group_name_options += `<option value="${data.id}">${data.name}</option>`;
                    });

                    // Generate options for shipping company
                    // var shipping_company_name_options = '<option value="">{{ __("messages.please-select") }}</option>';
                    // response.shipping_company.forEach(data => {
                    //     // Add the "selected" attribute if the company name is "SMSA"
                    //     if (data.name === "SMSA") {
                    //         shipping_company_name_options += `<option value="${data.id}" selected>${data.name}</option>`;
                    //     } else {
                    //         shipping_company_name_options += `<option value="${data.id}">${data.name}</option>`;
                    //     }
                    // });

                    // Add dropdowns to the modal
                    var content1 = "{{ __('messages.select-group') }}";
                    // var content2 = "{{ __('messages.please-select-shipping-company') }}";

                    var html = `
                        <label class="form-label">${content1}</label>
                        <div class="input-group mb-3">
                            <select id="shipping_group" class="form-select">
                                ${shipping_group_name_options}
                            </select>
                        </div>
                       
                    `;
                    $('#data').append(html);

                    // Set the selected values for dropdowns
                    $('#shipping_group').val(response.shipping_price.group_id); // Set selected group
                    // $('#shipping_company').val(response.shipping_price.company_id); // Ensure selected company matches backend data

                    // Set the remaining fields
                    $('#id').val(response.shipping_price.id);
                    $('#shipping_price').val(response.shipping_price.price);
                    $('#return_price').val(response.shipping_price.return_price);

                    // Show the modal
                    $('#shipping_model_edit').modal('toggle');
                });
            });


            $('.update').click(function(){
                var id = $('#id').val();
                var update = {
                    'id':id,
                    'shipping_price':$('#shipping_price').val(),
                    'return_price':$('#return_price').val(),
                    'shipping_group':$('#shipping_group').val(),
                    'shipping_company':$('#shipping_company').val(),
                };
                console.log(update);
                $.ajax({
                    url:'{{route('admin.shipping.company.update')}}',
                    type:'POST',
                    data:update,
                    success:function(response){
                        if (response.status == "fail")
                        {
                            $('#update_error').html('');
                            $('#update_error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#update_error').append('<li>'+error+'</li>');
                            })
                        }
                        else{
                            $('#shipping_model_edit').modal('hide');
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
                });
            });
            function loct() {
                window.location.href = '{{route('admin.cities.prices')}}';
            }

          $(document).on('click', '.delete-city', function(){
    var cityId = $(this).data('city-id');
    var groupId = $(this).data('group-id');

    // Confirmation dialog using SweetAlert
    swal({
        title: 'Are you sure?',
        text: 'This city will be deleted from the group!',
        icon: 'warning',
        showCancelButton: true,
        dangerMode: true,
        confirmButtonText: "Ok, Delete it!",
        showConfirmButton: true,
    }).then(function (value) {
        if (value) {
            $.ajax({
                url: '{{ route("admin.delete.group.cities") }}', // Corrected delete route
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}", // Laravel CSRF protection
                    city_id: cityId,
                    group_id: groupId
                },
                success: function(response){
                console.log(response)
                    if(response.success){
                         Swal.fire({
                                title: 'Congratulations!',
                                text: 'City has been removed',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        location.reload();
                    } else {
                        swal("Error!", "City could not be removed.", "error");
                    }
                },
                error: function(){
                    swal("Error!", "Something went wrong!", "error");
                }
            });
        }
        else {
                        Swal.fire({
                            title: 'Your City is Safe!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
    });
});

 $('#select-10').click(function() {
            var select = $('#city-select');
            var selectedCount = select.find('option:selected').length;
            select.find('option:not(:selected)[value!=""]').slice(0, 10).prop('selected', true);
            select.trigger('change');
        });

        });
    </script>
@endsection
