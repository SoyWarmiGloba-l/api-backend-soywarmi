<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return responseJSON(Comment::with('publication', 'person')->get(), 200, 'Comments retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        try {
            $comment=Comment::create([
                "person_id"=> $request->person_id,
                "publication_id"=>  $request->publication_id,
                "content"=>  $request->content,
                "state"=>  $request->state,
                "created_at"=> $request->created_at,
                "updated_at"=> $request->updated_at,
                "deleted_at"=> $request->deleted_at
            ]);
            return response()->json(['message' => "OK"], 200);
        } catch (\Exception $exception) {
            return responseJSON(null, 500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return responseJSON($comment->load('publication', 'person'), 200, 'Comment retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        try {
            $comment->update($request->validated());

            return responseJSON($comment, 200, 'Comment updated successfully');
        } catch (\Exception $exception) {
            return responseJSON(null, 500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        try {
            $comment->delete();

            return responseJSON(null, 200, 'Comment deleted successfully');
        } catch (\Exception $exception) {
            return responseJSON(null, 500, $exception->getMessage());
        }
    }
}
