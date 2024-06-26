<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use Illuminate\Http\Request;

class AdminExaminationController extends Controller
{
    public function index()
    {
        $examinations = Examination::with('educationLevel')->paginate(10);
        return view('admin.examinations.index', compact('examinations'));
    }

    public function create()
    {
        return view('admin.examinations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'educational_level_id' => 'required|exists:education_levels,id',
            'title' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        Examination::create($validated);

        return redirect()->route('admin.examinations.index')->with('success', 'Concurso criado com sucesso!');
    }

    public function show($id)
    {
        $examination = Examination::with('educationLevel')->findOrFail($id);
        return view('admin.examinations.show', compact('examination'));
    }

    public function edit($id)
    {
        $examination = Examination::findOrFail($id);
        return view('admin.examinations.edit', compact('examination'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'educational_level_id' => 'required|exists:education_levels,id',
            'title' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'active' => 'required|boolean',
        ]);

        $examination = Examination::findOrFail($id);
        $examination->update($validated);

        return redirect()->route('admin.examinations.index')->with('success', 'Concurso atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $examination = Examination::findOrFail($id);
        $examination->delete();

        return redirect()->route('admin.examinations.index')->with('success', 'Concurso excluído com sucesso!');
    }
}
