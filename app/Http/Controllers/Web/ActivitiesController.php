<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Models\EventType;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
class ActivitiesController extends Controller
{
    public function index()
    {
        $eventTypes = EventType::all();
        return view('admin.activities_index', compact('eventTypes'));
    }
    public function saveActivity(Request $request)
    {
        $activity = Activity::create([
            'event_type_id' => $request->eventType,
            'name' => $request->name,
            'description' => $request->description,
            'end_date' => $request->dataEnd,
            'step' => json_encode($request->steps),
            'area' => json_encode($request->areas),
            'requirement' => json_encode($request->requirements),
        ]);
        if ($request->file('kifPholder'))
        {
            $files = $request->file('kifPholder');
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
        session()->flash('success', 'Actividad creada');
        return redirect()->back();
    }
}
