<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

final class User
{
    private string $status;

    public function __construct(
        private readonly string  $username,
        private readonly string  $password,
        private readonly string  $name,
        private readonly string $email,
    ) {
        if (trim($username) === '') {
            throw new InvalidArgumentException('El nom del usuari no valid.');
        }
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

}
