<?php

use Symfony\Component\Debug\Debug;
use Symfony\Component\HttpFoundation\Request;

/** @var \Composer\Autoload\ClassLoader $loader */

# Config autoload
$loader = require __DIR__ . '/../app/autoload.php';

# Enable Symfony Debug
Debug::enable();

# Create kernel instance
$kernel = new AppKernel('dev', true);

# Create request instance
$request = Request::createFromGlobals();

# Give response instance
$response = $kernel->handle($request);
$response->send();

# Bye!
$kernel->terminate($request, $response);