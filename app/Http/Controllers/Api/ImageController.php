<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        return responseJSON(Image::with('imageable')->get(), 200, 'Image retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : JsonResponse
    {
        //
        /*try {
            $image = Image::create([

                'name' => $request->name,
                'type' => $request->type,

            ]);

            return responseJSON($image, 201, 'Image created successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }*/
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image) : JsonResponse
    {
        try {
            return responseJSON($image->load('imageable'), 200, 'Image retrieved.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        try {
            $image->delete();
            return responseJSON(null, 200, 'Image deleted.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }
}
