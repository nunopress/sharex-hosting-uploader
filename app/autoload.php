<?php

# Composer autoloader
/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require __DIR__  .'/../vendor/autoload.php';

# Enable doctrine annotations
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader([ $loader, 'loadClass' ]);

return $loader;