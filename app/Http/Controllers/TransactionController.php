<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::connection("app")->table("transactions")
            ->join("users", "users.id", "=", "transactions.user_id");

        // APPLY FILTERS
        if ($request->status) $query->where('status', $request->status);
        if ($request->date_from) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->min_amount) $query->where('amount', '>=', $request->min_amount);
        if ($request->max_amount) $query->where('amount', '<=', $request->max_amount);

        $transactions = $query->select(["transactions.*", "users.name"])
            ->latest()
            ->paginate(15);

        // ANALYTICS
        $stats = [
            'total'   => DB::connection("app")->table("transactions")->count(),
            'revenue' => DB::connection("app")->table("transactions")
                            ->where("status", "success")
                            ->sum("amount"),

            'success' => DB::connection("app")->table("transactions")
                            ->where("status", "success")->count(),

            'failed'  => DB::connection("app")->table("transactions")
                            ->where("status", "failed")->count(),
        ];

        return view('transactions.index', compact('transactions', 'stats'));
    }

    public function show($id){
        $trans = DB::connection("app")->table("transactions")
        ->join("users", "users.id", "=", "transactions.user_id")
        ->join("plans", "plans.id", "=", "transactions.plan_id")
        ->select(["transactions.*", "plans.*","users.*","transactions.id AS id"])
        ->where("transactions.id", $id)->first();
        if(!$trans)
            return back()->with("error", "No details found");
        // return $trans;
        return view("transactions.show", ["transaction" => $trans]);
    }

    public function print($id){
    {
        $trans = DB::connection("app")->table("transactions")
        ->join("users", "users.id", "=", "transactions.user_id")
        ->join("plans", "plans.id", "=", "transactions.plan_id")
        ->select(["transactions.*", "plans.*", "users.*", "transactions.id AS id"])
        ->where("transactions.id", $id)->first();
        if(!$trans)
            return back()->with("error", "No details found");
        // Prepare PDF
        $pdf = Pdf::loadView("pdf.subscription-receipt", [
            'transaction' => $trans
        ])->setPaper('A4', 'portrait');

        // Filename
        $fileName = "INVOICE-{$trans->transaction_code}.pdf";

        return $pdf->download($fileName);
    }
    }
}
