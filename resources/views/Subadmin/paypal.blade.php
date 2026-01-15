@extends('Subadmin.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">Bank Add/List</h4>
                </div>
            </div>
            <!--End Page header-->
            {{-- div --}}
            <div class="col-sm-4 offset-4">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Add New Bank</div>
                    </div>
                    <div class="card-body">
                        @if (Session::has('success'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                <strong>Well done!</strong> {{Session::get('success')}}
                            </div>
                        @endif
                        <form action="{{route('subadmin.paypal.add')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">Paypal Email</label>
                                <input type="text" class="form-control @if($errors->has('paypal_email'))is-invalid @endif" value="{{old('paypal_email')}}" name="paypal_email"/>
                                @if ($errors->has('paypal_email'))
                                    <span class="text-danger">{{ $errors->first('paypal_email') }}</span>
                                @endif
                            </div>
                            <div class="form-group text-center">
                                <input class="btn btn-primary" type="submit" value="Add Bank">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        {{-- /div --}}
        <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Paypal Account List</div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive-lg">
                            <table id="example" class="table table-bordered text-nowrap key-buttons">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">Paypal Email</th>
                                    <th class="border-bottom-0">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($records))
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach($records as $record)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$record->paypal_email}}</td>
                                            <td><button id="{{$record->id}}" class="btn btn-danger del"><i class="fa fa-trash-o"></i></button></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="13" class="text-center">Record Not Found</td>
                                    </tr>
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
    <!-- CONTAINER END -->

@endsection
@section('query')
    <script type="application/javascript">
        $(Document).ready(function() {
            $('.del').click(function(){
                var th = $(this);
                var id = $(this).attr("id");

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Paypal Details!",
                    icon:"warning",
                    showCancelButton: true,
                    dangerMode: true,
                    confirmButtonText: "Ok, Delete it!",
                    showConfirmButton: true,

                })
                    .then((willDelete) => {

                        if (willDelete) {
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                url:'{{route('subadmin.paypal.del')}}',
                                type:'post',
                                data:{id:id},
                                success:function(data){
                                    th.parents('tr').hide();
                                    Swal.fire({
                                        title: 'Congratulations!',
                                        text: "Your Paypal has been successfully deleted",
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                }
                            });


                        } else {
                            Swal.fire({
                                title: 'Your Paypal is Safe!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    });


            });
        });
    </script>
@endsection