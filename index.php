<?php
declare(strict_types=1);

use InsulinJunkieDe\NightscoutToMqtt\NightscoutToMqtt;

require __DIR__ . '/vendor/autoload.php';

(new NightscoutToMqtt(__DIR__))();
