<?php
namespace App\MyClasses;

use App\Models\Stockins_list;
use Hamcrest\Arrays\IsArray;
use Illuminate\Support\Facades\DB;

class WarehouseClass
{
    public static function getWarehouse($products, $orderCity)
    {
        $warehouseProducts = Stockins_list::select(
            'stock_ins_list.warehouse_id',
            'stock_ins_list.product_id',
            DB::raw('SUM(stock_ins_list.available) as available'),
            'shippings.our_system_cities as city_name'
        )
        ->leftJoin('warehouses', 'stock_ins_list.warehouse_id', '=', 'warehouses.id')
        ->leftJoin('shippings', 'warehouses.city', '=', 'shippings.id')
        ->where('stock_ins_list.available', '>', 0)
        ->groupBy(
            'stock_ins_list.warehouse_id',
            'stock_ins_list.product_id',
            'shippings.our_system_cities'
        )
        ->get();

        $warehouses = [];

        foreach ($warehouseProducts as $item) {
            $warehouseId = $item->warehouse_id;
            $productId = $item->product_id;
            $available = $item->available;
            $cityName = strtolower($item->city_name);

            if ($cityName == strtolower($orderCity)) {
                $warehouseData = [$productId => $available];

                if (isset($warehouses[$warehouseId])) {
                    $warehouses[$warehouseId] = $warehouseData + $warehouses[$warehouseId];
                } else {
                    $warehouses = [$warehouseId => $warehouseData] + $warehouses;
                }
            } else {
                $warehouses[$warehouseId][$productId] = $available;
            }
        }

        $maxProductCount = 0;
        $previousWarehouse = [];

        foreach ($warehouses as $warehouseId => $productStocks) {
            $intersection = array_intersect(array_keys($productStocks), array_keys($products));
            if (count($intersection) > $maxProductCount || (count($intersection) == $maxProductCount && $maxProductCount > 0)) {
                $maxProductCount = count($intersection);
                $verifiedProducts = self::verifyProducts($productStocks, $products);

                if (!empty($previousWarehouse)) {
                    $previousWarehouseProducts = reset($previousWarehouse);
                    $maxTrueCount_previousWarehouse = count(array_filter($previousWarehouseProducts, function ($item) {
                        return $item["status"] === "true";
                    }));

                    $maxTrueCount_verifiedProduct = count(array_filter($verifiedProducts, function ($item) {
                        return $item["status"] === "true";
                    }));

                    if ($maxTrueCount_verifiedProduct > $maxTrueCount_previousWarehouse) {
                        $previousWarehouse = [$warehouseId => $verifiedProducts];
                    }
                    elseif($maxTrueCount_verifiedProduct == $maxTrueCount_previousWarehouse) {
                        // Calculate percentage availability of products in the previous warehouse
                        $previousWarehousePercentage = self::calculateWarehouseAvailabilityPercentage($previousWarehouseProducts, $products);

                        // Calculate percentage availability of verified products
                        $verifiedProductsPercentage = self::calculateWarehouseAvailabilityPercentage($verifiedProducts, $products);

                        if ($verifiedProductsPercentage > $previousWarehousePercentage) {
                            $previousWarehouse = [$warehouseId => $verifiedProducts];
                        }
                    }
                }
                else {
                    $previousWarehouse[$warehouseId] = $verifiedProducts;
                }
            }
        }
        return !empty($previousWarehouse) ? $previousWarehouse : null;
    }

    private static function verifyProducts($productStocks, $products)
    {
        $verified_products = [];

        foreach ($products as $productId => $productQty) {
            if (array_key_exists($productId, $productStocks)) {
                if ($productStocks[$productId] < $productQty) {
                    $qty = $productStocks[$productId];
                } else {
                    $qty = $productQty;
                }

                $status = ($qty > 0) ? 'true' : 'false';

                $verified_products[$productId] = [
                    'status' => $status,
                    'qty' => $qty,
                ];
            } else {
                $verified_products[$productId] = [
                    'status' => 'false',
                    'qty' => 0,
                ];
            }
        }

        return $verified_products;
    }

    private static function calculateWarehouseAvailabilityPercentage($warehouseProducts, $products) {
        $percentage = 0;
        foreach ($warehouseProducts as $index => $item) {
            if ($item['status'] === 'true') {
                $totalValue = $products[$index];
                $availableValue = $item['qty'];
                $percentage += ($availableValue / $totalValue) * 100;
            }
        }
        return $percentage;
    }

    public static function getProductStock($productId, $warehouse)
    {
        if (!empty($warehouse)) {
            $warehouse_id = array_keys($warehouse)[0];
            $products = reset($warehouse);
            $productQty = $products[$productId]['qty'] ?? 0;

            if ($productQty > 0) {
                $stockEntry = Stockins_list::where('warehouse_id', $warehouse_id)
                    ->where('product_id', $productId)
                    ->get();

                $stock = 0;

                foreach ($stockEntry as $value) {
                    if ($stock < $productQty) {
                        if ($value->available > $productQty - $stock) {
                            $value->reserved += $productQty - $stock;
                            $value->available -= $productQty - $stock;
                            $stock = $productQty;
                        } else {
                            $value->reserved += $value->available;
                            $stock += $value->available;
                            $value->available = 0;
                        }

                        $value->update();
                    } else {
                        break;
                    }
                }

                return $productQty;
            }
        }


        return 0;
    }
}
