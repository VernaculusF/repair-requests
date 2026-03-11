<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateRepairRequestTest extends TestCase
{
    use RefreshDatabase;

    private function postWithCsrf(string $uri, array $data = [])
    {
        $token = 'test-csrf-token';

        return $this->withSession(['_token' => $token])
            ->post($uri, array_merge($data, ['_token' => $token]));
    }

    /**
     * Test successful repair request submission.
     */
    public function test_successful_repair_request_submission(): void
    {
        $response = $this->postWithCsrf('/requests', [
            'client_name' => 'John Doe',
            'phone' => '+1-234-567-8900',
            'address' => '123 Main Street, City',
            'problem_text' => 'The washing machine is making a loud noise',
        ]);

        $response->assertRedirect('/requests/success');
        $this->assertDatabaseHas('repair_requests', [
            'client_name' => 'John Doe',
            'phone' => '+1-234-567-8900',
            'status' => 'new',
        ]);
    }

    /**
     * Test validation failure with empty data.
     */
    public function test_validation_failure_with_empty_data(): void
    {
        $response = $this->postWithCsrf('/requests', []);

        $response->assertSessionHasErrors(['client_name', 'phone', 'address', 'problem_text']);
        $this->assertDatabaseCount('repair_requests', 0);
    }

    /**
     * Test validation failure with invalid phone format.
     */
    public function test_validation_failure_with_invalid_phone(): void
    {
        $response = $this->postWithCsrf('/requests', [
            'client_name' => 'John Doe',
            'phone' => 'invalid',
            'address' => '123 Main Street',
            'problem_text' => 'Problem description',
        ]);

        $response->assertSessionHasErrors(['phone']);
        $this->assertDatabaseCount('repair_requests', 0);
    }

    /**
     * Test valid phone formats.
     */
    public function test_various_valid_phone_formats(): void
    {
        $validPhones = [
            '+1-234-567-8900',
            '1234567890',
            '+1 (234) 567-8900',
            '234.567.8900',
        ];

        foreach ($validPhones as $phone) {
            $this->postWithCsrf('/requests', [
                'client_name' => 'John Doe',
                'phone' => $phone,
                'address' => '123 Main Street',
                'problem_text' => 'Problem',
            ])->assertRedirect('/requests/success');
        }

        $this->assertDatabaseCount('repair_requests', count($validPhones));
    }
}
