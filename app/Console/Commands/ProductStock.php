<?php

namespace App\Console\Commands;

use App\Models\Drop_shipping;
use App\Models\ShopifyProduct;
use App\Models\Stockins_list;
use App\Models\User;
use App\Models\Wooproduct;
use App\MyClasses\Helpers;
use Illuminate\Console\Command;

class ProductStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:stock';

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
        $sellers = User::where('role', 'Seller')->get();

        foreach ($sellers as $seller) {
            // Get the Shopify products for this seller
            $shopifyProducts = ShopifyProduct::where('user_id', $seller->id)->get();

            foreach ($shopifyProducts as $product) {
                // Calculate stock information
                $totalStock = Stockins_list::where('product_id', $product->product_id)->sum('stock');
                $soldStock = Stockins_list::where('product_id', $product->product_id)->sum('sold');
                $reservedStock = Stockins_list::where('product_id', $product->product_id)->sum('reserved');
                $reservedStockInWarehouse = $reservedStock + $soldStock;
                $availableStockForCustomers = $totalStock - $reservedStockInWarehouse;

                // Update stock on Shopify if the product is active
                $activeProduct = Drop_shipping::where('user_id', $product->user_id)
                    ->where('product_id', $product->product_id)
                    ->where('status', 'Active')
                    ->first();

                $shopifyClient = Helpers::shopify_client($product->user_id);


                if ($shopifyClient && $activeProduct) {
                    $shopifyData = [
                        'product' => [
                            'variants' => [
                                [
                                    'inventory_quantity' => $availableStockForCustomers,
                                    'price' => $activeProduct->selling_price,
                                ],
                            ],
                        ],
                    ];

                    // Update Shopify product data
                    $send = $shopifyClient->put('products/' . $product->shopify_id . '.json', $shopifyData);
                }
            }

            $wooProducts = Wooproduct::where('user_id', $seller->id)->get();

            foreach ($wooProducts as $product) {
                // Calculate stock information
                $totalStock = Stockins_list::where('product_id', $product->product_id)->sum('stock');
                $soldStock = Stockins_list::where('product_id', $product->product_id)->sum('sold');
                $reservedStock = Stockins_list::where('product_id', $product->product_id)->sum('reserved');
                $reservedStockInWarehouse = $reservedStock + $soldStock;
                $availableStockForCustomers = $totalStock - $reservedStockInWarehouse;

                // Update stock on Shopify if the product is active
                $activeProduct = Drop_shipping::where('user_id', $product->user_id)
                    ->where('product_id', $product->product_id)
                    ->where('status', 'Active')
                    ->first();

                $wooClient = Helpers::woocommerce_client($product->user_id);

                if ($wooClient && $activeProduct) {
                    $products_stock = [
                        'stock_quantity' => $availableStockForCustomers,
                    ];

                    // Update woocommerce product data
                    $send = $wooClient->put('products/' . $product->woo_id, $products_stock);
                }
            }
        }
    }
}
