<?php

namespace App\Http\Controllers;

use App\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphsController extends Controller
{
    /**
     * populate the donut chart
     * for past 4 days revenue
     *
     * @return mixed
     */
    public function donut(){
        $controller = new Controller();
        $shopId = $controller->shopId;
        $carbon = new Carbon();
        //get the data for last 3 days
        $sales = Sales::where('shop_id', $shopId)
            ->where('updated_at', '>=', $carbon->now()->subDays(2)->toDateTimeString())
            ->select(DB::raw('DATE(updated_at) as date, SUM(revenue) as revenue'))
            ->groupBy('date')
            ->get();

        return $sales;
    }


    /**
     * show revenue comparison of past week
     */
    public function line(){
        $controller = new Controller();
        $shopId = $controller->shopId;
        $carbon = new Carbon();

        //get the data for last 3 days
        $currentWeekData = Sales::where('shop_id', $shopId)
            ->where('updated_at', '>=', $carbon->now()->subDays(6)->toDateTimeString())
            ->select(DB::raw('DATE(updated_at) as date, SUM(revenue) as revenue'))
            ->groupBy('date')
            ->get();

        return $currentWeekData;
    }

    /**
     * populate line chart for
     * one month's sales
     *
     * @return mixed
     */
    public function lineMonth(){
        $controller = new Controller();
        $shopId = $controller->shopId;
        $carbon = new Carbon();

        //get the data for last 3 days
        $monthData = Sales::where('shop_id', $shopId)
            ->where('updated_at', '>', $carbon->now()->subMonth(1)->toDateTimeString())
            ->select(DB::raw('DATE(updated_at) as date, SUM(revenue) as revenue'))
            ->groupBy('date')
            ->get();

        return $monthData;
    }
}
