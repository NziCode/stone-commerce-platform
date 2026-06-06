<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('sitemap:generate')->daily();
Schedule::command('responsecache:clear')->weekly();
