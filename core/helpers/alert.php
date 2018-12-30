<?php

function set_flash_message($status = false, $message) {
  $result = array();
  if($status) $result['status'] = $status;
  $result['message'] = $message;
  $_SESSION['flash_message'] = $result;
}