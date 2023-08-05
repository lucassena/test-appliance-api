<?php

namespace App\Domain\Entities;

readonly class Brand
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
