<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Resource\StoreResourceRequest;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        return responseJSON(Resource::all(), 200, 'Resource retrieved');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResourceRequest $request) : JsonResponse
    {
        try {
            $path = Storage::disk('public')->putFileAs('resources', $request->file('file'), trim($request->file('file')->getClientOriginalName()));
            $resource = Resource::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'code_type' => $request->file('file')->getClientOriginalExtension(),
                'url' => config('app.url') . '/storage/' . trim($path)
            ]);

            return responseJSON($resource, 201, 'Resource created');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource) : JsonResponse
    {
        try {
            return responseJSON($resource, 200, 'Resource retrieved');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Resource $resource)
    {
        try {
            $path = Storage::disk('public')->putFileAs('resources', $request->file('file'), trim($request->file('file')->getClientOriginalName()));
            $resource->update([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'code_type' => $request->file('file')->getClientOriginalExtension(),
                'url' => config('app.url') . '/storage/' . trim($path)
            ]);
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource)
    {
        try {
            $resource->delete();
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }
}
