<?php

namespace App\Application\Services;

use App\Application\Contracts\ApplianceRepositoryInterface;
use App\Domain\Entities\Appliance;

class ApplianceService
{
    public function __construct(
        private ApplianceRepositoryInterface $applianceRepository
    ) {}

    public function getAllAppliances(): array
    {
        return $this->applianceRepository->getAll();
    }

    public function findApplianceById(int $id): ?Appliance
    {
        return $this->applianceRepository->findById($id);
    }

    public function createAppliance(array $data): Appliance
    {
        return $this->applianceRepository->create($data);
    }

    public function updateAppliance(int $id, array $data): Appliance
    {
        return $this->applianceRepository->update($id, $data);
    }

    public function deleteAppliance(int $id): true
    {
        return $this->applianceRepository->delete($id);
    }
}
