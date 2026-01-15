@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.stock-details')}}</h4>
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
                <form action="{{route('admin.details.filter')}}" method="post">
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('messages.supplier')}}</label>
                                <select class="form-control select2-show-search" name="supplier">
                                    <option value="" selected disabled>{{__('messages.please-select')}}</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}" {{(old('supplier') == $supplier->id)?'selected':''}}>{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('messages.product')}}</label>
                                <select class="form-control select2-show-search" name="product">
                                    <option value="" selected disabled>{{__('messages.please-select')}}</option>
                                    @foreach($products as $product)
                                        @php
                                            $data = \App\MyClasses\Helpers::get_lang($product->product_name,$product->id,"product",App::getLocale());
                                            $product_data = json_decode($data);
                                            @endphp
                                        <option value="{{$product->id}}" {{(old('product') == $product->id)?'selected':''}}>@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$product->product_name}} @endif</option>
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
                    <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{__('messages.warehouse')}}</th>
                            <th>{{__('messages.supplier')}}</th>
                            <th>{{__('messages.category')}}</th>
                            <th>{{__('messages.product-name')}}</th>
                            <th>{{__('messages.quantity')}}</th>
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
                                $stock_ins_list = \App\Models\Stockins_list::where('warehouse_id',$result->id)->with('get_supplier','get_products')->get()->groupBy(['supplier_id','product_id']);
                                $stock_ins = \App\Models\StockIn::with('suppliers_name')->get();

                                @endphp
                            @foreach($stock_ins_list as $supplier_id=>$stock_in_list)
                            @foreach($stock_in_list as $product)
                            @foreach ($stock_ins as $supplier)
                            <tr>
                                <td class="text-center">{{$i++}}</td>
                                @php
                                            $data = \App\MyClasses\Helpers::get_lang($warehouse->warehouse_name,$warehouse->id,"warehouse",App::getLocale());
                                            $warehouse_data = json_decode($data);
                                            $catagory_data = \App\MyClasses\Helpers::get_lang_cat($product[0]->get_products[0]->category,"category",App::getLocale());
                                            @endphp
                                        <td>{{$result->warehouse_id}}/@if(App::getLocale() == "en"){{$data}} @elseif($warehouse_data) {{$warehouse_data->name}} @else {{$result->warehouse_name}} @endif</td>
                                        {{-- <td>{{ $product[0]->get_supplier[0]->name }}</td> --}}
                                        <td>{{ $supplier->suppliers_name[0]->name }}</td>
                                        <td>@if(App::getLocale() == "en"){{$catagory_data}} @elseif($catagory_data) {{$catagory_data}} @else {{$product[0]->get_products[0]->category}} @endif</td>
                                        @php
                                            $data = \App\MyClasses\Helpers::get_lang($product[0]->get_products[0]->product_name,$product[0]->get_products[0]->id,"product",App::getLocale());
                                            $product_data = json_decode($data);
                                        @endphp
                                        <td>@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$product[0]->get_products[0]->product_name}} @endif</td>
                                        <td>{{\App\Models\StockIn::get_sum($result->id,$supplier_id,$product[0]->product_id)}}</td>
                                        <td>{{\App\Models\StockIn::get_value($result->id,$supplier_id,$product[0]->product_id)}}</td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                        @endif
                        @if($filter == 1)
                                @foreach($results as $result)
                                    @foreach($result as $product)
                                        <tr>
                                            <td class="text-center">{{$i++}}</td>
                                            <td>{{\App\Models\StockIn::get_warehouse($product[0]->warehouse_id)}}</td>
                                            <td>{{$product[0]->get_supplier[0]->name}}</td>
                                            <td>{{$product[0]->get_products[0]->category}}</td>
                                            <td>{{$product[0]->get_products[0]->product_name}}</td>
                                            <td>{{\App\Models\StockIn::get_sum($product[0]->warehouse_id,$product[0]->supplier_id,$product[0]->product_id)}}</td>
                                            <td>{{\App\Models\StockIn::get_value($product[0]->warehouse_id,$product[0]->supplier_id,$product[0]->product_id)}}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--/div-->


    </div>
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
