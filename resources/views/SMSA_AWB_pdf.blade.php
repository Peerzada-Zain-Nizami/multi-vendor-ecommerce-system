<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

    <!-- Meta data -->
    <meta charset="UTF-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    {{--<link rel="stylesheet" href="{{asset ('assets/pdf/style.css')}}">--}}

    <!-- jQuery library -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Title -->
    <title>Barcode Download</title>
</head>

<body>
    <?php
      $seller =App\Models\User::where('id',$Order->user_id)->first();
      $consignee = json_decode($Order->shipping_address);
?>
    <div class="container-fluid">
        <div class="row" style=" border-bottom: 2px solid black;font-family: Arial;">
            <table class="col-sm-12 table-responsive-lg-sm">
                <tr>


                    <td class="col-sm-10" style="text-align: left; margin-left: 3px">
                        <h5>{{$seller->company_name}}</h5>
                        <p>Order # {{$Order->order_no}}</p>
                        <h6>{{$Order->created_at}}</h6>
                    </td>
                    <td class="col-sm-6">
                    </td>
                    <td class="col-sm-1" style="text-align: center;padding-top: 4px">
                        <b style="font-size: 30px; " >{{$consignee->country}}</b>
                    </td>
                    <td class="col-sm-2" style="text-align: right;">
                            <b style="font-size: 70px;font-weight: bolder;" >{{ucfirst($consignee->city)}}</b>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row" style=" border-bottom: 2px solid black;font-family: Arial;">
            <table class="col-sm-12 table-responsive-lg-sm">
                <tr>
                    <td class="col-sm-6" style="text-align: left;">
                        <p>Shipper: {{$seller->city}}</p>
                    </td>
                    <td class="col-sm-6" style="text-align: right;">
                        <p>{{$seller->mobile_no}}</p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="row" style=" border-bottom: 2px solid black;font-family: Arial;">
            <table class=" col-sm-12 table-responsive-lg-sm">
                <tr>
                    <td class="col-sm-10" style="text-align: left; margin-left: 3px">
                        <h6>{{$seller->name}}</h6>
                        {{-- <p>{{$seller->sContact}}</p> --}}
                        <p>{{$seller->address}}</p>
                        <h6>{{$seller->country}}</h6>
                    </td>

                </tr>
            </table>
        </div>
        <div class="row" style=" border-bottom: 2px solid black;font-family: Arial;">
            <table class=" col-sm-12 table-responsive-lg-sm">
                <tr>
                    <td class="col-sm-6" style="text-align: left;">
                        <h6>To: {{$consignee->city}}</h6>
                    </td>
                    <td class="col-sm-6" style="text-align: right; ">
                        <h6>{{$consignee->phone}}</h6>
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-12" style="text-align: left;">
                        <h6 style="text-align: center">{{$consignee->first_name}}</h6>
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-12" style="text-align: left;">
                        <p>{{$consignee->address_1}}</p>
                    </td>
                </tr>
                <tr>
                    <td class="col-sm-6" style="text-align: left;">
                        <h6>{{$consignee->city}}</h6>
                    </td>
                    <td class="col-sm-6" style="text-align: right;">
                        <p>{{$consignee->country}}</p>
                    </td>
                </tr>
            </table>
        </div>
       
        <div class="row" style=" border-bottom: 2px solid black;font-family: Arial;">
            <table class=" col-sm-12 table-responsive-lg-sm">
                <tr>
                    <td class="col-sm-12 " style="text-align: right;">
                        <h6>{{$Order->created_at}}</h6>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row" style=" font-family: Arial;">
            <table class=" col-sm-12 table-responsive-lg-sm">
                <tr>
                    <td colspan="4" style="text-align: left;">
                        <table class="col-sm-12 table-responsive-lg-sm" >
                            <tr>
                                {{-- <td style="padding-left: 3px">
                                    <h6 style="padding-top: 6px">Ref # {{$other_data->refNo}}</h6>
                                </td> --}}
                            </tr>
                            <tr>
                                <td class="col-sm-8">
                                    <p>Wgt # 0.5 Kg</p>
                                </td>
                                <td class="col-sm-4">
                                    <p>PCs # 1</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-8">
                                    <p>Customs # 0</p>
                                </td>
                                <td class="col-sm-4">
                                    <p>Carriage #</p>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-sm-12">
                                    <p>SAR</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="text-align: center; width: 100px; height: 100px;">
                        <div class="card" style="border:3px solid black; ">
                            <div class="card-header" style="padding-top: 3px; padding-bottom: 3px; border-bottom: 3px solid black">
                                <h4>COD&nbsp;#</h4>
                            </div>
                            <div class="card-body" style="padding-top: 4px; padding-bottom: 5px; background-color: #c2d69b;">
                                <h1>0</h1>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row" style=" border-bottom: 2px solid black;font-family: Arial;">
            <table class=" col-sm-12 table-responsive-lg-sm">
                <tr>
                    <td class="col-sm-12 ">
                        <p>Description : Supplies and catalogs</p>
                    </td>
                </tr>
            </table>
        </div>
        <div class="row" style=" font-family: Arial;">
            <table class="col-sm-12 table-responsive-lg-sm">
                <tr  >
                    <td class="col-sm-6" style="text-align: left;">
                        <p>Payment Duty : Bill Consignee</p>
                    </td>
                    <td class="col-sm-6" style="text-align: left;">
                        <p>Payment VAT : Bill Shipper</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
