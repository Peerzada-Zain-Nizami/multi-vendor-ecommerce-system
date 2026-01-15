@extends('W_admin.base')


@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.placement-history')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{__('messages.stock-history')}}</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive-lg">
                            <table id="example" class="table table-responsive-lg-sm table-bordered text-nowrap key-buttons dataTable no-footer" role="grid" aria-describedby="example1_info">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{__('messages.user-name')}}</th>
                                    <th>{{__('messages.block/room')}}</th>
                                    <th>{{__('messages.rack')}}</th>
                                    <th>{{__('messages.shelf')}}</th>
                                    <th>{{__('messages.product-name')}}</th>
                                    <th>{{__('messages.quantity')}}</th>
                                    <th>{{__('messages.type')}}</th>
                                    <th>{{__('messages.created-at')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($placements as $placement)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$placement->user_get[0]->name}}</td>
                                        <td>
                                            @php
                                                $block = \App\Models\Room_Block::find($placement->shelf_get[0]->block_id)
                                            @endphp
                                            {{$block->block_code}}
                                        </td>
                                        <td>
                                            @php
                                                $rack = \App\Models\Rack::find($placement->shelf_get[0]->rack_id)
                                            @endphp
                                            {{$rack->rack_code}}
                                        </td>
                                        <td>{{$placement->shelf_get[0]->shelf_code}}</td>
                                        <td>
                                            @php
                                                $products = \App\Models\Product::where('id',$placement->stock_in_get[0]->product_id)->get();
                                                $data = \App\MyClasses\Helpers::get_lang($products[0]->product_name,$products[0]->id,"product",App::getLocale());
                                                $product_data = json_decode($data);
                                            @endphp

                                            @if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else  {{$products->product_name}} @endif
                                        </td>
                                        <td>{{$placement->quantity}}</td>
                                        <td class="@if ($placement->type == 'in')
                                                text-success
                                                @elseif ($placement->type == 'out')
                                                text-danger
                                                @elseif ($placement->type == 'move')
                                                text-primary
                                                @endif">
                                            @if ($placement->type == 'in')
                                                {{__('messages.in')}}
                                            @elseif ($placement->type == 'out')
                                                {{__('messages.out')}}
                                            @elseif ($placement->type == 'move')
                                                {{__('messages.move')}}
                                            @endif
                                        </td>
                                        <td>{{$placement->created_at}}</td>
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
