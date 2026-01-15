@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">

            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.admin-cities')}}</h4>
                </div>
                
            </div>
            <!--End Page header-->
        <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.admin-cities-name')}}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                        <table id="example" class="table table-responsive-lg-sm table-bordered text-nowrap key-buttons">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('messages.admin-cities')}}</th>
                                <th hidden>{{__('messages.seller-cities')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach($admin_citys as $admin_city)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$admin_city->our_system_cities}}</td>
                                </tr>
                            @endforeach
                            {{-- @foreach ($seller_citys as $seller_city)
                              <tr>
                                  <td>{{$i++}}</td>
                                  <td>{{$seller_city->seller_city_name}}</td>
                              </tr>
                            @endforeach --}}
                            </tbody>
                        </table>
                   </div>
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
