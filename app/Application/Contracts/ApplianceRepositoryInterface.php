<?php

namespace App\Application\Contracts;

use App\Domain\Entities\Appliance;

interface ApplianceRepositoryInterface
{
    public function getAll(): array;
    public function findById(int $id): ?Appliance;
    public function create(array $data): Appliance;
    public function update(int $id, array $data): Appliance;
    public function delete(int $id): true;
}
