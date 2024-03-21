#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Kernel;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Symfony\Component\Dotenv\Dotenv;

// replace with path to your own project bootstrap file
require_once __DIR__ . '/../vendor/autoload.php';

// replace with mechanism to retrieve EntityManager in your app
function getEntityManager()
{
    (new Dotenv())->bootEnv(__DIR__ . '/../.env');

    $kernel = new Kernel($_SERVER['APP_ENV'], (bool)$_SERVER['APP_DEBUG']);
    $kernel->boot();

    return $kernel->getContainer()->get('doctrine')->getManager();
}

$entityManager = getEntityManager();

$commands = [];

ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands,
);
