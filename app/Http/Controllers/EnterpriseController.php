<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnterpriseController extends Controller
{

    public $appDBConnect;
    public function __construct()
    {
        $this->appDBConnect = DB::connection("app")->table("enterprises");
    }
    public function index()
    {
        $enterprises = $this->appDBConnect->latest()->paginate(100);

        $stats = [
            'total' => $this->appDBConnect->count(),
            'employees' => $this->appDBConnect->sum('employees_count'),
        ];

        return view('enterprises.index', compact('enterprises', 'stats'));
    }

    public function create()
    {
        return view('enterprises.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'employees_count' => 'required|integer|min:1'
        ]);

        $this->appDBConnect->create($request->all());

        return redirect()->route('enterprises.index')
            ->with('success', 'Enterprise created successfully');
    }

    public function edit($enterprise)
    {
        $enterpriseDetails = $this->appDBConnect->find($enterprise);
        return view('enterprises.form', compact('enterpriseDetails'));
    }

    public function update(Request $request, $enterprise)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'employees_count' => 'required|integer|min:1'
        ]);
        $enterpriseDetails = $this->appDBConnect->find($enterprise);
        $enterpriseDetails->update($request->all());
        return redirect()->route('enterprises.index')
            ->with('success', 'Enterprise updated successfully');
    }

    public function destroy($enterprise)
    {
        $enterpriseDetails = $this->appDBConnect->find($enterprise);
        $enterpriseDetails->delete();
        return back()->with('success', 'Enterprise deleted');
    }
}
