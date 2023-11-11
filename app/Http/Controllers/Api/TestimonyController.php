<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimony;
use Illuminate\Http\Request;

class TestimonyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return responseJSON(Testimony::with('user')->get(), 200, 'Testimonies retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimony $testimony)
    {
        try {
            return responseJSON($testimony->load('user'), 200, 'Testimony retrieved successfully');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    public function showSlug($slug)
    {
        try {
            return responseJSON(Testimony::where('slug', $slug)->with('user')->first(), 200, 'Testimony retrieved successfully');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimony $testimony)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimony $testimony)
    {
        //
    }
}
