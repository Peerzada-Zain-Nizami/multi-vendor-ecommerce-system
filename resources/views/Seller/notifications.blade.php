@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.notifications')}}</h4>
                </div>
            </div>
            <!--End Page header-->
        <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.notifications-details')}}</div>
                    <div class="card-options">
                        <a href="{{route('seller.notifications.read')}}" class="btn btn-sm btn-primary">{{__('messages.mark-all-as-read')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($notifications as $notification)
                        @if($notification->type == "App\Notifications\MyNotification")
                                @if($notification->data['user']['type'] == 'stock')
                                    <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="@if($notification->data['user']['invoice'] == 'Listed') {{route('seller.company.catalog.view',$notification->data['user']['id'])}} @elseif($notification->data['user']['invoice'] == 'Unlisted') @endif" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                        <div>
                                            <span class="fs-13">#{{__('messages.product')}} {{$notification->data['user']['invoice']}}</span>
                                            <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                        </div>
                                    </a>
                                @endif
                                    @if($notification->data['user']['type'] == 'Plan Payment')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{--@if($notification->data['user']['invoice'] == 'Listed') {{route('seller.company.catalog.view',$notification->data['user']['id'])}} @elseif($notification->data['user']['invoice'] == 'Unlisted') {{route('seller.company.catalog',$notification->data['user']['id'])}} @endif--}} #" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{$notification->data['user']['type']}} {{__('messages.successful-paid')}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'Balance Updated')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('seller.view.trans',$notification->data['user']['trs_id'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{__('messages.balance-updated-successful')}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'Balance Request Rejected')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('seller.view.trans',$notification->data['user']['trs_id'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{__('messages.balance-request-rejected')}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'order_status')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('seller.invoice.checkout.view',$notification->data['user']['id'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{__('messages.order')}} {{ucfirst($notification->data['user']['status'])}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'Insufficient Balance')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('seller.wallet')}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{__('messages.account-balance')}} {{ucfirst($notification->data['user']['type'])}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'Verify Order')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('seller.invoice.checkout.view',$notification->data['user']['id'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{__('messages.please')}} {{ucfirst($notification->data['user']['type'])}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'Order payment Return Received')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('seller.invoice.checkout.view',$notification->data['user']['order'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{ucfirst($notification->data['user']['type'])}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'Pay the Order')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('seller.woo.order.management')}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{__('messages.please')}} {{$notification->data['user']['type']}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'Woocommerce New Order')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('seller.invoice.checkout.view',$notification->data['user']['order_id'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{$notification->data['user']['type']}} {{__('messages.received')}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'Shopify New Order')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('seller.invoice.checkout.view',$notification->data['user']['order_id'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{$notification->data['user']['type']}} {{__('messages.received')}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'Added in List')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('seller.drop.catalog')}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{__('messages.product')}} {{$notification->data['user']['type']}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                                    @if($notification->data['user']['type'] == 'Seller City')
                                        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('admin.seller.city.view',$notification->data['user']['id'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                            <div>
                                                <span class="fs-13">#{{__('messages.new')}} {{$notification->data['user']['type']}} {{__('messages.added')}}</span>
                                                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                            </div>
                                        </a>
                                    @endif
                            {{--@if($notification->data['user']['type'] == "invoice_reject")
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('admin.view.invoice',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.new-order-reject')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'invoice_process')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('admin.view.invoice',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.new-order-in-process')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'invoice_onway')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('admin.view.invoice',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.new-order-is-onWay')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'return_reject')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('admin.view.invoice.return',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.return-reject')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'return_accept')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('admin.view.invoice.return',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.return-accepted')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'return_received')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('admin.view.invoice.return',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.return-received')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif--}}
                        @endif
                    @endforeach
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        {{$notifications->links('pagination')}}
                    </div>
                </div>
            </div>
        </div>
        <!--/div-->


    </div>
    </div>
    <!-- CONTAINER END -->

@endsection
