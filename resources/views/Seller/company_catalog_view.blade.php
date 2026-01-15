@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.product-view')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.product-details')}}</div>
                </div>
                <div class="card-body">
                    <div class="ibox-content">
                        <div class="row mb-3">
                            <div class="col-md-12 col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xl-5">
                                        <div class="row">
                                            <div class="col-2">
                                                <div class="clearfix carousel-slider">
                                                    <div id="thumbcarousel" class="carousel slide" data-interval="false">
                                                        <div class="carousel-inner">
                                                            <div class="carousel-item active">
                                                                @php
                                                                    $images = $result->product_images;
                                                                    $new_images = explode("|",$images);
                                                                $i = 1;
                                                                @endphp
                                                                <div data-bs-target="#carousel" data-bs-slide-to="0" class="thumb my-2"><img src="{{asset('uploads/featured_images/'.$result->featured_image)}}" class="img-fluid br-7 border"></div>
                                                                @foreach($new_images as $img)
                                                                    @if(!empty($img))
                                                                        <div data-bs-target="#carousel" data-bs-slide-to="{{$i++}}" class="thumb my-2"><img src="{{asset('uploads/product_images/'.$img)}}" class="img-fluid br-7 border"></div>
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <div class="product-carousel border br-7">
                                                    <div id="carousel" class="carousel slide" data-bs-ride="false">
                                                        <div class="carousel-inner">
                                                            <div class="carousel-item mt-2 active"><img src="{{asset('uploads/featured_images/'.$result->featured_image)}}" class="img-fluid br-7">
                                                            </div>
                                                            @foreach($new_images as $img)
                                                                @if(!empty($img))
                                                                    <div class="carousel-item mt-2"><img src="{{asset('uploads/product_images/'.$img)}}" class="img-fluid br-7">
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-sm-12 col-xs-12 col-xl-7 mt-5">
                                        @php
                                            $data = \App\MyClasses\Helpers::get_lang($result->product_name,$result->id,"product",App::getLocale());
                                            $product_data = json_decode($data);
                                            $catagory_data = \App\MyClasses\Helpers::get_lang_cat($result->category,"category",App::getLocale());
                                        @endphp
                                        <h3>
                                            <p class="text-navy">@if(App::getLocale() == "en"){{$data}} @elseif($product_data) {{$product_data->product_name}} @else {{$result->product_name}} @endif</p>
                                        </h3>
                                        <h4>{{__('messages.category')}}: @if(App::getLocale() == "en"){{$result->category}} @elseif($catagory_data) {{$catagory_data}} @else {{$result->category}} @endif</h4>
                                        <h5>{{__('messages.status')}}: <span class="badge @if($result->status == "Active") bg-success-transparent @else bg-danger-transparent @endif">@if($result->status == "Active") {{__('messages.active')}} @else {{__('messages.deactivate')}} @endif</span></h5>
                                        <div>
                                            <h5>{{__('messages.short-description')}}</h5>
                                            <p>
                                                @if(App::getLocale() == "en"){{$result->short_description}} @elseif($product_data) {{$product_data->short_description}} @else {{$result->short_description}} @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.brief-description')}}</div>
                </div>
                <div class="card-body">
                    @if(App::getLocale() == "en"){!! $result->brief_description !!} @elseif($product_data) {!!$product_data->brief_description!!} @else {!! $result->brief_description !!} @endif
                </div>
            </div>
            <!--/div-->
        </div>


    </div>
    </div>
    <!-- CONTAINER END -->

@endsection
