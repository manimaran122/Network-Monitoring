<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Models\Monitor;
use App\Jobs\RunMonitorCheck;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function () {
    Monitor::active()->get()->filter->shouldCheck()->each(fn($m) => RunMonitorCheck::dispatch($m));
})->everyMinute();
