<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TranslationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get("search");
        $translations = DB::connection("app")->table("translations")->latest()
        ->when($search, fn($q) => $q->where("default", "LIKE", "%$search%"))
        ->paginate(100);
        // return $translations;
        return view('translations.index', compact('translations'));
    }

    public function create()
    {
        return view('translations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|string|unique:translations,value',
            'en'    => 'required|string',
            'de'    => 'required|string',
        ]);

        DB::connection("app")->table("translations")->create([
            'value' => $request->value,
            'translation' => [
                'en' => $request->en,
                'de' => $request->de,
            ],
        ]);

        return redirect()->route('translations.index')
            ->with('success', 'Translation added successfully');
    }

    public function edit($translation)
    {
        $translation = DB::connection("app")->table("translations")->find($translation);
         $translation->translation = json_decode( $translation->translation, true);
        return view('translations.edit', compact('translation'));
    }

    public function update(Request $request, $translation)
    {
        $request->validate([
            'value' => 'required|string',
            'en'    => 'required|string',
            'de'    => 'required|string',
        ]);

        DB::connection("app")->table("translations")->where("id", $translation)->update([
            'default' => $request->value,
            'translation' => [
                'en' => $request->en,
                'de' => $request->de,
            ],
        ]);

        return redirect()->route('translations.index')
            ->with('success', 'Translation updated successfully');
    }

    public function destroy($translation)
    {
        DB::connection("app")->table("translations")->delete();

        return back()->with('success', 'Translation deleted');
    }
}
