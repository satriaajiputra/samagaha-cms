<?php

function store_flash_input() {
  $input = $_POST;
  unset($input['password'], $input['password_confirmation']);

  foreach($input as $index => $row) {
    $_SESSION['flash_input'][$index] = $row;
  }
}

function old_input($name) {
  return isset($_SESSION['flash_input'][$name]) ? $_SESSION['flash_input'][$name] : '';
}