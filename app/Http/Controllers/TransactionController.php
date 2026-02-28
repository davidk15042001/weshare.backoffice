<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Transaction;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $isExport = $request->get("export_type");
        $query = DB::connection("app")->table("transactions")
            ->join("users", "users.id", "=", "transactions.user_id")
            ->leftJoin("plans", "plans.id", "=", "transactions.plan_id");

        // APPLY FILTERS
        if ($request->status) $query->where('status', $request->status);
        if ($request->date_from) $query->whereDate('transactions.created_at', '>=', $request->date_from);
        if ($request->date_to) $query->whereDate('transactions.created_at', '<=', $request->date_to);
        if ($request->min_amount) $query->where('amount', '>=', $request->min_amount);
        if ($request->max_amount) $query->where('amount', '<=', $request->max_amount);

        $transactions = $query->select(["transactions.*", "plans.*", "users.*", "transactions.id AS id"])
            ->latest("transactions.created_at")
            ->when($isExport, fn($q) => $q->get())
            ->when(!$isExport, fn($q) => $q->paginate(100));

        if($isExport == "excel"){
            return Excel::download(
                new TransactionsExport($transactions),
                "transactions-". now()->toDateTimeString().".xlsx"
            );
        }

        // return $transactions;

        if ($request->export_type === 'invoice_zip') {

            $zipFileName = 'transaction_invoices_' . now()->timestamp . '.zip';
            $zipPath = storage_path($zipFileName);

            $zip = new ZipArchive;

            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {

                foreach ($transactions as $transaction) {

                    $pdf = Pdf::loadView("pdf.subscription-receipt", [
                        'transaction' => $transaction
                    ]);

                    $fileName = 'invoice_' . $transaction->transaction_code . '.pdf';
                    $tempPath = storage_path($fileName);

                    $pdf->save($tempPath);

                    $zip->addFile($tempPath, $fileName);
                }

                $zip->close();
            }

            // Delete temp PDF files after zipping
            foreach ($transactions as $transaction) {
                $tempFile = storage_path('invoice_' . $transaction->transaction_code . '.pdf');
                if (File::exists($tempFile)) {
                    File::delete($tempFile);
                }
            }

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        if ($isExport === 'pdf') {
            $pdf = Pdf::loadView('exports.transactions-pdf', [
                'transactions' => $transactions
            ]);
            return $pdf->download("transactions-". now()->toDateTimeString().".pdf");
        }
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
