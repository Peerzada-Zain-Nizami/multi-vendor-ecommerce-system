<?php
namespace App\MyClasses;
use Illuminate\Support\Facades\Http;

class woocommerce
{
    private $url;

    public function __construct($api,$password,$hostname)
    {
        // $this->url = "https://".$api.":".$password."@".$hostname."/admin/api/2023-01/";
        $this->url = "https://{$api}:{$password}@{$hostname}/admin/api/2023-01/";
    }

    public function get($path)
    {
        $api_url = $this->url.$path;
        // $response = Http::get($api_url);
        $response = Http::withOptions(['verify' => false])->get($api_url);
        return $response->body();
    }
    public function post($path,$data)
    {
        $api_url = $this->url.$path;
        $response = Http::post($api_url,$data);
        return $response->body();
    }
    public function put($path,$data)
    {
        $api_url = $this->url.$path;
        $response = Http::put($api_url,$data);
        return $response->body();
    }
    public function delete($path)
    {
        $api_url = $this->url.$path;
        $response = Http::delete($api_url);
        return $response->body();
    }

}
