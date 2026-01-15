@extends('Supplier.base')

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
                        <a href="{{route('supplier.notifications.read')}}" class="btn btn-sm btn-primary">{{__('messages.mark-all-as-read')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($notifications as $notification)
                        @if($notification->type == "App\Notifications\MyNotification")
                            @if($notification->data['user']['type'] == 'new_order')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.myorder.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.new-order-received')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'complete_order')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.myorder.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.order-is-completed')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'received_order')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.myorder.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.order-is-received')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'cancel_order')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.myorder.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.order-is-cancel')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'invoice_custom_pay')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.transhistory').'/view/'.$notification->data['user']['trs']}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['trs']}} {{__('messages.invoice-custom-payment-paid')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'invoice_full_pay')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.transhistory').'/view/'.$notification->data['user']['trs']}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['trs']}} {{__('messages.invoice-full-payment-paid')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'new_partial_return')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.return.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.new-partial-return')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'new_full_return')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.return.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.new-full-return')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'return_cancel')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.return.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.return-cancel')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'return_complete')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.return.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.return-completed')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'return_process')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.return.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.return-in-process')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'return_onway')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.return.view',$notification->data['user']['invoice'])}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['invoice']}} {{__('messages.return-is-onWay')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'return_custom_pay')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.transhistory').'/view/'.$notification->data['user']['trs']}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['trs']}} {{__('messages.return-custom-payment-paid')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
                            @if($notification->data['user']['type'] == 'return_full_pay')
                                <a @if($notification->read_at == Null)style="background: #F8F8FC;" @endif href="{{route('supplier.transhistory').'/view/'.$notification->data['user']['trs']}}" onclick="see('{{$notification->id}}')" class="dropdown-item border-bottom d-flex ps-4">
                                    <div>
                                        <span class="fs-13">#{{$notification->data['user']['trs']}} {{__('messages.return-full-payment-paid')}}</span>
                                        <div class="small text-muted">{{$notification->created_at->diffForHumans()}}</div>
                                    </div>
                                </a>
                            @endif
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