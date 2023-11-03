<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Faq\StoreFaqRequest;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        return responseJSON(Faq::all(), 200, 'Faqs retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFaqRequest $request) : JsonResponse
    {
        try {
            $faq = Faq::create($request->validated());
            return responseJSON($faq, 201, 'Faq created successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq) : JsonResponse
    {
        try {
            return responseJSON($faq, 200, 'Faq retrieved successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Faq $faq) : JsonResponse
    {
        try {
            $faq->update($request->all());
            return responseJSON($faq, 200, 'Faq updated successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq) : JsonResponse
    {
        try {
            $faq->delete();
            return responseJSON(null, 200, 'Faq deleted successfully.');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }
}
