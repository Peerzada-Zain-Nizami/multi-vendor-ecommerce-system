@foreach($notifications as $notification)
    @if($notification->type == "App\Notifications\MyNotification" )
        @if($notification->data['user']['type'] == 'invoice_onway')
            <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('wadmin.company.invoice.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.new-order-is-onWay')}}</span>
                    <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                </div>
            </a>
        @endif
        @if($notification->data['user']['type'] == 'return_accept')
        <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('wadmin.company.return.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
            <div>
                <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.return-accepted')}}</span>
                <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
            </div>
        </a>
    @endif
        @if(ucfirst($notification->data['user']['type']) == "Deactive")
            <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="#" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">New Seller Register</span>
                    <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                </div>
            </a>
        @endif
        @if($notification->data['user']['type'] == 'New Order')
            <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('wadmin.order.checkout.view',$notification->data['user']['id'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{$notification->data['user']['type']}} {{__('messages.received')}}</span>
                    <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                </div>
            </a>
        @endif
        @if($notification->data['user']['type'] == 'order_status')
            <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('wadmin.order.checkout.view',$notification->data['user']['id'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{__('messages.order')}} {{ucfirst($notification->data['user']['status'])}}</span>
                    <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                </div>
            </a>
        @endif
        @if(ucfirst($notification->data['user']['type']) == "Deactive")
            <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="#" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">New Seller Register</span>
                    <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                </div>
            </a>
        @endif
        @if($notification->data['user']['type'] == 'api_error')
            <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{__('messages.can-not-generate-waybill')}} {{ucfirst($notification->data['user']['type'])}}</span>
                    <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                </div>
            </a>
        @endif
    @endif
@endforeach
