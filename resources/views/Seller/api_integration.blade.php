@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">
            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.API-integration')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            <div class="card">
                @if (Session::has('success'))
                    <div class="alert alert-light-success" role="alert">
                        <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                        <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                    </div>
                @endif
                @if (Session::has('danger'))
                    <div class="alert alert-light-danger" role="alert">
                        <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert"
                            aria-hidden="true">×</button>
                        <strong>{{ __('messages.oopps') }}</strong> {{ Session::get('danger') }}
                    </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                <div class="card-header">
                    <div class="card-title">{{ __('messages.platforms') }}</div>
                </div>
                <div class="card-body">
                    <div class="panel panel-primary">
                        <div class=" tab-menu-heading p-0 bg-light">
                            <div class="tabs-menu1 ">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs">
                                    <li class=""><a href="#woo"
                                            class="@if (old('tab') == 'woo') active @endif"
                                            data-bs-toggle="tab">{{ __('messages.wooCommerce') }}</a></li>
                                    <li class=""><a href="#shopify" class=""
                                            data-bs-toggle="tab">{{ __('messages.shopify') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane p-5 @if (old('tab') == 'woo') active @endif" id="woo">
                                    <div class="form-group text-end">
                                        <label class="custom-switch">
                                            <input id="woo_check" type="checkbox" name="woo"
                                                class="custom-switch-input on_off"
                                                @if ($data->woo_details == null) disabled @endif
                                                @if ($data->woo == 'true') checked @endif>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                    @if ($data->woo_details == null)
                                        <form action="{{ route('seller.api.woo') }}" method="post">
                                            @csrf
                                            <input type="text" name="tab" value="woo" hidden>
                                            <div class="row mt-5">
                                                <div class="col-sm-5">
                                                    <div class="card">
                                                        <div class="card-header bg-primary">
                                                            <div class="card-title text-white">
                                                                {{ __('messages.key-details') }}</div>
                                                        </div>
                                                        <div class="card-body bg-light">
                                                            <div class="form-group">
                                                                <label>{{ __('messages.domain-URL') }}</label>
                                                                <input type="text" name="domain_url"
                                                                    class="form-control @if ($errors->has('domain_url')) is-invalid @endif"
                                                                    value="{{ old('domain_url') }}">
                                                            </div>
                                                            @if ($errors->has('domain_url'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('domain_url') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group">
                                                                <label>{{ __('messages.consumer-key') }}</label>
                                                                <input type="password" name="consumer_key"
                                                                    class="form-control @if ($errors->has('consumer_key')) is-invalid @endif"
                                                                    value="{{ old('consumer_key') }}">
                                                            </div>
                                                            @if ($errors->has('consumer_key'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('consumer_key') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group">
                                                                <label>{{ __('messages.consumer-secret') }}</label>
                                                                <input type="password" name="consumer_secret"
                                                                    class="form-control @if ($errors->has('consumer_secret')) is-invalid @endif"
                                                                    value="{{ old('consumer_secret') }}">
                                                            </div>
                                                            @if ($errors->has('consumer_secret'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('consumer_secret') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group text-end">
                                                                <input type="submit" class="btn btn-primary"
                                                                    value="{{ __('messages.save') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        @php
                                            $woo = json_decode($data->woo_details);
                                            $c_k = substr(decrypt($woo->consumer_key), -7);
                                        @endphp
                                        <div class="row mt-5">
                                            <div class="col-sm-5">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <div class="card-title text-white">{{ __('messages.key-details') }}
                                                        </div>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="form-group">
                                                            <label>{{ __('messages.domain-URL') }}</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $woo->domain_url }}" disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ __('messages.consumer-key-ending-in') }}</label>
                                                            <input type="text" class="form-control"
                                                                value="...{{ $c_k }}" disabled>
                                                        </div>
                                                        <div class="form-group text-end">
                                                            <a href="{{ route('seller.api.delete', ['type' => 'woo']) }}"
                                                                class="btn btn-danger">{{ __('messages.delete') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane p-5 @if (old('tab') == 'shopify') active @endif" id="shopify">
                                    <div class="form-group text-end">
                                        <label class="custom-switch">
                                            <input id="shopify_check" type="checkbox" name="shopify"
                                                class="custom-switch-input on_off"
                                                @if ($data->shopify_details == null) disabled @endif
                                                @if ($data->shopify == 'true') checked @endif>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>



                                    @if ($data->shopify_details == null)
                                        <form action="{{ route('seller.api.shopify') }}" method="post">
                                            @csrf
                                            <input type="text" name="tab" value="shopify" hidden>
                                            <div class="row">
                                                <div class="col-sm-5">
                                                    <div class="card">
                                                        <div class="card-header bg-primary">
                                                            <div class="card-title text-white">
                                                                {{ __('messages.key-details') }}</div>
                                                        </div>
                                                        <div class="card-body bg-light">
                                                            <div class="form-group">
                                                                <label>{{ __('messages.API-key') }}</label>
                                                                <input type="text" name="api_key"
                                                                    class="form-control @if ($errors->has('api_key')) is-invalid @endif"
                                                                    value="{{ old('api_key') }}">
                                                            </div>
                                                            @if ($errors->has('api_key'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('api_key') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group">
                                                                <label>{{ __('messages.password') }}</label>
                                                                <input type="password" name="password"
                                                                    class="form-control @if ($errors->has('password')) is-invalid @endif"
                                                                    value="{{ old('password') }}">
                                                            </div>
                                                            @if ($errors->has('password'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('password') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group">
                                                                <label>{{ __('messages.hostname-(Domain URL)') }}</label>
                                                                <input type="text" name="hostname"
                                                                    class="form-control @if ($errors->has('hostname')) is-invalid @endif"
                                                                    value="{{ old('hostname') }}">
                                                            </div>
                                                            @if ($errors->has('hostname'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('hostname') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group">
                                                                <label>{{ __('messages.access-token') }}</label>
                                                                <input type="text" name="access_token"
                                                                    class="form-control @if ($errors->has('access_token')) is-invalid @endif"
                                                                    value="{{ old('access_token') }}">
                                                            </div>
                                                            @if ($errors->has('access_token'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('access_token') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group text-end">
                                                                <input type="submit" class="btn btn-primary"
                                                                    value="{{ __('messages.save') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <h1>{{ __('messages.integration') }}</h1>
                                                    <div class="bg-light">
                                                        <div class="embed-responsive embed-responsive-16by9">
                                                            <iframe class="embed-responsive-item"
                                                                src="{{ asset('uploads/video/integration.mp4') }}"
                                                                allowfullscreen></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        @php
                                            $shopify = json_decode($data->shopify_details);
                                            $hostname = decrypt($shopify->hostname);
                                            $a_k = substr(decrypt($shopify->api_key), -7);
                                        @endphp
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <div class="card-title text-white">
                                                            {{ __('messages.key-details') }}</div>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="form-group">
                                                            <label>{{ __('messages.hostname-(Domain URL)') }}</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $hostname }}" disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ __('messages.API-key-ending-in') }}</label>
                                                            <input type="text" class="form-control"
                                                                value="...{{ $a_k }}" disabled>
                                                        </div>
                                                        <div class="form-group text-end">
                                                            <a href="{{ route('seller.api.delete', ['type' => 'shopify']) }}"
                                                                class="btn btn-danger">{{ __('messages.delete') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <h1>{{ __('messages.integration') }}</h1>

                                                <div class="bg-light">
                                                    <div class="embed-responsive embed-responsive-16by9">
                                                        <iframe class="embed-responsive-item"
                                                            src="{{ asset('uploads/video/integration.mp4') }}"
                                                            allowfullscreen></iframe>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="tab-pane p-5 @if (old('tab') == 'Salla') active @endif"
                                    id="Salla">
                                    <div class="form-group text-end">
                                        <label class="custom-switch">
                                            <input id="shopify_check" type="checkbox" name="Salla"
                                                class="custom-switch-input on_off"
                                                @if ($data->shopify_details == null) disabled @endif
                                                @if ($data->shopify == 'true') checked @endif>
                                            <span class="custom-switch-indicator"></span>
                                        </label>
                                    </div>
                                    @if ($data->shopify_details == null)
                                        <form action="{{-- {{route('seller.api.Salla')}} --}}" method="post">
                                            @csrf
                                            <input type="text" name="tab" value="Salla" hidden>
                                            <div class="row mt-5">
                                                <div class="col-sm-5">
                                                    <div class="card">
                                                        <div class="card-header bg-primary">
                                                            <div class="card-title text-white">
                                                                {{ __('messages.key-details') }}</div>
                                                        </div>
                                                        <div class="card-body bg-light">
                                                            <div class="form-group">
                                                                <label>{{ __('messages.API-key') }}</label>
                                                                <input type="text" name="api_key"
                                                                    class="form-control @if ($errors->has('api_key')) is-invalid @endif"
                                                                    value="{{ old('api_key') }}">
                                                            </div>
                                                            @if ($errors->has('api_key'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('api_key') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group">
                                                                <label>{{ __('messages.password') }}</label>
                                                                <input type="password" name="password"
                                                                    class="form-control @if ($errors->has('password')) is-invalid @endif"
                                                                    value="{{ old('password') }}">
                                                            </div>
                                                            @if ($errors->has('password'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('password') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group">
                                                                <label>{{ __('messages.hostname-(Domain URL)') }}</label>
                                                                <input type="text" name="hostname"
                                                                    class="form-control @if ($errors->has('hostname')) is-invalid @endif"
                                                                    value="{{ old('hostname') }}">
                                                            </div>
                                                            @if ($errors->has('hostname'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('hostname') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group">
                                                                <label>{{ __('messages.access-token') }}</label>
                                                                <input type="text" name="access_token"
                                                                    class="form-control @if ($errors->has('access_token')) is-invalid @endif"
                                                                    value="{{ old('access_token') }}">
                                                            </div>
                                                            @if ($errors->has('access_token'))
                                                                <span
                                                                    class="text-danger">{{ $errors->first('access_token') }}</span>
                                                                <br>
                                                            @endif
                                                            <div class="form-group text-end">
                                                                <input type="submit" class="btn btn-primary"
                                                                    value="{{ __('messages.save') }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    @else
                                        @php
                                            $shopify = json_decode($data->shopify_details);
                                            $hostname = decrypt($shopify->hostname);
                                            $a_k = substr(decrypt($shopify->api_key), -7);
                                        @endphp
                                        <div class="row mt-5">
                                            <div class="col-sm-5">
                                                <div class="card">
                                                    <div class="card-header bg-primary">
                                                        <div class="card-title text-white">
                                                            {{ __('messages.key-details') }}</div>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="form-group">
                                                            <label>{{ __('messages.hostname-(Domain URL)') }}</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $hostname }}" disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>{{ __('messages.API-key-ending-in') }}</label>
                                                            <input type="text" class="form-control"
                                                                value="...{{ $a_k }}" disabled>
                                                        </div>
                                                        <div class="form-group text-end">
                                                            <a href="{{ route('seller.api.delete', ['type' => 'Salla']) }}"
                                                                class="btn btn-danger">{{ __('messages.delete') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
@section('query')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(".on_off").click(function() {
            var id = $(this).attr("id");
            var checkBox = document.getElementById(id);
            if (checkBox.checked === true) {
                var data = {
                    'tab': id,
                    'value': "true",
                };
                $.ajax({
                    url: "{{ route('seller.api.on_off') }}",
                    type: "POST",
                    dataType: "json",
                    data: data,
                })
            } else {
                var data = {
                    'tab': id,
                    'value': "null",
                };
                $.ajax({
                    url: "{{ route('seller.api.on_off') }}",
                    type: "POST",
                    dataType: "json",
                    data: data,
                })
            }
        });
    </script>
@endsection
