<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Artisan::command('delete:recent-users', function () {
//    $this->info('Comman run');
// })->purpose('Delete recent users')->everyTwoSeconds();
