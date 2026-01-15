<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIn extends Model
{
    use HasFactory;
    protected $table = 'stock_ins';
    public static function get_sum($warehouse_id,$product_id)
    {
        $where = [
            'warehouse_id'=>$warehouse_id,
            'product_id'=>$product_id,
        ];
        return $sum = Stockins_list::where($where)->sum('stock');
    }
    public static function get_available_stock($warehouse_id,$product_id)
    {
        $where = [
            'warehouse_id'=>$warehouse_id,
            'product_id'=>$product_id,
        ];
        $stock_ins_list = Stockins_list::where($where)->first();
        return $sum = Final_Stock::where('stock_ins_id',$stock_ins_list->stock_ins_id)->where('warehouse_id',$warehouse_id)->first() ;
    }
    public static function get_value($warehouse_id,$product_id)
    {

        $where = [
            'warehouse_id'=>$warehouse_id,
            'product_id'=>$product_id,
        ];
        $lists = Stockins_list::where($where)->get();
        $value = array();
        foreach ($lists as $result)
        {
            $order = CompanyOrder::where('invoice_no',$result->invoice_no)->first();
            $products = json_decode($order->original_order);
            foreach ($products as $product) {
                if ($product->product_id == $product_id)
                {

                    $value[] = $product->rate*$result->stock;
                }

            }

        }
        return array_sum($value);
    }
    public static function get_value_by_supplier($supplier_id,$product_id,$warehouse_id)
    {
        $results0 = StockIn::where('supplier_id',$supplier_id)->where('product_id',$product_id)->first();
        $results = Stockins_list::where('stock_ins_id',$results0->id)->where('warehouse_id',$warehouse_id)->get();

        $value = array();
        foreach ($results as $result)
        {
            $order = CompanyOrder::where('invoice_no',$result->invoice_no)->first();
            $products = json_decode($order->original_order);
            foreach ($products as $product) {
                if ($product->product_id == $product_id)
                {
                    $value[] = $product->rate*$result->stock;
                }
            }

        }
        return array_sum($value);
    }
    public static function get_warehouse($id)
    {
        $data = Warehouse::find($id);
        return $return = $data->warehouse_id.'/'.$data->warehouse_name;
    }
    public function get_products()
    {
        return $this->hasMany(Product::class,'id','product_id');
    }
    public function suppliers_name()
    {
        return $this->hasMany(User::class,'id','supplier_id');
    }
    public function product_name()
    {
        return $this->hasMany(Product::class,'id','product_id');
    }

}
