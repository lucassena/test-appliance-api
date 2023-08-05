<?php

namespace App\Http\Controllers\API;

use App\Application\Services\ApplianceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplianceRequest;
use App\Http\Requests\UpdateApplianceRequest;

class ApplianceController extends Controller
{
    public function __construct(
        private ApplianceService $applianceService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appliances = $this->applianceService->getAllAppliances();
        return response()->json($appliances);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplianceRequest $request)
    {
        $appliance = $this->applianceService->createAppliance($request->all());
        return response()->json($appliance, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $appliance = $this->applianceService->findApplianceById($id);

        if (!$appliance) {
            return response()->json(['message' => 'Appliance not found.'], 404);
        }

        return response()->json($appliance);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApplianceRequest $request, int $id)
    {
        $appliance = $this->applianceService->updateAppliance($id, $request->all());

        if (!$appliance) {
            return response()->json(['message' => 'Appliance not found.'], 404);
        }
        
        return response()->json($appliance);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->applianceService->deleteAppliance($id);
        return response()->json([], 204);
    }
}
