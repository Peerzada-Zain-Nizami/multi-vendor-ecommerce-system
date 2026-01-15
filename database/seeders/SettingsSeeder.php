<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\shipping_company;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lang = ["en","ar"];
        $settings = array();
        $settings[]= ['option_name'=>"paypal_deposit_fees",'option_value'=>'0',"created_at" =>  date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $settings[]= ['option_name'=>"usd_to_sar",'option_value'=>'0',"created_at" =>  date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $settings[]= ['option_name'=>"paypal_withdraw_fee",'option_value'=>'0',"created_at" =>  date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $settings[]= ['option_name'=>"sar_to_usd",'option_value'=>'0',"created_at" =>  date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        $settings[]= ['option_name'=>"languages",'option_value'=>json_encode($lang),"created_at" =>  date('Y-m-d H:i:s'),"updated_at" => date('Y-m-d H:i:s')];
        foreach ($settings as $setting)
        {
            DB::table('settings')
                ->insert($setting);
        }

        $shipping_company = ["SMSA"];
        foreach ($shipping_company as $company)
        {
            $add = new shipping_company();
            $add->name = $company;
            $add->save();
        }
    }
}
