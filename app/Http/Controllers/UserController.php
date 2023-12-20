<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agent;
use App\Models\Order;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index_view ()
    {
        return view('pages.user.user-data', [
            'user' => User::class
        ]);
    }

    public function set_language ($code)
    {
        session()->put('hs_language', $code);
        return redirect()->back();
    }

    public function dashboard_view ()
    {   $agents = Agent::get();
        $filter_start_date = request('start_date') ? request('start_date') : Carbon::now()->startOfMonth()->format('Y-m-d');
        $start_date = $filter_start_date ? Carbon::createFromFormat('Y-m-d', $filter_start_date) : Carbon::now()->startOfMonth();
        $filter_end_date = request('end_date') ? request('end_date') : Carbon::now()->format('Y-m-d');
        $end_date = $filter_end_date ? Carbon::createFromFormat('Y-m-d', $filter_end_date) : Carbon::now();

        if(request('start_date') || request('end_date')){
            $liquidityRecieved = Order::whereBetween('created_at', [$start_date, $end_date])->where('order_status_id', 2)->orWhere('order_status_id', 6)->sum('paid_amount');
            $totalOrder= Order::whereBetween('created_at', [$start_date, $end_date])->count();
            $cancelledOrder = Order::whereBetween('created_at', [$start_date, $end_date])->where('order_status_id', 5)->count();
            $cancellationRate= $cancelledOrder && $totalOrder ? $cancelledOrder/$totalOrder * 100 : 0;
            $avgOrderValue= Order::whereBetween('created_at', [$start_date, $end_date])->avg('amount');
        } else {
            $liquidityRecieved = Order::where('order_status_id', 2)->orWhere('order_status_id', 6)->sum('amount');
            $totalOrder= Order::count();
            $cancellationRate= Order::where('order_status_id', 5)->count() ? Order::where('order_status_id', 5)->count()/$totalOrder * 100 : 0;
            $avgOrderValue= Order::avg('amount');
        }


        return view('dashboard', compact('agents', 'liquidityRecieved', 'cancellationRate', 'avgOrderValue', 'totalOrder','filter_start_date','filter_end_date', 'start_date', 'end_date'));
    }
}
