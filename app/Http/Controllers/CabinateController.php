<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cabinate;
use App\CabinatePeople;
use MongoDB\BSON\ObjectId;

class CabinateController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $peoples = Cabinate::where('is_show',true)->orderBy('_id')->get();
        return response()->json($peoples, 200);
    }
    public function cabinatepeople(Request $request)
    {    
        $cabinateDetails = Cabinate::find($request->id);
        
        // $peoples = CabinatePeople::where('is_show',true)
        // ->where('cabinate_id',new ObjectId($request->id))
        // ->orderBy('_id')
        // ->get();     
        
            $filter= ['$match' => ["cabinate_id"=>new ObjectId($request->id)]];

        $peoples = CabinatePeople::raw(function ($collection) use($filter){
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => "people",
                        'localField' => "people_id",
                        'foreignField' =>  "_id",
                        'as' => "people"
                    ]
                    ],
                $filter  , 
                [ '$sort' => ['_id'=> 1] ]            
            ]);
        })->toArray();   
                
        return response()->json(array('cabinate_people'=>$peoples,'cabinate'=>$cabinateDetails), 200);
    }

}
