<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class AdminSubjectController extends Controller
{
    public function index(Request $request)
    {
        $orderBy = $request->get('order_by', 'id');
        $order = $request->get('order', 'desc');
        $search = $request->get('search', '');

        $subjects = Subject::with('educationLevel')
            ->where('title', 'like', "%{$search}%")
            ->orderBy($orderBy, $order)
            ->paginate();

        return view('admin.subjects.index', [
            'subjects' => $subjects,
            'paginationLinks' => $subjects->links('pagination::bootstrap-4'),
            'editRoute' => 'admin.subjects.edit',
            'deleteRoute' => 'admin.subjects.destroy'
        ]);
    }


    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'education_level_id' => 'required|exists:education_levels,id',
            'study_area_id' => 'required|exists:study_areas,id',
            'title' => 'required|string|max:255',
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')->with('success', 'Matéria criada com sucesso!');
    }

    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return view('admin.subjects.edit', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'education_level_id' => 'nullable|exists:education_levels,id',
            'study_area_id' => 'nullable|exists:study_areas,id',
            'title' => 'nullable|string|max:255',
        ]);

        $subject = Subject::findOrFail($id);
        $subject->update(array_filter($validated));

        return redirect()->route('admin.subjects.index')->with('success', 'Matéria atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.subjects.index')->with('success', 'Matéria excluída com sucesso!');
    }
}
