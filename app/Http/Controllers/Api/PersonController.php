<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Firebase\FirebaseToken;
use Carbon\Carbon;


class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return responseJSON(Person::with('doctor', 'team', 'role')->get(), 200, 'Success');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function getPeopleToChat(Request $request)
    {
        $token = new FirebaseToken($request->bearerToken());

        try {
            $payload = $token->verify_other(config('services.firebase.project_id'));
        } catch (\Exception $e) {
            return responseJSON(null, 401, $e->getMessage());
        }
        $email = $payload->authenticated_user->email;

        try {
            $people = DB::table('people as p')
            ->select('p.id','p.email')
            ->where('p.email', '!=',$email)
            ->get();
            
            return responseJSON($people, 200, 'Success');
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $url = Storage::disk('public')->put($request->image->getClientOriginalName(), $request->image);
            $request->merge([
                'photo' => env('APP_URL').'/storage/'.$url,
                'password' => Hash::make($request->password),
            ]);
            $person = Person::create($request->except('image'));

            return responseJSON($person, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Person $person)
    {
        try {
            return responseJSON($person->load('doctor', 'team', 'role'), 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Person $person)
    {
        try {
            if (isset($request->image)) {
                $url = Storage::disk('public')->put($request->image->getClientOriginalName(), $request->image);
                $request->merge([
                    'photo' => env('APP_URL').'/storage/'.$url,
                ]);
            }
            if (isset($request->password)) {
                $request->merge([
                    'password' => Hash::make($request->password),
                ]);
            }
            $person->update($request->all());

            return responseJSON($person, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, $e->getMessage(), 500);
        }
    }
    public function updateUser(Request $request,$id){
        $person = Person::find($id);
        try {
            $photo1Path = "";
            if ($request->hasFile('photo1')) {
                $photo1Path = Storage::disk('public')->path("publications/images/") . $request->file('photo1')->getClientOriginalName();
            }
            $fecha_actual_gmt_4 = Carbon::now('GMT-4')->toDateTimeString();
            $person->update([
                'name'=> isset(json_decode($request->data, true)["name"]) ? json_decode($request->data, true)["name"] :$person->name,
                "lastname"=>isset(json_decode($request->data, true)["lastname"]) ? json_decode($request->data, true)["lastname"] :$person->lastname,
                "mother_lastname"=> isset(json_decode($request->data, true)["mother_lastname"]) ? json_decode($request->data, true)["mother_lastname"] :$person->mother_lastname,
                "photo" => $photo1Path,
                "birthday"=>isset(json_decode($request->data, true)["birthday"]) ? json_decode($request->data, true)["birthday"] :$person->birthday,
                "gender"=>isset(json_decode($request->data, true)["gender"]) ? json_decode($request->data, true)["gender"] :$person->gender,
                "phone"=>isset(json_decode($request->data, true)["phone"]) ? json_decode($request->data, true)["phone"] :$person->phone,
                'updated_at' => (string) $fecha_actual_gmt_4,
            ]);
            //return responseJSON(isset(json_decode($request->data, true)["birthday"]) ?json_decode($request->data, true)["birthday"]:$person->birthday, 200,"OK");
            //return responseJSON(isset(json_decode($request->data, true)["birthday"]) ? json_decode($request->data, true)["birthday"] :$person->birthday , 200,"OK");
            return response()->json(['message' => 'Actualizacion exitosa'], 200);
        } catch (\Exception $exception) {
            return responseJSON(null, 500, $exception->getMessage());
        }

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Person $person)
    {
        try {
            $person->delete();

            return responseJSON($person, 200, 'Success');
        } catch (\Exception $e) {
            return responseJSON(null, $e->getMessage(), 500);
        }
    }
}
