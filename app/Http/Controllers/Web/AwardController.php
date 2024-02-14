<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Award;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AwardController extends Controller
{
    public function index()
    {
        return view('admin.award_index', [
            'awards' => Award::all(),
        ]);
    }

    public function saveAward(Request $request)
    {

        try {
            if ($request->save == "true") {
                if ($request->hasFile('fileIcon')) {
                    $award = new Award();
                    $award->title = $request->title;
                    $award->description = $request->description;
                    $award->icon = Storage::disk('public')->put('awards', $request->fileIcon);
                    $award->save();
                }

                session()->flash('success', 'Premio creado');
                return redirect()->back();
            }

            Award::where('id', $request->id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'icon' => Storage::disk('public')->put('awards', $request->fileIcon),
            ]);
            session()->flash('success', 'Premio actualizado');
            return redirect()->back();
        } catch (\Exception $e) {
            return responseJSON([], 500, $e->getMessage());
        }
    }

    public function getAward(Request $request)
    {

        $award = Award::where('id', $request->id)->first();
        return responseJSON($award, 200, 'Award encontrada');
    }

    public function deleteAward(Award $award)
    {
        try {
            $award->delete();
            session()->flash('success', 'Premio Eliminada');
            return redirect()->back();
        } catch (\Exception $e) {
            return responseJSON([], 500, $e->getMessage());
        }
    }
}
