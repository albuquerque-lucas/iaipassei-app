<?php

namespace App\Http\Controllers;

use App\Models\Examination;
use Illuminate\Http\Request;

class AdminExaminationController extends Controller
{
    public function index(Request $request)
    {
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
            'deleteRoute' => 'admin.examinations.destroy'
        ]);
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
