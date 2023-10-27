<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalService\StoreMedicalServiceRequest;
use App\Models\MedicalService;
use Illuminate\Http\Request;

class MedicalServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return responseJSON(MedicalService::with('medicalCenters')->get(), 200, 'Success');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicalServiceRequest $request)
    {
        try {
            $medicalService = MedicalService::create($request->validated());
            if ($request->has('center_id')) {
                $medicalService->medicalCenters()->attach($request->center_id);
            }

            return responseJSON($medicalService, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalService $medicalService)
    {
        try {
            return responseJSON($medicalService->load('medicalCenters'), 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalService $medicalService)
    {
        try {
            $medicalService->update($request->all());

            return responseJSON($medicalService, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalService $medicalService)
    {
        try {
            $medicalService->medicalCenters()->detach();
            $medicalService->delete();

            return responseJSON(null, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }
}
