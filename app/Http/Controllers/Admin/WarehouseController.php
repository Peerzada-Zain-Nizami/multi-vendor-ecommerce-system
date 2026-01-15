<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessModel;
use App\Models\Language_Meta;
use App\Models\Rack;
use App\Models\Room_Block;
use App\Models\Shelf;
use App\Models\shipping;
use App\Models\StockIn;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use PDF;

class WarehouseController extends Controller
{
    public function barcode_download($type,$id,$qty)
    {
        if ($type == "product")
        {
            $stock_in =StockIn::where('id',$id)->with('product_name')->first();
            $data = [
                'id' => $id,
                'qty' => $qty,
                'product'=>$stock_in,
                'type'=>$type,

            ];
            $pdf = PDF::loadView('mypdf',$data);
            return $pdf->stream('barcode.pdf');
        }
        if ($type == "shelf")
        {
            $shelf =Shelf::where('id',$id)->with('warehouse_get','rack_get','block_get')->first();
            $data = [
                'id' => $id,
                'qty' => $qty,
                'shelf'=>$shelf,
                'type'=>$type,

            ];

            $pdf = PDF::loadView('mypdf',$data);
            return $pdf->stream('barcode.pdf');
        }

    }
    public function overview()
    {
        $warehouses = Warehouse::with('blocks','racks','shelfs')->get();
        return view('Admin.warehouse_overview',compact('warehouses'));
    }
    public function index()
    {
        $warehouse_admin = User::where('role','Warehouse Admin')->get();
        $warehouses = Warehouse::with('blocks','cityRelation')->get();
        $cities = shipping::all();
        return view('Admin.add_warehouse',['admins'=>$warehouse_admin,'warehouses'=>$warehouses,'cities'=>$cities]);
    }
    public function view($id)
    {
        $warehouse = Warehouse::find($id);
        $blocks = Room_Block::where('warehouse_id',$id)->with('count_rack')->get();
        $racks = Rack::where('warehouse_id',$id)->with('count_shelf')->get();
        $shelfs = Shelf::where('warehouse_id',$id)->with('count_product')->get();
        return view('Admin.warehouse_view',compact('warehouse','id','blocks','racks','shelfs'));
    }
    public function add_warehouse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_name' => ['required', 'string'],
            'warehouse_code' => ['required', 'string', 'unique:warehouses,warehouse_id'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
            'responsible' => ['required'],
            'status' => ['required']
        ],[],[
            'warehouse_name' => 'Warehouse Name',
            'warehouse_code' => 'Warehouse Code',
            'city' => 'City',
            'address' => 'Address',
            'responsible' => 'Responsible',
            'status' => 'Status'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $add = new Warehouse();
            $add->warehouse_id = $request->warehouse_code;
            $add->warehouse_name = $request->warehouse_name;
            $add->city = $request->city;
            $add->address = $request->address;
            $add->responsible = json_encode($request->responsible);
            $add->status = $request->status;
            $add->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Warehouse Added Successful.'
            ]);
        }
    }
    public function edit_warehouse($id)
    {
        $warehouse = Warehouse::find($id);
        $admins = User::where('role','Warehouse Admin')->get();
        $cities = shipping::all();
        return view('Admin.edit_warehouse',compact('warehouse','admins','cities'));
    }
    public function update_warehouse(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_name' => ['required', 'string'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
            'responsible' => ['required'],
            'status' => ['required']
        ],[],[
            'warehouse_name' => 'Warehouse Name',
            'city' => 'City',
            'address' => 'Address',
            'responsible' => 'Responsible',
            'status' => 'Status'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $update = Warehouse::find($id);
            $update->warehouse_name = $request->warehouse_name;
            $update->address = $request->address;
            $update->city = $request->city;
            $update->responsible = json_encode($request->responsible);
            $update->status = $request->status;
            $update->update();
            return response()->json([
                'status' => 'success',
                'message' => 'Warehouse Update Successful.'
            ]);
            // Session::flash('success', 'Warehouse Update Successful.');
            // return redirect()->route('admin.warehouse');
        }
    }
    public function delete_warehouse($id)
    {
        $delete = Warehouse::where('id',$id)->with('blocks')->first();
        if ($delete->blocks->count() > 0)
        {
            return redirect()->back();
        }
        else{
            $delete->delete();
            Session::flash('success', 'Warehouse Delete Successful.');
            return redirect()->back();
        }
    }
    public function add_room_block(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'block_code' => [
                'required',
                'string',
                Rule::unique('room_blocks')->where('warehouse_id', $id)
            ],
            'status' => ['required']
        ],[],[
            'block_code' => 'Block/Room Code',
            'status' => 'Status'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $add = new Room_Block();
            $add->warehouse_id = $id;
            $add->block_code = $request->block_code;
            $add->status = $request->status;
            $add->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Block/Room Added Successful.'
            ]);
        }
    }
    public function add_rack(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rack_code' => [
                'required',
                'string',
                Rule::unique('racks')
                    ->where('warehouse_id', $id)
                    ->where('block_id', $request->block_id)
            ],
            'block_id' => ['required'],
            'status' => ['required']
        ], [], [
            'rack_code' => 'Rack Code',
            'block_id' => 'Block/Room',
            'status' => 'Status'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $add = new Rack();
            $add->warehouse_id = $id;
            $add->block_id = $request->block_id;
            $add->rack_code = $request->rack_code;
            $add->status = $request->status;
            $add->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Rack Added Successful.'
            ]);
        }
    }
    public function show_racks_list($block_id)
    {
        $data = Rack::where('block_id',$block_id)->get();
        return response()->json($data);
    }
    public function add_shelf(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'block_id' => ['required'],
            'rack_id' => ['required'],
            'shelf_code' => [
                'required',
                'string',
                Rule::unique('shelf')
                    ->where('warehouse_id', $id)
                    ->where('block_id', $request->block_id)
                    ->where('rack_id', $request->rack_id)
            ],
            'status' => ['required']
        ], [], [
            'block_id' => 'Block/Room',
            'rack_id' => 'Rack',
            'shelf_code' => 'Shelf Code',
            'status' => 'Status'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $add = new Shelf();
            $add->warehouse_id = $id;
            $add->block_id = $request->block_id;
            $add->rack_id = $request->rack_id;
            $add->shelf_code = $request->shelf_code;
            $add->status = $request->status;
            $add->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Shelf Added Successful.'
            ]);
        }
    }
    public function edit_room_block($id)
    {
        $data = Room_Block::find($id);
        return response()->json($data);
    }
    public function update_room_block(Request $request)
    {
        $update = Room_Block::find($request->id);
        $update->status = $request->status;
        $update->update();
        return response()->json([
            'status'=>200,
            'message'=>"Block/Room Updated Successful.",
        ]);

    }
    public function delete_room_block($id)
    {
        $delete = Room_Block::where('id',$id)->with('count_rack')->first();
        if ($delete->count_rack->count() > 0)
        {
            return redirect()->back();
        }
        else{
            $delete->delete();
            // Session::flash('success', 'Block/Room Deleted Successful.');
            // return redirect()->back();
            return response()->json([
                'status' => 'success',
                'message' => "Block/Room Deleted Successful.",
            ]);
        }

    }
    public function edit_rack($id)
    {
        $data = Rack::find($id);
        return response()->json($data);
    }
    public function update_rack(Request $request)
    {
        $update = Rack::find($request->id);
        $update->status = $request->status;
        $update->update();
        return response()->json([
            'status'=>200,
            'message'=>"Rack Updated Successful.",
        ]);

    }
    public function delete_rack($id)
    {
        $delete =  Rack::where('id',$id)->with('count_shelf')->first();
        if ($delete->count_shelf->count() > 0)
        {
            return redirect()->back();
        }
        else{
            $delete->delete();
            // Session::flash('success', 'Rack Deleted Successful.');
            // return redirect()->back();
            return response()->json([
                'status' => 'success',
                'message' => "Rack Deleted Successful.",
            ]);
        }

    }
    public function edit_shelf($id)
    {
        $data = Shelf::find($id);
        return response()->json($data);
    }
    public function update_shelf(Request $request)
    {
        $update = Shelf::find($request->id);
        $update->status = $request->status;
        $update->update();
        return response()->json([
            'status'=>200,
            'message'=>"Shelf Updated Successful.",
        ]);

    }
    public function delete_shelf($id)
    {
        $delete = Shelf::where('id',$id)->with('count_product')->first();
        if ($delete->count_product->count() > 0)
        {
            return redirect()->back();
        }
        else{
            $delete->delete();
            // Session::flash('success', 'Shelf Deleted Successful.');
            // return redirect()->back();
            return response()->json([
                'status' => 'success',
                'message' => "Shelf Deleted Successful.",
            ]);
        }

    }
    public function warehouse_lang_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_name'=>['required','string'],
            'address'=>['required','string'],
            'lang_type'=>['required'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $data = [
                'name'=>$request->warehouse_name,
                'address'=>$request->address,
            ];
            $add = new Language_Meta();
            $add->reference_id = $request->ref_id;
            $add->reference_type = "warehouse";
            $add->language = $request->lang_type;
            $add->lang_data = json_encode($data);
            $add->save();
            return response()->json([
                'status'=>"pass",
                'message'=>"Language Added Successful.",
            ]);
        }
    }
    public function warehouse_lang_edit($id)
    {
        $data = Language_Meta::find($id);
        $decode_data = json_decode($data->lang_data);
        return response()->json(['id'=>$data,'data'=>$decode_data]);
    }
    public function warehouse_lang_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>['required','string'],
            'address'=>['required','string'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $data = [
                'name'=>$request->name,
                'address'=>$request->address,
            ];
            $update = Language_Meta::find($request->id);
            $update->lang_data = json_encode($data);
            $update->update();
            return response()->json([
                'status'=>"pass",
                'message'=>"Language Updated Successful.",
            ]);
        }
    }
    public function warehouse_lang_del($id)
    {
        $del = Language_Meta::find($id);
        $del->delete();
        return back();
    }

}













