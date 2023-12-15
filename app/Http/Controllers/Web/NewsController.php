<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\EventType;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    public function index()
    {
        $eventTypes = EventType::all();
        $news = News::select('id', 'event_type_id', 'title', 'description')->with('eventType')->get();

        return view('admin.news_admin', compact('eventTypes', 'news'));
    }
    public function getNews(Request $request)
    {
        //        try {
        $news = News::where('id', $request->id)->with('eventType', 'images')->get();
        return responseJSON($news, 200, 'Noticia encontrada');
        //        } catch (\Exception $e) {
        //            return responseJSON(null, 404, 'Noticia no encontrada');
        //        }
    }

    public function deleteNews(News $news)
    {
        $news->delete();
        session()->flash('success', 'Noticia eliminada');
        return redirect()->back();
    }

    public function saveNews(Request $request)
    {

        if ($request->save == "true") {
            $news = News::create([
                'event_type_id' => $request->eventType,
                'title' => $request->tittle,
                'description' => $request->description,
                'start_date' => Carbon::now(),
                'end_date' => Carbon::now(),
            ]);
            if ($request->has('kifPholder')) {
                $files = $request->file('kifPholder');
                if (count($files) > 0) {
                    foreach ($files as $file) {
                        $image = new Image();
                        $image->name = $file->getClientOriginalName();
                        $image->type = '.' . $file->getClientOriginalExtension();
                        $image->url = '/storage/' . Storage::disk('public')->putFile('images', $file);
                        $news->images()->save($image);
                    }
                }
            }
            session()->flash('success', 'Noticia creada');
            return redirect()->back();
        }
        News::where('id', $request->id)->update([
            'event_type_id' => $request->eventType,
            'title' => $request->tittle,
            'description' => $request->description,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now(),
        ]);
        session()->flash('success', 'Noticia actualizada');
        return redirect()->back();
        //try {
        /*} catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }*/
    }
}
