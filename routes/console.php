<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sitemap:generate')->daily();
Schedule::command('responsecache:clear')->weekly();
Schedule::command('reservations:expire')->everyFiveMinutes();
Schedule::command('carts:expire')->everyFiveMinutes();
Schedule::command('events:sync-status')->hourly();
