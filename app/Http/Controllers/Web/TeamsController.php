<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Team;

class TeamsController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        //dd($teams);
        //dd(Person::all());
        $roles = Role::all();
        //dd($roles);

        return view('admin.team_index', compact('roles', 'teams'));
    }

    public function getTeam(Request $request)
    {
        $team = Team::where('id', $request->id)->with('role', 'person')->get();
        return responseJSON($team, 200, 'Equipo encontrada');
    }

    public function storeTeam(Request $request)
    {

        try {
            if ($request->save == "true") {
                Team::create([
                    'role_id' => $request->role,
                    'name' => $request->name,
                    'description' => strip_tags($request->description),
                    'social_networks' => json_encode($request->social),
                ]);
                session()->flash('success', 'Equipo creado');
                return redirect()->back();
            }
            Team::where('id', $request->id)->update([
                'role_id' => $request->role,
                'name' => $request->name,
                'description' => strip_tags($request->description),
                'social_networks' => isset($request->social) ? json_encode($request->social) : null,
            ]);
            session()->flash('success', 'Equipo actualizada');
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('success', 'Persona actualizada');
            return redirect()->back();
        }
    }
}
