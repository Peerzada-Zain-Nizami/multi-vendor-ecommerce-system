<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class FreePlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plan::create([
            'name' => 'Free',
            'plan_price' => '{"Monthly":"0","Yearly":"0"}',
            'listing_product' => 0,
            'push_product' => '{"push_product_by_hour":"0","push_product_by_day":"0"}',
            'plateform_sync' => 0,
            'product_price' => NULL,
            'shipping_price' => '{"discount":"0","method":"percentage"}',
            'order_cancellation' => '{"discount":"0","method":"percentage"}',
            'currency' => NULL,
            'status' => 'Active',
        ]);
    }
}
