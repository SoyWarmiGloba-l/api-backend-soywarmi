<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Models\Testimony;
use Illuminate\Support\Str;

class TestimonyController extends Controller
{
    public function index()
    {
        $testimonies = Testimony::with('person')->get();
        $persons = Person::all();

        return view('admin.testimony_index', compact('testimonies', 'persons'));
    }

    public function getTestimony(Request $request)
    {
        $testimony = Testimony::where('id', $request->id)->with('person')->first();
        return responseJSON($testimony, 200, 'Testimonio encontrado');
    }

    public function deleteTestimony(Testimony $testimony)
    {
        $testimony->delete();
        session()->flash('success', 'Testimonio eliminado');
        return redirect()->back();
    }

    public function save(Request $request)
    {
        if ($request->save == "true") {
            $testimony = Testimony::create([
                'person_id' => isset($request->person) ? $request->person : 1,
                'title' => $request->tittle,
                'slug' => Str::slug($request->tittle),
                'description' => strip_tags($request->description),
                'status' => $request->state == "on" ? "active" : "inactive",
            ]);
            session()->flash('success', 'Testimonio creado');
            return redirect()->back();
        }

        Testimony::where('id', $request->id)->update([
            'person_id' => isset($request->person) ? $request->person : 1,
            'title' => $request->tittle,
            'slug' => Str::slug($request->tittle),
            'description' => strip_tags($request->description),
            'status' => $request->state == "on" ? "active" : "inactive",
        ]);

        session()->flash('success', 'Testimonio actualizado');
        return redirect()->back();
    }
}
