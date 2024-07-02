<?php

namespace App\Http\Controllers;

use App\Models\StudyArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AdminStudyAreaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $orderBy = $request->get('order_by', 'id');
            $order = $request->get('order', 'desc');
            $search = $request->get('search', '');

            $studyAreas = StudyArea::where('name', 'like', "%{$search}%")
                ->orderBy($orderBy, $order)
                ->paginate();

            return view('admin.study_areas.index', [
                'studyAreas' => $studyAreas,
                'paginationLinks' => $studyAreas->links('pagination::bootstrap-4'),
                'editRoute' => 'admin.study_areas.edit',
                'deleteRoute' => 'admin.study_areas.destroy',
                'bulkDeleteRoute' => 'admin.study_areas.bulkDelete',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar áreas de estudo: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('admin.study_areas.create');
        } catch (Exception $e) {
            return redirect()->route('admin.study_areas.index')->with('error', 'Erro ao abrir o formulário de criação: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);

            StudyArea::create($validated);

            return redirect()->route('admin.study_areas.index')->with('success', 'Área de estudo criada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao criar a área de estudo: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $studyArea = StudyArea::findOrFail($id);
            return view('admin.study_areas.edit', compact('studyArea'));
        } catch (Exception $e) {
            return redirect()->route('admin.study_areas.index')->with('error', 'Erro ao abrir o formulário de edição: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'nullable|string|max:255',
            ]);

            $studyArea = StudyArea::findOrFail($id);
            $studyArea->update($validated);

            return redirect()->route('admin.study_areas.index')->with('success', 'Área de estudo atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar a área de estudo: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $studyArea = StudyArea::findOrFail($id);
            $studyArea->delete();

            return redirect()->route('admin.study_areas.index')->with('success', 'Área de estudo excluída com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.study_areas.index')->with('error', 'Erro ao excluir a área de estudo: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->input('selected_ids', '[]'));

        if (empty($ids)) {
            return redirect()->route('admin.study_areas.index')->with('error', 'Nenhuma área selecionada para exclusão.');
        }

        DB::beginTransaction();
        try {
            StudyArea::whereIn('id', $ids)->delete();
            DB::commit();
            return redirect()->route('admin.study_areas.index')->with('success', 'Áreas excluídas com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.study_areas.index')->with('error', 'Ocorreu um erro ao excluir as áreas. Por favor, tente novamente.');
        }
    }
}
