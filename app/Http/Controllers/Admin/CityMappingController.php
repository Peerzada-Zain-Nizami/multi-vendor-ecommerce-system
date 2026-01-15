<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group_city;
use App\Models\SellerCity;
use App\Models\ShippingCompany;
use App\Models\ShippingGroup;
use App\Models\ShippingPrice;
use Session;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\shipping;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


class CityMappingController extends Controller
{
    public function download_city_file()
        {
            ini_set('memory_limit', '2048M');
            set_time_limit(0);
            ob_end_clean();
            ob_start();

            // Load the existing Excel file
            $filePath = 'Import_cities.xlsx';
            $spreadsheet = IOFactory::load(Storage::path($filePath));

            // Get the active worksheet (assuming you have only one worksheet)
            $worksheet = $spreadsheet->getActiveSheet();

            // Insert data into the 3rd row
            $shippingData = Shipping::all();
            $row = 3; // 3rd row

            foreach ($shippingData as $data) {
                $worksheet->setCellValue('A' . $row, $data->id);
                $worksheet->setCellValue('B' . $row, $data->our_system_cities);
                $worksheet->setCellValue('C' . $row, $data->SMSA_cities);

                // Move to the next row
                $row++;
            }

            // Save the modified spreadsheet
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            return response()->stream(
                function () use ($writer) {
                    $writer->save('php://output');
                },
                200,
                [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Content-Disposition' => 'attachment; filename="Import_cities.xlsx"',
                ]
            );

        }

