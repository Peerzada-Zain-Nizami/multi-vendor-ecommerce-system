@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.users-transaction-history')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.transaction-list')}}</div>
                </div>
                <div class="card-body">

                        <div class="table-responsive-lg">
                            <table id="example" class="table table-responsive-lg-sm table-bordered text-nowrap key-buttons">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">{{__('messages.plan-name')}}</th>
                                    <th class="border-bottom-0">{{__('messages.subAdmin-name')}}</th>
                                    <th class="border-bottom-0">{{__('messages.seller-name')}}</th>
                                    <th class="border-bottom-0">{{__('messages.subscribe-plan')}}</th>
                                    <th class="border-bottom-0">{{__('messages.previous-balance')}}</th>
                                    <th class="border-bottom-0">{{__('messages.cash-out')}}</th>
                                    <th class="border-bottom-0">{{__('messages.current-balance')}}</th>
                                    <th class="border-bottom-0">{{__('messages.status')}}</th>
                                    <th class="border-bottom-0">{{__('messages.created-at')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($plans as $plan)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        @php
                                            $admin = \App\Models\User::find($plan->transfer_to);
                                            $seller = \App\Models\User::find($plan->transfer_from);
                                            $payment_price = json_decode($plan->payment_data);
                                            $plan_data = json_decode($plan->plan_data);
                                            $original_plan = \App\Models\Plan::find($plan_data->plan_id);
                                        @endphp
                                        <td>{{$original_plan->name}}</td>
                                        <td>{{$admin->name}}</td>
                                        <td>{{$seller->name}}</td>
                                        <td>{{$plan_data->type}}</td>
                                        <td class="text-warning">{{Crypt::decrypt($payment_price->previous_balance)}}</td>
                                        <td class="text-success">{{Crypt::decrypt($payment_price->cash_out)}}</td>
                                        <td class="text-warning">{{Crypt::decrypt($payment_price->remaining_balance)}}</td>
                                        <td>{{$plan['status']}}</td>
                                        <td>{{date('d-m-Y',strtotime($plan['created_at']))}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                </div>
            </div>
            <!--/div-->
        </div>
    </div>
    <!-- CONTAINER END -->

@endsection
