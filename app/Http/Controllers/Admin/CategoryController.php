<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessModel;
use App\Models\Category;
use App\Models\Language_Meta;
use App\MyClasses\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::orderBy('category_name', 'ASC')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('Admin.categorylist', ['categories' => $categories, 'results' => $category]);
    }
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => ['required', 'string', 'unique:category'],
        ], [], [
            'category_name' => 'Category Name',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            if (!empty($request->parent_category)) {
                $add = new Category();
                $add->category_name = $request->category_name;
                $add->parent_id = $request->parent_category;
                $add->save();
                return response()->json([
                    'status' => "pass",
                    'message' => 'Your Sub Category has been successfully Created',
                ]);
            } else {
                $add = new Category();
                $add->category_name = $request->category_name;
                $add->save();
                return response()->json([
                    'status' => "pass",
                    'message' => 'Your Category has been successfully Created',
                ]);
            }
        }
    }
    public function update(Request $request)
    {
        if (!empty($request->parent_category)) {
            $update = Category::find($request->id);
            $update->parent_id = $request->parent_category;
            $update->update();
            return response()->json([
                'status' => "pass",
                'message' => 'Your Category has been successfully Updated',
            ]);
        } else {
            $update = Category::find($request->id);
            $update->parent_id = null;
            $update->update();
            return response()->json([
                'status' => "pass",
                'message' => 'Your Category has been successfully Updated',
            ]);
        }
    }
    public function add_sub(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => ['required', 'string', 'unique:category'],
        ], [], [
            'category_name' => 'Category Name',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $add = new Category();
            $add->category_name = $request->category_name;
            $add->parent_id = $request->id;
            $add->save();
            return response()->json([
                'status' => "pass",
                'message' => 'Your Sub Category has been successfully Created',
            ]);
        }
    }
    public function delete(Request $request)
    {
        $del = Category::find($request->id);
        $cat = Category::where('parent_id', $request->id)->whereNotNull('id')->get();
        if ($cat->isEmpty()) {
            $del->delete();
            return response()->json(['status' => 'success', 'message' => 'Category deleted successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'This category cannot be deleted because it is linked to subcategories.']);
        }

        $get = Category::where('parent_id', $request->id)->first();
        if (!empty($get)) {
            $get->parent_id = null;
            $get->update();
        }
    }
    public function business_model()
    {
        $results = BusinessModel::orderBy('id', 'desc')->get();
        return view('Admin.business_model', compact('results'));
    }
    public function business_model_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:business_models'],
            'status' => ['required'],
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
            if ($validator) {
                $new = new BusinessModel();
                $new->name = $request->name;
                $new->status = $request->status;
                $new->save();
                return response()->json([
                    'status' => "success",
                    'message' => 'Business Model Added Successful.',
                ]);
            }
        }

    }
    public function business_model_edit($id)
    {
        $data = BusinessModel::find($id);
        return response()->json($data);
    }
    public function business_model_update(Request $request)
    {
        $update = BusinessModel::find($request->id);
        $update->status = $request->status;
        $update->update();
        return response()->json([
            'status' => 200,
            'message' => "Business Model Updated Successful.",
        ]);

    }
    public function business_model_delete(Request $request)
    {
        $delete = BusinessModel::find($request->id);
        if ($delete) {
            $delete->delete();
            return response()->json(['status' => 'success', 'message' => 'Business Model deleted successfully.']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Business Model not found.']);
        }
    }

    public function business_model_lang_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_model' => ['required', 'string'],
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
            $add->reference_type = "business_model";
            $add->language = $request->lang_type;
            $add->lang_data = $request->business_model;
            $add->save();
            return response()->json([
                'status' => "pass",
                'message' => "Language Added Successful.",
            ]);
        }
    }
    public function business_model_lang_edit($id)
    {
        $data = Language_Meta::find($id);
        return response()->json($data);
    }
    public function business_model_lang_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_model' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $update = Language_Meta::find($request->id);
            $update->lang_data = $request->business_model;
            $update->update();
            return response()->json([
                'status' => "pass",
                'message' => "Language Updated Successful.",
            ]);
        }
    }
    public function business_model_lang_del($id)
    {
        $del = Language_Meta::find($id);
        $del->delete();
        return back();
    }

    public function category_lang_add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => ['required', 'string'],
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
            $add->reference_type = "category";
            $add->language = $request->lang_type;
            $add->lang_data = $request->category_name;
            $add->save();
            return response()->json([
                'status' => "pass",
                'message' => "Language Added Successful.",
            ]);
        }
    }
    public function category_lang_list(Request $request)
    {
        $langs = Helpers::act_lang($request->ref_id, $request->ref_type);
        $view = view('Admin.temp.langs', compact('langs'))->render();
        return response()->json($view);
    }
    public function category_lang_edit($id)
    {
        $data = Language_Meta::find($id);
        return response()->json($data);
    }
    public function category_lang_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $update = Language_Meta::find($request->id);
            $update->lang_data = $request->category_name;
            $update->update();
            return response()->json([
                'status' => "pass",
                'message' => "Language Updated Successful.",
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
