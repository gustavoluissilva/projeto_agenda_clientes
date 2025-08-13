<?php

declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\User;
use Cake\Mailer\Mailer;
use Cake\Routing\Router;

class UserMailer extends Mailer
{
    /**
     * Define o conteúdo do email de redefinição de senha.
     */
    public function passwordReset(User $user)
    {
        $resetLink = Router::url([
            'controller' => 'Users',
            'action' => 'resetPassword',
            $user->password_reset_token
        ], true);

        $this
            ->setTo($user->email, $user->names)
            ->setSubject('Redefinição de Senha')

            // ADICIONE ESTA LINHA PARA ENVIAR APENAS O EMAIL EM HTML
            ->setEmailFormat('html')

            ->setViewVars([
                'resetLink' => $resetLink,
                'userName' => $user->names,
            ])
            ->viewBuilder()
            ->setTemplate('password_reset');
    }
}
