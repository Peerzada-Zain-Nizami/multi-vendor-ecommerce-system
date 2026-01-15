@extends('W_admin.base')

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
            <form action="{{route('wadmin.SMSA.add.shipping')}}" method="post">
                @csrf
                @php
                $shipper = json_decode($shipping_orders->shipper);
                $consignee = json_decode($shipping_orders->consignee);
                $data = json_decode($shipping_orders->data);
                @endphp
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
                                            <input type="text" name="company_name" class="form-control" value="{{$shipper->sName}}" readonly>
                                            @if ($errors->has('company_name'))
                                                <span class="text-danger">{{ $errors->first('company_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.contact')}}<font size="4" color="red"></font></label>
                                            <input type="text" name="contact_name" class="form-control" value="{{$shipper->sContact}}" readonly>
                                            @if ($errors->has('contact_name'))
                                                <span class="text-danger">{{ $errors->first('contact_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.phone')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="shipper_phone" class="form-control" value="{{$shipper->sPhone}}" readonly>
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
                                            <input type="text" name="shipper_country" class="form-control" value="{{$shipper->sCntry}}" readonly>
                                            @if ($errors->has('shipper_country'))
                                                <span class="text-danger">{{ $errors->first('shipper_country') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.city')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="shipper_city" class="form-control" value="{{$shipper->sCity}}" readonly>
                                            @if ($errors->has('shipper_city'))
                                                <span class="text-danger">{{ $errors->first('shipper_city') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">{{__('messages.address')}}<font size="4" color="red">*</font></label>
                                    <textarea name="shipper_address" class="form-control" cols="10" rows="2" readonly> {{$shipper->sAddr1}} </textarea>
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
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.name')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="consignee_name" class="form-control" value="{{$consignee->cName}}" readonly>
                                            @if ($errors->has('consignee_name'))
                                                <span class="text-danger">{{ $errors->first('consignee_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.mobile')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="consignee_phone" class="form-control" value="{{$consignee->cMobile}}" readonly>
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
                                            <input type="text" name="consignee_country" class="form-control" value="{{$consignee->cntry}}" readonly>
                                            @if ($errors->has('consignee_country'))
                                                <span class="text-danger">{{ $errors->first('consignee_country') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.city')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="consignee_city" class="form-control" value="{{$consignee->cCity}}" readonly>
                                            @if ($errors->has('consignee_city'))
                                                <span class="text-danger">{{ $errors->first('consignee_city') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="form-label">{{__('messages.address')}}<font size="4" color="red">*</font></label>
                                    <textarea name="consignee_address" class="form-control" cols="10" rows="2" readonly>{{$consignee->cAddr1}}</textarea>
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
                                    <textarea name="item_description" class="form-control" cols="5" rows="2" readonly>{{$data->itemDesc}}</textarea>
                                    @if ($errors->has('item_description'))
                                        <span class="text-danger">{{ $errors->first('item_description') }}</span>
                                    @endif
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.COD')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="COD_amount" class="form-control" value="{{$data->codAmt}}" readonly>
                                            @if ($errors->has('COD_amount'))
                                                <span class="text-danger">{{ $errors->first('COD_amount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.weight')}}<font size="4" color="red">*</font></label>
                                            <div class="input-group has-validation">
                                                <input type="text" name="weight" class="form-control" placeholder="{{__('messages.weight')}}">
                                                <span class="input-group-text">kg</span>
                                            </div>
                                            @if ($errors->has('weight'))
                                                <span class="text-danger">{{ $errors->first('weight') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.boxes')}}<font size="4" color="red">*</font></label>
                                            <input type="text" name="boxes" class="form-control" placeholder="{{__('messages.boxes')}}">
                                            @if ($errors->has('boxes'))
                                                <span class="text-danger">{{ $errors->first('boxes') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.customs')}}</label>
                                            <input type="text" name="customs" class="form-control" value="{{$data->codAmt}}" readonly>
                                            @if ($errors->has('customs'))
                                                <span class="text-danger">{{ $errors->first('customs') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 mb-0">
                                        <div class="form-group">
                                            <label class="form-label">{{__('messages.customs-currency')}}</label>
                                            <select name="customs_currency" readonly class="form-control custom-select select2">
                                                <option @if($data->custCurr == "SAR") selected @endif value="SAR">SAR</option>
                                                <option @if($data->custCurr == "USD") selected @endif value="USD">USD</option>
                                                <option @if($data->custCurr == "AED") selected @endif value="AED">AED</option>
                                                <option @if($data->custCurr == "BHD") selected @endif value="BHD">BHD</option>
                                                <option @if($data->custCurr == "EGP") selected @endif value="EGP">EGP</option>
                                                <option @if($data->custCurr == "KWD") selected @endif value="KWD">KWD</option>
                                                <option @if($data->custCurr == "OMR") selected @endif value="OMR">OMR</option>
                                                <option @if($data->custCurr == "JOD") selected @endif value="JOD">JOD</option>
                                                <option @if($data->custCurr == "QAR") selected @endif value="QAR">QAR</option>
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
                    <input type="hidden" name="order_id" value="{{$shipping_orders->order_id}}">
                    <input type="submit" class="btn btn-primary edit" value="{{__('messages.add-shipping')}}">
                </div>
            </form>
            <!--/Row-->


        </div>
    </div>
    <!-- CONTAINER END -->

@endsection
