<?php

ini_set('display_errors', 'on');
define('BASEPATH', __DIR__.'/..');

require_once BASEPATH . '/configs/config.php';
require_once BASEPATH . '/core/db/connection.php';

$conn = new Connection();