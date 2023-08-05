<?php

namespace App\Infrastructure\Repositories;

use App\Application\Contracts\ApplianceRepositoryInterface;
use App\Domain\Entities\Appliance as ApplianceEntity;
use App\Models\Appliance;
use App\Domain\Enums\ApplianceVoltage;

class EloquentApplianceRepository implements ApplianceRepositoryInterface
{
    public function getAll(): array
    {
        $appliances = Appliance::all();
        return $appliances->toArray();
    }

    public function findById(int $id): ?ApplianceEntity
    {
        $appliance = Appliance::find($id);
        return $appliance ? $this->mapToApplianceEntity($appliance) : null;
    }

    public function create(array $data): ApplianceEntity
    {
        $appliance = Appliance::create($data); 
        return $this->mapToApplianceEntity($appliance);
    }

    public function update(int $id, array $data): ApplianceEntity
    {
        $appliance = Appliance::findOrFail($id);
        $appliance->update($data);
        return $this->mapToApplianceEntity($appliance);
    }

    public function delete(int $id): true
    {
        $appliance = Appliance::findOrFail($id);
        return $appliance->delete();
    }

    private function mapToApplianceEntity(Appliance $appliance): ApplianceEntity
    {
        return new ApplianceEntity(
            $appliance->id,
            $appliance->name,
            $appliance->description,
            ApplianceVoltage::from($appliance->voltage),
            $appliance->brand_id,
        );
    }
}
