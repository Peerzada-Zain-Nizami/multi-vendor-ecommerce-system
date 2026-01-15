@extends('Seller.base')

@section('content')

    <!--app-content open-->
    <div class="app-content main-content">
        <div class="side-app">


            <!--Page header-->
            <div class="page-header">
                <div class="page-leftheader">
                    <h4 class="page-title mb-0 text-primary">{{__('messages.language-setup')}}</h4>
                </div>
            </div>
            <!--End Page header-->
            {{-- div --}}
            <div class="col-sm-4 offset-4">
                @if(empty($data))
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{__('messages.add-product-listing-language')}}</div>
                    </div>
                    <div class="card-body">
                        @if (Session::has('success'))
                            <div class="alert alert-light-success" role="alert">
                                <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                            </div>
                        @endif

                            <form action="{{route('seller.lang.setup.add')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">{{__('messages.language')}}</label>
                                <select name="language" class="form-control select2-show-search">
                                    <option value="">{{__('messages.please-select')}}</option>
                                    @foreach (Config::get('languages') as $lang => $language)
                                        <option value="{{$lang}}"><img src="{{asset ('assets/images/flags/'.$language['flag-icon'].'.svg')}}" alt="flag" class="me-2 country"> {{$language['display']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group text-center">
                                <input class="btn btn-primary" type="submit" value="{{__('messages.set-language')}}">
                            </div>
                        </form>

                    </div>
                </div>
                    @else
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">{{__('messages.update-product-listing-language')}}</div>
                        </div>
                        <div class="card-body">
                            @if (Session::has('success'))
                                <div class="alert alert-light-success" role="alert">
                                    <button type="button" class="btn-close text-success mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{{__('messages.well-done')}}</strong> {{Session::get('success')}}
                                </div>
                            @endif

                            <form action="{{route('seller.lang.setup.update',$data->id)}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label class="form-label">{{__('messages.languages')}}</label>
                                    <select name="language" class="form-control select2-show-search">
                                        <option value="">{{__('messages.please-select')}}</option>
                                        @foreach (Config::get('languages') as $lang => $language)
                                            <option @if($lang == $data->language) selected @endif value="{{$lang}}"><img src="{{asset ('assets/images/flags/'.$language['flag-icon'].'.svg')}}" alt="flag" class="me-2 country"> {{$language['display']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group text-center">
                                    <input class="btn btn-primary" type="submit" value="{{__('messages.set-language')}}">
                                </div>
                            </form>

                        </div>
                    </div>
                @endif
            </div>
            {{-- /div --}}
            <!--div-->
            {{--<div class="card">
                <div class="card-header">
                    <div class="card-title">{{__('messages.bank-list')}}</div>
                </div>
                <div class="card-body">
                    <div class="">
                        <div class="table-responsive-lg">
                            <table id="example" class="table table-bordered text-nowrap key-buttons">
                                <thead>
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">{{__('messages.bank-name')}}</th>
                                    <th class="border-bottom-0">{{__('messages.account-name')}}</th>
                                    <th class="border-bottom-0">{{__('messages.IBAN-No')}}</th>
                                    <th class="border-bottom-0">{{__('messages.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach($records as $record)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$record->bank_name}}</td>
                                            <td>{{$record->account_name}}</td>
                                            <td>{{$record->iban_no}}</td>
                                            <td><button id="{{$record->id}}" class="btn btn-danger del"><i class="fa fa-trash-o"></i></button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>--}}
            <!--/div-->


        </div>
    </div>
    <!-- CONTAINER END -->
@endsection

{{--
@section('query')
    <script type="application/javascript">
        $(Document).ready(function() {
            $('.del').click(function(){
                var th = $(this);
                var id = $(this).attr("id");

                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Bank Details!",
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
                                url:'{{route('seller.bank.del')}}',
                                type:'post',
                                data:{id:id},
                                success:function(data){
                                    th.parents('tr').hide();
                                    Swal.fire({
                                        title: 'Congratulations!',
                                        text: "Your Bank has been successfully deleted",
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500
                                    })
                                }
                            });


                        } else {
                            Swal.fire({
                                title: 'Your Bank is Safe!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        }
                    });


            });
        });
    </script>
@endsection--}}
