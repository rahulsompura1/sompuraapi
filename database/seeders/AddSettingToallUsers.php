<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Setting;

class AddSettingToallUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();
        foreach($users as $user){
            $settingCount = Setting::where(array('company_id' => $user->_id))->first();
            if(!empty($settingCount)){
                $setting = Setting::find($settingCount->_id);
            }else{
                $setting = new Setting;
            }    
            $setting->pdf_name = isset($setting->pdf_name)?$setting->pdf_name:$user->name.' store pdf' ;     
            $setting->store_name = isset($setting->store_name)?$setting->store_name:$user->name.' store' ;   
            $setting->company_id = isset($setting->company_id)?$setting->company_id:$user->_id ;   
            $setting->store_slug = isset($setting->store_slug)?$setting->store_slug:Str::random(4).$user->name.'_store' ;   
            $setting->save();  
        }
    }
}
