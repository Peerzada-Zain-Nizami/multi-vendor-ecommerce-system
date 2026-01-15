@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.add-city')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="row">
                @if (Session::has('success'))
                    <div class="alert alert-light-success" role="alert">
                        <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                        <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                    </div>
                @endif
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.city-list')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-responsive-lg-sm table-bordered text-nowrap key-buttons dataTable no-footer">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{__('messages.admin-cities-name')}}</th>
                                        <th>{{__('messages.our-cities-name')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach($datas as $data)
                                        <tr>
                                            @if(!($data->get_seller_cities)->isEmpty())
                                                <td>{{$i++}}</td>
                                                <td>{{$data->our_system_cities}}</td>
                                                <td>{{$data->get_seller_cities[0]->seller_city_name}}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    @foreach($datas as $data)
                                        <tr>
                                            @if(($data->get_seller_cities)->isEmpty())
                                                <td>{{$i++}}</td>
                                                <td>{{$data->our_system_cities}}</td>
                                                <td><span class="alert alert-light-danger pt-1 pb-1">{{__('messages.missed')}}</span></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
        <!--/div-->


    </div>
@endsection
@section('query')
    <script type="text/javascript">

        function getCity(e) {
            const address = e.value;

            const geocoder = new google.maps.Geocoder();

            let city;

            geocoder.geocode({ address: address }, (results, status) => {
                if (status === "OK") {

                    console.log(results[0]);

                    for (const component of results[0].address_components) {
                        const addressType = component.types[0];
                        if (addressType == "locality") {
                            city = component.long_name;
                        }
                    }

                    document.getElementById('g_city').value = city;

                    console.log('City:', city);
                }
            });

        }
    </script>
@endsection