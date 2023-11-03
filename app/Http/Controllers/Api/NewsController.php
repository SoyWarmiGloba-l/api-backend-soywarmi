<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\News\StoreNewsRequest;
use App\Models\Image;
use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return responseJSON(News::with('eventType', 'images')->get(), 200, 'News fetched successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNewsRequest $request): JsonResponse
    {
        try {
            $news = News::create([
                'event_type_id' => $request->event_type_id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => isset($request->start_date) ? $request->start_date : null,
                'end_date' => isset($request->end_date) ? $request->end_date : null,
            ]);

            if ($request->has('files'))
            {
            $files = $request->file('files');
                if (count($files) > 0) {
                    foreach ($files as $file) {
                        $image = new Image();
                        $image->name = $file->getClientOriginalName();
                        $image->type = '.'. $file->getClientOriginalExtension();
                        $image->url = '/storage/' . Storage::disk('public')->putFile('images', $file);
                        $news->images()->save($image);
                    }
                }
            }
            return responseJSON($news, 201, 'News created successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 400, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news): JsonResponse
    {
        try {
            return responseJSON($news->load('eventType', 'images'), 200, 'News fetched successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 400, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news): JsonResponse
    {
        try {
            $news->update($request->all());

            return responseJSON($news->load('eventType'), 200, 'News updated successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 400, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news): JsonResponse
    {
        try {
            $news->images()->delete();
            $news->delete();
            return responseJSON(null, 200, 'News deleted successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 400, $e->getMessage());
        }
    }
}
