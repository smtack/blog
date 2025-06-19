<?php
class Users extends Controller {
  private $db;

  public $userModel;
  public $postModel;

  public function __construct() {
    $this->db = new Database();

    $this->userModel = $this->model('User');
    $this->postModel = $this->model('Post');
  }

  public function index() {
    if(!loggedIn()) {
      redirect('users/signup');
    } else {
      $user_data = $this->userModel->getUser($_SESSION['user']);
    }

    $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;

    $start = ($page > 1) ? ($page * 10) - 10 : 0;

    $users = $this->userModel->getUsers($start);

    $total = $this->db->pdo->query("SELECT FOUND_ROWS() AS total")->fetch()->total;

    $pages = ceil($total / 10);

    $data = [
      'page_title' => 'Users',
      'userData' => $user_data,
      'page' => $page,
      'pages' => $pages,
      'users' => $users,
    ];

    $this->view('users/index', $data);
  }

  public function signup() {
    if(loggedIn()) {
      redirect('posts');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [
        'page_title' => 'Sign Up',
        'user_name' => escape($_POST['user_name']),
        'user_username' => escape($_POST['user_username']),
        'user_email' => escape($_POST['user_email']),
        'user_password' => escape($_POST['user_password']),
        'confirm_password' => escape($_POST['confirm_password']),
        'password_hash' => password_hash($_POST['user_password'], PASSWORD_BCRYPT),
        'token' => $_POST['token'],
        'errors' => array()
      ];

      if(!check($data['token'], 'token')) {
        array_push($data['errors'], 'Token Invalid');
      } else if(empty($data['user_name']) || empty($data['user_username']) || empty($data['user_email']) || empty($data['user_password']) || empty($data['confirm_password'])) {
        array_push($data['errors'], 'Fill in all fields');
      } else if(!filter_var($data['user_email'], FILTER_VALIDATE_EMAIL)) {
        array_push($data['errors'], 'Enter a valid email address');
      } else if($data['user_password'] != $data['confirm_password']) {
        array_push($data['errors'], 'Passwords must match');
      } else if($this->db->exists('users', array('user_username' => $data['user_username']))) {
        array_push($data['errors'], 'This username is already taken');
      } else if($this->db->exists('users', array('user_email' => $data['user_email']))) {
        array_push($data['errors'], 'This email address is already in use');
      }

      if(empty($data['errors'])) {
        if($this->userModel->signUp($data)) {
          flash('user_message', 'Welcome to Blog, ' . $data['user_name']);

          $_SESSION['user'] = $data['user_username'];

          redirect('posts');
        } else {
          array_push($data['errors'], 'Unable to Sign Up. Try again later');
        }
      } else {
        $this->view('users/signup', $data);
      }
    } else {
      $data = [
        'page_title' => 'Sign Up',
        'user_name' => '',
        'user_username' => '',
        'user_email' => '',
        'user_password' => '',
        'confirm_password' => '',
        'errors' => array()
      ];
    
      $this->view('users/signup', $data);
    }
  }

  public function login() {
    if(loggedIn()) {
      redirect('posts');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [
        'page_title' => 'Log In',
        'user_username' => escape($_POST['user_username']),
        'user_password' => escape($_POST['user_password']),
        'token' => $_POST['token'],
        'errors' => array()
      ];

      if(!check($data['token'], 'token')) {
        array_push($data['errors'], 'Token Invalid');
      } else if(empty($data['user_username']) || empty($data['user_password'])) {
        array_push($data['errors'], 'Enter your Username and Password');
      }
      
      if(empty($data['errors'])) {        
        $authenticate = $this->userModel->logIn($data['user_username'], $data['user_password']);

        if($authenticate) {
          $this->createSession($authenticate);
        } else {
          $data = [
            'page_title' => 'Log In',
            'user_username' => escape($_POST['user_username']),
            'user_password' => escape($_POST['user_password']),
            'errors' => array('Username or Password are incorrect')
          ];

          $this->view('users/login', $data);
        }
      } else {
        $this->view('users/login', $data);
      }
    } else {
      $data = [
        'page_title' => 'Log In',
        'user_username' => '',
        'user_password' => '',
        'errors' => array()
      ];

      $this->view('users/login', $data);
    }
  }

