<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\EducationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\BulkDeleteTrait;
use Exception;

class AdminSubjectController extends Controller
{
    use BulkDeleteTrait;

    public function index(Request $request)
    {
        try {
            $orderBy = $request->get('order_by', 'id');
            $order = $request->get('order', 'desc');
            $search = $request->get('search', '');

            $educationLevels = EducationLevel::all();

            $subjects = Subject::with('educationLevel')
                ->where('title', 'like', "%{$search}%")
                ->orderBy($orderBy, $order)
                ->paginate();

            return view('admin.subjects.index', [
                'subjects' => $subjects,
                'paginationLinks' => $subjects->links('pagination::bootstrap-4'),
                'editRoute' => 'admin.subjects.edit',
                'deleteRoute' => 'admin.subjects.destroy',
                'educationLevels' => $educationLevels,
                'bulkDeleteRoute' => 'admin.subjects.bulkDelete',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar matérias: ' . $e->getMessage());
        }
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'education_level_id' => 'required|exists:education_levels,id',
                'study_area_id' => 'nullable|exists:study_areas,id',
                'title' => 'required|string|max:255',
            ]);

            Subject::create($validated);

            return redirect()->route('admin.subjects.index')->with('success', 'Matéria criada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao criar matéria: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $educationLevels = EducationLevel::all();
            $deleteRoute = 'admin.subjects.destroy';
            return view('admin.subjects.edit', compact('subject', 'educationLevels', 'deleteRoute'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar matéria para edição: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'education_level_id' => 'nullable|exists:education_levels,id',
                'study_area_id' => 'nullable|exists:study_areas,id',
                'title' => 'nullable|string|max:255',
            ]);

            $subject = Subject::findOrFail($id);
            $subject->update(array_filter($validated));

            return redirect()->route('admin.subjects.edit', $id)->with('success', 'Matéria atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar matéria: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();

            return redirect()->route('admin.subjects.index')->with('success', 'Matéria excluída com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir matéria: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            return $this->bulkDeletes($request, Subject::class, 'admin.subjects.index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir matérias em massa: ' . $e->getMessage());
        }
    }
}
