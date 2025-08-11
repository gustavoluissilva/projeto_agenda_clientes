<?php
declare(strict_types=1);

namespace App\Model\Entity;

// A LINHA CORRETA É ESTA:
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * ...
 */
class User extends Entity
{
    /**
     * Campos que podem ser atribuídos em massa.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'names' => true,
        'email' => true,
        'password' => true,
        'phone' => true,
        'user_type' => true,
        'date_register' => true,
        'schedule' => true,
    ];

    /**
     * Campos escondidos.
     *
     * @var array<string>
     */
    protected array $_hidden = [
        'password',
    ];

    /**
     * Mutator para a senha.
     */
    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
        return $password;
    }
}