<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewSellerCities;
use App\Models\SellerCity;
use App\Models\shipping;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Notifications\MyNotification;
use PhpOffice\PhpSpreadsheet\IOFactory;
use function PHPUnit\Framework\isEmpty;
use Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class CityMappingController extends Controller
{
    public function admin_cities()
    {
        $user_id = Auth::id();
        $admin_city = shipping::all();
        $seller_city = SellerCity::where('seller_id', $user_id)->get();
        return view('Seller.admin_cities', ['admin_citys' => $admin_city, 'seller_citys' => $seller_city]);
    }
    public function Mapping_view()
    {
        return view('Seller.city_mapping');
    }

    // public function download_city_file()
    // {
    //     ini_set('memory_limit', '2048M');
    //     set_time_limit(0);
    //     ob_end_clean();
    //     ob_start();
    //     $filePath = 'seller_cities.xlsx';
    //     $headers = [
    //         'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //     ];
    //     return response()->download(Storage::path($filePath), 'seller_cities.xlsx', $headers);
    // }




    public function download_city_file()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0);
        ob_end_clean();
        ob_start();
        // Load the existing Excel file
        $filePath = 'seller_cities.xlsx';
        $spreadsheet = IOFactory::load(Storage::path($filePath));

        // Get the active worksheet (assuming you have only one worksheet)
        $worksheet = $spreadsheet->getActiveSheet();

        // Insert data into the 3rd row
        $datas = shipping::with('get_seller_cities')->get();
        $row = 3; // 3rd row
        foreach ($datas as $data) {
            if (isset($data->get_seller_cities[0])) {
                $worksheet->setCellValue('A' . $row, $data->id);
                $worksheet->setCellValue('B' . $row, $data->our_system_cities);
                $worksheet->setCellValue('C' . $row, $data->get_seller_cities[0]->seller_city_name);

                // Move to the next row
                $row++;
            }
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
                'Content-Disposition' => 'attachment; filename="seller_cities.xlsx"',
            ]
        );
    }

    public function add_city_file(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xls,xlsx',
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
                $row_range = range(2, $row_limit);
                $column_range = range('B', $column_limit);
                if (strtoupper($sheet->getCell('B2')->getValue()) == strtoupper("Admin Cities") && strtoupper($sheet->getCell('C2')->getValue()) == strtoupper("Seller Cities")) {
                    foreach ($row_range as $row) {
                        $value = $sheet->getCell('B' . $row)->getValue();
                        $shipping = shipping::where('our_system_cities', $value)->first();
                        if (!empty($shipping)) {
                            $name = $sheet->getCell('C' . $row)->getValue();
                            if (!empty($name)) {
                                $user = Auth::user()->id;
                                $seller_data = SellerCity::where('admin_city_id', $shipping->id)->where('seller_id', $user)->first();
                                if ($seller_data == null) {
                                    $seller = new SellerCity();
                                    $seller->admin_city_id = $shipping->id;
                                    $seller->seller_city_name = $name;
                                    $seller->seller_id = $user;
                                    $seller->save();
                                } else {
                                    continue;
                                }
                            }
                        }
                    }
                    // Session::flash('success', 'File Imported Successfully.');
                    // return back();
                    return response()->json([
                        'status' => 'success',
                        'message' => "File Imported Successfully.",
                    ]);
                } else {
                    // Session::flash('danger', 'Please Correct your File Format.');
                    // return back();
                    return response()->json([
                        'status' => 'danger',
                        'message' => "Please Correct your File Format.",
                    ]);
                }
            }
        }
    }
    public function our_city()
    {
        $datas = shipping::with('get_seller_cities')->get();
        return view('Seller.our_cities', ['datas' => $datas]);
    }
    public function get_city(Request $request)
    {
        $city_names = array();
        foreach ($request->id as $id) {
            $city_data = shipping::where('id', $id)->first();
            $city_names[] = $city_data->our_system_cities;
        }
        return response()->json([
            'city_name' => $city_names,
        ]);
    }
    public function city_add(Request $request)
    {
        $ids = explode(',', $request->city_id[0]);
        $user_id = Auth::id();
        if (!empty($ids)) {
            foreach ($ids as $id) {
                $old_data = NewSellerCities::where('city_id', $id)->where('seller_id', $user_id)->get();
                if (!$old_data->isEmpty()) {
                    Session::flash('danger', 'Cities Existed.');
                    return back();
                } else {
                    $add = new NewSellerCities();
                    $add->city_id = $id;
                    $add->seller_id = $user_id;
                    $add->save();

                    $notification = [
                        'type' => 'Seller City',
                        'id' => $user_id,
                    ];
                    $users = User::where('role', 'SuperAdmin')->get();
                    foreach ($users as $user) {
                        $user->notify(new MyNotification($notification));
                    }
                }
            }
            Session::flash('success', 'Cities added Successful.');
            return back();
        }
    }

    public function add_city_files_dummy(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xls,xlsx'
        ]);
        $the_file = $request->file('excel_file');
        if ($the_file) {
            $spreadsheet = IOFactory::load($the_file->getRealPath());
            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('B', $column_limit);
            if (strtoupper($sheet->getCell('B2')->getValue()) == strtoupper("Admin Cities") && strtoupper($sheet->getCell('C2')->getValue()) == strtoupper("Seller Cities")) {
                foreach ($row_range as $row) {
                    $value = $sheet->getCell('B' . $row)->getValue();
                    $shipping = shipping::where('our_system_cities', $value)->first();
                    if (!empty($shipping)) {
                        $name = $sheet->getCell('C' . $row)->getValue();
                        if (!empty($name)) {
                            $user = Auth::user()->id;
                            $seller_data = SellerCity::where('admin_city_id', $shipping->id)->where('seller_id', $user)->first();
                            if ($seller_data == null) {
                                $seller = new SellerCity();
                                $seller->admin_city_id = $shipping->id;
                                $seller->seller_city_name = $name;
                                $seller->seller_id = $user;
                                $seller->save();
                            } else {
                                continue;
                            }
                        }
                    }
                }
                Session::flash('success', 'File Imported Successfully.');
                return back();
            } else {
                Session::flash('danger', 'Please Correct your File Format.');
                return back();
            }
        }
    }
}
