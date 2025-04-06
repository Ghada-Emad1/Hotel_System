<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



app(Schedule::class)->command('app:send-inacrive-user-emails')
    ->daily() // Runs once daily at midnight
    ->at('12:00') // Or specify exact time
    ->timezone('Africa/Cairo') // Set your timezone
    ->emailOutputOnFailure('admin@example.com'); // Optional error notificationsgit status
