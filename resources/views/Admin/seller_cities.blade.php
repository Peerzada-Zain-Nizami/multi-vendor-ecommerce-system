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
                    <div class="card-title">{{__('messages.all-seller')}}</div>
                </div>
                <div class="card-body">
                    <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{__('messages.seller-name')}}</th>
                            <th class="text-center">{{__('messages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $i = 1;
                        @endphp
                        @foreach($new_sellers as $new_seller)
                            @if(!$new_seller->get_seller_cities->isempty())
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$new_seller->name}}</td>
                                    <td>
                                        <a href="{{route('admin.seller.city.view',$new_seller->id)}}" class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            @else
                            @endif
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