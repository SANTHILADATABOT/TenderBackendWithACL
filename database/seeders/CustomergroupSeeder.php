<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Customer_group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CustomergroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        $json = File::get("database/data/response.json");
        $customergroup = json_decode($json);
        var_dump($customergroup);
        foreach ($customergroup as $key => $value)
         {
            if( $value->smart_city == "yes"){

                $smart_city = "Smart City";

            }else if($value->smart_city == "no"){

                $smart_city = "Non Smart City";

            }
            Customer_group::create([
                "state" => $value->state,
                "customer_sub_category" => $value->customer_sub_category,
                "smart_city" => $smart_city 
            ]);
        }
       
    }
}
