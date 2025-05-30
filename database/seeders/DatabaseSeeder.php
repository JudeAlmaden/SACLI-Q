<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Queue;
use App\Models\Window;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::factory()->create([
            'name' => 'Admin',
            'account_id' => '0000-0001',
            'access_type' => 'admin',
            'password' => bcrypt('pXajsdua132'),
        ]);

        // Create Main Building Queue
        $queue = Queue::create([
            'name' => 'Main Building',
            'code' => strtoupper(Str::random(6)), 
        ]);

        // Define Windows
        $windows = [
            [
                'name' => 'Accounting',
                'description' => 'Handles billing and payment inquiries.'
            ],
            [
                'name' => 'Cashier',
                'description' => 'Processes payments and issues receipts.'
            ],
            [
                'name' => 'Registrar',
                'description' => 'Manages student records and enrollment.'
            ]
        ];

        $ticketNumber = 1;

        foreach ($windows as $windowData) {
            // Create Window under Main Building queue
            $window = Window::create([
                'name' => $windowData['name'],
                'description' => $windowData['description'],
                'queue_id' => $queue->id,
                'limit' => 50,
                'status' => 'open',
            ]);

            //Uncomment the following lines to seed the windows with tickets for testing purposes
            // Generate 10 tickets for today
            // for ($i = 1; $i <= 10; $i++) {
            //     // Generate 4-digit padded code
            //     $code = str_pad($ticketNumber, 4, '0', STR_PAD_LEFT);

            //     // Add a few seconds to each ticket's timestamp to make them unique
            //     $createdAt = Carbon::today()->addSeconds($ticketNumber * 10);

            //     Ticket::create([
            //         'queue_id' => $queue->id,
            //         'window_id' => $window->id,
            //         'name' => 'User ' . $i,
            //         'status' => 'Waiting',
            //         'ticket_number' => $i,
            //         'code' => $code,
            //         'created_at' => $createdAt,
            //         'updated_at' => $createdAt,
            //     ]);

            //     $ticketNumber++;
            // }
        }
    }
}
