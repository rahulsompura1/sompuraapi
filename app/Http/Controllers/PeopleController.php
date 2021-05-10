<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\People;
use App\Models\Family;
use MongoDB\BSON\ObjectId;

class PeopleController extends Controller
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
        $filter= ['$match' => ["families.0" => ['$exists' => true]]];
        if ($request->input('filter')) {
            $filter= ['$match' => ["families.0" => ['$exists' => true],"gotra"=>$request->input('filter')]];
        }
        $peoples = People::raw(function ($collection) use($filter){
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => "families",
                        'localField' => "_id",
                        'foreignField' =>  "family_owner_id",
                        'as' => "families"
                    ],
                ], $filter,
            [ '$sort' => ['date'=> -1, 'dob'=> 1 ] ]
                
            ]);
        })->toArray();
        $peoplesArr = [];
        if ($request->input('search')) {
            // dd($request->input('search'));

            $search = $request->input('search');
            foreach ($peoples as $people) {                
                if (str_contains($people['name'], $search) 
                || str_contains($people['occupation'], $search) 
                || str_contains($people['mobile_no'], $search)) {
                    $peoplesArr[] = $people;
                }
            }
            $peoples = $peoplesArr;
        }

        return response()->json($peoples, 200);
    }

    public function details($id)
    {
        $people =  People::find($id);
        $people->familymembers = People::where('family_id', $people->family_id)
            ->whereNotIn('_id', [$people->_id])
            ->get();
        return response()->json($people, 200);
    }
}
