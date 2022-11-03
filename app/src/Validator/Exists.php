<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Exists extends Constraint
{
    public ?string $repository = null;
    public ?string $column = null;

    public string $message = 'The value does not exists in database.';

    public function validatedBy()
    {
        return static::class.'Validator';
    }
}