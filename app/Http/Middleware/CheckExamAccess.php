<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Exam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CheckExamAccess
{
    public function handle(Request $request, Closure $next)
    {
        $examSlug = $request->route('exam');
        $user = Auth::user();
        $exam = Exam::where('slug', $examSlug)->firstOrFail();

        try {

            if ($user && $user->can('canAccessExam', $exam)) {
                return $next($request);
            }

            return redirect()->route('public.examinations.show', $exam->examination->slug)
                ->with('error', 'Você não tem permissão para acessar esta prova. Se ainda não estiver inscrito, clique no botão Inscrever.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('public.examinations.show', $exam->examination->slug)
                ->with('error', 'Prova não encontrada.');
        } catch (Exception $e) {
            return redirect()->route('public.examinations.show', $exam->examination->slug)
                ->with('error', 'Ocorreu um erro ao tentar acessar a prova. Tente novamente mais tarde.');
        }
    }
}
