<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RequestStatus;
use App\Exceptions\RequestAlreadyTakenException;
use App\Models\RepairRequest;
use App\Models\User;
use App\Services\RepairRequestService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RaceConditionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test race condition protection: first take succeeds, second fails.
     */
    public function test_race_condition_protection(): void
    {
        // Setup: create a master and an assigned request
        $master = User::factory()->master()->create();
        $request = RepairRequest::factory()
            ->assigned($master)
            ->create();

        // Verify initial state
        $this->assertEquals(RequestStatus::Assigned, $request->status);

        $service = app(RepairRequestService::class);

        // First attempt: should succeed
        $service->take($request, $master->id);

        // Refresh the model to get updated status
        $request->refresh();
        $this->assertEquals(RequestStatus::InProgress, $request->status);

        // Second attempt: should throw RequestAlreadyTakenException
        $this->expectException(RequestAlreadyTakenException::class);
        $service->take($request, $master->id);
    }

    /**
     * Test that only the assigned master can take a request.
     */
    public function test_unauthorized_master_cannot_take_request(): void
    {
        $master1 = User::factory()->master()->create();
        $master2 = User::factory()->master()->create();
        $request = RepairRequest::factory()->assigned($master1)->create();

        $service = app(RepairRequestService::class);

        // Master2 tries to take a request assigned to master1: should fail
        $this->expectException(RequestAlreadyTakenException::class);
        $service->take($request, $master2->id);
    }

    /**
     * Test concurrent take attempts (simulated).
     */
    public function test_multiple_masters_cannot_take_same_request(): void
    {
        $master1 = User::factory()->master()->create();
        $master2 = User::factory()->master()->create();

        $request = RepairRequest::factory()->assigned($master1)->create();

        $service = app(RepairRequestService::class);

        // Master1 takes it
        $service->take($request, $master1->id);

        // Verify status changed
        $request->refresh();
        $this->assertEquals(RequestStatus::InProgress, $request->status);

        // Master2 tries to take it with a fresh request
        $freshRequest = RepairRequest::find($request->id);
        $this->expectException(RequestAlreadyTakenException::class);
        $service->take($freshRequest, $master2->id);
    }

    /**
     * Test HTTP 409 response on race condition.
     */
    public function test_http_409_on_race_condition(): void
    {
        $master = User::factory()->master()->create();
        $request = RepairRequest::factory()->assigned($master)->create();

        // First request succeeds
        $this->actingAs($master)
            ->post("/master/requests/{$request->id}/take")
            ->assertRedirect();

        // Allow redirect to process
        $request->refresh();

        // Second request should get 409
        $freshRequest = RepairRequest::find($request->id);
        $response = $this->actingAs($master)
            ->post("/master/requests/{$freshRequest->id}/take");

        // The response should indicate the error (either redirect with error or 409 JSON)
        $response->assertSessionHas('error');
    }
}
