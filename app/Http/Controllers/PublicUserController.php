<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Controllers\Traits\ValidatesAndPreparesData;
use App\Services\EmailService;
use App\Helpers\MessageHelper;

class PublicUserController extends Controller
{
    use ValidatesAndPreparesData;

    protected $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function update(UserUpdateRequest $request)
    {
        $validated = $this->validateAndPrepareData($request);

        $user = Auth::user();
        $emailUpdated = $this->emailService->processEmailUpdate($validated, $user);

        try {
            $this->updateUserProfile($validated, $user);

            $successMessage = MessageHelper::generateSuccessMessage($emailUpdated);

            if ($this->allKeysAreNull($validated) && $emailUpdated) {
                return redirect()->back()->with('success', 'Um e-mail de confirmação foi enviado para o novo endereço.');
            }

            return redirect()->back()->with('success', $successMessage);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Erro ao atualizar perfil: ' . $e->getMessage()]);
        }
    }

    private function updateUserProfile($validated, $user)
    {
        $user->update(array_filter($validated));
    }

    private function allKeysAreNull($validated)
    {
        return empty(array_filter($validated, function ($value) {
            return !is_null($value);
        }));
    }
}
