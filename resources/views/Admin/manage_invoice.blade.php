@php
 function payment_status($st)
{
    if($st == "Paid")
    {
        echo "text-success";
    }elseif($st == "Pending")
    {
        echo "text-warning";
    }
    else{
        echo "text-danger";
    }
}
function payment_status_lang($st)
{
    if($st == "Paid")
    {
        echo __('messages.paid');
    }elseif($st == "Pending")
    {
        echo __('messages.pending');
    }
    else{
        echo __('messages.unpaid');
    }
}
 function invoice_status($st)
{
    if($st == "Complete")
    {
        echo "text-success";
    }elseif($st == "Reject" || $st == "Cancel")
    {
        echo "text-danger";
    }elseif($st == "Received" || $st == "Accepted" || $st == "New Order")
       {
        echo "text-info";
       }elseif($st == "Onway")
       {
        echo "text-muted";
       }
    else{
        echo "text-warning";
    }
}
function invoice_status_lang($st)
{
    if($st == "Complete")
    {
        echo __('messages.complete');
    }elseif($st == "Reject")
    {
        echo __('messages.reject');
    }elseif($st == "Process")
    {
        echo __('messages.process');
    }elseif($st == "Cancel")
    {
        echo __('messages.cancel');
    }elseif($st == "Received")
       {
        echo __('messages.received');
       }elseif($st == "Accepted")
       {
        echo __('messages.accepted');
       }elseif($st == "New Order")
       {
        echo __('messages.new-order');
       }elseif($st == "Onway")
       {
        echo __('messages.onway');
       }
    else{
        echo __('messages.reject');
    }
}
@endphp
@extends('Admin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.invoices-management')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            @if (Session::has('success'))
                <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                </div>
            @endif
            @if (Session::has('danger'))
                <div class="alert alert-light-danger" role="alert">
                    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                    <strong>{{__('messages.oopps')}}</strong> {{Session::get('danger')}}
                </div>
        @endif
        <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.invoices-details')}}</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive-lg">
                    <table id="example" class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                        <tr>
                            <th class="text-center">#</th>
                            <th>{{__('messages.invoice-no')}}</th>
                            <th>{{__('messages.supplier')}}</th>
                            <th>{{__('messages.invoice-amount')}}</th>
                            <th>{{__('messages.remaining-paid')}}</th>
                            <th>{{__('messages.paid')}}</th>
                            <th>{{__('messages.unpay-return')}}</th>
                            <th>{{__('messages.refund')}}</th>
                            <th>{{__('messages.remaining-refund')}}</th>
                            <th>{{__('messages.total-return')}}</th>
                            <th>{{__('messages.payment-status')}}</th>
                            <th>{{__('messages.invoice-status')}}</th>
                            <th>{{__('messages.receiving-warehouse')}}</th>
                            <th>{{__('messages.receiving-admin')}}</th>
                            <th>{{__('messages.created-by')}}</th>
                            <th>{{__('messages.created-at')}}</th>
                            <th>{{__('messages.action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $result)
                            @php
                            /*dd($result);*/
                                $returned = \App\Models\CompanyReturn::where('invoice_no',$result->invoice_no)->where('payment',"Return")->where('status',"Complete")->sum('total');
                                $refund_rem = \App\Models\CompanyReturn::where('invoice_no',$result->invoice_no)->where('payment','!=',"Return")->where('status',"Complete")->sum('remaining');
                                $refund = \App\Models\CompanyReturn::where('invoice_no',$result->invoice_no)->where('payment','!=',"Return")->where('status',"Complete")->sum('total');
                                $total_return = \App\Models\CompanyReturn::where('invoice_no',$result->invoice_no)->where('status',"Complete")->sum('total');
                            @endphp
                            <tr>
                                <td class="text-center">{{$loop->iteration}}</td>
                                <td>{{$result->invoice_no}}</td>
                                <td><a href="{{route('admin.user.view',['id'=>$result->suppliers_name[0]->id])}}">{{$result->suppliers_name[0]->name}}</a></td>
                                <td>{{$result->total}}</td>
                                <td>{{$result->total-$result->paid-$returned}}</td>
                                <td>{{$result->paid}}</td>
                                <td>{{$returned}}</td>
                                <td>{{$refund-$refund_rem}}</td>
                                <td>{{$refund_rem}}</td>
                                <td>{{$total_return}}</td>
                                <td class="{{payment_status($result->payment)}}">{{payment_status_lang($result->payment)}}</td>
                                <td class="{{invoice_status($result->status)}}">{{invoice_status_lang($result->status)}}</td>
                                <td>
                                    @if (!empty($result->warehouse_id))
                                        @php
                                            $warehouse = \App\Models\Warehouse::find($result->warehouse_id);
                                             $data = \App\MyClasses\Helpers::get_lang($warehouse->warehouse_name,$warehouse->id,"warehouse",App::getLocale());
                                             $warehouse_data = json_decode($data);
                                             $name = null;
                                             if(App::getLocale() == "en")
                                             {
                                                $name = $data;
                                             }
                                             elseif ($warehouse_data)
                                             {
                                                $name = $warehouse_data->name;
                                             }
                                             else
                                             {
                                                $name = $warehouse->warehouse_name;
                                             }
                                            echo "<a href='".route('admin.warehouse.view',['id'=>$warehouse->id])."'>".$name."</a>";
                                        @endphp
                                    @endif
                                </td>
                                <td>
                                    @if (!empty($result->receiver_admin))
                                        @php
                                            $receiver = \App\Models\User::find($result->receiver_admin);
                                            echo "<a href='".route('admin.user.view',['id'=>$receiver->id])."'>".$receiver->name."</a>";
                                        @endphp
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $user = \App\Models\User::find($result->user_id);
                                        echo "<a href='".route('admin.user.view',['id'=>$user->id])."'>".$user->name."</a>";
                                    @endphp
                                </td>
                                <td>{{$result->created_at}}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-light btn-pill dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('messages.action')}}</button>
                                        <div class="dropdown-menu" style="">
                                            <a class="dropdown-item" href="{{route('admin.view.invoice',['id'=> $result->invoice_no])}}"><i class="fa fa-eye me-2"></i> {{__('messages.view')}}</a>
                                            @if($result->status == "Complete" && $result->payment != "Paid")
                                                <a class="dropdown-item" href="javascript:void(0);" onclick="model({{$result->id}},{{$result->remaining}})"><i class="fa fa-dollar me-2"></i> {{__('messages.pay')}}</a>
                                            @endif
                        
                                            @if($result->status == "Complete" && $result->return == false)
                                                <a class="dropdown-item" href="{{route('admin.manage.invoice.return.partial',['id'=> $result->id])}}"><i class="fa fa-mail-reply me-2"></i> {{__('messages.partial-return')}}</a>
                                                <a class="dropdown-item" href="{{route('admin.manage.invoice.return.full',['id'=> $result->id])}}"><i class="fa fa-mail-reply-all me-2"></i> {{__('messages.full-return')}}</a>
                                            @endif
                                        </div>
                                    </div>
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
    <div class="modal fade" id="pay">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content-demo">
                <form method="POST" id="pay_form" enctype="multipart/form-data">
                    @csrf
                <div class="modal-header">
                    <h6 class="modal-title">{{__('messages.pay-invoice-payment')}}</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="error">
                    </div>
                    <input type="number" name="id" id="id" hidden>
                    <div class="custom-controls-stacked">
                        <label class="custom-control custom-radio">
                            <input id="option1" onclick="opt('Full')" type="radio" class="custom-control-input opt" name="option" value="{{__('messages.full')}}"/>
                            <span class="custom-control-label">{{__('messages.full-payment')}} (<span id="amount"></span>)</span>
                        </label>
                    </div>
                    <div class="custom-controls-stacked">
                        <label class="custom-control custom-radio">
                            <input id="option2" onclick="opt('Custom')" type="radio" class="custom-control-input opt" name="option" value="{{__('messages.custom')}}"/>
                            <span class="custom-control-label">{{__('messages.custom-payment')}}</span>
                        </label>
                    </div>
                    <div id="hide" class="form-group" style="display: none">
                        <label>{{__('messages.amount')}}</label>
                        <input id="payamount" type="number" class="form-control" name="amount">
                    </div>
                    <div class="col-md-12 position-relative">
                        <label class="form-label">{{__('messages.attach-proof')}}</label>
                        <input id="proof" type="file" class="form-control" name="proof" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">{{__('messages.pay')}}</button> <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">{{__('messages.close')}}</button>
                </div>
                </form>

            </div>
        </div>
    </div>
    <!-- CONTAINER END -->

@endsection
@section('query')
    <script type="text/javascript">
        function opt(value) {
            if (value == "Custom")
            {
                $('#hide').show();
            }
            else{
                $('#hide').hide();
            }
        }
        function model(id,amount)
        {
            $('#pay').modal('show');
            $('#id').val(id);
            $('#amount').html(amount);
        }
        $(document).ready(function () {
            $('#pay_form').submit(function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                console.log(formData);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.invoice.pay')}}",
                    method:"POST",
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response){
                        if (response.status == 400)
                        {
                            $('#error').html('');
                            $('#error').addClass('alert alert-light-danger');
                            $.each(response.errors,function (key,error) {
                                $('#error').append('<li>'+error+'</li>');
                            })
                        }
                        else{
                            $('#error').html('');
                            $('#error').removeClass('alert alert-light-danger');
                            $('#pay').modal('hide');
                            Swal.fire({
                                title: 'Congratulations!',
                                text: response.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                                window.location.href = '{{route('admin.manage.invoice')}}';
                            }, 2000);
                        }
                    }
                })
            });
        });
    </script>
@endsection
