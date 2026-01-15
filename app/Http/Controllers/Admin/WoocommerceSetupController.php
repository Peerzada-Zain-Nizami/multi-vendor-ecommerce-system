<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tax;
use App\Models\Woo_Continent;
use App\Models\Woo_shipping_cost;
use App\Models\Woo_shipping_method;
use App\Models\woo_shipping_setups;
use App\Models\Woo_Shipping_Zone;
use App\Models\Woo_Tax_Setup;
use Illuminate\Http\Request;
use Session;


class WoocommerceSetupController extends Controller
{
    public function index()
    {
        $taxs = Tax::where('status','Active')->get();
        $woo_taxs = Woo_Tax_Setup::with('tax_name')->get();
        $woo_ships = woo_shipping_setups::all();
        $woo_contients = Woo_Continent::with('get_country')->get();
        $woo_shipping_zone = Woo_Shipping_Zone::all();
        $woo_shipping_method = Woo_shipping_method::all();
        $woo_shipping_cost = Woo_shipping_cost::all();

        return view('Admin.woocommerce_setup',['taxs'=>$taxs,'woo_taxs'=>$woo_taxs,'woo_ships'=>$woo_ships,'woo_contients'=>$woo_contients,'woo_shipping_zone'=>$woo_shipping_zone,'woo_shipping_methods'=>$woo_shipping_method,'woo_shipping_costs'=>$woo_shipping_cost]);
    }
    public function add_tax_class(Request $request)
    {
        $request->validate([
            'tax_class_name' => ['required','string','unique:woo__tax__setups'],
            'tax_id' => ['required'],
        ]);
        $add = new Woo_Tax_Setup();
        $add->tax_class_name = $request->tax_class_name;
        $add->tax_id = $request->tax_id;
        $add->save();
        Session::flash('success', 'Tax Class added Successful.');
        return back();
    }
    public function delete_tax_class($id)
    {
        $del = Woo_Tax_Setup::find($id);
        $del->delete();
        Session::flash('success', 'Tax Class deleted Successful.');
        return redirect()->back();
    }

    public function add_shipping_class(Request $request)
    {
        $request->validate([
            'shipping_class' => ['required','string','unique:woo_shipping_setups'],
        ]);
        $add = new woo_shipping_setups();
        $add->shipping_class = $request->shipping_class;
        $add->save();
        Session::flash('success', 'Shipping Class added Successful.');
        return back();
    }
    public function delete_shipping_class($id)
    {
        $del = woo_shipping_setups::where('id',$id)->first();
        $del->delete();
        $woo_cost = Woo_shipping_cost::where('shipping_class_id',$id)->first();
        if ($woo_cost)
        {
            $woo_cost->delete();
        }
        Session::flash('success', 'Shipping Class deleted Successful.');
        return redirect()->back();
    }

    public function add_shipping_zone(Request $request)
    {
        $request->validate([
            'shipping_zone' => ['required','string','unique:woo__shipping__zones'],
            'zone_region' => ['required'],
        ]);
        $add = new Woo_Shipping_Zone();
        $add->shipping_zone = $request->shipping_zone;
        $add->zone_region = json_encode($request->zone_region);
        $add->save();
        Session::flash('success', 'Shipping Zone added Successful.');
        return back();
    }
    public function delete_shipping_zone($id)
    {
        $del = Woo_Shipping_Zone::where('id',$id)->first();
        $del->delete();
        $woo_cost = Woo_shipping_cost::where('shipping_zone_id',$id)->first();
        if ($woo_cost)
        {
            $woo_cost->delete();
        }
        $woo_cost = Woo_shipping_method::where('hipping_zone_id',$id)->first();
        if ($woo_cost)
        {
            $woo_cost->delete();
        }
        Session::flash('success', 'Shipping Zone deleted Successful.');
        return redirect()->back();
    }

    public function add_shipping_zone_method(Request $request)
    {
        $request->validate([
            'shipping_method' => ['required','string','unique:woo_shipping_methods'],
            'shipping_zone_id' => ['required'],
        ]);
        $add = new Woo_shipping_method();
        $add->shipping_method = $request->shipping_method;
        $add->hipping_zone_id = $request->shipping_zone_id;
        $add->save();
        Session::flash('success', 'Shipping Method added Successful.');
        return back();
    }
    public function delete_shipping_zone_method($id)
    {
        $del = Woo_shipping_method::where('id',$id)->first();
        $del->delete();
        $woo_cost = Woo_shipping_cost::where('shipping_method_id',$id)->first();
        if ($woo_cost)
        {
            $woo_cost->delete();
        }
        Session::flash('success', 'Shipping Method deleted Successful.');
        return redirect()->back();
    }

    public function add_shipping_cost(Request $request)
    {
        $request->validate([
            'shipping_zone_cost_id' => ['required','string'],
            'shipping_method_id' => ['required'],
            'shipping_class_id' => ['required'],
            'shipping_cost' => ['required'],
        ]);
        $add = new Woo_shipping_cost();
        $add->shipping_zone_id = $request->shipping_zone_cost_id;
        $add->shipping_method_id = $request->shipping_method_id;
        $add->shipping_class_id = $request->shipping_class_id;
        $add->shipping_cost = $request->shipping_cost;
        $add->save();
        Session::flash('success', 'Shipping Method added Successful.');
        return back();
    }
    public function delete_shipping_cost($id)
    {
        $del = Woo_shipping_cost::find($id);
        $del->delete();
        Session::flash('success', 'Shipping Method deleted Successful.');
        return redirect()->back();
    }
}
