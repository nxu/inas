#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new \Nxu\Inas\Commands\Install());
$application->add(new \Nxu\Inas\Commands\AddSite());
$application->add(new \Nxu\Inas\Commands\RemoveSite());
$application->add(new \Nxu\Inas\Commands\Generate());
$application->add(new \Nxu\Inas\Commands\Start());
$application->add(new \Nxu\Inas\Commands\Stop());
$application->add(new \Nxu\Inas\Commands\ExecPhp());
$application->add(new \Nxu\Inas\Commands\ExecComposer());

$application->run();
