<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccountPlan;
use App\Http\Controllers\Traits\BulkDeleteTrait;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;

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
            'editRoute' => 'admin.profile.index',
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

        try {
            User::create($validated);
            return redirect()->route('admin.users.index')->with('success', 'Usuário criado com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.users.index')->withErrors(['error' => 'Erro ao criar usuário: ' . $e->getMessage()]);
        }
    }

    public function edit($slug)
    {
        $user = User::findOrFail($slug);
        $accountPlans = AccountPlan::all();
        return view('auth.profile', compact('user', 'accountPlans'));
    }

    public function update(UserUpdateRequest $request, $slug)
    {
        $validated = $request->validated();
        $user = User::where('slug', $slug)->firstOrFail();

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        if ($request->hasFile('profile_img')) {
            $image = $request->file('profile_img');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/storage/admin/profile');
            $image->move($destinationPath, $name);
            $validated['profile_img'] = $name;
        }

        try {
            if (isset($validated['email']) && $validated['email'] !== $user->email) {
                $newEmail = $validated['email'];

                $user->new_email = $newEmail;
                $user->save();

                Notification::route('mail', $newEmail)
                    ->notify(new VerifyEmail);

                return redirect()->back()->with('success', 'Um e-mail de confirmação foi enviado para o novo endereço.');
            }

            $user->update(array_filter($validated));

            return redirect()->back()->with('success', 'Usuário atualizado com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro ao atualizar usuário: ' . $e->getMessage()]);
        }
    }

    public function destroy($slug)
    {
        try {
            $user = User::findOrFail($slug);
            $user->delete();

            return redirect()->route('admin.users.index')->with('success', 'Usuário excluído com sucesso!');
        } catch (Exception $e) {
            return redirect()->route('admin.users.index')->withErrors(['error' => 'Erro ao excluir usuário: ' . $e->getMessage()]);
        }
    }

    public function bulkDelete(Request $request)
    {
        return $this->bulkDeletes($request, User::class, 'admin.users.index');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        return $this->update($request, Auth::id());
    }

    public function confirmEmailChange(Request $request, $id, $email)
    {
        try {
            if (! $request->hasValidSignature()) {
                return redirect()->route('public.profile.index', ['slug' => User::findOrFail($id)->slug])
                                ->withErrors(['error' => 'Token inválido ou expirado.']);
            }

            $user = User::findOrFail($id);

            if ($user->new_email !== $email) {
                return redirect()->route('public.profile.index', ['slug' => $user->slug])
                                ->withErrors(['error' => 'Token inválido ou expirado.']);
            }

            $user->email = $user->new_email;
            $user->new_email = null;
            $user->save();

            event(new Verified($user));

            return redirect()->route('public.profile.index', ['slug' => $user->slug])->with('success', 'E-mail alterado com sucesso!');
        } catch (Exception $e) {
            // Log::error($e->getMessage());

            return redirect()->route('public.profile.index', ['slug' => User::findOrFail($id)->slug])
                            ->withErrors(['error' => 'Ocorreu um erro ao tentar alterar o e-mail. Por favor, tente novamente mais tarde.']);
        }
    }

}
