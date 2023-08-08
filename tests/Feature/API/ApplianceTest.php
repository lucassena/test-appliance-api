<?php

namespace Tests\Feature\API;

use App\Models\Appliance;
use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ApplianceTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * A basic feature test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertSuccessful();
    }

    public function test_it_gets_appliances(): void
    {
        $brand = Brand::factory()->createOne();
        $appliances = Appliance::factory(3)->create([
            'brand_id' => $brand->id,
        ]);
        
        $response = $this->getJson('/api/appliances');

        $response->assertOk();
        $response->assertJsonCount(3);

        $response->assertJson(function (AssertableJson $json) use ($appliances) {
            $json->hasAll(['0.id', '0.name', '0.description', '0.voltage', '0.brand_id']);

            $json->whereAllType([
                '0.name' => 'string',
                '0.description' => 'string',
                '0.voltage' => 'string',
                '0.brand_id' => 'integer',
            ]);

            $appliance = $appliances->first();
            $json->whereAll([
                '0.id' => $appliance->id,
                '0.name' => $appliance->name,
                '0.description' => $appliance->description,
                '0.voltage' => $appliance->voltage,
                '0.brand_id' => $appliance->brand_id,
            ]);
        });
    }

    public function test_it_gets_single_appliance(): void
    {
        $appliance = Appliance::factory()->createOne([
            'brand_id' => Brand::factory()->createOne(),
        ]);
        
        $response = $this->getJson('/api/appliances/' . $appliance->id);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) use ($appliance) {
            $json->hasAll(['id', 'name', 'description', 'voltage', 'brand_id'])->etc();

            $json->whereAllType([
                'name' => 'string',
                'description' => 'string',
                'voltage' => 'string',
                'brand_id' => 'integer',
            ]);

            $json->whereAll([
                'id' => $appliance->id,
                'name' => $appliance->name,
                'description' => $appliance->description,
                'voltage' => $appliance->voltage,
                'brand_id' => $appliance->brand_id,
            ]);
        });
    }

    public function test_it_stores_new_appliance(): void
    {
        $data = Appliance::factory()->raw([
            'brand_id' => Brand::factory()->createOne(),
        ]);
        
        $response = $this->postJson('/api/appliances', $data);

        $response->assertCreated();

        $response->assertJson(function (AssertableJson $json) use ($data) {
            $json->hasAll(['id', 'name', 'description', 'voltage', 'brand_id'])->etc();
            $json->whereAll([
                'name' => $data['name'],
                'description' => $data['description'],
                'voltage' => $data['voltage'],
                'brand_id' => $data['brand_id'],
            ])->etc();
        });
    }

    public function test_it_validates_storing_new_appliance(): void
    {
        $response = $this->postJson('/api/appliances', []);

        $response->assertUnprocessable();

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors']);
            $json->whereAll([
                'errors.name.0' => "The name field is required.",
                'errors.description.0' => "The description field is required.",
                'errors.voltage.0' => "The voltage field is required.",
                'errors.brand_id.0' => "The brand id field is required.",
            ]);
        });
    }

    public function test_it_put_updates_appliance(): void
    {
        $brand = Brand::factory()->createOne();
        Appliance::factory(3)->create([
            'brand_id' => $brand->id,
        ]);

        $appliance = Appliance::with('brand')->first();

        $data['name'] = 'Updated Appliance';
        $data['description'] = 'Updated description lorem ipsum';
        $data['voltage'] = $appliance->voltage;
        $data['brand_id'] = $appliance->brand->id;

        $response = $this->putJson('/api/appliances/' . $appliance->id, $data);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) use ($data) {
            $json->hasAll(['id', 'name', 'description', 'voltage', 'brand_id'])->etc();
            $json->whereAll([
                'name' => $data['name'],
                'description' => $data['description'],
                'voltage' => $data['voltage'],
                'brand_id' => $data['brand_id'],
            ])->etc();
        });
    }

    public function test_it_patch_updates_appliance(): void
    {
        $brand = Brand::factory()->createOne();
        Appliance::factory(3)->create([
            'brand_id' => $brand->id,
        ]);

        $appliance = Appliance::with('brand')->first();

        $data['name'] = 'Patch Appliance';

        $response = $this->patchJson('/api/appliances/' . $appliance->id, $data);

        $response->assertOk();

        $response->assertJson(function (AssertableJson $json) use ($data) {
            $json->hasAll(['id', 'name', 'description', 'voltage', 'brand_id'])->etc();
            $json->where('name', $data['name']);
        });
    }

    public function test_it_validates_updating_appliance_when_not_found(): void
    {
        $brand = Brand::factory()->createOne();

        $nonExistentId = 404; // just for fun, this could be any number

        $data['name'] = 'Updated Appliance';
        $data['description'] = 'Updated description lorem ipsum';
        $data['voltage'] = '220V';
        $data['brand_id'] = $brand->id;

        $response = $this->putJson('/api/appliances/' . $nonExistentId, $data);
        
        $response->assertNotFound();
    }

    public function test_it_validates_updating_appliance(): void
    {
        $brand = Brand::factory()->createOne();
        Appliance::factory(3)->create([
            'brand_id' => $brand->id,
        ]);

        $appliance = Appliance::with('brand')->first();

        $data['name'] = 'Updated Appliance';
        $data['description'] = 'Updated description lorem ipsum';
        $data['voltage'] = '';
        $data['brand_id'] = 2;

        $response = $this->putJson('/api/appliances/' . $appliance->id, $data);

        $response->assertUnprocessable();

        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['message', 'errors']);
            $json->whereAll([
                'errors.brand_id.0' => "The selected brand id is invalid.",
                'errors.voltage.0' => "The voltage field is required.",
            ]);
        });
    }

    public function test_it_deletes_appliance(): void
    {
        $appliance = Appliance::factory()->createOne([
            'brand_id' => Brand::factory()->createOne(),
        ]);

        // Delete the resource
        $response = $this->deleteJson('/api/appliances/' . $appliance->id);
        $response->assertNoContent();

        // Check resource existence after deleting it
        $response = $this->getJson('/api/appliances/' . $appliance->id);
        $response->assertNotFound();
    }
}
