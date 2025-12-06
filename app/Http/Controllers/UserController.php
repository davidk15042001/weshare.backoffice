<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Filters
        $search = $request->query('search');
        $status = $request->query('status'); // free / subscribed

        $query = DB::connection("app")->table("users");

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        if ($status == "free") {
            $query->whereNull('subscription');
        } elseif ($status == "subscribed") {
            $query->whereNotNull('subscription');
        }

        $users = $query->orderBy("created_at", "DESC")->paginate(20);

        // Cards
        $totalUsers = DB::connection("app")->table("users")->count();
        $totalSubscribed =DB::connection("app")->table("users")->whereNotNull('subscription')->count();
        $totalFree = $totalUsers - $totalSubscribed;

        return view('users.index', compact(
            'users', 'search', 'status',
            'totalUsers', 'totalFree', 'totalSubscribed'
        ));
    }

    public function show($user)
    {
        $user = DB::connection("app")->table("users")->find($user);
        if(!$user)
            return redirect()->route("users.index")->with("error", "User not found");
        $subscriptions = DB::connection("app")->table("subscriptions")
        ->join("plans", "plans.stripe_plan", "=", "subscriptions.stripe_price")
        ->select(["subscriptions.*", "plans.price"])
        ->where('user_id', $user->id)->get();
        $transactions = DB::connection("app")->table("transactions")->where('user_id', $user->id)->latest()->paginate(10);
        $cards = DB::connection("app")->table("cards")->where('user_id', $user->id)->get();

        return view('users.show', compact(
            'user',
            'subscriptions',
            'transactions',
            'cards'
        ));
    }
}
