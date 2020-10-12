<?php

use Crunz\Schedule;

$schedule = new Schedule();
$task = $schedule->run('/usr/local/bin/php /srv/app/bin/console update:stats');
$task->everyHour();


return $schedule;
