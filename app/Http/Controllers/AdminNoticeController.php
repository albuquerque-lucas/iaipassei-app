<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Models\Examination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminNoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::with('examination')->paginate();

        return view('admin.notices.index', [
            'notices' => $notices,
            'paginationLinks' => $notices->links('pagination::bootstrap-4'),
            'editRoute' => 'admin.notices.edit',
            'deleteRoute' => 'admin.notices.destroy'
        ]);
    }

    public function create()
    {
        return view('admin.notices.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'examination_slug' => 'required|exists:examinations,slug',
                'file' => 'required|mimes:pdf|max:500000',
            ]);

            $examination = Examination::where('slug', $validated['examination_slug'])->firstOrFail();

            $file = $request->file('file');
            $filePath = $file->store('notices', 'public');
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            Notice::create([
                'examination_id' => $examination->id,
                'file_path' => $filePath,
                'file_name' => $fileName,
                'extension' => $extension,
            ]);

            return redirect()->back()->with('success', 'Edital criado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao criar o edital: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        return view('admin.notices.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'examination_id' => 'nullable|exists:examinations,id',
            'file' => 'nullable|mimes:pdf|max:10240',
        ]);

        try {
            $notice = Notice::findOrFail($id);

            if ($request->hasFile('file')) {
                if ($notice->file_path) {
                    Storage::disk('public')->delete($notice->file_path);
                }

                $file = $request->file('file');
                $filePath = $file->store('notices', 'public');
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                $notice->update([
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'extension' => $extension,
                ]);
            }

            if (isset($validated['examination_id'])) {
                $notice->update([
                    'examination_id' => $validated['examination_id'],
                ]);
            }

            return redirect()->back()->with('success', 'Edital atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar o edital: ' . $e->getMessage());
        }
    }



    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);

        if ($notice->file_path) {
            Storage::disk('public')->delete($notice->file_path);
        }

        $notice->delete();

        return redirect()->back()->with('success', 'Edital excluído com sucesso!');
    }

    public function download($id)
{
    try {
        $notice = Notice::findOrFail($id);
        $filePath = storage_path('app/public/' . $notice->file_path);

        if (file_exists($filePath)) {
            return response()->download($filePath, $notice->file_name);
        } else {
            return redirect()->back()->with('error', 'Arquivo não encontrado.');
        }
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Erro ao fazer download do arquivo: ' . $e->getMessage());
    }
}
}
