<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Exception;
use App\Notifications\VerifyEmail;
use App\Http\Requests\UserUpdateRequest;

class PublicUserController extends Controller
{
    public function update(UserUpdateRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        if ($request->hasFile('profile_img')) {
            $image = $request->file('profile_img');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/storage/profile');
            $image->move($destinationPath, $name);
            $validated['profile_img'] = $name;
        }

        $emailUpdated = false;

        try {
            if (isset($validated['email']) && $validated['email'] !== $user->email) {
                $newEmail = $validated['email'];

                $user->new_email = $newEmail;
                $user->save();

                Notification::route('mail', $newEmail)
                    ->notify(new VerifyEmail($newEmail, $user->id));

                unset($validated['email']);
                $emailUpdated = true;
            }

            $user->update(array_filter($validated));

            $successMessage = 'Perfil atualizado com sucesso!';
            if ($emailUpdated) {
                $successMessage .= ' Um e-mail de confirmação também foi enviado para o novo endereço.';
            }

            $allNull = empty(array_filter($validated, function ($value) {
                return !is_null($value);
            }));

            if ($allNull && $emailUpdated) {
                return redirect()->back()->with('success', 'Um e-mail de confirmação foi enviado para o novo endereço.');
            }

            return redirect()->back()->with('success', $successMessage);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro ao atualizar perfil: ' . $e->getMessage()]);
        }
    }


}