  public function createSession($user) {
    $_SESSION['user'] = $user->user_username;

    flash('user_message', 'Welcome back ' . $user->user_name);

    redirect('posts');
  }

  public function logout() {
    unset($_SESSION['user']);

    session_destroy();

    redirect('users');
  }

  public function profile($user = false) {
    if(!$user) {
      redirect('posts');
    } else if(!$profile = $this->userModel->getUser($user)) {
      redirect(404);
    }

    if(loggedIn()) {
      $user_data = $this->userModel->getUser($_SESSION['user']);
      $profile_info = $this->userModel->getProfileInfo($user_data->user_id, $profile->user_id);
    }

    $page = isset($_GET['p']) ? (int)$_GET['p'] : 1;

    $start = ($page > 1) ? ($page * 10) - 10 : 0;

    $posts = $this->postModel->getUsersPosts($profile->user_id, $start);

    $total = $this->db->pdo->query("SELECT FOUND_ROWS() AS total")->fetch()->total;

    $pages = ceil($total / 10);

    $data = [
      'page_title' => $profile->user_name . "'s Profile",
      'profile' => $profile,
      'profile_info' => $profile_info,
      'page' => $page,
      'pages' => $pages,
      'posts' => $posts
    ];

    $this->view('users/profile', $data);
  }

