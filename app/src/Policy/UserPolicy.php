<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * User policy
 */
class UserPolicy
{
    /**
     * Verifica se um usuário tem permissão de administrador.
     */
    public function canAdmin(IdentityInterface $identity): bool
    {
        return $identity->user_type === 'admin';
    }

    /**
     * Verifica se um usuário pode ver o dashboard.
     */
    public function canDashboard(IdentityInterface $identity, User $user): bool
    {
        return $identity->id === $user->id;
    }

    /**
     * Verifica se um usuário pode acessar a tela de confirmação.
     */
    public function canConfirm(IdentityInterface $identity, User $user): bool
    {
        // Qualquer usuário logado pode confirmar um agendamento.
        return true;
    }

    /**
     * Verifica se um usuário pode salvar um agendamento.
     */
    public function canSave(IdentityInterface $identity, User $user): bool
    {
        // Qualquer usuário logado pode salvar um agendamento.
        return true;
    }
}