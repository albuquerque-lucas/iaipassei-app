<?php

namespace App\Helpers;

class MessageHelper
{
    public static function generateSuccessMessage($emailUpdated)
    {
        $successMessage = 'Perfil atualizado com sucesso!';
        if ($emailUpdated) {
            $successMessage .= ' Um e-mail de confirmação também foi enviado para o novo endereço.';
        }
        return $successMessage;
    }
}
