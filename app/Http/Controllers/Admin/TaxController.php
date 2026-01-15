<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language_Meta;
use App\Models\Product;
use App\Models\Tax;
use App\Models\Woo_Tax_Setup;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class TaxController extends Controller
{
    public function index()
    {
        $results = Tax::all();
        return view('Admin.tax_manage', compact('results'));
    }
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:taxes'],
            'percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required'],
        ], [], [
            'name' => 'Name',
            'percent' => 'Percentage',
            'status' => 'Status',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $new = new Tax();
            $new->name = $request->name;
            $new->percent = $request->percent;
            $new->status = $request->status;
            $new->save();
            return response()->json([
                'status' => "success",
                'message' => 'Tax Added Successfully.',
            ]);
        }
       
    }
    public function edit($id)
    {
        $data = Tax::find($id);
        return response()->json($data);
    }
    public function update(Request $request)
    {
        $update = Tax::find($request->id);
        $validator = Validator::make($request->all(), [
            'percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'status' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ]);
        } else {
            $update->status = $request->status;
            $update->percent = $request->percent;
            $update->update();
            return response()->json([
                'status' => 200,
                'message' => "Tax Updated Successful.",
            ]);
        }
    }
    public function delete(Request $request)
    {
        $delete = Tax::find($request->id);

        if (!$delete) {
            return response()->json(['status' => 'error', 'message' => 'Tax not found.']);
        }
        $products = Product::whereJsonContains('taxes', $request->id)->get();
        if ($products->isEmpty()) {
            $delete->delete();
            $woo_tax = Woo_Tax_Setup::where('tax_id', $request->id)->first();

            if ($woo_tax) {
                $woo_tax->delete();
            }

            return response()->json(['status' => 'success', 'message' => 'Tax deleted successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Cannot delete this tax. It is associated with products.']);
        }
    }

    public function tax_lang_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_name' => ['required', 'string'],
            'lang_type' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $add = new Language_Meta();
            $add->reference_id = $request->ref_id;
            $add->reference_type = "tax";
            $add->language = $request->lang_type;
            $add->lang_data = $request->tax_name;
            $add->save();
            return response()->json([
                'status' => "pass",
                'message' => "Language Added Successful.",
            ]);
        }
    }
    public function tax_lang_edit($id)
    {
        $data = Language_Meta::find($id);
        return response()->json($data);
    }
    public function tax_lang_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tax_name' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $update = Language_Meta::find($request->id);
            $update->lang_data = $request->tax_name;
            $update->update();
            return response()->json([
                'status' => "pass",
                'message' => "Language Updated Successful.",
            ]);
        }
    }
    public function tax_lang_del($id)
    {
        $del = Language_Meta::find($id);
        $del->delete();
        return back();
    }
}
