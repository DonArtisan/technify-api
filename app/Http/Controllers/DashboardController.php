<?php

namespace App\Http\Controllers;

use App\Http\Stats\SalesStats;
use App\Http\Stats\UserStats;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $salesStats = SalesStats::query()
            ->start(now()->subDays(2))
            ->end(now()->subSecond())
            ->groupByDay()
            ->get();

        $salesTotal = $salesStats[1]->value;

        $salesToday = $salesStats[1];

        $usersStats = UserStats::query()
            ->start(now()->subWeeks(2))
            ->end(now()->subSecond())
            ->groupByWeek()
            ->get();

        $usersTotal = $usersStats->last()->value;
        logger($usersTotal);

        $salesPercent = (($salesToday->difference) -  ($salesStats[0]->difference)) * 100;
        $usersPercent = (($usersStats->last()->difference) -  ($usersStats[0]->difference)) * 100;

        return view('dashboard', compact('salesTotal', 'salesPercent', 'usersStats', 'usersTotal', 'usersPercent') );
    }
}
