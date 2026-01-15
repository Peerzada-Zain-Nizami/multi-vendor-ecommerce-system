@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">

            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.seller-cities')}}</h4>
                </div>
            </div>
            <!--End Page header-->
        <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.all-cities')}}</div>
                </div>
                <div class="card-body">
                    <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{__('messages.our-cities-name')}}</th>
                            <th class="text-center">{{__('messages.seller-cities-name')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach($admin_cities as $admin_city)
                            <tr>
                                @foreach ($seller_cities as $seller_city)
                                @if($seller_city->admin_city_id == $admin_city->id)
                                        <td>{{$i++}}</td>
                                        <td>{{$admin_city->our_system_cities}}</td>
                                        <td>{{$seller_city->seller_city_name}}</td>
                                    @endif
                                @endforeach
                                {{--<td> @if($value == true) <span class="alert alert-light-danger pt-1 pb-1">{{__('messages.missed')}}</span> @else {{$seller_city->seller_city_name}} @endif</td>--}}
                            </tr>
                        @endforeach
                        @foreach($admin_cities as $admin_city)
                            <tr>
                                @php
                                    $flag = false;
                                @endphp
                                @foreach ($seller_cities as $seller_city)
                                @if($seller_city->admin_city_id == $admin_city->id)
                                        @php
                                            $flag = true;
                                        @endphp
                                    @endif
                                @endforeach
                                @if($flag == false)
                                    <td>{{$i++}}</td>
                                    <td><span class="text-danger">{{$admin_city->our_system_cities}}</span></td>
                                    <td><span class="alert alert-light-danger pt-1 pb-1">{{__('messages.missed')}}</span></td>
                                @endif
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

@endsection
@section('query')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endsection