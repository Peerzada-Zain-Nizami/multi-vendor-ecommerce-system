@extends('W_admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.product-search')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{__('messages.product-detail')}}</div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{__('messages.product-image')}}</th>
                                <th>{{__('messages.product-name')}}</th>
                                <th>{{__('messages.warehouse-name')}}</th>
                                <th>{{__('messages.block/room')}}</th>
                                <th>{{__('messages.racks')}}</th>
                                <th>{{__('messages.shelf')}}</th>
                                <th>{{__('messages.quantity')}}</th>
                                <th>{{__('messages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                            $i = 1;
                            @endphp
                            @foreach($datas as $data)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td><img class="avatar avatar-lg br-7" src="{{asset('uploads/featured_images/'.$data['product']->featured_image)}}"></td>
                                    <td>{{$data['product']->product_name}}</td>
                                    <td>{{$data['warehouse']->warehouse_name}}</td>
                                    <td>{{$data['block']->block_code}}</td>
                                    <td>{{$data['rack']->rack_code}}</td>
                                    <td>{{$data['shelf']->shelf_code}}</td>
                                    <td>{{$data['stock']->quantity}}</td>
                                    <td>
                                        <a href="{{route('wadmin.product.place.stock.out',["id"=>$data['shelf']->id,"order_id"=>$order_id])}}" class="btn btn-primary">{{__('messages.stock-out')}}</a>
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
    <!-- CONTAINER END -->

@endsection