@extends('Admin.base')

@section('content')
    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">

            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{ __('messages.shipping-company-cities') }}</h4>
                </div>
            </div>
            <!--End Page header-->
            <!--div-->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('messages.shipping-companies') }}</div>
                </div>
                <div class="card-body">
                    <table id="example"
                        class="table table-responsive-lg-sm table-hover table-bordered text-nowrap key-buttons">
                        <thead class="border-bottom-0 pt-3 pb-3">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ __('messages.system-cities') }}</th>
                                <th>{{ __('messages.SMSA-cities') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($datas as $data)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $data->our_system_cities }}</td>
                                    <td>
                                        @if ($data->SMSA_cities == null)
                                        @else
                                            {{ $data->SMSA_cities }}
                                        @endif
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
@endsection
