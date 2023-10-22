<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return responseJSON(Doctor::with('person')->get(), 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDoctorRequest $request)
    {
        try {
            return responseJSON(Doctor::create($request->validated()), 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        try {
            return responseJSON($doctor->load('person'), 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        try {
            $doctor->update($request->all());

            return responseJSON($doctor, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        try {
            $doctor->delete();

            return responseJSON(null, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }
}
