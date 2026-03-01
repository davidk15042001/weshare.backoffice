<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use Illuminate\Support\Facades\DB;

class AppSettingController extends Controller
{
    //
    public $appDBConnect;
    public function __construct()
    {
        $this->appDBConnect = DB::connection("app")->table("app_settings");
    }

    public function editLegal()
    {
        $terms = DB::connection("app")->table("app_settings")->where(['key' => 'terms'])->first();
        $privacy = DB::connection("app")->table("app_settings")->where(['key' => 'privacy_policy'])->first();
        $cookie = DB::connection("app")->table("app_settings")->where(['key' => 'cookie_policy'])->first();
        $imprint = DB::connection("app")->table("app_settings")->where(['key' => 'imprint'])->first();
        $agb = DB::connection("app")->table("app_settings")->where(['key' => 'agb'])->first();
        return view('settings.legal', compact('terms', 'privacy', 'cookie', 'imprint', 'agb'));
    }

    public function updateLegal(Request $request)
    {
        $request->validate([
            'key' => 'required|string',
            'value' => 'nullable|string',
        ]);

        $this->appDBConnect->updateOrInsert(
            ['key' => $request->key],
            ['value' => $request->value]
        );

        return back()->with('success', 'Content updated successfully.');
    }
}
