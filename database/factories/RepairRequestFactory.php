<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RequestStatus;
use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RepairRequest>
 */
class RepairRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'problem_text' => fake()->paragraph(3),
            'status' => fake()->randomElement([
                RequestStatus::New,
                RequestStatus::Assigned,
                RequestStatus::InProgress,
                RequestStatus::Done,
                RequestStatus::Canceled,
            ]),
            'assigned_to' => null,
        ];
    }

    /**
     * Set request to "new" status (not assigned).
     */
    public function asNew(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::New,
            'assigned_to' => null,
        ]);
    }

    /**
     * Set request to "assigned" status with a master.
     */
    public function assigned(?User $master = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::Assigned,
            'assigned_to' => $master?->id ?? User::factory()->master()->create()->id,
        ]);
    }

    /**
     * Set request to "in_progress" status.
     */
    public function inProgress(?User $master = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::InProgress,
            'assigned_to' => $master?->id ?? User::factory()->master()->create()->id,
        ]);
    }

    /**
     * Set request to "done" status.
     */
    public function done(?User $master = null): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::Done,
            'assigned_to' => $master?->id ?? User::factory()->master()->create()->id,
        ]);
    }

    /**
     * Set request to "canceled" status.
     */
    public function canceled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::Canceled,
            'assigned_to' => null,
        ]);
    }
}
