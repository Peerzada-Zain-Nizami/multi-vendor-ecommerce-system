<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Language_Meta;
use App\Models\SellerTag;
use App\MyClasses\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Session;

class SellerTagsController extends Controller
{
    public function tags_list()
    {
        $user = Auth::user()->id;
        $results = SellerTag::where('user_id', $user)->orderBy('id', 'desc')->get();
        return view('Seller.tags', compact('results'));
    }
    public function tag_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'status' => 'required',
        ], [], [
            'name' => 'Name',
            'status' => 'Status',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $new = new SellerTag();
            $new->user_id = Auth::user()->id;
            $new->name = $request->name;
            $new->status = $request->status;
            $new->save();
            // Session::flash('success', 'New Tag Added Successful.');
            // return back();
            return response()->json([
                'status' => 'success',
                'message' => "New Tag Added Successful.",
            ]);
        }
    }
    public function tag_edit($id)
    {
        $data = SellerTag::find($id);
        return response()->json($data);
    }
    public function tag_update(Request $request)
    {
        $update = SellerTag::find($request->id);
        $update->status = $request->status;
        $update->update();
        return response()->json([
            'status' => 200,
            'message' => "Tag Updated Successful.",
        ]);
    }
    public function tag_delete(Request $request)
    {
        $delete = SellerTag::find($request->id);
        $delete->delete();
        // Session::flash('success', 'Tag Deleted Successful.');
        // return back();
        return response()->json([
            'status' => 'success',
            'message' => "Tag Deleted Successful.",
        ]);
    }

    public function get_languages(Request $request)
    {
        return Helpers::rem_lang($request->ref_id, $request->ref_type);
    }
    public function tag_lang_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tag_name' => ['required', 'string'],
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
            $add->reference_type = "Seller_tag";
            $add->language = $request->lang_type;
            $add->lang_data = $request->tag_name;
            $add->save();
            return response()->json([
                'status' => "pass",
                'message' => "Language Added Successful.",
            ]);
        }
    }
    public function tag_lang_edit($id)
    {
        $data = Language_Meta::find($id);
        return response()->json($data);
    }
    public function tag_lang_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tag_name' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $update = Language_Meta::find($request->id);
            $update->lang_data = $request->tag_name;
            $update->update();
            return response()->json([
                'status' => "pass",
                'message' => "Language Updated Successful.",
            ]);
        }
    }
    public function tag_lang_del($id)
    {
        $del = Language_Meta::find($id);
        $del->delete();
        return back();
    }
}
