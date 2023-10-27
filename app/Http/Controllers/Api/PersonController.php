<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return responseJSON(Person::with('doctor', 'team', 'role')->get(), 200, 'Success');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $url = Storage::disk('public')->put($request->image->getClientOriginalName(), $request->image);
            $request->merge([
                'photo' => env('APP_URL').'/storage/'.$url,
                'password' => Hash::make($request->password),
            ]);
            $person = Person::create($request->except('image'));

            return responseJSON($person, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        try {
            return responseJSON($person->load('doctor', 'team', 'role'), 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        try {
            if (isset($request->image)) {
                $url = Storage::disk('public')->put($request->image->getClientOriginalName(), $request->image);
                $request->merge([
                    'photo' => env('APP_URL').'/storage/'.$url,
                ]);
            }
            if (isset($request->password)) {
                $request->merge([
                    'password' => Hash::make($request->password),
                ]);
            }
            $person->update($request->all());

            return responseJSON($person, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        try {
            $person->delete();

            return responseJSON($person, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, $e->getMessage(), 500);
        }
    }
}
