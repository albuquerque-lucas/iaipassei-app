<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait BulkDeleteTrait
{
    public function bulkDeletes(Request $request, $model, $route)
    {
        $ids = json_decode($request->input('selected_ids', '[]'));

        if (empty($ids)) {
            return redirect()->route($route)->with('error', 'Nenhum item selecionado para exclusão.');
        }

        DB::beginTransaction();
        try {
            $model::whereIn('id', $ids)->delete();
            DB::commit();
            return redirect()->route($route)->with('success', 'Itens excluídos com sucesso!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route($route)->with('error', 'Ocorreu um erro ao excluir os itens. Por favor, tente novamente.');
        }
    }
}
