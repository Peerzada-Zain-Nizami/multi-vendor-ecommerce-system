@extends('Admin.base')


@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.users-list')}}</h4>
                </div>
            </div>
            <!--End Page header-->

            <!--Row-->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.users-details')}}</div>
                        </div>
                        <div class="card-body">
                            <div class="">
                                <div class="table-responsive-lg">
                                    <table id="example" class="table table-bordered text-nowrap key-buttons">
                                        <thead>
                                        <tr>
                                            <th class="border-bottom-0">#</th>
                                            <th class="border-bottom-0">{{__('messages.user-ID')}}</th>
                                            <th class="border-bottom-0">{{__('messages.name')}}</th>
                                            <th class="border-bottom-0">{{__('messages.email')}}</th>
                                            <th class="border-bottom-0">{{__('messages.role')}}</th>
                                            <th class="border-bottom-0">{{__('messages.created-at')}}</th>
                                            <th class="border-bottom-0">{{__('messages.action')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($users))
                                            @php $i = 1; @endphp
                                            @foreach($users as $user)
                                            <tr>
                                                <td>{{$i++}}</td>
                                                <td>{{$user->id}}</td>
                                                <td>{{$user->name}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{$user->role}}</td>
                                                <td>{{date('d-m-Y',strtotime($user->created_at))}}</td>
                                                <td><a href="{{route('admin.user.view',['id'=>$user->id])}}" class="btn btn-primary"><i class="fa fa-eye"></i></a></td>
                                            </tr>
                                            @endforeach
                                        @else
                                        <tr colspan="6" class="text-secondary"> {{__('messages.record-not-found')}} </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/div-->
                </div>
            </div>
            <!--/Row-->


        </div>
    </div>
    <!-- CONTAINER END -->

@endsection