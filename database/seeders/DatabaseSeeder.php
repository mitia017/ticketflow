<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $agents = [];
        for ($i = 1; $i <= 3; $i++) {
            $agents[] = User::create([
                'name' => "Agent {$i}",
                'email' => "agent{$i}@demo.com",
                'password' => Hash::make('password'),
                'role' => 'agent',
            ]);
        }

        $viewer = User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@demo.com',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);

        $statuses = ['open', 'in_progress', 'resolved', 'closed'];
        $priorities = ['low', 'medium', 'high', 'critical'];

        // Liste des utilisateurs pour les commentaires
        $allUserIds = array_merge([$admin->id], array_column($agents, 'id'), [$viewer->id]);

        for ($i = 1; $i <= 50; $i++) {
            $ticket = Ticket::create([
                'title' => "Sample Ticket {$i}",
                'description' => "This is a sample description for ticket {$i}.",
                'status' => $statuses[array_rand($statuses)],
                'priority' => $priorities[array_rand($priorities)],
                'created_by' => $viewer->id,
                'assigned_to' => $agents[array_rand($agents)]->id,
                'closed_at' => rand(0, 1) ? now()->subDays(rand(1, 30)) : null,
                'created_at' => now()->subDays(rand(1, 60)),
            ]);

            for ($j = 1; $j <= rand(0, 5); $j++) {
                Comment::create([
                    'content' => "Comment {$j} for ticket {$i}.",
                    'ticket_id' => $ticket->id,
                    'user_id' => $allUserIds[array_rand($allUserIds)],
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}