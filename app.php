#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application('echo', '1.0.0');
$command = new \Kuli\FileCheckCommand();

$application->add($command);

$application->run();