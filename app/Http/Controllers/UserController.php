<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $plans = DB::connection("app")->table("plans")->get();
        return view('users.show', compact(
            'user',
            'subscriptions',
            'plans',
            'transactions',
            'cards'
        ));
    }

    public function changePlan(Request $request, $userid)
    {
        $request->validate([
            'plan_id' => 'required'
        ]);

        $user = DB::connection("app")->table("users")->find($userid);

        $newPlan = DB::connection("app")->table("plans")->find($request->plan_id);
        $rsp = DB::connection("app")->table("subscriptions")->insertGetId([
            "user_id"=> $user->id,
            "name" => $newPlan->name,
            "stripe_id" => Str::uuid(),
            "stripe_status" => "active",
            "stripe_price" => $newPlan->stripe_plan,
            "quantity" => 1,
            "updated_at" => now(),
            "created_at" => now()
        ]);
        DB::connection("app")->table("users")->update([
            "product" => $newPlan->id,
            "subscription" => $rsp
        ]);

        return back()->with('success', 'Subscription plan updated successfully.');
    }
}
