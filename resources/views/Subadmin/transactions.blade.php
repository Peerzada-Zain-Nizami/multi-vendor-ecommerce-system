@extends('Subadmin.base')

@section('content')

                <!--app-content open-->
				<div class="app-content main-content">
					<div class="side-app">

                        
						<!--Page header-->
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0 text-primary">Transaction History</h4>
							</div>
						</div>
						<!--End Page header-->

						<!--div-->
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Transaction List</div>
                            </div>
                            <div class="card-body">
                                <div class="">
                                    <div class="table-responsive-lg">
                                        <table id="example" class="table table-bordered text-nowrap key-buttons">
                                            <thead>
                                                <tr>
                                                    <th class="border-bottom-0">#</th>
                                                    <th class="border-bottom-0">Transaction ID</th>
                                                    <th class="border-bottom-0">Cash In</th>
                                                    <th class="border-bottom-0">Cash Out</th>
                                                    <th class="border-bottom-0">Previous Balance</th>
                                                    <th class="border-bottom-0">Payment Type</th>
                                                    <th class="border-bottom-0">Status</th>
{{--                                                    <th class="border-bottom-0">Payment From</th>
                                                    <th class="border-bottom-0">Transfer To</th>
                                                    <th class="border-bottom-0">Payment Method</th>
                                                    <th class="border-bottom-0">Method Transactrion ID</th>
                                                    <th class="border-bottom-0">Note</th>--}}
                                                    <th class="border-bottom-0">Created at</th>
                                                    <th class="border-bottom-0">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                    @php
                                                        $i = 1;
                                                    @endphp
                                                    @foreach($trs as $tr)
                                                        <tr>
                                                            <td>{{$i++}}</td>
                                                            <td>{{$tr['transaction_id']}}</td>
                                                            <td class="text-success">
                                                                @if(!empty($tr['cash_in']))
                                                                    {{Crypt::decrypt($tr['cash_in'])}}
                                                                @endif
                                                            </td>
                                                            <td class="text-danger">
                                                                @if(!empty($tr['cash_out']))
                                                                    {{Crypt::decrypt($tr['cash_out'])}}
                                                                @endif
                                                            </td>
                                                            <td class="text-warning">{{Crypt::decrypt($tr['previous_balance'])}}</td>
                                                            <td>{{$tr['type']}}</td>
                                                            <td>{{$tr['status']}}</td>
{{--                                                            <td>{{$tr['transfar_from']}}</td>
                                                            <td>{{$tr['transfar_to']}}</td>
                                                            <td>{{$tr['method']}}</td>
                                                            <td>{{$tr['method_trs_id']}}</td>
                                                            <td>{{$tr['note']}}</td>--}}
                                                            <td>{{date('d-m-Y',strtotime($tr['created_at']))}}</td>
                                                            <td><a href="{{route('subadmin.transhistory').'/view/'.$tr['transaction_id']}}" class="btn btn-primary"><i class="fe fe-eye"></i></a></td>
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