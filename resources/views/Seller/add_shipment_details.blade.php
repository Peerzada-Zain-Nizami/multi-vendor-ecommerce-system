@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">

            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.generate-air-way-bill')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <li class="text-danger">{{$error}}</li>
                @endforeach
            @endif
            @if (Session::has('danger'))
                <div class="alert alert-light-danger" role="alert">
                    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}}
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                </div>
            @endif

            <!--Row-->
            <form action="{{route('Seller.add.shipping.details')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{__('messages.shipper-details')}}</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.company-name')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="company_name" class="form-control" placeholder="{{__('messages.company-name')}}">
                                            @if ($errors->has('company_name'))
                                                <span class="text-danger">{{ $errors->first('company_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.contact')}}<font size="4" color="red"></font></label>
                                            <input type="text" name="contact_name" class="form-control" @if(!empty($user->name)) value="{{$user->name}}" @endif placeholder="{{__('messages.name')}}">
                                            @if ($errors->has('contact_name'))
                                                <span class="text-danger">{{ $errors->first('contact_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.phone')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="shipper_phone" class="form-control" @if(!empty($user->mobile_no)) value="{{$user->mobile_no}}" @endif placeholder="{{__('messages.phone')}}">
                                            @if ($errors->has('shipper_phone'))
                                                <span class="text-danger">{{ $errors->first('shipper_phone') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.country')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="shipper_country" class="form-control" @if(!empty($user->country)) value="{{$user->country}}" @endif placeholder="country">
                                            @if ($errors->has('shipper_country'))
                                                <span class="text-danger">{{ $errors->first('shipper_country') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.city')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="shipper_city" class="form-control" @if(!empty($user->city)) value="{{$user->city}}" @endif placeholder="city">
                                            @if ($errors->has('shipper_city'))
                                                <span class="text-danger">{{ $errors->first('shipper_city') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">{{__('messages.address')}}<font size="4" color="red">*</font></label>
                                    <textarea name="shipper_address" class="form-control" cols="10" rows="2" placeholder="{{__('messages.address')}}" > @if(!empty($user->address)) {{$user->address}} @endif </textarea>
                                    @if ($errors->has('shipper_address'))
                                        <span class="text-danger">{{ $errors->first('shipper_address') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{__('messages.destination-details')}}</h3>
                            </div>
                            @php
                                /*dd(json_decode($orders)->order);*/
                            @endphp
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.name')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="consignee_name" class="form-control" @if ($shipping_orders->platform == "shopify")@if(!empty(json_decode($orders)->order->billing_address->first_name || json_decode($orders)->order->billing_address->last_name)) value="{{json_decode($orders)->order->billing_address->first_name}} {{json_decode($orders)->order->billing_address->last_name }}" readonly @else @endif @else @if(!empty($orders->billing->first_name || $orders->billing->last_name)) value="{{$orders->billing->first_name}} {{$orders->billing->last_name }}" readonly @else @endif @endif placeholder="{{__('messages.consignee-name')}}">
                                            @if ($errors->has('consignee_name'))
                                                <span class="text-danger">{{ $errors->first('consignee_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.mobile')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="consignee_phone" class="form-control" @if ($shipping_orders->platform == "shopify")@if(!empty(json_decode($orders)->order->billing_address->phone || json_decode($orders)->order->billing_address->phone)) value="{{json_decode($orders)->order->billing_address->phone}} {{json_decode($orders)->order->billing_address->phone }}" readonly @else @endif @else @if(!empty($orders->billing->phone || $orders->billing->phone)) value="{{$orders->billing->phone}} {{$orders->billing->phone }}" readonly @else @endif @endif placeholder="{{__('messages.mobile')}}">
                                            @if ($errors->has('consignee_phone'))
                                                <span class="text-danger">{{ $errors->first('consignee_phone') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.country')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="consignee_country" class="form-control" @if ($shipping_orders->platform == "shopify")@if(!empty(json_decode($orders)->order->billing_address->country || json_decode($orders)->order->billing_address->country)) value="{{json_decode($orders)->order->billing_address->country}} {{json_decode($orders)->order->billing_address->country }}" readonly @else @endif @else @if(!empty($orders->billing->country || $orders->billing->country)) value="{{$orders->billing->country}} {{$orders->billing->country }}" readonly @else @endif @endif placeholder="country">
                                            @if ($errors->has('consignee_country'))
                                                <span class="text-danger">{{ $errors->first('consignee_country') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.city')}}<font size="4" color="red">*</font></label>
                                            @php
                                                $smsa_cities = "SMSA Cities";
                                            @endphp
                                            <input type="text" name="consignee_city" class="form-control" @if(!empty($shipping_orders->company_name == "SMSA")) value="{{$shipping_orders->shipping_company[0]->$smsa_cities}}" readonly @else @endif placeholder="city">
                                            @if ($errors->has('consignee_city'))
                                                <span class="text-danger">{{ $errors->first('consignee_city') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">{{__('messages.address')}}<font size="4" color="red">*</font></label>
                                    <textarea name="consignee_address" class="form-control" cols="10" rows="2" placeholder="{{__('messages.address')}}" @if ($shipping_orders->platform == "shopify")@if(!empty(json_decode($orders)->order->billing_address->address1 || json_decode($orders)->order->billing_address->address1)) value="{{json_decode($orders)->order->billing_address->address1}} {{json_decode($orders)->order->billing_address->address1 }}" readonly @else @endif @else @if(!empty($orders->billing->address_1 || $orders->billing->address_1)) value="{{$orders->billing->address_1}} {{$orders->billing->address_1 }}" readonly @else @endif @endif >@if($shipping_orders->platform == "shopify") {{json_decode($orders)->order->billing_address->address1}} @else {{$orders->billing->address_1}} @endif</textarea>
                                    @if ($errors->has('consignee_address'))
                                        <span class="text-danger">{{ $errors->first('consignee_address') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div  class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{__('messages.shipping-detail')}}</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="form-label">{{__('messages.item-description')}}<font size="4" color="red">*</font></label>
                                    <textarea name="item_description" class="form-control" cols="5" rows="2" placeholder="{{__('messages.item-description')}}" ></textarea>
                                    @if ($errors->has('item_description'))
                                        <span class="text-danger">{{ $errors->first('item_description') }}</span>
                                    @endif
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.COD')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="COD_amount" class="form-control" placeholder="{{__('messages.COD-amount')}}">
                                            @if ($errors->has('COD_amount'))
                                                <span class="text-danger">{{ $errors->first('COD_amount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.customs')}} <font size="4" color="red"></font></label>
                                            <input type="text" name="customs" class="form-control" placeholder="{{__('messages.customs')}}">
                                            @if ($errors->has('customs'))
                                                <span class="text-danger">{{ $errors->first('customs') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.customs-currency')}} <font size="4" color="red"></font></label>
                                            <select name="customs_currency" class="form-control custom-select select2">
                                                <option value="SAR">SAR</option>
                                                <option value="USD">USD</option>
                                                <option value="AED">AED</option>
                                                <option value="BHD">BHD</option>
                                                <option value="EGP">EGP</option>
                                                <option value="KWD">KWD</option>
                                                <option value="OMR">OMR</option>
                                                <option value="JOD">JOD</option>
                                                <option value="QAR">QAR</option>
                                            </select>
                                            @if ($errors->has('customs_currency'))
                                                <span class="text-danger">{{ $errors->first('customs_currency') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group text-center">
                    <input type="hidden" name="order_id" value="{{$shipping_orders->id}}">
                    <input type="submit" class="btn btn-primary edit" value="{{__('messages.add-waybill')}}">
                </div>
            </form>
            <!--/Row-->


        </div>
    </div>
    <!-- CONTAINER END -->

@endsection
