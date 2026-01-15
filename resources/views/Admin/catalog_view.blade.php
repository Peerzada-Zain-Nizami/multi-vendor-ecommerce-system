@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.product-view') }}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('messages.product-details') }}</div>
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
                                                                    $images = $product->product_images;
                                                                    $new_images = explode('|', $images);
                                                                    $i = 1;
                                                                @endphp
                                                                <div data-bs-target="#carousel" data-bs-slide-to="0"
                                                                    class="thumb my-2"><img
                                                                        src="{{ asset('uploads/featured_images/' . $product->featured_image) }}"
                                                                        class="img-fluid br-7 border"></div>
                                                                @foreach ($new_images as $img)
                                                                    @if (!empty($img))
                                                                        <div data-bs-target="#carousel"
                                                                            data-bs-slide-to="{{ $i++ }}"
                                                                            class="thumb my-2"><img
                                                                                src="{{ asset('uploads/product_images/' . $img) }}"
                                                                                class="img-fluid br-7 border"></div>
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
                                                            <div class="carousel-item mt-2 active"><img
                                                                    src="{{ asset('uploads/featured_images/' . $product->featured_image) }}"
                                                                    class="img-fluid br-7">
                                                            </div>
                                                            @foreach ($new_images as $img)
                                                                @if (!empty($img))
                                                                    <div class="carousel-item mt-2"><img
                                                                            src="{{ asset('uploads/product_images/' . $img) }}"
                                                                            class="img-fluid br-7">
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
                                            $data = \App\MyClasses\Helpers::get_lang(
                                                $product->product_name,
                                                $product->id,
                                                'product',
                                                App::getLocale(),
                                            );
                                            $product_data = json_decode($data);
                                        @endphp
                                        <h3>
                                            <p class="text-navy">
                                                @if (App::getLocale() == 'en')
                                                    {{ $data }}
                                                @elseif($product_data)
                                                    {{ $product_data->product_name }}
                                                @else
                                                    {{ $product->product_name }}
                                                @endif
                                            </p>
                                        </h3>
                                        <h4>{{ __('messages.category') }}: {{ $product->category }}</h4>
                                        <h5>{{ __('messages.status') }}: <span
                                                class="badge @if ($product->status == 'Active') bg-success-transparent @else bg-danger-transparent @endif">
                                                @if ($product->status == 'Active')
                                                    {{ __('messages.active') }}
                                                @else
                                                    {{ __('messages.deactivate') }}
                                                @endif
                                            </span></h5>
                                        <div>
                                            <h5>{{ __('messages.short-description') }}</h5>
                                            @php
                                                $data = \App\MyClasses\Helpers::get_lang(
                                                    $product->product_name,
                                                    $product->id,
                                                    'product',
                                                    App::getLocale(),
                                                );
                                                $product_data = json_decode($data);
                                            @endphp
                                            <p>
                                                @if (App::getLocale() == 'en')
                                                    {{ $product->short_description }}
                                                @elseif($product_data)
                                                    {{ $product_data->short_description }}
                                                @else
                                                    {{ $product->short_description }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="ms-3">{{ __('messages.supplier-details') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="panel panel-primary tabs-style-3">
                                <div class="tab-menu-heading">
                                    <div class="tabs-menu ">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs">
                                            <li class=""><a href="#supplier" class="active"
                                                    data-bs-toggle="tab">{{ __('messages.supplier') }}</a></li>
                                            <li class=""><a href="#perchase"
                                                    data-bs-toggle="tab">{{ __('messages.purchase') }}</a></li>
                                            <li class=""><a href="#return"
                                                    data-bs-toggle="tab">{{ __('messages.return') }}</a></li>
                                            <li class=""><a href="#bar_code"
                                                    data-bs-toggle="tab">{{ __('messages.barcode') }}</a></li>
                                            <li class=""><a href="#placement"
                                                    data-bs-toggle="tab">{{ __('messages.placement') }}</a></li>
                                            <li class=""><a href="#placement_history"
                                                    data-bs-toggle="tab">{{ __('messages.placement-history') }}</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="supplier">
                                            <div class="table-responsive-lg">
                                                <table
                                                    class="table table-responsive-lg-sm table-hover table-bordered text-nowrap">
                                                    <thead class="border-bottom-0 pt-3 pb-3">
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>{{ __('messages.supplier') }}</th>
                                                            <th>{{ __('messages.selling-price') }}</th>
                                                            <th>{{ __('messages.shipping-charges') }}</th>
                                                            <th>{{ __('messages.stock') }}</th>
                                                            <th>{{ __('messages.status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($suppliers as $supplier)
                                                            <tr>
                                                                <td class="text-center">{{ $i++ }}</td>
                                                                <td class="fs-13"><a
                                                                        href="{{ route('admin.user.view', ['id' => $supplier->suppliers_name[0]['id']]) }}">{{ $supplier->suppliers_name[0]['name'] }}</a>
                                                                </td>
                                                                <td>{{ $supplier->selling_price }}</td>
                                                                <td>{{ $supplier->shipping_charges }}</td>
                                                                <td>{{ $supplier->stock }}</td>
                                                                <td>
                                                                    <h3><span
                                                                            class="badge @if ($supplier->status == 'Available') bg-success-transparent @else bg-danger-transparent @endif">
                                                                            @if ($supplier->status == 'Available')
                                                                                {{ __('messages.available') }}
                                                                            @else
                                                                                {{ __('messages.available') }}
                                                                            @endif
                                                                        </span></h3>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="perchase">
                                            <div class="table-responsive-lg">
                                                <table
                                                    class="table table-responsive-lg-sm table-hover table-bordered text-nowrap ">
                                                    <thead class="border-bottom-0 pt-3 pb-3">
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>{{ __('messages.invoice-no') }}</th>
                                                            <th>{{ __('messages.supplier-name') }}</th>
                                                            <th>{{ __('messages.price') }}</th>
                                                            <th>{{ __('messages.quantity') }}</th>
                                                            <th>{{ __('messages.amount') }}</th>
                                                            <th>{{ __('messages.receiving-warehouse') }}</th>
                                                            <th>{{ __('messages.receiving-admin') }}</th>
                                                            <th>{{ __('messages.created-by') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $i = 1;
                                                        @endphp
                                                        @foreach ($company_orders as $company_order)
                                                            @php
                                                                $products0 = json_decode($company_order->products);
                                                                $data = null;
                                                            @endphp
                                                            @foreach ($products0 as $product0)
                                                                @if ($product0->product_id == $product->id)
                                                                    @php
                                                                        $data = $product0;
                                                                    @endphp
                                                                @endif
                                                            @endforeach
                                                            <tr>
                                                                <td class="text-center">{{ $i++ }}</td>
                                                                <td><a
                                                                        href="{{ route('admin.view.invoice', ['id' => $company_order->invoice_no]) }}">{{ $company_order->invoice_no }}</a>
                                                                </td>
                                                                <td><a
                                                                        href="{{ route('admin.user.view', ['id' => $company_order->suppliers_name[0]['id']]) }}">{{ $company_order->suppliers_name[0]->name }}</a>
                                                                </td>
                                                                <td>{{ $data->rate }}</td>
                                                                <td>{{ $data->quantity }}</td>
                                                                <td>{{ $data->rate * $data->quantity }}</td>
                                                                @php
                                                                    $warehouse = \App\Models\Warehouse::where(
                                                                        'id',
                                                                        $company_order->warehouse_id,
                                                                    )->first();
                                                                    $admin = \App\Models\User::where(
                                                                        'id',
                                                                        $company_order->receiver_admin,
                                                                    )->first();
                                                                    $Created_admin = \App\Models\User::where(
                                                                        'id',
                                                                        $company_order->user_id,
                                                                    )->first();
                                                                    $data = \App\MyClasses\Helpers::get_lang(
                                                                        $warehouse->warehouse_name,
                                                                        $warehouse->id,
                                                                        'warehouse',
                                                                        App::getLocale(),
                                                                    );
                                                                    $warehouse_data = json_decode($data);
                                                                @endphp
                                                                <td><a
                                                                        href="{{ route('admin.warehouse.view', ['id' => $warehouse->id]) }}">
                                                                        @if (App::getLocale() == 'en')
                                                                            {{ $data }}
                                                                        @elseif($warehouse_data)
                                                                            {{ $warehouse_data->name }}
                                                                        @else
                                                                            {{ $warehouse->warehouse_name }}
                                                                        @endif
                                                                    </a></td>
                                                                <td><a
                                                                        href="{{ route('admin.user.view', ['id' => $admin->id]) }}">{{ $admin->name }}</a>
                                                                </td>
                                                                <td><a
                                                                        href="{{ route('admin.user.view', ['id' => $Created_admin->id]) }}">{{ $Created_admin->name }}</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="return">
                                            <div class="table-responsive-lg">
                                                <table
                                                    class="table table-responsive-lg-sm table-hover table-bordered text-nowrap ">
                                                    <thead class="border-bottom-0 pt-3 pb-3">
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>{{ __('messages.invoice-no') }}</th>
                                                            <th>{{ __('messages.supplier-name') }}</th>
                                                            <th>{{ __('messages.price') }}</th>
                                                            <th>{{ __('messages.quantity') }}</th>
                                                            <th>{{ __('messages.amount') }}</th>
                                                            <th>{{ __('messages.created-by') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($company_returns))
                                                            @php
                                                                $i = 1;
                                                            @endphp
                                                            @foreach ($company_returns as $company_return)
                                                                @php
                                                                    $products0 = json_decode($company_return->products);
                                                                    $data0 = null;
                                                                @endphp
                                                                @foreach ($products0 as $product0)
                                                                    @if ($product0->product_id == $product->id)
                                                                        @php
                                                                            $data0 = $product0;
                                                                        @endphp
                                                                    @endif
                                                                @endforeach
                                                                <tr>
                                                                    <td class="text-center">{{ $i++ }}</td>
                                                                    <td><a
                                                                            href="{{ route('admin.view.invoice.return', ['id' => $company_return->id]) }}">{{ $company_return->invoice_no }}</a>
                                                                    </td>
                                                                    <td><a
                                                                            href="{{ route('admin.user.view', ['id' => $company_return->suppliers_name[0]['id']]) }}">{{ $company_return->suppliers_name[0]->name }}</a>
                                                                    </td>
                                                                    <td>{{ $data0->rate }}</td>
                                                                    <td>{{ $data0->return_quantity }}</td>
                                                                    <td>{{ $data0->rate * $data0->return_quantity }}</td>
                                                                    @php
                                                                        $admin = \App\Models\User::where(
                                                                            'id',
                                                                            $company_return->user_id,
                                                                        )->first();
                                                                    @endphp
                                                                    <td><a
                                                                            href="{{ route('admin.user.view', ['id' => $admin->id]) }}">{{ $admin->name }}</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="bar_code">
                                            <div class="table-responsive-lg">
                                                <table
                                                    class="table table-responsive-lg-sm table-hover table-bordered text-nowrap ">
                                                    <thead class="border-bottom-0 pt-3 pb-3">
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>{{ __('messages.supplier') }}</th>
                                                            <th>{{ __('messages.barcode') }}</th>
                                                            <th>{{ __('messages.QRcode') }}</th>
                                                            <th>{{ __('messages.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($barcodes))
                                                            @php
                                                                $i = 1;
                                                            @endphp
                                                            @foreach ($barcodes as $barcode)
                                                                <tr>
                                                                    <td class="text-center">{{ $i++ }}</td>
                                                                    <td class="fs-13"><a
                                                                            href="{{ route('admin.user.view', ['id' => $barcode->suppliers_name[0]['id']]) }}">{{ $barcode->suppliers_name[0]['name'] }}</a>
                                                                    </td>
                                                                    <td>
                                                                        <img src="data:image/png;base64,{{ DNS1D::setStorPath(__DIR__ . '/cache/')->getBarcodePNG((string) $barcode->id, 'C128') }}"
                                                                            alt="sultan">
                                                                    </td>
                                                                    <td>
                                                                        <img src="data:image/png;base64,{{ DNS2D::setStorPath(__DIR__ . '/cache/')->getBarcodePNG((string) $barcode->id, 'QRCODE') }}"
                                                                            alt="sultan">
                                                                    </td>
                                                                    <td>
                                                                        <a href="{{ route('admin.barcode.download', ['type' => 'product', 'id' => $barcode->id, 'qty' => 1]) }}"
                                                                            class="btn btn-warning"><i
                                                                                class="fa fa-print"></i></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="placement">
                                            <div class="table-responsive-lg">
                                                <table
                                                    class="table table-responsive-lg-sm table-hover table-bordered text-nowrap ">
                                                    <thead class="border-bottom-0 pt-3 pb-3">
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>{{ __('messages.product-image') }}</th>
                                                            <th>{{ __('messages.product-name') }}</th>
                                                            <th>{{ __('messages.warehouse-name') }}</th>
                                                            <th>{{ __('messages.blocks/room') }}</th>
                                                            <th>{{ __('messages.rack') }}</th>
                                                            <th>{{ __('messages.shelf') }}</th>
                                                            <th>{{ __('messages.quantity') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($placements))
                                                            @php
                                                                $i = 1;
                                                            @endphp
                                                            @foreach ($placements as $placement)
                                                                <tr>
                                                                    <td class="text-center">{{ $i++ }}</td>
                                                                    @php
                                                                        $product = \App\Models\Product::where(
                                                                            'id',
                                                                            $placement->stock_in_get[0]->product_id,
                                                                        )->first();
                                                                    @endphp
                                                                    <td><img class="avatar avatar-lg br-7"
                                                                            src="{{ asset('uploads/featured_images/' . $product->featured_image) }}">
                                                                    </td>
                                                                    @php
                                                                        $data = \App\MyClasses\Helpers::get_lang(
                                                                            $product->product_name,
                                                                            $product->id,
                                                                            'product',
                                                                            App::getLocale(),
                                                                        );
                                                                        $product_data = json_decode($data);
                                                                    @endphp
                                                                    <td><a
                                                                            href="{{ route('admin.product.manage') . '/view/' . $product->id }}">
                                                                            @if (App::getLocale() == 'en')
                                                                                {{ $data }}
                                                                            @elseif($product_data)
                                                                                {{ $product_data->product_name }}
                                                                            @else
                                                                                {{ $product->product_name }}
                                                                            @endif
                                                                        </a></td>
                                                                    @php
                                                                        $warehouse = \App\Models\Warehouse::where(
                                                                            'id',
                                                                            $placement->shelf_get[0]->warehouse_id,
                                                                        )->first();
                                                                        $data = \App\MyClasses\Helpers::get_lang(
                                                                            $warehouse->warehouse_name,
                                                                            $warehouse->id,
                                                                            'warehouse',
                                                                            App::getLocale(),
                                                                        );
                                                                        $warehouse_data = json_decode($data);
                                                                    @endphp
                                                                    <td>
                                                                        @if (App::getLocale() == 'en')
                                                                            {{ $data }}
                                                                        @elseif($warehouse_data)
                                                                            {{ $warehouse_data->name }}
                                                                        @else
                                                                            {{ $warehouse->warehouse_name }}
                                                                        @endif
                                                                    </td>
                                                                    @php
                                                                        $shelf = \App\Models\Shelf::where(
                                                                            'id',
                                                                            $placement->shelf_get[0]->id,
                                                                        )->first();
                                                                        $room_block = \App\Models\Room_Block::where(
                                                                            'id',
                                                                            $placement->shelf_get[0]->block_id,
                                                                        )->first();
                                                                        $rack = \App\Models\Rack::where(
                                                                            'id',
                                                                            $shelf->rack_id,
                                                                        )->first();
                                                                    @endphp
                                                                    <td>{{ $room_block->block_code }}</td>
                                                                    <td>{{ $rack->rack_code }}</td>
                                                                    <td>{{ $shelf->shelf_code }}</td>
                                                                    <td>{{ $placement->quantity }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="placement_history">
                                            <div class="table-responsive-lg">
                                                <table
                                                    class="table table-responsive-lg-sm table-hover table-bordered text-nowrap ">
                                                    <thead class="border-bottom-0 pt-3 pb-3">
                                                        <tr>
                                                            <th class="text-center">#</th>
                                                            <th>{{ __('messages.product-image') }}</th>
                                                            <th>{{ __('messages.product-name') }}</th>
                                                            <th>{{ __('messages.user-name') }}</th>
                                                            <th>{{ __('messages.warehouse-name') }}</th>
                                                            <th>{{ __('messages.blocks/room') }}</th>
                                                            <th>{{ __('messages.rack') }}</th>
                                                            <th>{{ __('messages.shelf') }}</th>
                                                            <th>{{ __('messages.quantity') }}</th>
                                                            <th>{{ __('messages.type') }}</th>
                                                            <th>{{ __('messages.created-at') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if (!empty($placement_histories))
                                                            @php
                                                                $i = 1;
                                                            @endphp
                                                            @foreach ($placement_histories as $placement_history)
                                                                <tr>
                                                                    <td class="text-center">{{ $i++ }}</td>
                                                                    @php
                                                                        $product = \App\Models\Product::where(
                                                                            'id',
                                                                            $placement_history->stock_in_get[0]
                                                                                ->product_id,
                                                                        )->first();
                                                                        $user = \App\Models\User::where(
                                                                            'id',
                                                                            $placement_history->user_id,
                                                                        )->first();
                                                                    @endphp
                                                                    <td><img class="avatar avatar-lg br-7"
                                                                            src="{{ asset('uploads/featured_images/' . $product->featured_image) }}">
                                                                    </td>
                                                                    @php
                                                                        $data = \App\MyClasses\Helpers::get_lang(
                                                                            $product->product_name,
                                                                            $product->id,
                                                                            'product',
                                                                            App::getLocale(),
                                                                        );
                                                                        $product_data = json_decode($data);
                                                                    @endphp
                                                                    <td><a
                                                                            href="{{ route('admin.product.manage') . '/view/' . $product->id }}">
                                                                            @if (App::getLocale() == 'en')
                                                                                {{ $data }}
                                                                            @elseif($product_data)
                                                                                {{ $product_data->product_name }}
                                                                            @else
                                                                                {{ $product->product_name }}
                                                                            @endif
                                                                        </a></td>
                                                                    <td class="text-center"><a
                                                                            href="{{ route('admin.user.view', ['id' => $user->id]) }}">{{ $user->name }}</a>
                                                                    </td>
                                                                    @php
                                                                        $warehouse = \App\Models\Warehouse::where(
                                                                            'id',
                                                                            $placement_history->shelf_get[0]
                                                                                ->warehouse_id,
                                                                        )->first();
                                                                        $data = \App\MyClasses\Helpers::get_lang(
                                                                            $warehouse->warehouse_name,
                                                                            $warehouse->id,
                                                                            'warehouse',
                                                                            App::getLocale(),
                                                                        );
                                                                        $warehouse_data = json_decode($data);
                                                                    @endphp
                                                                    <td>
                                                                        @if (App::getLocale() == 'en')
                                                                            {{ $data }}
                                                                        @elseif($warehouse_data)
                                                                            {{ $warehouse_data->name }}
                                                                        @else
                                                                            {{ $warehouse->warehouse_name }}
                                                                        @endif
                                                                    </td>
                                                                    @php
                                                                        $shelf = \App\Models\Shelf::where(
                                                                            'id',
                                                                            $placement_history->shelf_get[0]->id,
                                                                        )->first();
                                                                        $room_block = \App\Models\Room_Block::where(
                                                                            'id',
                                                                            $placement_history->shelf_get[0]->block_id,
                                                                        )->first();
                                                                        $rack = \App\Models\Rack::where(
                                                                            'id',
                                                                            $shelf->rack_id,
                                                                        )->first();
                                                                    @endphp
                                                                    <td>{{ $room_block->block_code }}</td>
                                                                    <td>{{ $rack->rack_code }}</td>
                                                                    <td>{{ $shelf->shelf_code }}</td>
                                                                    <td>{{ $placement_history->quantity }}</td>
                                                                    <td
                                                                        class="@if ($placement_history->type == 'in') text-success
                                                                    @elseif ($placement_history->type == 'out')
                                                                    text-danger
                                                                    @elseif ($placement_history->type == 'move')
                                                                    text-primary @endif">
                                                                        {{ ucfirst($placement_history->type) }}
                                                                    </td>
                                                                    <td>{{ $placement_history->created_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
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
    <!-- CONTAINER END -->

@endsection
