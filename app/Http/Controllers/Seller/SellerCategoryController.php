<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Language_Meta;
use App\Models\SellerCategory;
use App\MyClasses\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SellerCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user()->id;
        $category = SellerCategory::where('user_id',$user)->orderBy('category_name','ASC')->get();
        $categories = SellerCategory::where('user_id',$user)->whereNull('parent_id')->with('children')->get();
        return view('Seller.my_categories', ['categories'=>$categories,'results'=>$category]);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name'=>['required','string','unique:category'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            if (!empty($request->parent_category))
            {
                $add = new SellerCategory();
                $add->user_id = Auth::user()->id;
                $add->category_name = $request->category_name;
                $add->parent_id = $request->parent_category;
                $add->save();
                return response()->json([
                    'status'=>"pass",
                    'message'=>'Your Sub Category has been successfully Created',
                ]);
            }
            else{
                $add = new SellerCategory();
                $add->user_id = Auth::user()->id;
                $add->category_name = $request->category_name;
                $add->save();
                return response()->json([
                    'status'=>"pass",
                    'message'=>'Your Category has been successfully Created',
                ]);
            }
        }
    }
    public function update(Request $request)
    {
        if (!empty($request->parent_category))
        {
            $update = SellerCategory::find($request->id);
            $update->parent_id = $request->parent_category;
            $update->update();
            return response()->json([
                'status'=>"pass",
                'message'=>'Your Category has been successfully Updated',
            ]);
        }
        else{
            $update = SellerCategory::find($request->id);
            $update->parent_id = null;
            $update->update();
            return response()->json([
                'status'=>"pass",
                'message'=>'Your Category has been successfully Updated',
            ]);
        }
    }
    public function add_sub(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name'=>['required','string','unique:category'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $add = new SellerCategory();
            $add->user_id = Auth::user()->id;
            $add->category_name = $request->category_name;
            $add->parent_id = $request->id;
            $add->save();
            return response()->json([
                'status'=>"pass",
                'message'=>'Your Sub Category has been successfully Created',
            ]);
        }
    }
    public function delete(Request $request)
    {
        $del = SellerCategory::find($request->id);
        $cat = SellerCategory::where('parent_id', $request->id)->whereNotNull('id')->get();
        if ($cat->isEmpty()) {
            $del->delete();
            return response()->json(['status' => 'success', 'message' => 'Category deleted successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'This category cannot be deleted because it is linked to subcategories.']);
        }
        $get = SellerCategory::where('parent_id',$request->id)->first();
        if (!empty($get))
        {
            $get->parent_id = null;
            $get->update();
        }
    }
    public function category_lang_list(Request $request)
{
    $langs = Helpers::act_lang($request->ref_id,$request->ref_type);
    $view = view('Seller.temp.langs',compact('langs'))->render();
    return response()->json($view);
}
    public function category_lang_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name'=>['required','string'],
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
            $add = new Language_Meta();
            $add->reference_id = $request->ref_id;
            $add->reference_type = "seller_category";
            $add->language = $request->lang_type;
            $add->lang_data = $request->category_name;
            $add->save();
            return response()->json([
                'status'=>"pass",
                'message'=>"Language Added Successful.",
            ]);
        }
    }
    public function category_lang_edit($id)
    {
        $data = Language_Meta::find($id);
        return response()->json($data);
    }
    public function category_lang_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name'=>['required','string'],
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $update = Language_Meta::find($request->id);
            $update->lang_data = $request->category_name;
            $update->update();
            return response()->json([
                'status'=>"pass",
                'message'=>"Language Updated Successful.",
            ]);
        }
    }
    public function category_lang_del($id)
    {
        $del = Language_Meta::find($id);
        $del->delete();
        return back();
    }
}