    public function import_city()
    {
        
        return view('Admin.import_file');
    }
    public function add_city_file(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xls,xlsx'
        ], [], [
            'excel_file' => 'Excel File',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {
            $the_file = $request->file('excel_file');
            if ($the_file) {
                $spreadsheet = IOFactory::load($the_file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $row_limit = $sheet->getHighestDataRow();
                $column_limit = $sheet->getHighestDataColumn();
                $row_range = range(3, $row_limit);
                $column_range = range('B', $column_limit);
                if (strtoupper($sheet->getCell('B2')->getValue()) == strtoupper("System Cities") && strtoupper($sheet->getCell('C2')->getValue()) == strtoupper("SMSA Cities")) {
                    foreach ($row_range as $row) {
                        $value = $sheet->getCell('B' . $row)->getValue();
                        $shipping = shipping::where('our_system_cities', $value)->first();
                        if (!empty($shipping)) {
                            $name = $sheet->getCell('C' . $row)->getValue();
                            if (!empty($name)) {
                                if (empty($shipping->SMSA_cities)) {
                                    $shipping->SMSA_cities = $name;
                                    $shipping->save();
                                }
                            }
                        } else {
                            $add = new shipping();
                            $add->our_system_cities = $value;
                            $name = $sheet->getCell('C' . $row)->getValue();
                            if (!empty($name)) {
                                $add->SMSA_cities = $name;
                            }
                            $add->save();
                        }
                    }
                    return response()->json([
                        'status' => 'success',
                        'message' => 'File Imported Successfully.'
                    ]);
                } else {
                    return response()->json([
                        'status' => 'danger',
                        'message' => "Warning! Don't change the file format. Please Correct your File Format."
                    ]);
                }
            }
        }
    }

    //City Mapping Section
    public function my_cities()
    {
        $datas =  shipping::all();
        return view('Admin.our_cities',['datas'=>$datas]);
    }
    public function cities_prices()
    {
        $city_datas =  DB::table('shippings AS t1')
            ->select('t1.id','t1.our_system_cities')
            ->leftJoin('group_cities AS t2','t2.city_id','=','t1.id')
            ->whereNull('t2.city_id')->get();

        $datas =  shipping::all();
        $shipping_groups = ShippingGroup::with('shipping_cities','group_cities')->get();
        $allCitiesSelected = $city_datas->isEmpty();
        return view('Admin.city_price',['city_datas'=>$city_datas,'datas'=>$datas,'shipping_groups'=>$shipping_groups,'allCitiesSelected'=>$allCitiesSelected]);
    }
    public function add_group(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_name'=>['required','string','unique:shipping_groups,name'],
        ], [], [
            'group_name' => 'Group Name',
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $add = new ShippingGroup();
            $add->name = $request->group_name;
            $add->save();
            return response()->json([
                'status'=>"pass",
                'message'=>'Your Group has been successfully Created',
            ]);
        }
    }
    public function add_group_price(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id'=>['required','string'],
            'shipping_company'=>['required'],
            'shipping_price'=>['required'],
            'return_price'=>['required'],
        ], [], [
            'group_id' => 'Group Name',
            'shipping_price' => 'Shipping Price',
            'shipping_group' => 'Shipping Group',
            'shipping_company' => 'Shipping Company',
            'return_price' => 'Return Price',
        ]);

        $shipping_price = ShippingPrice::where('group_id',$request->group_id)->where('shipping_company',$request->shipping_company)->first();
        if (!empty($shipping_price))
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>["Data already exist."],
            ]);
        }
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $add = new ShippingPrice();
            $add->group_id = $request->group_id;
            $add->shipping_company = $request->shipping_company;
            $add->price = $request->shipping_price;
            $add->return_price = $request->return_price;
            $add->save();
            return response()->json([
                'status'=>"pass",
                'message'=>'Your Group has been successfully Created',
            ]);
        }
    }
    public function edit($id)
    {
        $groups = ShippingGroup::all();
        $shipping_price = ShippingPrice::where('group_id',$id)->first();
        $shipping_company = ShippingCompany::all();
        return response()->json([
            'status'=>"pass",
            'group'=>$groups,
            'shipping_price'=>$shipping_price,
            'shipping_company'=>$shipping_company,
            'message'=>'Your Group has been successfully Created',
        ]);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=>['required'],
            'shipping_price'=>['required'],
            'shipping_group'=>['required'],
            'shipping_company'=>['required'],
            'return_price'=>['required'],
        ], [], [
            'shipping_price' => 'Shipping Price',
            'shipping_group' => 'Shipping Group',
            'shipping_company' => 'Shipping Company',
            'return_price' => 'Return Price',
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            $update = ShippingPrice::find($request->id);
            $update->group_id = $request->shipping_group;
            $update->price = $request->shipping_price;
            $update->return_price = $request->return_price;
            $update->shipping_company = $request->shipping_company;
            $update->update();
            return response()->json([
                'status'=>"pass",
                'message'=>'Your Data has been Updated Successfully',
            ]);
        }
    }
    public function delete($id)
    {
        $group = ShippingGroup::find($id);
        $shipping_price = ShippingPrice::where('group_id',$group->id)->first();
        $group_citys = Group_city::where('group_id',$group->id)->get();
        $group->delete();
        $shipping_price->delete();
        foreach ($group_citys as $group_city)
        {
            $group_city->delete();
        }
        // Session::flash('success', 'Deleted Successful.');
        // return back();

        return response()->json([
            'status' => "success",
            'message' => 'Deleted Successful.',
        ]);
    }
    public function add_cities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'group_id'=>['required','string'],
            'group_cities'=>['required','unique:group_cities,city_id']
        ], [], [
            'group_id' => 'Group Name',
            'group_cities' => 'Seller Cities',
        ]);
        if ($validator->fails())
        {
            return response()->json([
                'status'=>"fail",
                'errors'=>$validator->errors(),
            ]);
        }
        else{
            foreach ($request->group_cities as $city)
            {
                $add = new Group_city();
                $add->group_id = $request->group_id;
                $add->city_id = $city;
                $add->save();
            }
            return response()->json([
                'status'=>"pass",
                'message'=>'Your Group has been successfully Created',
            ]);
        }
    }
    public function delete_group_cities(Request $request)
        {
            $groupId = $request->group_id;
            $cityId = $request->city_id;
            // return response()->json(['Group Id' => $groupId, 'City Id' => $cityId]);

            $groupCity = Group_city::where('group_id', $groupId)->where('city_id', $cityId)->first();

            if ($groupCity) {
                $groupCity->delete(); // Delete the record
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false]);
        }

    /*public function get_group_cities(Request $request)
    {
        $S_datas = ShippingPrice::where('group_id',$request->id)->with('shipping_cities')->get();
        $datas = array();
        foreach ($S_datas as $data)
        {
            $datas[] = ['city_name'=>];
        }
            return response()->json([
                'status'=>"pass",
                'data'=>$datas,
                'message'=>'Your Group has been successfully Created',
            ]);
    }*/
    public function add_city_files_dummy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xls,xlsx'
        ], [], [
            'excel_file' => 'Excel File'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => "fail",
                'errors' => $validator->errors(),
            ]);
        } else {

            $the_file = $request->file('excel_file');
            if ($the_file) {
                $spreadsheet = IOFactory::load($the_file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $row_limit = $sheet->getHighestDataRow();
                $column_limit = $sheet->getHighestDataColumn();
                $row_range = range(2, $row_limit);
                $column_range = range('B', $column_limit);
                if (strtoupper($sheet->getCell('B2')->getValue()) == strtoupper("System Cities") && strtoupper($sheet->getCell('C2')->getValue()) == strtoupper("SMSA Cities")) {
                    foreach ($row_range as $row) {
                        $value = $sheet->getCell('B' . $row)->getValue();
                        $shipping = shipping::where('our_system_cities', $value)->first();
                        if (!empty($shipping)) {
                            $name = $sheet->getCell('C' . $row)->getValue();
                            if (!empty($name)) {
                                $shipping->SMSA_cities = $name;
                                $shipping->save();
                            }
                        }
                    }
                    return redirect()->back()->with('success', 'File Imported Successfully.');
                } elseif (strtoupper($sheet->getCell('C2')->getValue()) == strtoupper("System Cities") && strtoupper($sheet->getCell('B2')->getValue()) == strtoupper("SMSA Cities")) {
                    foreach ($row_range as $row) {
                        $value = $sheet->getCell('C' . $row)->getValue();
                        $shipping = shipping::where('our_system_cities', $value)->first();
                        if (!empty($shipping)) {
                            $name = $sheet->getCell('B' . $row)->getValue();
                            if (!empty($name)) {
                                $shipping->SMSA_cities = $name;
                                $shipping->save();
                                SessiUon::flash('success', 'File Imported Successfully.');
                                return back();
                            }
                        }
                    }
                } else {
                    Session::flash('danger', 'Please Correct your File Format.');
                    return back();
                }
            }
        }
    }

    public function city_mapping()
    {
        $datas =  shipping::all();
        return view('Admin.city_mapping',['datas'=>$datas]);
    }
    public function seller_cities()
    {
        $new_sellers = User::where('role','Seller')->with('get_seller_cities')->get();
        return view('Admin.seller_cities',['new_sellers'=>$new_sellers]);
    }
    public function seller_cities_view($id)
    {
        $admin_cities = shipping::all();
        $seller_cities = SellerCity::where('seller_id',$id)->get();
        return view('Admin.seller_cities_view',['admin_cities'=>$admin_cities,'seller_cities'=>$seller_cities]);
    }

}

