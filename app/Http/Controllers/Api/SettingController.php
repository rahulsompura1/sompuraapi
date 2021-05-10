<?php

namespace App\Http\Controllers\Api;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('company_id')){
            return response()->json(['setting'=> Setting::where('company_id',$request->get('company_id'))->first()->toArray()],200);
        }else if($request->has('store_slug')) {
            return response()->json(['setting'=> Setting::where('store_slug',$request->get('store_slug'))->first()->toArray()],200);
        }else {
            return response()->json(['message'=> 'Settings not found'],404);
        }
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserSlug(Request $request)
    {
        return response()->json(['setting'=> Setting::where('company_id',$request->company_id)->first(['store_slug'])],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "pdf_name" => "required|min:2|max:191",
            "store_name" => "required|min:2|max:191",
            "welcome_message" => "nullable|max:500"
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) { 
            return response()->json(["message" => $validator->messages()], 201);
        }
        $settingArray = $request->all();
        if(!$request->has('store_slug')){
            $settingArray['store_slug'] =  Str::random(4).Str::replace(' ', '_', $settingArray['store_name']);
        }
        $setting = Setting::first();
        $isSaved = false;
        if ($setting) {
            $setting->fill($settingArray );
            $isSaved = $setting->update();
        } else {
            $setting = Setting::create($settingArray );
            $isSaved = $setting ? true : $isSaved;
        }

        return response()->json(['setting'=> $setting,'message' => 'Setting has changed'],200);
    }
}
