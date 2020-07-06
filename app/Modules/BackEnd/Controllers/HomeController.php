<?php

namespace App\Modules\BackEnd\Controllers;

use App\Http\Controllers\Controller;
use App\Libs\Lib;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function __construct(){
        Lib::addBreadcrumb();
    }

    public function index(Request $request){
        $data = [];
        $agoDate = Carbon::now()->subDays(Carbon::now()->dayOfWeek);
//        $cheer = Carbon::now()->subDays(Carbon::now()->dayOfWeek);

//        dd(Carbon::now()->subMonth()->endOfMonth()->format('d/m/Y'));

        if(!empty($request->time_from)){
            $time_from = Lib::getTimestampFromVNDate($request->time_from);
        }else {
            $time_from = strtotime('-30 days');
            $request->time_from = Lib::dateFormat($time_from,'d/m/Y');
        }
        if(!empty($request->time_to)){
            $time_to = Lib::getTimestampFromVNDate($request->time_to, true);
        }else {
            $time_to = time();
            $request->time_to = Lib::dateFormat($time_to,'d/m/Y');
        }

        $grossRevenue = Order::revenue(@$time_from,@$time_to);
        $netRevenue = Order::revenue(@$time_from,@$time_to,true);
        $numOfOrders = Order::numOfOrders(@$time_from,@$time_to);
        $numOfDoneOrders = Order::numOfOrders(@$time_from,@$time_to,true);
        $avgValueOrder = Order::avgOrderValue(@$time_from,@$time_to);
        $topCustomer = Order::topCustomers(@$time_from,@$time_to);
        $topCoupon = Order::topCoupon(@$time_from,@$time_to);
        $topProducts = Order::topProducts(@$time_from,@$time_to);
        $topCategories = Order::topCategories(@$time_from,@$time_to);
//        dd($topCategories);

        $data = [
            'grossRevenue' => $grossRevenue,
            'netRevenue' => $netRevenue,
            'numOfOrders' => $numOfOrders,
            'numOfDoneOrders' => $numOfDoneOrders,
            'avgValueOrder' => $avgValueOrder,
            'topCustomer' => $topCustomer,
            'topCoupon' => $topCoupon,
            'topProducts' => $topProducts,
            'topCategories' => $topCategories,
            'thisWeek' => '',
            'lastWeek' => ['time_from' => $agoDate->startOfWeek()->format('d/m/Y'),'time_to' => $agoDate->endOfWeek()->format('d/m/Y')],
            'thisMonth' => ['time_from' => Carbon::now()->startOfMonth()->format('d/m/Y'),'time_to' => Carbon::now()->endOfMonth()->format('d/m/Y')],
            'lastMonth' => ['time_from' => Carbon::now()->startOfMonth()->subMonth()->format('d/m/Y'),'time_to' => Carbon::now()->subMonth()->endOfMonth()->format('d/m/Y')],
            'lastYear' => ['time_from' => Carbon::now()->startOfYear()->subYear()->format('d/m/Y'),'time_to' => Carbon::now()->subYear()->endOfYear()->format('d/m/Y')],
            'thisYear' => ['time_from' => Carbon::now()->startOfYear()->format('d/m/Y'),'time_to' => Carbon::now()->endOfYear()->format('d/m/Y')],
        ];

        return view('BackEnd::pages.home.index', [
            'site_title' => 'Trang chủ',
            'key' => 'home',
            'search_data' => $request,
            'data' => $data
        ]);
    }

    public function checkAuth(){
        return redirect()->to(url()->full().'/login')->send();
    }
}
