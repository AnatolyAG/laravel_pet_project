<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\User;

use Exception;

class LogUserCreated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        // dd($event);
        if ($event->error) {
            Log::error('Error creating user: ' . $event->error->getMessage());
        }else {
            Log::info('New user created with ID: ' . $event->user->id);
        }
    }
}
