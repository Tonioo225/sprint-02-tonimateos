<?php

declare(strict_types=1);

namespace App\Validation;

final class SprintValidator
{
    public function validate(array $values): array
    {
        $errors = [];

        $name      = trim((string) ($values['name'] ?? ''));
        $goal      = trim((string) ($values['goal'] ?? ''));
        $startDate = trim((string) ($values['start_date'] ?? ''));
        $endDate   = trim((string) ($values['end_date'] ?? ''));
        $status    = trim((string) ($values['status'] ?? ''));

        if ($name === '') {
            $errors[] = 'El nom és obligatori.';
        }

        if ($goal === '') {
            $errors[] = 'L’objectiu és obligatori.';
        }

        if ($startDate === '') {
            $errors[] = 'La data d’inici és obligatòria.';
        }

        if ($endDate === '') {
            $errors[] = 'La data de fi és obligatòria.';
        }

        if ($startDate !== '' && $endDate !== '' && $startDate > $endDate) {
            $errors[] = 'La data d’inici no pot ser posterior a la data de fi.';
        }

        if (!in_array($status, ['pending', 'active', 'completed'], true)) {
            $errors[] = 'L’estat no és vàlid.';
        }

        return $errors;
    }
}