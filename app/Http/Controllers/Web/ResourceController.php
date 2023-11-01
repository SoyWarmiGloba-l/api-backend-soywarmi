<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Brian2694\Toastr\Facades\Toastr;
class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::all('id', 'name', 'description', 'type', 'url')->toArray();;

        return view('admin.resource_admin', compact('resources'));
    }
    public function deleteResource(Resource $resource)
    {
        $resource->delete();
        return Redirect::back()->with('success', 'Resource deleted successfully');
    }
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveResource(Request $request)
    {

//        try {
            $path = Storage::disk('public')->putFileAs('resources', $request->file('file'), trim($request->file('file')->getClientOriginalName()));
            Resource::create([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'code_type' => $request->file('file')->getClientOriginalExtension(),
                'url' => config('app.url') . '/storage/' . trim($path)
            ]);
            session()->flash('success', 'Recurso creado exitosamente');
            return Redirect::back()->with('success', 'Resource created successfully');
  /*      } catch (\Exception $e) {
            return Redirect::back()->with('error', $e->getMessage());
        }*/
    }
}
