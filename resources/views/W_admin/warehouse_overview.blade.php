@extends('W_admin.base')


@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.warehouses-overview')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{__('messages.warehouses-list')}}</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-lg">
                            <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('messages.warehouse-code')}}</th>
                                    <th>{{__('messages.warehouse-name')}}</th>
                                    <th>{{__('messages.blocks/rooms')}}</th>
                                    <th>{{__('messages.racks')}}</th>
                                    <th>{{__('messages.shelf')}}</th>
                                    <th>{{__('messages.status')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($warehouses as $warehouse)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$warehouse->warehouse_id}}</td>
                                        @php
                                            $data = \App\MyClasses\Helpers::get_lang($warehouse->warehouse_name,$warehouse->id,"warehouse",App::getLocale());
                                            $warehouse_data = json_decode($data);
                                        @endphp
                                        <td>@if(App::getLocale() == "en"){{$data}} @elseif($warehouse_data) {{$warehouse_data->name}} @else {{$warehouse->warehouse_name}} @endif</td>
                                        <td>{{count($warehouse->blocks)}}</td>
                                        <td>{{count($warehouse->racks)}}</td>
                                        <td>{{count($warehouse->shelfs)}}</td>
                                        <td class="@if($warehouse->status == "Active") text-success @else text-danger @endif">@if($warehouse->status == "Active") {{__('messages.active')}} @else {{__('messages.deactive')}} @endif</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--/Row-->


        </div>
    </div>
    <!-- CONTAINER END -->

@endsection
