<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventType\StoreEventTypeRequest;
use App\Models\EventType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return responseJSON(EventType::with('news')->get(), 200, 'Get data successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventTypeRequest $request): JsonResponse
    {
        try {
            $eventType = EventType::create($request->validated());

            return responseJSON($eventType, 200, 'Create data successfully.');
        } catch (\Exception $e) {
            return responseJSON($e->getMessage(), 400, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EventType $eventType): JsonResponse
    {
        try {
            return responseJSON($eventType->load('news'), 200, 'Get data successfully.');
        } catch (\Exception $e) {
            return responseJSON($e->getMessage(), 400, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EventType $eventType): JsonResponse
    {
        try {
            $eventType->update($request->validated());

            return responseJSON($eventType->load('news'), 200, 'Update data successfully.');
        } catch (\Exception $e) {
            return responseJSON($e->getMessage(), 400, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventType $eventType): JsonResponse
    {
        try {
            $eventType->delete();

            return responseJSON(null, 200, 'Delete data successfully.');
        } catch (\Exception $e) {
            return responseJSON($e->getMessage(), 400, $e->getMessage());
        }
    }
}
