<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AccountPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\BulkDeleteTrait;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Exception;

class AdminUserController extends Controller
{
    use BulkDeleteTrait;

    public function index(Request $request)
    {
        $order = $request->get('order', 'desc');
        $orderBy = $request->get('order_by', 'id');
        $search = $request->get('search', '');

        $query = User::with('accountPlan')
            ->when($search, function ($query, $search) {
                return $query->where('username', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy($orderBy, $order);

        $users = $query->paginate();

        return view('admin.users.index', [
            'users' => $users,
            'paginationLinks' => $users->appends($request->except('page'))->links('pagination::bootstrap-4'),
            'editRoute' => 'admin.users.edit',
            'deleteRoute' => 'admin.users.destroy',
            'bulkDeleteRoute' => 'admin.users.bulkDelete',
        ]);
    }

    public function create()
    {
        $accountPlans = AccountPlan::all();
        return view('admin.users.create', compact('accountPlans'));
    }

    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $accountPlans = AccountPlan::all();
        return view('admin.users.edit', compact('user', 'accountPlans'));
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user = User::findOrFail($id);
        $user->update(array_filter($validated));

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuário excluído com sucesso!');
    }

    public function bulkDelete(Request $request)
    {
        return $this->bulkDeletes($request, User::class, 'admin.users.index');
    }
}
