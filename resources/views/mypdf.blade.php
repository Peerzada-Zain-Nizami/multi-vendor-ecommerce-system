<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

    <!-- Meta data -->
    <meta charset="UTF-8">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">

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
    if ($type == "product"){
        for ($x = 1; $x <= $qty; $x++) {
            echo '
                             <div class="col-12 p-2 text-center">
                                <div class="mb-4">
                                    <img width="170px" height="30px" src="data:image/png;base64,'.DNS1D::setStorPath(__DIR__.'/cache/')->getBarcodePNG((string)$id, 'C128').'">
                                </div>
                                <div>
                                    <img width="100px" height="90px" src="data:image/png;base64,'.DNS2D::setStorPath(__DIR__.'/cache/')->getBarcodePNG((string)$id, 'QRCODE').'">
                                </div><br>
                                <div style="font-size:15px ;">
                                    <p style="line-height: 1.2px;"> Supplier ID: '.$product->supplier_id.'</p>
                                    <p style="line-height: 1.2px;"> Product ID: '.$product->product_id.'</p>
                                    <p style="line-height: 1.2px;">'.$product->product_name[0]->product_name.'</p>
                                </div>
                            </div>
    ';
            if ($x % 2 === 0 && $x != $qty)
            {
                echo "<div style='page-break-after: always;'></div>";
            }
        }
    }
    if ($type == "all"){
        foreach ($results as $result)
            {
                for ($x = 1; $x <= $result['qty']; $x++) {
                    echo '
                             <div class="col-12 p-2 text-center">
                                <div class="mb-4">
                                    <img width="170px" height="30px" src="data:image/png;base64,'.DNS1D::setStorPath(__DIR__.'/cache/')->getBarcodePNG((string)$result['id'], 'C128').'">
                                </div>
                                <div>
                                    <img width="100px" height="90px" src="data:image/png;base64,'.DNS2D::setStorPath(__DIR__.'/cache/')->getBarcodePNG((string)$result['id'], 'QRCODE').'">
                                </div><br>
                                <div style="font-size:15px ;">
                                    <p style="line-height: 1.2px;"> Supplier ID: '.$result['product']->supplier_id.'</p>
                                    <p style="line-height: 1.2px;"> Product ID: '.$result['product']->product_id.'</p>
                                    <p style="line-height: 1.2px;">'.$result['product']->product_name[0]->product_name.'</p>
                                </div>
                            </div>
    ';
                    if ($x % 2 === 0 && $x != $result['qty'])
                    {
                        echo "<div style='page-break-after: always;'></div>";
                    }
                }
            }
    }
    if($type == "shelf")
    {
        for ($x = 1; $x <= $qty; $x++) {
            echo '
                             <div class="col-12 p-2 text-center">
                                <div class="mb-4">
                                    <img width="170px" height="30px" src="data:image/png;base64,'.DNS1D::setStorPath(__DIR__.'/cache/')->getBarcodePNG((string)$id, 'C128').'">
                                </div>
                                <div>
                                    <img width="100px" height="90px" src="data:image/png;base64,'.DNS2D::setStorPath(__DIR__.'/cache/')->getBarcodePNG((string)$id, 'QRCODE').'">
                                </div><br>
                                <div style="font-size:15px ;">
                                    <p style="line-height: 1.2px;"> Warehouse: '.$shelf->warehouse_get[0]->warehouse_name.'</p>
                                    <p style="line-height: 1.2px;"> Room/Block: '.$shelf->block_get[0]->block_code.'</p>
                                    <p style="line-height: 1.2px;"> Rack: '.$shelf->rack_get[0]->rack_code.'</p>
                                    <p style="line-height: 1.2px;"> Shelf: '.$shelf->shelf_code.'</p>
                                </div>
                            </div>
    ';
            if ($x % 2 === 0 && $x != $qty)
            {
                echo "<div style='page-break-after: always;'></div>";
            }

        }
    }

    ?>
</body>

</html>
