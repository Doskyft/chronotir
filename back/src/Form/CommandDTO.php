<?php

declare(strict_types=1);

namespace App\Form;

class CommandDTO
{
    public string $syncId;

    public ?int $preparationTime = 10;

    public ?int $shotTime = 120;

    public ?int $warningTime = 30;
}
