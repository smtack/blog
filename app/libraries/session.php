<?php
session_start();

// Flash function to display messages to user

function flash($name = '', $message = '', $class = 'flash') {
  if(!empty($name)) {
    if(!empty($message) && empty($_SESSION['name'])) {
      if(!empty($_SESSION['name'])) {
        unset($_SESSION['name']);
      }

      if(!empty($_SESSION[$name . '_class'])) {
        unset($_SESSION[$name . '_class']);
      }

      $_SESSION[$name] = $message;
      $_SESSION[$name . '_class'] = $class;
    } else if(empty($message) && !empty($_SESSION[$name])) {
      $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';

      echo '<div class="' . $class . '">' . 
              '<p>' . $_SESSION[$name] . '</p>' .
              '<span class="close">&times;</span>' .
            '</div>';
      
      unset($_SESSION[$name]);
      unset($_SESSION[$name . '_class']);
    }
  }
}


// Check if user is logged in

function loggedIn() {
  if(isset($_SESSION['user'])) {
    return true;
  } else {
    return false;
  }
}

// Create Token

function generate($token) {
  return $_SESSION[$token] = random(64);
}

// Check Token

function check($token, $name) {
  if(isset($_SESSION[$name]) && hash_equals($_SESSION[$name], $token)) {
    unset($_SESSION[$name]);

    return true;
  }

  return false;
}