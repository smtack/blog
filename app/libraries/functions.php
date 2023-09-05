<?php
// Check for a value in a multidimensional array

function findValue($array, $key, $value) {
  foreach($array as $item) {
    if(is_array($item) && findValue($item, $key, $value)) {
      return true;
    }

    if(isset($item[$key]) && $item[$key] == $value) {
      return true;
    }
  }

  return false;
}

// Format dates

function formatDate($date, $format = APP_DATE_TIME_FORMAT) {
  return date($format, strtotime($date));
}

// Sanitize user input and database output

function escape($string) {
  return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

// Create Random Bytes

function random($number) {
  return bin2hex(random_bytes($number));
}