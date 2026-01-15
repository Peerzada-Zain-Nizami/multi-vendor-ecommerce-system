@extends('Admin.base')

@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">
            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.product-lists') }}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('messages.product-details') }}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                        <table id="example"
                            class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                            <thead class="border-bottom-0 pt-3 pb-3">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>{{ __('messages.product-category') }}</th>
                                    <th>{{ __('messages.business-model') }}</th>
                                    <th>{{ __('messages.product-name') }}</th>
                                    <th>{{ __('messages.status') }}</th>
                                    <th>{{ __('messages.suppliers') }}</th>
                                    <th>{{ __('messages.image') }}</th>
                                    <th>{{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach ($results as $result)
                                    <tr>
                                        <td class="text-center">{{ $i++ }}</td>
                                        @php
                                            $data = \App\MyClasses\Helpers::get_lang(
                                                $result->product_name,
                                                $result->id,
                                                'product',
                                                App::getLocale(),
                                            );
                                            $product_data = json_decode($data);
                                            $catagory_data = \App\MyClasses\Helpers::get_lang_cat(
                                                $result->category,
                                                'category',
                                                App::getLocale(),
                                            );
                                        @endphp
                                        <td class="fs-13">
                                            @if (App::getLocale() == 'en')
                                                {{ $result->category }}
                                            @elseif($catagory_data)
                                                {{ $catagory_data }}
                                            @else
                                                {{ $result->category }}
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $models = json_decode($result->business_model);
                                            @endphp
                                            @foreach ($models as $model)
                                                @php
                                                    $modal_datas = \App\MyClasses\Helpers::get_lang_pro(
                                                        $model,
                                                        'business_model',
                                                        App::getLocale(),
                                                    );
                                                @endphp
                                                <span class="badge rounded-pill bg-primary mt-2">
                                                    @if (App::getLocale() == 'en')
                                                        {{ $model }}
                                                    @elseif($modal_datas)
                                                        {{ $modal_datas }}
                                                    @else
                                                        {{ $model }}
                                                    @endif
                                                </span>
                                                @if ($loop->even == true)
                                                    <br>
                                                @endif
                                            @endforeach
                                        </td>
                                        @php
                                            $data = \App\MyClasses\Helpers::get_lang(
                                                $result->product_name,
                                                $result->id,
                                                'product',
                                                App::getLocale(),
                                            );
                                            $product_data = json_decode($data);
                                        @endphp
                                        <td>
                                            @if (App::getLocale() == 'en')
                                                {{ $data }}
                                            @elseif($product_data)
                                                {{ $product_data->product_name }}
                                            @else
                                                {{ $result->product_name }}
                                            @endif
                                        </td>
                                        <td>
                                            <h3><span
                                                    class="badge @if ($result->status == 'Active') bg-success-transparent @else bg-danger-transparent @endif">
                                                    @if ($result->status == 'Active')
                                                        {{ __('messages.active') }}
                                                    @else
                                                        {{ __('messages.deactivate') }}
                                                    @endif
                                                </span></h3>
                                        </td>
                                        <td>{{ $result->get_suppliers->count() }} {{ __('messages.suppliers') }}</td>
                                        <td><img class="avatar avatar-xxl"
                                                src="{{ asset('uploads/featured_images/' . $result->featured_image) }}">
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.product.catalog.view', $result->id) }}"
                                                class="btn btn-primary"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->


    </div>
    </div>
    <!-- CONTAINER END -->
@endsection
