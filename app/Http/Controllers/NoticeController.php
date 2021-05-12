<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;

use MongoDB\BSON\ObjectId;

class NoticeController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
   

    public function people()
    {
        $notice =  Notice::where('slug','people')->first();       
        return response()->json($notice, 200);
    }
}
