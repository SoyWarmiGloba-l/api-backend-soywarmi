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
        $activities = Activity::select('id', 'event_type_id', 'name', 'description', 'end_date')->with('eventType')->get();
        return view('admin.activities_index', compact('eventTypes', 'activities'));
    }

    public function getActivities(Request $request)
    {
        $activities = Activity::where('id', $request->id)->with('eventType', 'images')->get();
        return responseJSON($activities, 200, 'Actividad encontrada');
    }

    public function deleteactivity(Activity $activity)
    {
        $activity->delete();
        session()->flash('success', 'Actividad eliminada');
        return redirect()->back();
    }
    public function saveActivity(Request $request)
    {
        if($request->save == "true"){
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
        Activity::where('id', $request->id)->update([
            'event_type_id' => $request->eventType,
            'name' => $request->name,
            'description' => $request->description,
            'end_date' => $request->dataEnd,
            'step' => isset($request->steps) ? json_encode($request->steps) : null,
            'area' => isset($request->areas) ? json_encode($request->areas) : null,
            'requirement' => isset($request->requirements) ? json_encode($request->requirements) : null,
        ]);
        session()->flash('success', 'Actividad actualizada');
        return redirect()->back();
    }
}
