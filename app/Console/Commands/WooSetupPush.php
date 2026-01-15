<?php

namespace App\Console\Commands;

use App\Models\CronJob;
use App\Models\SellerApi;
use App\Models\Woo_Setup;
use App\Models\Woo_shipping_cost;
use App\Models\Woo_Shipping_Zone;
use App\Models\Woo_Tax_Setup;
use Automattic\WooCommerce\Client;
use Illuminate\Console\Command;

class WooSetupPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'woo:setup_push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $jobs = CronJob::where('job_type', "woo_setup_push")
            ->where('job_status', "Pending")->limit(2)->get();
        foreach ($jobs as $job) {
            $user = $job->user_id;
            /*woo config*/
            $api = SellerApi::where('user_id', $user)->first();
            $api_details = json_decode($api->woo_details);
            $woo_store = new Client(
                $api_details->domain_url,
                decrypt($api_details->consumer_key),
                decrypt($api_details->consumer_secret),
                [
                    'wp_api' => true,
                    'version' => 'wc/v3',
                ]
            );
            $tax_lists = Woo_Tax_Setup::with('tax_name')->get();
            $classes_name = array();
            $shipping_zone_ids = array();
            $shipping_method_ids = array();
            $shipping_class_ids = array();
            $classes_rates = array();
            foreach ($tax_lists as $list) {
                $class_data = [
                    'name' => $list->tax_class_name
                ];
                $tax_class = $woo_store->post('taxes/classes', $class_data);
                $class_rate = [
                    'rate' => $list->tax_name[0]->percent,
                    'name' => $list->tax_name[0]->name,
                    'class' => $tax_class->slug,
                    'shipping' => false
                ];
                $tax_rate = $woo_store->post('taxes', $class_rate);
                $classes_rates[] = $tax_rate->id;
                $classes_name[] = $tax_class->name;
            }

            $zone_name = Woo_Shipping_Zone::all();
            foreach ($zone_name as $name)
            {
                $data = [
                    'name' => $name->shipping_zone,
                ];
                $out = $woo_store->post('shipping/zones',$data);
                $shipping_zone_ids[] = $out->id;
                $js_de = json_decode($name->zone_region);
                $region = array();
                foreach ($js_de as $single)
                {
                    $exp = explode('|',$single);
                    if ($exp[1] == "state")
                    {
                        $arr = [
                            'code'=> $exp[2].":".$exp[0],
                            'type'=> $exp[1],
                        ];
                    }
                    else{
                        $arr = [
                            'code'=> $exp[0],
                            'type'=> $exp[1],
                        ];
                    }
                    $region[] = $arr;
                }

                $woo_store->post('shipping/zones/'.$out->id.'/locations',$region);
                $method = [
                    'method_id' => 'flat_rate',
                ];
                $x = $woo_store->post('shipping/zones/'.$out->id.'/methods',$method);
                $shipping_method_ids[] = $x->id;
                $get = Woo_shipping_cost::where('shipping_zone_id',$name->id)->with('get_method','get_class')->first();
                $class_arr = [
                    'name'=>$get->get_class[0]->shipping_class,
                ];
                $class = $woo_store->post('products/shipping_classes',$class_arr);
                $shipping_class_ids[] = $class->id;
                $key = 'class_cost_'.$class->id;
                $data = [
                    'settings' => [
                        'title'=>   $get->get_method[0]->shipping_method,
                        $key => $get->shipping_cost
                    ]
                ];
                $woo_store->put('shipping/zones/'.$out->id.'/methods/'.$x->id,$data);
            }
            $add_classes = new Woo_Setup();
            $add_classes->user_id = $user;
            $add_classes->tax_class_name = json_encode($classes_name);
            $add_classes->tax_class_rates = json_encode($classes_rates);
            $add_classes->shipping_zone_ids = json_encode($shipping_zone_ids);
            $add_classes->shipping_method_ids = json_encode($shipping_method_ids);
            $add_classes->shipping_class_ids = json_encode($shipping_class_ids);
            $add_classes->save();

            $job->job_status = "Successful";
            $job->update();
        }

    }
}
