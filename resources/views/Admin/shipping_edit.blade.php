@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.shipping-management')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="row">
                @if (Session::has('success'))
                    <div class="alert alert-light-success" role="alert">
                        <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                        <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                    </div>
                @endif
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.update-company')}}</div>
                        </div>
                        <div class="card-body">
                            <form action="{{route('admin.shipping.company.update',$data->id)}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input name="company_name" class="form-control @if($errors->has('company_name'))is-invalid @endif" value="{{$data->company_name}}" type="text" >
                                </div>
                                @if ($errors->has('company_name'))
                                    <span class="text-danger">{{ $errors->first('company_name') }}</span>
                                    <br>
                                @endif
                                <div class="form-group">
                                    <select name="locations[]" class="form-control select2" data-placeholder="Choose Areas" multiple>
                                        @php
                                            $regions = json_decode($data->locations);
                                        @endphp
                                        @foreach ($regions as $region)
                                            @php
                                                $exp = explode("|",$region);
                                            @endphp
                                            @foreach($woo_contients as $woo_contient)
                                                @php
                                                $json_continent = json_decode($woo_contient->data);
                                                @endphp
                                                <option value="{{$json_continent->code}}|continent" @if($exp[1] == "continent" && $exp[0] == $json_continent->code ) selected @endif >{{$json_continent->name}}</option>
                                                    @foreach($woo_contient->get_country as $woo_country)
                                                        @php
                                                            $json_country = json_decode($woo_country->data);
                                                            $states = \App\Models\Woo_State::where('country_id',$woo_country->id)->get();
                                                        @endphp
                                                        <option value="{{$json_country->code}}|country" @if($exp[1] == "country" && $exp[0] == $json_country->code ) selected @endif>_{{$json_country->name}}</option>
                                                            @foreach($states as $state)
                                                                @php
                                                                    $json_state = json_decode($state->data);
                                                                @endphp
                                                                <option value="{{$json_state->code}}|state|{{$json_country->code}}" @if($exp[1] == "state" && $exp[0] == $json_state->code && $exp[2] == $json_country->code ) selected @endif>__{{$json_state->name}}</option>
                                                            @endforeach
                                                     @endforeach
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('locations'))
                                    <span class="text-danger">{{ $errors->first('locations') }}</span>
                                    <br>
                                @endif
                                <div class="form-group">
                                    <input name="price" class="form-control @if($errors->has('price'))is-invalid @endif" value="{{$data->price}}" type="text" placeholder="{{__('messages.price')}}">
                                </div>
                                @if ($errors->has('price'))
                                    <span class="text-danger">{{ $errors->first('price') }}</span>
                                    <br>
                                @endif
                                <div class="form-group">
                                    <select name="status" class="form-control select2">
                                        <option disabled selected>{{__('messages.please-select-status')}}</option>
                                        <option value="{{$data->status}}" @if($data->status == "Active") selected @else @endif>{{__('messages.active')}}</option>
                                        <option value="{{$data->status}}" @if($data->status == "Deactive") selected @else @endif >{{__('messages.deactive')}}</option>
                                    </select>
                                </div>
                                @if ($errors->has('status'))
                                    <span class="text-danger">{{ $errors->first('status') }}</span>
                                    <br>
                                @endif
                                <div class="form-group">
                                    <input class="btn btn-primary" value="{{__('messages.update')}}" type="submit" >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->


    </div>
    <!-- CONTAINER END -->

@endsection
