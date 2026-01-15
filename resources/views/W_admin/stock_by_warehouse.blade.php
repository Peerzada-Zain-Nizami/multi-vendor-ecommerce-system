@extends('W_admin.base')
@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">
            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.stock-by-warehouse')}}</h4>
                </div>
                <div class="page-rightheader">
                    <div class="btn-list">
                        <button onclick="show_hide()" class="btn btn-primary"><i class="fa fa-filter"></i>
                            {{__('messages.filter')}}</button>
                    </div>
                </div>
            </div>
            <!--End Page header-->
        <!--div-->
            <div class="card" id="filter" @if($filter != 1)style="display: none;"@endif>
                <div class="card-header">
                    <div class="card-title">{{__('messages.filter-data')}}</div>
                </div>
                <form action="{{route('wadmin.stock.warehouse.filter')}}" method="post">
                    @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('messages.warehouse')}}</label>
                                <select class="form-control select2-show-search" name="warehouse">
                                    <option value="" selected disabled>{{__('messages.please-select')}}</option>
                                    @foreach($warehouses as $warehouse)
                                        @php
                                            $data = \App\MyClasses\Helpers::get_lang($warehouse->warehouse_name,$warehouse->id,"warehouse",App::getLocale());
                                            $warehouse_data = json_decode($data);
                                        @endphp
                                        <option value="{{$warehouse->id}}" {{(old('warehouse') == $warehouse->id)?'selected':''}}>{{$warehouse->warehouse_id}}/@if(App::getLocale() == "en"){{$data}} @elseif($warehouse_data) {{$warehouse_data->name}} @else {{$warehouse->warehouse_name}} @endif</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary " value="Search">
                </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.warehouse-details')}}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                    <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{__('messages.warehouse')}}</th>
                            <th>{{__('messages.product-name')}}</th>
                            <th>{{__('messages.quantity')}}</th>
                            <th>{{__('messages.placed')}}</th>
                            <th>{{__('messages.not-placed')}}</th>
                            <th>{{__('messages.packed-stock')}}</th>
                            <th>{{__('messages.delivered-stock')}}</th>
                            <th>{{__('messages.amount-value')}}</th>
                        </tr>
                        </thead>
                        <tbody id="result">
                        @php
                            $i = 1;
                        @endphp
                        @if($filter == 0)
                        @foreach($results as $result)
                            @php
                                $stock_list = \App\Models\Stockins_list::where('warehouse_id',$result->id)->get();
                                $array = array();
                                foreach ($stock_list as $list)
                                {
                                    $array[] = $list->stock_ins_id;
                                }
                                $products = \App\Models\StockIn::with('get_products')->whereIn('id',$array)->get()->groupBy('product_id');
                            @endphp
                            @foreach($products as $product)
                            <tr>
                                <td class="text-center">{{$i++}}</td>
                                @php
                                    $w_data = \App\MyClasses\Helpers::get_lang($result->warehouse_name,$result->id,"warehouse",App::getLocale());
                                    $warehouse_data = json_decode($data);
                                    $data = \App\MyClasses\Helpers::get_lang($product[0]->get_products[0]->product_name,$product[0]->get_products[0]->id,"product",App::getLocale());
                                    $product_data = json_decode($data);

                                @endphp
                                <td>{{$result->warehouse_id}}/@if(App::getLocale() == "en"){{$w_data}} @elseif($warehouse_data) {{$warehouse_data->name}} @else {{$result->warehouse_name}} @endif</td>
                                <td>@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$product[0]->get_products[0]->product_name}} @endif</td>
                                <td>{{\App\Models\StockIn::get_sum($result->id,$product[0]->product_id)}}</td>
                                <td>{{\App\Models\StockIn::get_available_stock($result->id,$product[0]->product_id)->display}}</td>
                                <td>{{\App\Models\StockIn::get_available_stock($result->id,$product[0]->product_id)->stock}}</td>
                                <td>{{\App\Models\StockIn::get_available_stock($result->id,$product[0]->product_id)->selected_stock}}</td>
                                <td>{{\App\Models\StockIn::get_available_stock($result->id,$product[0]->product_id)->delivered_stock}}</td>
                                <td>{{\App\Models\StockIn::get_value($result->id,$product[0]->product_id)}}</td>
                            </tr>
                            @endforeach
                        @endforeach
                        @endif

                        @if($filter == 1)
                            @php
                                $stock_list = \App\Models\Stockins_list::where('warehouse_id',$results->id)->get();
                                $array = array();
                                foreach ($stock_list as $list)
                                {
                                    $array[] = $list->stock_ins_id;
                                }
                                $products = \App\Models\StockIn::with('get_products')->whereIn('id',$array)->get()->groupBy('product_id');
                            @endphp
                             {{-- this is the warehouse stock --}}
                            @foreach($products as $product)
                                <tr>
                                    <td class="text-center">{{$i++}}</td>
                                    <td>{{$results->warehouse_id}}/{{$results->warehouse_name}}</td>
                                    <td>{{$product[0]->get_products[0]->product_name}}</td>
                                    <td>{{\App\Models\StockIn::get_sum($results->id,$product[0]->product_id)}}</td>
                                    <td>{{\App\Models\StockIn::get_available_stock($results->id,$product[0]->product_id)->display}}</td>
                                    <td>{{\App\Models\StockIn::get_available_stock($results->id,$product[0]->product_id)->stock}}</td>
                                    <td>{{\App\Models\StockIn::get_available_stock($results->id,$product[0]->product_id)->selected_stock}}</td>
                                    <td>{{\App\Models\StockIn::get_available_stock($results->id,$product[0]->product_id)->delivered_stock}}</td>
                                    <td>{{\App\Models\StockIn::get_value($results->id,$product[0]->product_id)}}</td>
                                </tr>
                            @endforeach
                        @endif
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
        function show_hide()
        {
            var x = document.getElementById("filter");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
        $(function() {

            $('#date').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#date').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' / ' + picker.endDate.format('DD-MM-YYYY'));
            });

            $('#date').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

        });
    </script>
@endsection
