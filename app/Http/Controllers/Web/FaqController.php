<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return view('admin.faqs_admin', [
            'faqs' => Faq::all()
        ]);
    }

    public function saveFaq(Request $request)
    {
        if ($request->save == "true") {
            Faq::create([
                'question' => $request->question,
                'answer' => $request->answer,
                'status' => $request->state
            ]);
            session()->flash('success', 'Pregunta creada');
            return redirect()->back();
        }
    }

    public function getFaq(Request $request)
    {
        $faq = Faq::where('id', $request->id)->first();

        return responseJSON($faq, 200, 'Faq encontrada');
    }

    public function deleteFaq(Faq $faq)
    {
        try {
            $faq->delete();
            session()->flash('success', 'Pregunta Eliminada');
            return redirect()->back();
        } catch (\Exception $e) {
            return responseJSON(null, 500, $e->getMessage());
        }
    }
}
