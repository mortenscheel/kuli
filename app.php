#!/usr/bin/env php
<?php
require __DIR__ . '/vendor/autoload.php';

use Kuli\FileCheckCommand;
use Kuli\GithubCloneCommand;
use Kuli\LaravelInstallCommand;
use Symfony\Component\Console\Application;

define('KULI_VERSION', '0.1.2');
$application = new Application('Kuli', KULI_VERSION);
$commands = [
    new FileCheckCommand(),
    new GithubCloneCommand(),
    new LaravelInstallCommand()
];
$application->addCommands($commands);
$application->run();