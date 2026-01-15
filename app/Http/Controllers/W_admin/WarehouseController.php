<?php

namespace App\Http\Controllers\W_admin;

use App\Http\Controllers\Controller;
use App\Models\Rack;
use App\Models\Room_Block;
use App\Models\Shelf;
use App\Models\StockIn;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class WarehouseController extends Controller
{
    public function overview()
    {
        $user =Auth::user()->id;
        $lists = array();
        $warehouses0 = Warehouse::all();
        foreach ($warehouses0 as $warehouse)
        {
            if (in_array($user,json_decode($warehouse->responsible)))
            {
                $lists[] = $warehouse->id;
            }
        }
        $warehouses = Warehouse::whereIn('id',$lists)->with('blocks','racks','shelfs')->get();
        return view('W_admin.warehouse_overview',compact('warehouses'));
    }
    public function get_block_list($id)
    {
        $data = Room_Block::where('warehouse_id',$id)->get();
        return response()->json($data);
    }
    public function get_rack_list($id)
    {
        $data = Rack::where('block_id',$id)->get();
        return response()->json($data);
    }
    public function get_shelf_list($id)
    {
        $data = Shelf::where('rack_id',$id)->get();
        return response()->json($data);
    }
    public function stock_placement(Request $request,$id)
    {
        $records = array();
        $products = $request->product_id;
        $blocks = $request->blocks;
        $racks = $request->racks;
        $shelfs = $request->shelfs;
        $i = 0;
        foreach ($products as $prodct)
        {
            $index = $i++;
            $record = array();
            $record['product_id'] = $prodct;
            $record['block_id'] = $blocks[$index];
            $record['rack_id'] = $racks[$index];
            $record['shelf_id'] = $shelfs[$index];
            $records[] = $record;
        }
        foreach ($records as $record) {
            $where = [
                'invoice_no'=>$id,
                'product_id'=>$record['product_id'],
            ];
            $data = StockIn::where($where)->first();
            $data->block_id = $record['block_id'];
            $data->rack_id = $record['rack_id'];
            $data->shelf_id = $record['shelf_id'];
            $data->update();
        }
        Session::flash('success', 'Placement has been successfully updated.');
        return redirect()->back();
    }
}
