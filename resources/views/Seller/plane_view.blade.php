@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.plan-view')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <li class="text-danger">{{$error}}</li>
                @endforeach
            @endif
            @if (Session::has('success'))
                <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xl-8">
                    <div>
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">
                                    <span class="ms-3">{{__('messages.plan-detail')}}</span>
                                </div>
                            </div>
                            <div class="table-responsive-lg">
                                <table class="table card-table table-responsive-lg-sm table-vcenter text-nowrap shopping-carttable">
                                    <thead class="border-bottom-0 pt-3 pb-3 ">
                                    <tr>
                                        <th>{{__('messages.name')}}</th>
                                        <th>{{__('messages.plan-monthly-price')}}</th>
                                        <th>{{__('messages.plan-yearly-price')}}</th>
                                        <th>{{__('messages.status')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        @php
                                            $push_product = json_decode($datas->push_product);
                                            $plan_price = json_decode($datas->plan_price);
                                        @endphp
                                        <td>{{$datas->name}}</td>
                                        <td>@if($plan_price) {{$plan_price->Monthly}} @else 0 @endif</td>
                                        <td>@if($plan_price) {{$plan_price->Yearly}} @else 0 @endif</td>
                                        <td>{{$datas->status}}</td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                    <div >
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">{{__('messages.plan-detail')}}</div>
                            </div>
                            <div class="card-body">
                                <h5 class="fs-4">{{__('messages.more-detail')}}</h5>
                                <div class="table-responsive-lg">
                                    <table class="table table-borderless mb-1">
                                        <tbody>
                                        @php
                                            $products = json_decode($datas->product_price);
                                        @endphp
                                        <tr>
                                            <td class="text-start"><span class="font-weight-bold  ms-auto">{{__('messages.listing-product')}}</span></td>
                                            <td class="text-end"><span class="ms-auto">{{$datas->listing_product}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start"><span class="font-weight-bold  ms-auto">{{__('messages.no-of-push-product-by-hour')}}</span></td>
                                            <td class="text-end"><span class="ms-auto">{{$push_product->push_product_by_hour}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start"><span class="font-weight-bold  ms-auto">{{__('messages.no-of-push-product-by-day')}}</span></td>
                                            <td class="text-end"><span class="ms-auto">{{$push_product->push_product_by_day}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start"><span class="font-weight-bold  ms-auto">{{__('messages.platform-sync')}}</span></td>
                                            <td class="text-end"><span class="ms-auto">{{$datas->plateform_sync}}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-start"><span class="font-weight-bold  ms-auto">{{__('messages.customs-currency')}}</span></td>
                                            <td class="text-end"><span class="ms-auto">{{$datas->currency}}</span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-xl-4 col-sm-12 col-md-12">
                    <div >
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">{{__('messages.plan-detail')}}</div>
                            </div>
                            <div class="card-body">
                                <h5 class="fs-4">{{__('messages.product-detail')}}</h5>
                                <div class="table-responsive-lg">
                                    <table class="table table-borderless mb-1">
                                        <tbody>
                                        @php
                                            $products = json_decode($datas->product_price);
                                        @endphp
                                        <tr>
                                            <td class="text-start"><span class="font-weight-bold  ms-auto">{{__('messages.product-category')}}</span></td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto">{{__('messages.product-price-discount')}}</span></td>
                                        </tr>
                                        @if ($products)
                                            @foreach($products as $product)
                                                @php
                                                    $j_product = json_decode($product);
                                                    $category = \App\Models\Category::find($j_product->category);
                                                @endphp
                                                <tr>
                                                    <td class="text-start">{{$category->category_name}}</td>
                                                    <td class="text-end"><span class="font-weight-bold  ms-auto">{{$j_product->price}}@if($j_product->method == "percentage")% @else {{__('messages.fixed-price')}} @endif</span></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <h5 class="fs-4">{{__('messages.shipping-detail')}}</h5>
                                <div class="table-responsive-lg">
                                    <table class="table table-borderless mb-1">
                                        <tbody>
                                        @php
                                            $shipping = json_decode($datas->shipping_price);
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{__('messages.shipping-price-discount')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto">{{$shipping->discount}}@if($shipping->method == "percentage")% @else {{__('messages.fixed-price')}} @endif</span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <h5 class="fs-4">{{__('messages.cancellation-detail')}}</h5>
                                <div class="table-responsive-lg">
                                    <table class="table table-borderless">
                                        <tbody>
                                        @php
                                            $cancellation = json_decode($datas->order_cancellation);
                                        @endphp
                                        <tr>
                                            <td class="text-start">{{__('messages.cancellation-price-discount')}}</td>
                                            <td class="text-end"><span class="font-weight-bold  ms-auto">{{$cancellation->discount}}@if($cancellation->method == "percentage")% @else {{__('messages.fixed-price')}} @endif</span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- End Row-->
        </div>
        <!--/div-->
    </div>
    <!-- CONTAINER END -->

@endsection
