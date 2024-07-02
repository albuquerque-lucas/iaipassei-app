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
    }

    public function create()
    {
        return view('admin.account_plans.create');
    }

    public function store(Request $request)
    {
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
    }

    public function edit($id)
    {
        $accountPlan = AccountPlan::findOrFail($id);
        return view('admin.account_plans.edit', compact('accountPlan'));
    }

    public function update(Request $request, $id)
    {
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
    }

    public function destroy($id)
    {
        $accountPlan = AccountPlan::findOrFail($id);
        $accountPlan->delete();

        return redirect()->route('admin.account_plans.index')->with('success', 'Plano de conta excluído com sucesso!');
    }

    public function bulkDelete(Request $request)
    {
        return $this->bulkDeletes($request, AccountPlan::class, 'admin.account_plans.index');
    }
}
