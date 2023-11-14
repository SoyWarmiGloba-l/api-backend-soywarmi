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

        return view('admin.team_index', compact('roles'));
    }
}
