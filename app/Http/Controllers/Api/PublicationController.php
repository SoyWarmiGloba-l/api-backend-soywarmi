<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publication\StorePublicationRequest;
use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Firebase\FirebaseToken;
use Carbon\Carbon;
use App\Models\Person;


class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return responseJSON(Publication::with('comments', 'person')->get(), 200, 'Publications retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function savePublication(Request $request)
    {
        $token = new FirebaseToken($request->bearerToken());
        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        $photo1='';
        if ($request->hasFile('photo1')) {
            $files = $request->file('photo1');
            $photo1 = '/storage/' . Storage::disk('public')->putFile('/publications/images', $files);
        }
        $photo2='';
        if ($request->hasFile('photo2')) {
            $files = $request->file('photo2');
            $photo2 = '/storage/' . Storage::disk('public')->putFile('/publications/images', $files);
        }
        $photo3='';
        $nombreArchivo="";
        if ($request->hasFile('photo3')) {
            $files = $request->file('photo3');
            $photo3 = '/storage/' . Storage::disk('public')->putFile('/publications/images', $files);
            
        }
        $fecha_actual_gmt_4 = Carbon::now('GMT-4')->toDateTimeString();
        $email = $payload->authenticated_user->email;
        $userId = Person::where('email', $email)->first()->value('id');
        Publication::create([
            'person_id' => $userId,
            'title' => json_decode($request->data,true)["title"],
            'content' => json_decode($request->data,true)["content"],
            'anonymous' => isset(json_decode($request->data, true)["anonymous"]) ? json_decode($request->data, true)["anonymous"] : 1,
            'created_at' => (string)$fecha_actual_gmt_4,
            'updated_at' => (string)$fecha_actual_gmt_4,
            'photo1' => ($request->hasFile('photo1'))?Storage::disk('public')->path("")."publications/images/".$request->file('photo1')->getClientOriginalName():"",
            'photo2' => ($request->hasFile('photo2'))?Storage::disk('public')->path("")."publications/images/".$request->file('photo2')->getClientOriginalName():"",
            'photo3' => ($request->hasFile('photo3'))?Storage::disk('public')->path("")."publications/images/".$request->file('photo3')->getClientOriginalName():"",
    ]);
        return responseJSON($request, 200,"OK");
    }
    public function example(Request $request)
    {
       
       
       
        
        return responseJSON(json_decode($request->input('data'))->title, 200, "OK");
        //return responseJSON($request, 200,"OK");
    }
    public function store(StorePublicationRequest $request)
    {
        try {
            return responseJSON(Publication::create($request->validated()), 201, 'Publication created successfully');
        } catch (\Exception $exception) {
            return responseJSON(null, 500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication,$id)
    {
        try {
            return responseJSON($publication->load('comments', 'person'), 200, 'Publication retrieved successfully');
        } catch (\Exception $exception) {
            return responseJSON(null, 500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePublication(Request $request,$id)
    {
        $publication = Publication::find($id);
        try {
            $title = isset(json_decode($request->data, true)["title"]) ? json_decode($request->data, true)["title"] : "";
            $content = isset(json_decode($request->data, true)["content"]) ? json_decode($request->data, true)["content"] : "";

            $photo1Path = "";
            if ($request->hasFile('photo1')) {
                $photo1Path = Storage::disk('public')->path("publications/images/") . $request->file('photo1')->getClientOriginalName();
            }

            $photo2Path = "";
            if ($request->hasFile('photo2')) {
                $photo2Path = Storage::disk('public')->path("publications/images/") . $request->file('photo2')->getClientOriginalName();
            }

            $photo3Path = "";
            if ($request->hasFile('photo3')) {
                $photo3Path = Storage::disk('public')->path("publications/images/") . $request->file('photo3')->getClientOriginalName();
            }
            $fecha_actual_gmt_4 = Carbon::now('GMT-4')->toDateTimeString();
            $publication->update([
                'title'      => $title,
                'content'    => $content,
                'updated_at' => (string) $fecha_actual_gmt_4,
                'photo1'     => $photo1Path,
                'photo2'     => $photo2Path,
                'photo3'     => $photo3Path,
                'anonymous' => isset(json_decode($request->data, true)["anonymous"]) ? json_decode($request->data, true)["anonymous"] : $publication->anonymous,
            ]);
            return response()->json(['message' => 'Actualizacion exitosa'], 200);
        } catch (\Exception $exception) {
            return responseJSON(null, 500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        try {
            $publication->comments()->delete();
            $publication->delete();

            return responseJSON(null, 200, 'Publication deleted successfully');
        } catch (\Exception $exception) {
            return responseJSON(null, 500, $exception->getMessage());
        }
    }
}
