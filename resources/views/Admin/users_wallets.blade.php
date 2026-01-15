@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.users-wallets-history')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.users-list')}}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                            <table id="example" class="table table-bordered text-nowrap key-buttons">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">{{__('messages.user-ID')}}</th>
                                    <th class="border-bottom-0">{{__('messages.user-name')}}</th>
                                    <th class="border-bottom-0">{{__('messages.email')}}</th>
                                    <th class="border-bottom-0">{{__('messages.role')}}</th>
                                    <th class="border-bottom-0">{{__('messages.balance')}}</th>
                                    <th class="border-bottom-0">{{__('messages.created-at')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i = 1;
                                @endphp
                                @foreach($trs as $tr)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$tr['id']}}</td>
                                        <td><a href="{{route('admin.user.view',['id'=>$tr['id']])}}">{{$tr['name']}}</a></td>
                                        <td>{{$tr['email']}}</td>
                                        <td>{{$tr['role']}}</td>
                                        <td class="text-warning">{{Crypt::decrypt($tr['balance'])}}</td>
                                        <td>{{date('d-m-Y',strtotime($tr['created_at']))}}</td>
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