<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ClearPermissionCache extends Command
{
    protected $signature = 'permission:clear-cache {user_id?}';
    protected $description = 'Clear permission cache untuk user tertentu atau semua user';

    public function handle()
    {
        $userId = $this->argument('user_id');
        
        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $user->refreshRelations();
                $this->info("Cache cleared for user ID: {$userId}");
            } else {
                $this->error("User not found: {$userId}");
            }
        } else {
            $users = User::all();
            foreach ($users as $user) {
                $user->refreshRelations();
            }
            $this->info("Cache cleared for all {$users->count()} users");
        }
    }
}