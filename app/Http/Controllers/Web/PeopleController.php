<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Team;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PeopleController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        $roles = Role::all();
        $people = Person::all();

        return view('admin.people_index', compact('teams', 'roles', 'people'));
    }

    public function deletePeople(Person $people)
    {

        $people->delete();
        session()->flash('success', 'Persona eliminada');
        return redirect()->back();
    }

    public function getPerson(Request $request)
    {
        $person = Person::where('id', $request->id)->with('role', 'team')->get();
        return responseJSON($person, 200, 'Persona encontrada');
    }

    public function savePeople(Request $request)
    {

        if ($request->save == "true") {
            if ($request->file('kifPholder')) {
                $files = $request->file('kifPholder');
                $photo = '/storage/' . Storage::disk('public')->putFile('/profile/images', $files);
            }
            $people = Person::create([
                'role_id' => $request->role,
                'team_id' => empty($request->team) ? null : $request->team,
                'name' => $request->name,
                'description' => strip_tags($request->description),
                'lastname' => $request->lastname,
                'mother_lastname' => empty($request->secondlastname) ? null : $request->secondlastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'photo' => $photo,
                'birthday' => $request->birthday,
                'gender' => $request->gender,
                'phone' => $request->phone,
            ]);
            session()->flash('success', 'Actividad creada');
            return redirect()->back();
        }
        if ($request->file('kifPholder')) {
            $files = $request->file('kifPholder');
            $photo = '/storage/' . Storage::disk('public')->putFile('/profile/images', $files);
        }
        $person = Person::where('id', $request->id)->first();

        Person::where('id', $request->id)->update([
            'role_id' => $request->role,
            'team_id' => empty($request->team) ? null : $request->team,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'mother_lastname' => empty($request->secondlastname) ? null : $request->secondlastname,
            'email' => $request->email,
            'description' => strip_tags($request->description),
            'password' => Hash::make($request->password),
            'photo' => isset($photo) ? $photo : $person->photo,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'phone' => $request->phone,
        ]);

        session()->flash('success', 'Persona actualizada');
        return redirect()->back();
    }
}
