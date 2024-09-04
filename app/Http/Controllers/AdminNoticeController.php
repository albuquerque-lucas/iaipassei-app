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
            // Validação dos dados recebidos
            $validated = $request->validate([
                'examination_slug' => 'required|exists:examinations,slug',
                'file' => 'required|mimes:pdf|max:500000', // Validação para PDF
            ]);

            // Buscar o exame pelo slug
            $examination = Examination::where('slug', $validated['examination_slug'])->firstOrFail();

            // Obter o arquivo do request
            $file = $request->file('file');

            // Definir o diretório de armazenamento baseado no slug do exame
            $folderPath = 'notices/' . $examination->slug; // Criar diretório com o slug
            $filePath = $file->store($folderPath, 'public'); // Salvar arquivo no diretório público

            // Obter o nome do arquivo e extensão
            $fileName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            // Criar o novo Notice associado ao Examination
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
        // Validação dos campos
        $validated = $request->validate([
            'examination_id' => 'nullable|exists:examinations,id',
            'file' => 'nullable|mimes:pdf|max:50000',
        ]);

        try {
            $notice = Notice::findOrFail($id);

            if ($request->hasFile('file')) {
                if ($notice->file_path) {
                    \Storage::disk('public')->delete($notice->file_path);
                }

                // Obter o novo arquivo do request
                $file = $request->file('file');

                // Verificar o slug do Examination atual ou atualizado
                $examination = $notice->examination; // Exame relacionado atual
                if (isset($validated['examination_id'])) {
                    $examination = Examination::findOrFail($validated['examination_id']); // Novo exame, se foi alterado
                }

                // Criar o diretório com base no slug do exame
                $folderPath = 'notices/' . $examination->slug;
                $filePath = $file->store($folderPath, 'public'); // Salvar o arquivo no diretório correto
                $fileName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();

                // Atualizar os dados do Notice com o novo arquivo
                $notice->update([
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                    'extension' => $extension,
                ]);
            }

            // Atualizar o examination_id, caso seja enviado
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
        try {
            $notice = Notice::findOrFail($id);

            if ($notice->file_path) {
                Storage::disk('public')->delete($notice->file_path);
            }

            $notice->delete();

            return redirect()->back()->with('success', 'Edital excluído com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir o edital: ' . $e->getMessage());
        }
    }

    public function download($id)
    {
        try {
            $notice = Notice::findOrFail($id);

            $filePath = storage_path('app/public/' . $notice->file_path);

            if (Storage::disk('public')->exists($notice->file_path)) {
                return response()->download($filePath, $notice->file_name);
            } else {
                return redirect()->back()->with('error', 'Arquivo não encontrado.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao fazer download do arquivo: ' . $e->getMessage());
        }
    }

}
