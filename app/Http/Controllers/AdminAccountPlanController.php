<?php

namespace App\Http\Controllers;

use App\Models\AccountPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\BulkDeleteTrait;
use Exception;

class AdminAccountPlanController extends Controller
{
    use BulkDeleteTrait;

    public function index(Request $request)
    {
        try {
            $order = $request->get('order', 'desc');
            $orderBy = $request->get('order_by', 'id');
            $search = $request->get('search', '');

            $query = AccountPlan::when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%");
                })
                ->orderBy($orderBy, $order);

            $accountPlans = $query->paginate();

            return view('admin.account_plans.index', [
                'accountPlans' => $accountPlans,
                'paginationLinks' => $accountPlans->appends($request->except('page'))->links('pagination::bootstrap-4'),
                'editRoute' => 'admin.account_plans.edit',
                'deleteRoute' => 'admin.account_plans.destroy',
                'bulkDeleteRoute' => 'admin.account_plans.bulkDelete',
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao carregar planos de conta: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('admin.account_plans.create');
        } catch (Exception $e) {
            return redirect()->route('admin.account_plans.index')->with('error', 'Erro ao abrir o formulário de criação: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'access_level_id' => 'required|exists:access_levels,id',
                'name' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'price' => 'required|numeric|min:0',
                'duration_days' => 'required|integer|min:1',
                'is_public' => 'required|boolean',
            ]);

            AccountPlan::create($validated);

            return redirect()->route('admin.account_plans.index')->with('success', 'Plano de conta criado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao criar o plano de conta: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $accountPlan = AccountPlan::findOrFail($id);
            return view('admin.account_plans.edit', compact('accountPlan'));
        } catch (Exception $e) {
            return redirect()->route('admin.account_plans.index')->with('error', 'Erro ao carregar o plano de conta para edição: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'access_level_id' => 'nullable|exists:access_levels,id',
                'name' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'nullable|numeric|min:0',
                'duration_days' => 'nullable|integer|min:1',
                'is_public' => 'nullable|boolean',
            ]);

            $accountPlan = AccountPlan::findOrFail($id);
            $accountPlan->update($validated);

            return redirect()->route('admin.account_plans.index')->with('success', 'Plano de conta atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar o plano de conta: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $accountPlan = AccountPlan::findOrFail($id);
            $accountPlan->delete();

            return redirect()->route('admin.account_plans.index')->with('success', 'Plano de conta excluído com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.account_plans.index')->with('error', 'Erro ao excluir o plano de conta: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            return $this->bulkDeletes($request, AccountPlan::class, 'admin.account_plans.index');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao excluir planos de conta em massa: ' . $e->getMessage());
        }
    }
}
