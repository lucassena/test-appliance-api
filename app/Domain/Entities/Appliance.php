<?php

namespace App\Domain\Entities;

use App\Domain\Enums\ApplianceVoltage;

readonly class Appliance
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public ApplianceVoltage $voltage,
        public int $brand_id,
    ) {}
}
