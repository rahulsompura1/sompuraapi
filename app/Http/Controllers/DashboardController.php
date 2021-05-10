<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dashboard;
use App\Models\People;
use App\Models\Family;
use MongoDB\BSON\ObjectId;

class DashboardController extends Controller
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
        $Dashboard = Dashboard::where('is_show', true)->get();
        $peopleCnt = People::count();
        $familyCnt = Family::count();
        $groupByGotra = Family::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$gotra',
                        'count' => ['$sum' => 1]
                    ],

                ],
                [
                    '$sort' => ['count' => -1]

                ],
            ]);
        });
        $gotraArr = [];
        $dataArr = [];
        foreach ($groupByGotra as $gotra) {
            $gotraArr['categories'][] = [$gotra->_id];
            $dataArr['data'][] = $gotra->count;
        }
        return response()->json(array('dashboard' => $Dashboard, 'total_people' => $peopleCnt, 'total_family' => $familyCnt, 'gotra' => $gotraArr, 'series' => $dataArr), 200);
    }
    public function checkConnection(Request $request)
    {
        $Dashboard = Dashboard::where('is_show', true)->count();        
        return response()->json($Dashboard, 200);
    }
}
