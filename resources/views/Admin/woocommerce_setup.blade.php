@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.woocommerce-setup')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            <div class="card">
                @if (Session::has('success'))
                    <div class="alert alert-light-success" role="alert">
                        <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                        <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                    </div>
                @elseif (Session::has('success'))
                        <div class="alert alert-light-danger" role="alert">
                            <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                            <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                        </div>
                @endif
                <div class="card-header">
                    <div class="card-title">{{__('messages.charges')}}</div>
                </div>
                <div class="card-body">
                    <div class="panel panel-primary">
                        <div class=" tab-menu-heading p-0 bg-light">
                            <div class="tabs-menu1 ">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs">
                                    <li class=""><a href="#tax" class="@if(old('tab') == "tax") active @endif" data-bs-toggle="tab">{{__('messages.tax')}}</a></li>
                                    <li class=""><a href="#shipping_class" class="@if(old('tab') == "shipping_class") active @endif" data-bs-toggle="tab">{{__('messages.shipping-class')}}</a></li>
                                    <li class=""><a href="#shipping_zone" class="@if(old('tab') == "shipping_zone") active @endif" data-bs-toggle="tab">{{__('messages.shipping-zone')}}</a></li>
                                    <li class=""><a href="#shipping_method" class="@if(old('tab') == "shipping_method") active @endif" data-bs-toggle="tab">{{__('messages.shipping-method')}}</a></li>
                                    <li class=""><a href="#shipping_cost" class="@if(old('tab') == "shipping_cost") active @endif" data-bs-toggle="tab">{{__('messages.shipping-cost')}}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane p-5 @if(old('tab') == "tax") active @endif" id="tax">
                                    <form action="{{route('admin.add.tax.class')}}" method="post">
                                        @csrf
                                        <input type="text" name="tab" value="tax" hidden>
                                        <div class="row mt-5">
                                            <div class="col-sm-5">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <div class="card-title text-white">{{__('messages.add-tax-class')}}</div>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="form-group">
                                                            <label>{{__('messages.tax-class-name')}}</label>
                                                            <input type="text" name="tax_class_name" class="form-control">
                                                        </div>
                                                        @if ($errors->has('tax_class_name'))
                                                            <span class="text-danger">{{ $errors->first('tax_class_name') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group">
                                                            <label>{{__('messages.tax-name')}}</label>
                                                            <select name="tax_id" class="form-control select2-show-search">
                                                                <option value="">{{__('messages.please-select')}}</option>
                                                                @foreach($taxs as $tax)
                                                                    <option value="{{$tax->id}}" {{--@if($result->category == $value->category_name) selected @endif--}}>{{\App\MyClasses\Helpers::get_lang($tax->name,$tax->id,"tax",App::getLocale())}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @if ($errors->has('tax_id'))
                                                            <span class="text-danger">{{ $errors->first('tax_id') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group text-end">
                                                            <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Tax Classes</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive-lg">
                                                        <table class="table table-bordered card-table table-vcenter text-nowrap" id="datatable">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th>Tax Class Name</th>
                                                                <th>Tax</th>
                                                                <th>Rate %</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @php
                                                                $i = 1;
                                                            @endphp
                                                            @foreach($woo_taxs as $woo_tax)
                                                            <tr>
                                                                <td class="text-center">{{$i++}}</td>
                                                                <td>{{$woo_tax->tax_class_name}}</td>
                                                                <td>{{$woo_tax->tax_name[0]->name}}</td>
                                                                <td>{{$woo_tax->tax_name[0]->percent}}</td>
                                                                <td class="text-center" >
                                                                    <a href="{{route('admin.woo.tax.delete',$woo_tax->id)}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane p-5 @if(old('tab') =="shipping_class")active @endif" id="shipping_class">
                                    <form action="{{route('admin.add.shipping.class')}}" method="post">
                                        @csrf
                                        <input type="text" name="tab" value="shipping_class" hidden>
                                        <div class="row mt-5">
                                            <div class="col-sm-5">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <div class="card-title text-white">{{__('messages.shipping-class')}}</div>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="form-group">
                                                            <label>{{__('messages.add-shipping-class')}}</label>
                                                            <input type="text" name="shipping_class" class="form-control">
                                                        </div>
                                                        @if ($errors->has('shipping_class'))
                                                            <span class="text-danger">{{ $errors->first('shipping_class') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group text-end">
                                                            <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Shipping Classes</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive-lg">
                                                        <table class="table table-bordered card-table table-vcenter text-nowrap" id="datatable">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th>Shipping Class Name</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @php
                                                                $i = 1;
                                                            @endphp
                                                            @foreach($woo_ships as $woo_ship)
                                                                <tr>
                                                                    <td class="text-center">{{$i++}}</td>
                                                                    <td>{{$woo_ship->shipping_class}}</td>
                                                                    <td class="text-center" >
                                                                        <a href="{{route('admin.woo.shipping.delete',$woo_ship->id)}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane p-5 @if(old('tab') =="shipping_zone")active @endif" id="shipping_zone">
                                    <form action="{{route('admin.add.shipping.zone')}}" method="post">
                                        @csrf
                                        <input type="text" name="tab" value="shipping_zone" hidden>
                                        <div class="row mt-5">
                                            <div class="col-sm-7">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <div class="card-title text-white">{{__('messages.shipping-zone')}}</div>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="form-group">
                                                            <label>{{__('messages.add-shipping-zone')}}</label>
                                                            <input type="text" name="shipping_zone" class="form-control">
                                                        </div>
                                                        @if ($errors->has('shipping_zone'))
                                                            <span class="text-danger">{{ $errors->first('shipping_zone') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group">
                                                            <label>{{__('messages.zone-region')}}</label>
                                                            <select name="zone_region[]" class="form-control select2" data-placeholder="Choose Areas" multiple>
                                                                @foreach($woo_contients as $woo_contient)
                                                                    @php
                                                                        $json_continent = json_decode($woo_contient->data);
                                                                    @endphp
                                                                    <option value="{{$json_continent->code}}|continent" >{{$json_continent->name}}</option>
                                                                    @foreach($woo_contient->get_country as $woo_country)
                                                                        @php
                                                                            $json_country = json_decode($woo_country->data);
                                                                            $states = \App\Models\Woo_State::where('country_id',$woo_country->id)->get();
                                                                        @endphp
                                                                        <option value="{{$json_country->code}}|country" >_{{$json_country->name}}</option>
                                                                        @foreach($states as $state)
                                                                            @php
                                                                                $json_state = json_decode($state->data);
                                                                            @endphp
                                                                            <option value="{{$json_state->code}}|state|{{$json_country->code}}">__{{$json_state->name}}</option>
                                                                        @endforeach
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @if ($errors->has('zone_region'))
                                                            <span class="text-danger">{{ $errors->first('zone_region') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group text-end">
                                                            <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Shipping Zone</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive-lg">
                                                        <table class="table table-bordered card-table table-vcenter text-nowrap" id="datatable">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th>Zone Name</th>
                                                                <th>Zone Region</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @php
                                                                $i = 1;
                                                            @endphp
                                                            @foreach($woo_shipping_zone as $shipping_zone)
                                                                <tr>
                                                                    <td class="text-center">{{$i++}}</td>
                                                                    <td>{{$shipping_zone->shipping_zone}}</td>
                                                                    <td>
                                                                        @php
                                                                        $regions = json_decode($shipping_zone->zone_region);
                                                                            foreach ($regions as $region)
                                                                            {
                                                                                $output = \App\MyClasses\Helpers::get_woo_location($region);
                                                                                echo '<span class="badge rounded-pill bg-primary mt-2">'.$output.'</span>';
                                                                            }
                                                                        @endphp
                                                                    </td>
                                                                    <td class="text-center" >
                                                                        <a href="{{route('admin.woo.shipping.zone.delete',$shipping_zone->id)}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane p-5 @if(old('tab') =="shipping_method")active @endif" id="shipping_method">
                                    <form action="{{route('admin.add.shipping.zone.method')}}" method="post">
                                        @csrf
                                        <input type="text" name="tab" value="shipping_method" hidden>
                                        <div class="row mt-5">
                                            <div class="col-sm-5">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <div class="card-title text-white">{{__('messages.shipping-method')}}</div>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="form-group">
                                                            <label>{{__('messages.add-shipping-method')}}</label>
                                                            <input type="text" name="shipping_method" class="form-control">
                                                        </div>
                                                        @if ($errors->has('shipping_method'))
                                                            <span class="text-danger">{{ $errors->first('shipping_method') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group">
                                                            <label>{{__('messages.shipping-zone')}}</label>
                                                            <select name="shipping_zone_id" class="form-control select2-show-search">
                                                                <option value="">{{__('messages.please-select')}}</option>
                                                                @foreach($woo_shipping_zone as $shipping_zone)
                                                                    <option value="{{$shipping_zone->id}}">{{$shipping_zone->shipping_zone}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @if ($errors->has('shipping_zone_id'))
                                                            <span class="text-danger">{{ $errors->first('shipping_zone_id') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group text-end">
                                                            <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Shipping Company</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive-lg">
                                                        <table class="table table-bordered card-table table-vcenter text-nowrap" id="datatable">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th>Shipping Company</th>
                                                                <th>Shipping Zone</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @php
                                                                $i = 1;
                                                            @endphp
                                                            @foreach($woo_shipping_methods as $woo_shipping_method)
                                                                <tr>
                                                                    <td class="text-center">{{$i++}}</td>
                                                                    <td>{{$woo_shipping_method->shipping_method}}</td>
                                                                    @php
                                                                        $woo_zone = \App\Models\Woo_Shipping_Zone::where('id',$woo_shipping_method->hipping_zone_id)->get();
                                                                    @endphp
                                                                    <td>{{$woo_zone[0]->shipping_zone}}</td>
                                                                    <td class="text-center" >
                                                                        <a href="{{route('admin.woo.shipping.zone.method.delete',$woo_shipping_method->id)}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane p-5 @if(old('tab') =="shipping_cost")active @endif" id="shipping_cost">
                                    <form action="{{route('admin.add.shipping.cost')}}" method="post">
                                        @csrf
                                        <input type="text" name="tab" value="shipping_cost" hidden>
                                        <div class="row mt-5">
                                            <div class="col-sm-5">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <div class="card-title text-white">{{__('messages.shipping-cost')}}</div>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="form-group">
                                                            <label>{{__('messages.select-shipping-zone')}}</label>
                                                            <select name="shipping_zone_cost_id" class="form-control select2-show-search">
                                                                <option value="">{{__('messages.please-select')}}</option>
                                                                @foreach($woo_shipping_zone as $shipping_zone)
                                                                    <option value="{{$shipping_zone->id}}">{{$shipping_zone->shipping_zone}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @if ($errors->has('shipping_zone_cost_id'))
                                                            <span class="text-danger">{{ $errors->first('shipping_zone_cost_id') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group">
                                                            <label>{{__('messages.shipping-method')}}</label>
                                                            <select name="shipping_method_id" class="form-control select2-show-search">
                                                                <option value="">{{__('messages.please-select')}}</option>
                                                                @foreach($woo_shipping_methods as $woo_shipping_method)
                                                                    <option value="{{$woo_shipping_method->id}}">{{$woo_shipping_method->shipping_method}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @if ($errors->has('shipping_method_id'))
                                                            <span class="text-danger">{{ $errors->first('shipping_method_id') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group">
                                                            <label>{{__('messages.shipping-class')}}</label>
                                                            <select name="shipping_class_id" class="form-control select2-show-search">
                                                                <option value="">{{__('messages.please-select')}}</option>
                                                                @foreach($woo_ships as $woo_ship)
                                                                    <option value="{{$woo_ship->id}}">{{$woo_ship->shipping_class}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        @if ($errors->has('shipping_class_id'))
                                                            <span class="text-danger">{{ $errors->first('shipping_class_id') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group">
                                                            <label>{{__('messages.add-shipping-cost')}}</label>
                                                            <input type="text" name="shipping_cost" class="form-control">
                                                        </div>
                                                        @if ($errors->has('shipping_cost'))
                                                            <span class="text-danger">{{ $errors->first('shipping_cost') }}</span>
                                                            <br>
                                                        @endif
                                                        <div class="form-group text-end">
                                                            <input type="submit" class="btn btn-primary" value="{{__('messages.save')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title">Shipping Cost</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive-lg">
                                                        <table class="table table-bordered card-table table-vcenter text-nowrap" id="datatable">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th>Shipping Zone</th>
                                                                <th>Shipping Company</th>
                                                                <th>Shipping Class</th>
                                                                <th>Shipping Cost</th>
                                                                <th>Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @php
                                                                $i = 1;
                                                            @endphp
                                                            @foreach($woo_shipping_costs as $woo_shipping_cost)
                                                                <tr>
                                                                    <td class="text-center">{{$i++}}</td>
                                                                    @php
                                                                        $woo_zone = \App\Models\Woo_Shipping_Zone::where('id',$woo_shipping_cost->shipping_zone_id)->get();
                                                                        $woo_method = \App\Models\Woo_shipping_method::where('id',$woo_shipping_cost->shipping_method_id)->get();
                                                                        $woo_class = \App\Models\woo_shipping_setups::where('id',$woo_shipping_cost->shipping_class_id)->get();
                                                                    /*dd($woo_shipping_cost);*/
                                                                    @endphp
                                                                    <td>{{$woo_zone[0]->shipping_zone}}</td>
                                                                    <td>{{$woo_method[0]->shipping_method}}</td>
                                                                    <td>{{$woo_class[0]->shipping_class}}</td>
                                                                    <td>{{$woo_shipping_cost->shipping_cost}}</td>
                                                                    <td class="text-center" >
                                                                        <a href="{{route('admin.woo.shipping.cost.delete',$woo_shipping_cost->id)}}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- CONTAINER END -->
@endsection