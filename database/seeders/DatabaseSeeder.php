<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\RepairRequest;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 1 dispatcher
        $dispatcher = User::firstOrCreate(
            ['email' => 'dispatcher@example.com'],
            [
                'name' => 'John Dispatcher',
                'password' => bcrypt('password'),
                'role' => 'dispatcher',
                'email_verified_at' => now(),
            ]
        );

        // Create 2 masters
        $master1 = User::firstOrCreate(
            ['email' => 'master1@example.com'],
            [
                'name' => 'Alice Master',
                'password' => bcrypt('password'),
                'role' => 'master',
                'email_verified_at' => now(),
            ]
        );

        $master2 = User::firstOrCreate(
            ['email' => 'master2@example.com'],
            [
                'name' => 'Bob Master',
                'password' => bcrypt('password'),
                'role' => 'master',
                'email_verified_at' => now(),
            ]
        );

        // Create sample repair requests across all statuses
        // 1. New request (not assigned)
        RepairRequest::firstOrCreate(
            ['client_name' => 'Ivan Petrov', 'phone' => '+7-999-123-4567'],
            [
                'address' => '123 Main Street',
                'problem_text' => 'Water leakage from ceiling',
                'status' => 'new',
                'assigned_to' => null,
            ]
        );

        // 2. Another new request
        RepairRequest::firstOrCreate(
            ['client_name' => 'Maria Smirnova', 'phone' => '+7-999-234-5678'],
            [
                'address' => '456 Oak Ave',
                'problem_text' => 'Broken door lock',
                'status' => 'new',
                'assigned_to' => null,
            ]
        );

        // 3. Assigned request (waiting for master to take)
        RepairRequest::firstOrCreate(
            ['client_name' => 'Sergey Volkov', 'phone' => '+7-999-345-6789'],
            [
                'address' => '789 Pine Road',
                'problem_text' => 'Electrical outlet not working',
                'status' => 'assigned',
                'assigned_to' => $master1->id,
            ]
        );

        // 4. In progress request
        RepairRequest::firstOrCreate(
            ['client_name' => 'Anna Kuznetsova', 'phone' => '+7-999-456-7890'],
            [
                'address' => '321 Birch Lane',
                'problem_text' => 'Plumbing issue in kitchen',
                'status' => 'in_progress',
                'assigned_to' => $master2->id,
            ]
        );

        // 5. Another in progress
        RepairRequest::firstOrCreate(
            ['client_name' => 'Dmitry Sokolov', 'phone' => '+7-999-567-8901'],
            [
                'address' => '654 Elm Court',
                'problem_text' => 'Wall crack repair needed',
                'status' => 'in_progress',
                'assigned_to' => $master1->id,
            ]
        );

        // 6. Completed request
        RepairRequest::firstOrCreate(
            ['client_name' => 'Elena Morozova', 'phone' => '+7-999-678-9012'],
            [
                'address' => '987 Spruce Way',
                'problem_text' => 'Roof inspection and repair',
                'status' => 'done',
                'assigned_to' => $master2->id,
            ]
        );

        // 7. Canceled request
        RepairRequest::firstOrCreate(
            ['client_name' => 'Georgy Popov', 'phone' => '+7-999-789-0123'],
            [
                'address' => '147 Maple Drive',
                'problem_text' => 'Window replacement',
                'status' => 'canceled',
                'assigned_to' => null,
            ]
        );
    }
}
