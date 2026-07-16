<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\ReleaseExpiredHoldsJob;

Schedule::job(new ReleaseExpiredHoldsJob)
    ->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');
