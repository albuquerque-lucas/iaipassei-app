<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examination;
use Auth;
use Exception;

class PublicPagesController extends Controller
{
    public function examinations(Request $request)
    {
        try {
            $order = $request->get('order', 'desc');
            $orderBy = $request->get('order_by', 'id');
            $search = $request->get('search', '');

            $query = Examination::with('educationLevel')
                ->when($search, function ($query, $search) {
                    return $query->where('title', 'like', "%{$search}%");
                })
                ->orderBy($orderBy, $order);

            $examinations = $query->paginate();

            return view('public.examinations.index', [
                'examinations' => $examinations,
                'paginationLinks' => $examinations->appends($request->except('page'))->links('pagination::bootstrap-4'),
                'title' => 'Concursos | IaiPassei',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar concursos: ' . $e->getMessage());
        }
    }

    public function examination($slug)
    {
        try {
            $examination = Examination::with(['educationLevel', 'exams'])->where('slug', $slug)->firstOrFail();
            return view('public.examinations.show', [
                'examination' => $examination,
                'title' => $examination->title . ' | Concursos | IaiPassei',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar concurso: ' . $e->getMessage());
        }
    }

    public function subscribe($id)
    {
        try {
            $examination = Examination::findOrFail($id);
            $user = Auth::user();

            if (!$user->examinations->contains($examination->id)) {
                $user->examinations()->attach($examination->id);
                return redirect()->back()->with('success', 'Inscrição realizada com sucesso!');
            }

            return redirect()->back()->with('info', 'Você já está inscrito neste concurso.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao realizar inscrição: ' . $e->getMessage());
        }
    }
}
