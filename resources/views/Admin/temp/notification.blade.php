@foreach ($notifications as $notification)
    @if ($notification->type == 'App\Notifications\MyNotification')
        @if ($notification->data['user']['type'] == 'invoice_accepted')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.view.invoice', $notification->data['user']['invoice']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['invoice'] }}
                        {{ __('messages.new-order-accepted') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'Deposit Approval Request')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.users.transactions.view', $notification->data['user']['trs_id']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['type'] }} {{ __('messages.received') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'Plan Payment')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif href="{{-- {{route('admin.view.invoice',$notification->data['user']['invoice'])}} --}}#"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['type'] }}
                        {{ __('messages.successful-paid') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'order_custom_pay')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.view.trans', $notification->data['user']['trs']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['trs'] }}
                        {{ __('messages.order-custom-payment-paid') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'order_full_pay')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.view.trans', $notification->data['user']['trs']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['trs'] }}
                        {{ __('messages.order-full-payment-paid') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'order_status')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.order.invoice.checkout.view', $notification->data['user']['id']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ __('messages.order') }}
                        {{ $notification->data['user']['status'] }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'order_send')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.order.invoice.checkout.view', $notification->data['user']['id']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ __('messages.new-order-received') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'invoice_reject')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.view.invoice', $notification->data['user']['invoice']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['invoice'] }}
                        {{ __('messages.new-order-reject') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'invoice_process')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.view.invoice', $notification->data['user']['invoice']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['invoice'] }}
                        {{ __('messages.new-order-in-process') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'invoice_onway')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.view.invoice', $notification->data['user']['invoice']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['invoice'] }}
                        {{ __('messages.new-order-is-onWay') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'return_reject')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.view.invoice.return', $notification->data['user']['link_id']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['invoice'] }}
                        {{ __('messages.return-reject-request') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'return_accept')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.view.invoice.return', $notification->data['user']['link_id']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['invoice'] }}
                        {{ __('messages.return-accepted') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'return_received')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.view.invoice.return', $notification->data['user']['link_id']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['invoice'] }}
                        {{ __('messages.return-received') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'Seller City')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.seller.city.view', $notification->data['user']['id']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ __('messages.new') }} {{ $notification->data['user']['type'] }}
                        {{ __('messages.added') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'received_order')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.view.invoice', $notification->data['user']['invoice']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ $notification->data['user']['invoice'] }}
                        {{ $notification->data['user']['type'] }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'Cancelled')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.order.invoice.checkout.view', $notification->data['user']['id']) }}"
                onclick="see('{{ $notification->id }}')" class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ __('messages.order-canceled') }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'Pay the Order')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.woo.order.management') }}" onclick="see('{{ $notification->id }}')"
                class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ __('messages.please') }} {{ $notification->data['user']['type'] }}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
        @if ($notification->data['user']['type'] == 'Refund Order')
            <a @if ($notification->read_at == null) style="background: #F8F8FC;" @endif
                href="{{ route('admin.woo.order.management') }}" onclick="see('{{ $notification->id }}')"
                class="dropdown-item border-bottom d-flex ps-4">
                <div>
                    <span class="fs-13">#{{ __('messages.refund-order') }} {{ $notification->data['user']['id']}}</span>
                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @endif
    @endif
@endforeach
