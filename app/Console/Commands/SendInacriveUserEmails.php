<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\InactiveUserNotification;
use Carbon\Carbon;
use App\Models\User;

class SendInacriveUserEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-inacrive-user-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where('last_login_at', '<', Carbon::now()->subMonth())->get();

        foreach ($users as $user) {
            $user->notify(new InactiveUserNotification());
        }

        $this->info('Inactive user emails sent successfully!');
    }
}
