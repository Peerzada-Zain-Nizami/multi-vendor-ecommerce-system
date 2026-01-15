@extends('Supplier.base')
@section('content')

<!--app-content open-->
<div class="app-content main-content">
    <div class="side-app">
        <!-- Row -->
        <div class="row mt-5">
            <div class="col-xl-5 col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            {{__('messages.wallet-balance')}}
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <p class="display-2">SAR:{{$balance}}</p>
                    </div>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </div>
    <!-- CONTAINER END -->
</div>
@endsection