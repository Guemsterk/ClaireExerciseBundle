<?php
error_reporting(E_ALL | E_STRICT);

$loader = require __DIR__ . '/../vendor/autoload.php';

$loader->add('SimpleIT\ClaireAppBundle\\', __DIR__ .'');
