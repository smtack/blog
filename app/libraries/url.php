<?php
// Redirect Function 

function redirect($location = null) {
  if($location) {
    if(is_numeric($location)) {
      switch($location) {
        case 404:
          header('HTTP/1.0 404 Not Found');

          include_once VIEW_ROOT . '/errors/404.php';

          exit();
        break;
      }
    } else {
      header('Location: ' . BASE_URL . $location);

      exit();
    }
  }
}

// Return BASE_URL + Optional location

function base_url($location = null) {
  if(!$location) {
    return BASE_URL;
  } else {
    return BASE_URL . $location;
  }
}

// Return PHP_SELF

function self() {
  return $_SERVER['PHP_SELF'];
}

// Create a slug

function slug($string) {
  $divider = '-';

  if(mb_check_encoding($string, 'ASCII')) {
    $string = preg_replace('~[^\pL\d]+~u', $divider, $string);

    $string = iconv('utf-8', 'ASCII//TRANSLIT', $string);
  
    $string = preg_replace('~[^-\w]+~', '', $string);
  
    $string = preg_replace('~-+~', $divider, $string);
  
    $string = strtolower($string);
  
    $string = trim($string, $divider);
  
    $string .= $divider . random(6);
  } else {
    $string = random(25);
  }

  return $string;
}