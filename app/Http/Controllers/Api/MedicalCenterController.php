<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalCenter\StoreMedicalCenterRequest;
use App\Models\MedicalCenter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class MedicalCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return responseJSON(MedicalCenter::with('medicalServices')->get(), 200, 'Medical Centers');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicalCenterRequest $request)
    {
        try {
            $medicalCenter = MedicalCenter::create($request->validated());
            if (isset($request->service_id)) {
                $medicalCenter->medicalServices()->attach($request->service_id);
            }

            return responseJSON($medicalCenter, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalCenter $medicalCenter)
    {
        try {
            return responseJSON($medicalCenter->load('medicalServices'), 200, 'Success');
        } catch (\Exception $e) {
            if ($e instanceof ModelNotFoundException) {

                return responseJSON(null, 404, 'Medical Center not found');
            }

            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalCenter $medicalCenter)
    {
        try {
            $medicalCenter->update($request->all());

            return responseJSON($medicalCenter, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalCenter $medicalCenter)
    {
        try {
            $medicalCenter->medicalServices()->detach();
            $medicalCenter->delete();

            return responseJSON(null, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }
}
