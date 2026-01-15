@extends('Admin.base')

@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.invoice-checkout') }}</h4>
                </div>
            </div>
            <!--End Page header-->
            @if (Session::has('success'))
                <div class="alert alert-light-success" role="alert">
                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert"
                        aria-hidden="true">×</button>
                    <strong>{{ __('messages.well-done') }}</strong> {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::has('danger'))
                <div class="alert alert-light-danger" role="alert">
                    <button type="button" class="btn-close text-danger mr-negative-16" data-bs-dismiss="alert"
                        aria-hidden="true">×</button>
                    <strong>{{ __('messages.oopps') }}</strong> {{ Session::get('danger') }}
                </div>
            @endif
            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('messages.invoices-details') }}</div>
                </div>
                <div class="card-body">
                    <table id="example"
                        class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ __('messages.supplier') }}</th>
                                <th>{{ __('messages.quantity') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.invoice-creator') }}</th>
                                <th>{{ __('messages.created-at') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($results as $result)
                                <tr>
                                    <td class="text-center">{{ $i++ }}</td>
                                    <td><a
                                            href="{{ route('admin.user.view', ['id' => $result[0]->suppliers_name[0]->id]) }}">{{ $result[0]->suppliers_name[0]->name }}</a>
                                    </td>
                                    <td>
                                        @php
                                            echo $quantity = \Illuminate\Support\Facades\DB::table('company_checkouts')
                                                ->where('status', 'Checkout')
                                                ->where('supplier_id', $result[0]->supplier_id)
                                                ->sum('quantity');
                                        @endphp
                                    </td>
                                    <td>
                                        @if ($result[0]->status == 'Checkout')
                                            {{ __('messages.checkout') }}
                                        @else
                                            {{ __('messages.orderd') }}
                                        @endif
                                    </td>
                                    <td><a
                                            href="{{ route('admin.user.view', ['id' => $result[0]->invoicer_name[0]->id]) }}">{{ $result[0]->invoicer_name[0]->name }}</a>
                                    </td>
                                    <td>{{ $result[0]->created_at }}</td>
                                    <td>
                                        <a href="{{ route('admin.invoice.checkout.view', ['id' => $result[0]->supplier_id]) }}"
                                            class="btn btn-primary"><i class="fa fa-eye"></i></a>
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
                    window.location.href = url;
                } else {
                    Swal.fire({
                        title: 'Your Invoice is Safe!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            });
        });
    </script>
@endsection
