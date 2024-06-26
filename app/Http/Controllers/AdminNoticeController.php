<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminNoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::with('examination')->paginate();
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        return view('admin.notices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'file' => 'required|mimes:pdf|max:10240',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('notices', 'public');
        $fileName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        Notice::create([
            'examination_id' => $validated['examination_id'],
            'file_path' => $filePath,
            'file_name' => $fileName,
            'extension' => $extension,
        ]);

        return redirect()->route('admin.notices.index')->with('success', 'Edital criado com sucesso!');
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

        $notice->update(array_filter([
            'examination_id' => $validated['examination_id'],
        ]));

        return redirect()->route('admin.notices.index')->with('success', 'Edital atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);

        if ($notice->file_path) {
            Storage::disk('public')->delete($notice->file_path);
        }

        $notice->delete();

        return redirect()->route('admin.notices.index')->with('success', 'Edital excluído com sucesso!');
    }
}
