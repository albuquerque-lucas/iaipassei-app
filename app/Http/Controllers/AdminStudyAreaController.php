<?php

namespace App\Http\Controllers;

use App\Models\StudyArea;
use Illuminate\Http\Request;

class AdminStudyAreaController extends Controller
{
    public function index()
    {
        $studyAreas = StudyArea::paginate();
        $columns = [
            ['label' => 'ID', 'field' => 'id'],
            ['label' => 'Nome', 'field' => 'name'],
        ];

        return view('admin.study_areas.index', [
            'studyAreas' => $studyAreas,
            'columns' => $columns,
            'paginationLinks' => $studyAreas->links('pagination::bootstrap-4'),
            'editRoute' => 'admin.study_areas.edit',
            'deleteRoute' => 'admin.study_areas.destroy'
        ]);
    }

    public function create()
    {
        return view('admin.study_areas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        StudyArea::create($validated);

        return redirect()->route('admin.study_areas.index')->with('success', 'Área de estudo criada com sucesso!');
    }

    public function edit($id)
    {
        $studyArea = StudyArea::findOrFail($id);
        return view('admin.study_areas.edit', compact('studyArea'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        $studyArea = StudyArea::findOrFail($id);
        $studyArea->update($validated);

        return redirect()->route('admin.study_areas.index')->with('success', 'Área de estudo atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $studyArea = StudyArea::findOrFail($id);
        $studyArea->delete();

        return redirect()->route('admin.study_areas.index')->with('success', 'Área de estudo excluída com sucesso!');
    }
}
