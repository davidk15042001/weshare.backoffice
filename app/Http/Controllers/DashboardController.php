<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Revenue filter
        $filter = $request->query('filter', 'all');
        $chartRange = (int) $request->query('chart_range', 7); // default last 7 days

        $revenueQuery = DB::connection("app")
            ->table("transactions")
            ->where('status', 'success');

        switch ($filter) {
            case 'today':
                $revenueQuery->whereDate('created_at', Carbon::today());
                break;

            case 'week':
                $revenueQuery->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
                break;

            case 'month':
                $revenueQuery->whereMonth('created_at', Carbon::now()->month);
                break;
        }

        $totalRevenue = $revenueQuery->sum('amount');

        // Basic metrics
        $totalUsers = DB::connection("app")->table("users")->count();
        $completedTransactions = DB::connection("app")->table("transactions")->where('status', 'success')->count();

        // Pie chart
        $subscribedUsers = DB::connection("app")->table("users")->whereNotNull('subscription')->count();
        $freeUsers = $totalUsers - $subscribedUsers;

        // BAR CHART FILTER LOGIC
        $startDate = Carbon::now()->subDays($chartRange);

        $subscriptionChart = DB::connection("app")->table("subscriptions")
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Recent transactions
        $recentTransactions = DB::connection("app")->table("transactions")
            ->join("users", "users.id", "=", "transactions.user_id")
            ->select(["transactions.*", "users.name"])
            ->latest()->take(10)->get();

        return view('dashboard', [
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'completedTransactions' => $completedTransactions,
            'freeUsers' => $freeUsers,
            'subscribedUsers' => $subscribedUsers,
            'subscriptionChart' => $subscriptionChart,
            'recentTransactions' => $recentTransactions,
            'filter' => $filter,
            'chartRange' => $chartRange
        ]);
    }
}
