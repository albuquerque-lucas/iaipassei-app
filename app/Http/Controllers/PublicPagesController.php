<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examination;
use App\Models\ExamQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class PublicPagesController extends Controller
{
    public function home(Request $request)
    {
        $title = "Iai Passei";
        return view('public.guests.home', compact('title'));
    }

    public function aboutUs(Request $request)
    {
        $title = "Sobre nós | Iai Passei";
        return view('public.guests.aboutUs', compact('title'));
    }

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
            $examination = Examination::with(['educationLevel', 'exams.examQuestions'])
                                    ->where('slug', $slug)
                                    ->firstOrFail();

            $user = auth()->user();

            foreach ($examination->exams as $exam) {
                $totalQuestions = $exam->examQuestions->count();
                $answeredQuestions = $user->markedAlternatives()
                                        ->whereIn('user_question_alternatives.exam_question_id', $exam->examQuestions->pluck('id'))
                                        ->count();

                if ($answeredQuestions === 0) {
                    $exam->resultStatus = null;
                } elseif ($answeredQuestions < $totalQuestions) {
                    $exam->resultStatus = 'partial';
                } else {
                    $exam->resultStatus = 'final';
                }
            }

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

    public function unsubscribe($id)
    {
        try {
            $examination = Examination::findOrFail($id);
            $user = Auth::user();

            if ($user->examinations->contains($examination->id)) {
                $examQuestionIds = ExamQuestion::whereHas('exam', function ($query) use ($examination) {
                    $query->where('examination_id', $examination->id);
                })->pluck('id');

                DB::table('user_question_alternatives')
                    ->where('user_id', $user->id)
                    ->whereIn('exam_question_id', $examQuestionIds)
                    ->delete();

                $user->examinations()->detach($examination->id);

                return redirect()->back()->with('success', 'Inscrição retirada com sucesso!');
            }

            return redirect()->back()->with('info', 'Você não está inscrito neste concurso.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao retirar inscrição: ' . $e->getMessage());
        }
    }


}
