<?php

namespace App\Console\Commands;

use App\Models\CompanyOrder;
use App\Models\Final_Stock;
use App\Models\SMSAorder;
use App\Models\SMSACredential;
use App\Models\User;
use App\Models\Stockins_list;
use Illuminate\Console\Command;
use App\Models\Orders;
use App\Models\StockIn;
use SmsaSDK\Smsa;

class SMSAStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SMSA:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Orders::all();
        foreach ($orders as $order)
        {

            $SMSA_order = SMSAorder::where('order_id',$order->id)->first();
            $user = User::find($order->user_id);
            if ($user->shipping_from_us == 1) {
                $smsa =SMSACredential::where('user_id',1)->first();
            }
            else {
                $smsa =SMSACredential::where('user_id',4)->first();
            }
            if ($SMSA_order != null)
            {
                $AWB_no = $SMSA_order->AWB_no;
                $status = Smsa::getStatus($AWB_no,$smsa->passkey);
                $getstatus = $status->getGetStatusResult();
                if (in_array($getstatus,["PICKED UP","Collected from Retail","AT SMSA FACILITY","DEPARTED FORM ORIGIN",
                "ARRIVED HUB FACILITY","OUT FOR DELIVERY","CONSIGNEE NO RESPONSE",
                "PROOF OF DELIVERY CAPTURED","AWAITING CONSIGNEE FOR COLLECTION"]))
                {
                    if ($getstatus == "DATA RECIEVED") {
                        $order->status = "SMSA Processing";
                    } elseif ($getstatus == "COLLECTED FROM RETAIL") {
                        $order->status = "Collected from Retail";
                    } elseif ($getstatus == "PICKED UP") {
                        $order->status = "Dispatched";
                    } elseif ($getstatus == "DEPARTED FORM ORIGIN") {
                        $order->status = "In Transit";
                    } elseif ($getstatus == "ARRIVED HUB FACILITY") {
                        $order->status = "In Transit";
                    } elseif ($getstatus == "DEPARTED HUB FACILITY") {
                        $order->status = "In Transit";
                    } elseif ($getstatus == "OUT FOR DELIVERY") {
                        $order->status = "Out for Delivery";
                    } elseif ($getstatus == "CONSIGNEE NO RESPONSE") {
                        $order->status = "Delivery Attempted";
                    } elseif ($getstatus == "AT SMSA FACILITY") {
                        $order->status = "In Transit";
                    } elseif ($getstatus == "PROOF OF DELIVERY CAPTURED") {
                        $order->status = "Delivered";
                    } elseif ($getstatus == "RETURNED TO SHIPPER") {
                        $order->status = "Returned";
                    } elseif ($getstatus == "RETURN PROCESS STARTED") {
                        $order->status = "Return Initiated";
                    } elseif ($getstatus == "AWAITING CONSIGNEE FOR COLLECTION") {
                        $order->status = "Awaiting Collection";
                    }
                    $order->update();
                    if ($getstatus == "PICKED UP")
                    {
                        $order->status = "Dispatched";
                        $order->update();

                        $products = json_decode($order->product);
                        foreach ($products as $product)
                        {
                            $stockIn = StockIn::where('product_id',$product->p_id)->first();
                            $stock = Stockins_list::where('stock_ins_id',$stockIn->id)->where('warehouse_id',$order->order_warehouse_id)->where('product_id',$product->p_id)->first();
                            $final_stock = Final_Stock::where('stock_ins_id',$stock->stock_ins_id)->first();
                            if ($final_stock->selected_stock >= $product->available_qty)
                            {
                                $final_stock->selected_stock = $final_stock->selected_stock - $product->available_qty;
                                $final_stock->delivered_stock = $final_stock->delivered_stock + $product->available_qty;
                                $final_stock->update();

                                $stock->sold = $product->packed_qty;
                                $stock->reserved = $stock->reserved - $stock->sold;
                                $stock->update();
                            }

                        }
                    }
                }
               elseif (in_array($getstatus,["RETURN PROCESS STARTED","RETURNED TO SHIPPER"])) {
                    if ($getstatus == "RETURN PROCESS STARTED") {
                        $order->refund_status = "Return Initiated";
                    } elseif ($getstatus == "RETURNED TO SHIPPER") {
                        $order->refund_status = "Returned";
                    }
                    $order->update();
               }
            }
        }
    }
}
