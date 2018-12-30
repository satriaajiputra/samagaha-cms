<?php
defined('BASEPATH') or die('No Script Kiddies Please!');
/*
Load all config data

Created At: 29 Dec 2018
At: Bandung
By: Satria Aji Putra Karma Jaya
*/

// load configs here
$config['app'] = require_once BASEPATH . "/configs/app.php";
$config['database'] = require_once BASEPATH . "/configs/database.php";

// function for calling config
if(!function_exists('config')) {
  function config($location) {
    global $config;
    $index = explode('.', $location);
    $result = $config;
    foreach($index as $row) {
      if(isset($result[$row])) {
        $result = $result[$row];
      }
    }
    return $result;
  }
}