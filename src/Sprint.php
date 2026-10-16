<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;

final class Sprint
{
    public function __construct(
        private readonly string $name,
        private readonly string $goal,
        private readonly string $startDate,
        private readonly string $endDate,
        private readonly string $status,
    ) {
        if (trim($name) === '') {
            throw new InvalidArgumentException('El nom de l’esprint no és vàlid.');
        }

        if (trim($goal) === '') {
            throw new InvalidArgumentException('L’objectiu no és vàlid.');
        }

        if (!in_array($status, ['pending', 'active', 'completed'], true)) {
            throw new InvalidArgumentException('L’estat no és vàlid.');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getGoal(): string
    {
        return $this->goal;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}