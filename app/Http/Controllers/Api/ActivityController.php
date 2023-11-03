<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Activity\StoreActivityRequest;
use App\Models\Activity;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        return responseJSON(Activity::with('eventType', 'images')->get(), 200, 'Activity list.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request) : JsonResponse
    {
        try {
            $activity = Activity::create([
                'event_type_id' => $request->event_type_id,
                'name' => $request->name,
                'description' => $request->description,
                'end_date' => $request->end_date,
                'area' => $request->area,
                'step' => $request->steps,
                'requirement' => $request->requirements,
            ]);

            if($request->has('files'))
            {
                $files = $request->file('files');
                if (count($files) > 0) {
                    foreach ($files as $file) {
                        $image = new Image();
                        $image->name = $file->getClientOriginalName();
                        $image->type = '.'. $file->getClientOriginalExtension();
                        $image->url = '/storage/' . Storage::disk('public')->putFile('images', $file);
                        $activity->images()->save($image);
                    }
                }
            }
            return responseJSON($activity, 201, 'Activity created.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity) : JsonResponse
    {
        try {
            return responseJSON($activity->load('eventType'), 200, 'Activity retrieved.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity) : JsonResponse
    {
        try {
            $activity->update($request->all());
            return responseJSON($activity, 200, 'Activity updated.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity) : JsonResponse
    {
        try {
            $activity->delete();
            return responseJSON(null, 200, 'Activity deleted.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }
}