  public function update() {
    if(!loggedIn()) {
      redirect('users/login');
    }

    $user_data = $this->userModel->getUser($_SESSION['user']);

    $data = [
      'page_title' => 'Update Profile',
      'user_data' => $user_data
    ];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      switch($_POST) {
        case isset($_POST['update']):
          $data = [
            'user_name' => escape($_POST['user_name']),
            'user_username' => escape($_POST['user_username']),
            'user_email' => escape($_POST['user_email']),
            'token' => $_POST['token'],
            'user_data' => $user_data,
            'user_id' => $user_data->user_id,
            'errors' => array()
          ];

          if(!check($data['token'], 'token')) {
            array_push($data['errors'], 'Token Invalid');
          } else if(empty($data['user_name']) || empty($data['user_username']) || empty($data['user_email'])) {
            array_push($data['errors'], 'Fill in all fields');
          } else if($this->db->exists('users', array('user_username' => $data['user_username'])) && $data['user_username'] !== $data['user_data']->user_username) {
            array_push($data['errors'], 'This username is already taken');
          } else if($this->db->exists('users', array('user_email' => $data['user_email'])) && $data['user_email'] !== $data['user_data']->user_email) {
            array_push($data['errors'], 'This email address is already taken');
          } else if(!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            array_push($data['errors'], 'Enter a valid email address');
          }

          if(empty($data['errors'])) {
            if($this->userModel->updateProfile($data)) {
              $_SESSION['user'] = $data['user_username'];
        
              flash('user_message', 'Profile Updated Successfully');

              redirect('users/update');
            } else {
              array_push($data['errors'], 'Unable to update profile');
            }
          } else {
            $this->view('users/update', $data);
          }

          break;
        case isset($_POST['upload_profile_picture']):
          $data = [
            'user_profile_picture' => basename($_FILES['profile_picture']['name']),
            'picture_errors' => array(),
            'user_data' => $user_data,
            'user_id' => $user_data->user_id,
            'token' => $_POST['picture-token']
          ];

          $upload_dir = "../uploads/profile-pictures/";
          $path = $upload_dir . $data['user_profile_picture'];
          $file_type = pathinfo($path, PATHINFO_EXTENSION);

          if(!check($data['token'], 'picture-token')) {
            array_push($data['picture_errors'], 'Token Invalid');
          } else if(empty($_FILES['profile_picture']['name'])) {
            array_push($data['picture_errors'], 'Select a file to upload');
          } else if(!in_array($file_type, $GLOBALS['allow_file_types'])) {
            array_push($data['picture_errors'], 'This file type is not allowed');
          } else if(!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $path)) {
            array_push($data['picture_errors'], 'Unable to upload profile picture');
          }

          if(empty($data['picture_errors'])) {
            if($this->userModel->uploadProfilePicture($data)) {
              flash('user_message', 'Profile Picture Uploaded');

              redirect('users/update');
            } else {
              array_push($data['picture_errors'], 'Unable to upload profile picture');
            }
          } else {
            $this->view('users/update', $data);
          }

          break;
        case isset($_POST['change_password']):
          $data = [
            'user_password' => escape($_POST['user_password']),
            'new_password' => escape($_POST['new_password']),
            'confirm_password' => escape($_POST['confirm_password']),
            'password_hash' => password_hash($_POST['new_password'], PASSWORD_BCRYPT),
            'user_data' => $user_data,
            'user_id' => $user_data->user_id,
            'token' => $_POST['password-token'],
            'password_errors' => array()
          ];

          if(!check($data['token'], 'password-token')) {
            array_push($data['password_errors'], 'Token Invalid');
          } else if(empty($data['user_password']) || empty($data['new_password']) || empty($data['confirm_password'])) {
            array_push($data['password_errors'], 'Fill in all fields');
          } else if(!password_verify($data['user_password'], $user_data->user_password)) {
            array_push($data['password_errors'], 'Enter your current password correctly');
          } else if($data['new_password'] !== $data['confirm_password']) {
            array_push($data['password_errors'], 'Passwords must match');
          }

          if(empty($data['password_errors'])) {
            if($this->userModel->changePassword($data)) {
              flash('user_message', 'Password updated succesfully');

              redirect('users/update');
            } else {
              array_push($data['password_errors'], 'Unable to change password');
            }
          } else {
            $this->view('users/update', $data);
          }

          break;
        case isset($_POST['delete_profile']):
          $data = [
            'user_data' => $user_data,
            'user_password' => escape($_POST['user_password']),
            'token' => $_POST['delete-token'],
            'delete_errors' => array()
          ];

          if(!check($data['token'], 'delete-token')) {
            array_push($data['delete_errors'], 'Token Invalid');
          } else if(empty($data['user_password'])) {
            array_push($data['delete_errors'], 'Enter your password');
          } else if(!password_verify($data['user_password'], $user_data->user_password)) {
            array_push($data['delete_errors'], 'Enter your password correctly');
          }

          if(empty($data['delete_errors'])) {
            if($this->userModel->deleteProfile($user_data->user_id)) {
              $this->logout();
            } else {
              array_push($data['delete_errors'], 'Unable to delete profile');
            }
          } else {
            $this->view('users/update', $data);
          }

          break;
        default:
          $this->view('users/update', $data);

          break;
      }
    }

    $this->view('users/update', $data);
  }

  public function follow($user) {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
      if(!loggedIn()) {
        redirect('users/login');
      } else if(!$user) {
        redirect('posts');
      } else {
        $user_data = $this->userModel->getUser($_SESSION['user']);
      }
  
      $follow = $this->userModel->getUser($user);
  
      if($follow) {
        if($follow === $user_data->user_id) {
          redirect('posts');
        } else {
          $data = [
            'user_id' => $user_data->user_id,
            'followed_id' => $follow->user_id
          ];
  
          if($this->userModel->follow($data)) {
            redirect('users/profile/' . $follow->user_username);
          }
        }
      } else {
        redirect('posts');
      }
    }
  }

  public function unfollow($user) {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
      if(!loggedIn()) {
        redirect('users/login');
      } else if(!$user) {
        redirect('posts');
      } else {
        $user_data = $this->userModel->getUser($_SESSION['user']);
      }
  
      $follow = $this->userModel->getUser($user);
  
      if($follow) {
        if($follow === $user_data->user_id) {
          redirect('posts');
        } else {
          $data = [
            'user_id' => $user_data->user_id,
            'followed_id' => $follow->user_id
          ];
  
          if($this->userModel->unfollow($data)) {
            redirect('users/profile/' . $follow->user_username);
          }
        }
      } else {
        redirect('posts');
      }
    }
  }
}