@extends('Admin.base')

@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">
            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.plan-management') }}</h4>
                </div>
            </div>
            <!--End Page header-->
            @if (Session::has('success'))
                {{-- <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                        aria-hidden="true">×</button>
                    <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                </div> --}}
            @elseif(Session::has('danger'))
                <div class="alert alert-light-danger" role="alert">
                    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert"
                        aria-hidden="true">×</button>
                    <strong>{{ __('messages.oopps') }}</strong> {{ Session::get('danger') }}
                </div>
            @endif
            <div id="msg">

            </div>
            <!--div-->
            <div class="card">
                <div class="card-body">
                    <table id="example"
                        class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                            <tr>
                                <th class="text-center">#</th>

                                <th>{{ __('messages.plan-name') }}</th>
                                <th>{{ __('messages.plan-monthly-price') }}</th>
                                <th>{{ __('messages.plan-yearly-price') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($datas as $data)
                                <tr>
                                    @php
                                        $plan_price = json_decode($data->plan_price);
                                    @endphp
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        @if ($plan_price)
                                            {{ $plan_price->Monthly }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>
                                        @if ($plan_price)
                                            {{ $plan_price->Yearly }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td>{{ $data->status }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-light btn-pill dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">{{ __('messages.action') }}</button>
                                            <div class="dropdown-menu" style="">
                                                <a href="{{ route('admin.seller.plan.view', $data->id) }}"
                                                    class="dropdown-item"><i class="fa fa-eye"></i>
                                                    {{ __('messages.view') }}</a>
                                                <a href="{{ route('admin.seller.plan.edit', $data->id) }}"
                                                    class="dropdown-item"><i class="fa fa-pencil"></i>
                                                    {{ __('messages.edit') }}</a>
                                                @if ($data->id == 1 || $data->name == 'Free')
                                                @else
                                                    <a href="{{ route('admin.seller.plan.delete', $data->id) }}"
                                                        class="dropdown-item delete-confirm"><i class="fa fa-trash"></i>
                                                        {{ __('messages.delete') }}</a>
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
        <!--/div-->


    </div>
    <!-- CONTAINER END -->
@endsection

@section('query')
    <script type="text/javascript">
        $('.delete-confirm').on('click', function(event) {
            event.preventDefault();
            const url = $(this).attr('href');
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover this Plan! ',
                icon: 'warning',
                showCancelButton: true,
                dangerMode: true,
                confirmButtonText: "Ok, Delete it!",
                showConfirmButton: true,
            }).then(function(value) {
                if (value) {
                    Swal.fire({
                        title: 'Congratulations!',
                        text: 'plan deleted',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    setTimeout(function() {
                        window.location.href = url;
                    }, 1500);

                } else {
                    Swal.fire({
                        title: 'Your Plan is Safe!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    </script>
@endsection
