<?php

require __DIR__ . '/../core/system/system.php';

$_SERVER['HOST_TYPE'] = $argv[1];

App::run(false);

(new \Model\Links\Storage())->removeExpiredLinks();