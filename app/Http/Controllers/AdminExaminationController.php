<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\BulkDeleteTrait;
use Exception;

class AdminExaminationController extends Controller
{
    use BulkDeleteTrait;

    public function index(Request $request)
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

            return view('admin.examinations.index', [
                'examinations' => $examinations,
                'paginationLinks' => $examinations->appends($request->except('page'))->links('pagination::bootstrap-4'),
                'editRoute' => 'admin.examinations.edit',
                'deleteRoute' => 'admin.examinations.destroy',
                'bulkDeleteRoute' => 'admin.examinations.bulkDelete',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar concursos: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('admin.examinations.create');
        } catch (Exception $e) {
            return redirect()->route('admin.examinations.index')->with('error', 'Erro ao abrir o formulário de criação: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'educational_level_id' => 'required|exists:education_levels,id',
                'title' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'active' => 'required|boolean',
            ]);

            Examination::create($validated);

            return redirect()->route('admin.examinations.index')->with('success', 'Concurso criado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao criar o concurso: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $examination = Examination::findOrFail($id);
            return view('admin.examinations.edit', compact('examination'));
        } catch (Exception $e) {
            return redirect()->route('admin.examinations.index')->with('error', 'Erro ao carregar o concurso para edição: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'educational_level_id' => 'required|exists:education_levels,id',
                'title' => 'required|string|max:255',
                'institution' => 'required|string|max:255',
                'active' => 'required|boolean',
            ]);

            $examination = Examination::findOrFail($id);
            $examination->update($validated);

            return redirect()->route('admin.examinations.index')->with('success', 'Concurso atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar o concurso: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $examination = Examination::findOrFail($id);
            $examination->delete();

            return redirect()->route('admin.examinations.index')->with('success', 'Concurso excluído com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.examinations.index')->with('error', 'Erro ao excluir o concurso: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            return $this->bulkDeletes($request, Examination::class, 'admin.examinations.index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir concursos em massa: ' . $e->getMessage());
        }
    }
}
