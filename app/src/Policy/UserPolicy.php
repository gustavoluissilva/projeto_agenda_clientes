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
        // Acessamos 'user_type' diretamente como uma propriedade.
        return $identity->user_type === 'admin';
    }

    /**
     * Verifica se um usuário pode ver o dashboard.
     */
    public function canDashboard(IdentityInterface $identity, User $user): bool
    {
        // Acessamos o ID do usuário logado diretamente com '->id'.
        // Comparamos com o ID do recurso ($user) que está sendo verificado.
        return $identity->id === $user->id;
    }
}