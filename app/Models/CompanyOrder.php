<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyOrder extends Model
{
    use HasFactory;
    protected $table = "company_orders";
    public function suppliers_name()
    {
        return $this->hasMany(User::class,'id','supplier_id');
    }
    public static function sold($invoice_no,$supplier_id,$product_id)
    {
        $skoclInId = StockIn::where('supplier_id',$supplier_id)->where('product_id', $product_id)->value('id');
        $where = [
            'invoice_no'=>$invoice_no,
            'stock_ins_id'=>$skoclInId,
            'product_id'=>$product_id,
        ];
        $data = Stockins_list::where($where)->first();
        return $data->sold;
    }
}
