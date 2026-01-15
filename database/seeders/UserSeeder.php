<?php

namespace Database\Seeders;

use App\Models\SellerApi;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array();
        $users[]= ['name'=>"Super Admin",'role'=>"SuperAdmin",'email'=>"superadmin@gmail.com",'password'=>Hash::make(11223344),'email_verified_at'=>date('Y-m-d H:i:s')];
        $users[]= ['name'=>"Admin",'role'=>"Subadmin",'email'=>"admin@gmail.com",'password'=>Hash::make(11223344),'email_verified_at'=>date('Y-m-d H:i:s')];
        $users[]= ['name'=>"Supplier",'role'=>"Supplier",'email'=>"supplier@gmail.com",'password'=>Hash::make(11223344),'email_verified_at'=>date('Y-m-d H:i:s')];
        $users[]= ['name'=>"Seller",'role'=>"Seller",'email'=>"seller@gmail.com",'password'=>Hash::make(11223344),'email_verified_at'=>date('Y-m-d H:i:s')];
        $users[]= ['name'=>"Warehouse Admin",'role'=>"Warehouse Admin",'email'=>"wadmin@gmail.com",'password'=>Hash::make(11223344),'email_verified_at'=>date('Y-m-d H:i:s')];
        foreach ($users as $user)
        {
            $add = new User();
            $add->name = $user['name'];
            $add->email = $user['email'];
            $add->password = $user['password'];
            $add->role = $user['role'];
            $add->email_verified_at = $user['email_verified_at'];
            $add->save();
            if ($user['role'] != "Warehouse Admin")
            {
                Wallet::create([
                    'user_id' => $add->id,
                    'balance'=> Crypt::encrypt(0),
                ]);
            }
            if ($user['role'] == "Seller")
            {
                SellerApi::create([
                    'user_id' => $add->id,
                ]);
            }
        }

    }
}
