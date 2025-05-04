<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:update-days-left')
    ->dailyAt('00:00');
