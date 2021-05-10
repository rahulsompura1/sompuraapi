<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use stdClass;

class SettingController extends Controller
{
    
    public function info()
    {
        $settings = Setting::first();
        $setting = new stdClass;                
        $setting->primary_color = $settings->primary_color;
        $setting->secondary_color = $settings->secondary_color;        

        return response()->json($setting);
    }
    
}
