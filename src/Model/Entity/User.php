<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher; // VERIFIQUE ESTA LINHA
use Cake\ORM\Entity;

class User extends Entity
{
    protected array $_accessible = [
        'names' => true,
        'email' => true,
        'password' => true,
        'phone' => true,
        'user_type' => true,
        'date_register' => true,
        'schedule' => true,
    ];

    protected array $_hidden = [
        'password',
    ];

    /**
     * Mutator para a senha. Criptografa a senha automaticamente.
     */
    protected function _setPassword(string $password) : ?string
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher())->hash($password);
        }
        return $password;
    }
}