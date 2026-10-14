<?php

declare(strict_types=1);

namespace App\Validation;

final class UserValidator
{
    /** @return list<string> */
    public function validate(array $values): array
    {
        $errors = [];
        $username = trim((string) ($values['username'] ?? ''));
        if ($username === '') {
            $errors[] = 'El username és obligatori.';
        }
        if (!mb_strlen(trim((string) ($values['email'] ?? '')))) {
            $errors[] = 'El correu no pot estar buit.';
        }
        if ($values['password'] === '' || mb_strlen($values['password']) < 4 || mb_strlen($values['password'])){
            $errors[] = 'La contrasenya te que tindre entre 4caracters i 20 caracters.';
        }
        if($values['password'] !== $values['repeat_password']){
                $errors[] = 'La contrasenya no coincideix.';
        }

        return $errors;
    }
}