<?php

function base_url($path = '/')
{
  $protocol = config('app.use_ssl') ? 'https://' : 'http://';
  return $protocol . $_SERVER['SERVER_NAME'] . $path;
}

function is_method($method)
{
  return ($_SERVER['REQUEST_METHOD'] == $method);
}

function redirect($url, $num){
  header(config('http.' . $num));
  header ("Location: $url");
  exit;
}

function abort($code)
{
  header(config('http.' . $code));
  require_once BASEPATH . '/views/error/' . $code . '.php';
  exit;
}