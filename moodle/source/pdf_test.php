<?php

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');

global $SESSION;

header('Content-Type: application/pdf');

$result = $SESSION->pdf;

echo $result;