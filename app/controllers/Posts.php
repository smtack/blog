<?php
class Posts extends Controller {
  public $userModel;
  public $postModel;
  
  public function __construct() {
    if(!loggedIn()) {
      redirect('users/login');
    }

    $this->userModel = $this->model('User');
    $this->postModel = $this->model('Post');
  }

  public function index() {
    $user_data = $this->userModel->getUser($_SESSION['user']);
    $posts = $this->postModel->getHomepagePosts($user_data->user_id);

    $data = [
      'user_data' => $user_data,
      'posts' => $posts
    ];

    $this->view('posts/index', $data);
  }

  public function create() {
    if(!loggedIn()) {
      redirect('users/login');
    }

    $user_data = $this->userModel->getUser($_SESSION['user']);

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [
        'page_title' => 'New Post',
        'user_data' => $user_data,
        'post_title' => escape($_POST['post_title']),
        'post_slug' => escape(slug($_POST['post_title'])),
        'post_text' => escape($_POST['post_text']),
        'post_by' => $user_data->user_id,
        'token' => $_POST['token'],
        'errors' => array()
      ];

      if(!check($data['token'], 'token')) {
        array_push($data['errors'], 'Token Invalid');
      } else if(empty($data['post_title']) || empty($_POST['post_text'])) {
        array_push($data['errors'], 'Enter a title and some text');
      }

      if(!empty($_FILES['post_image']['name'])) {
        $upload_dir = "../uploads/post-images/";
        $file_name = basename($_FILES['post_image']['name']);
        $path = $upload_dir . $file_name;
        $file_type = pathinfo($path, PATHINFO_EXTENSION);
        $allow_types = ['jpg', 'png', 'PNG', 'gif'];

        $data = [
          'page_title' => 'New Post',
          'user_data' => $user_data,
          'post_title' => escape($_POST['post_title']),
          'post_slug' => escape(slug($_POST['post_title'])),
          'post_text' => escape($_POST['post_text']),
          'post_image' => $file_name,
          'post_by' => $user_data->user_id,
          'errors' => array()
        ];
  
        if(!in_array($file_type, $allow_types)) {
          array_push($data['errors'], 'This file type is not allowed');
        } else if(!move_uploaded_file($_FILES['post_image']['tmp_name'], $path)) {
          array_push($data['errors'], 'Unable to upload image. Try again later.');
        }

        if(empty($data['errors'])) {
          if($this->postModel->createPost($data)) {
            flash('post_message', 'Post Created');

            redirect('posts');
          } else {
            array_push($data['errors'], 'Unable to create post');
          }
        } else {
          $this->view('posts/create', $data);
        }
      } else {
        if(empty($data['errors'])) {
          if($this->postModel->createPost($data)) {
            flash('post_message', 'Post Made');

            redirect('posts');
          } else {
            array_push($data['errors'], 'Unable to create post');
          }
        } else {
          $this->view('posts/create', $data);
        }
      }
    } else {
      $data = [
        'page_title' => 'New Post',
        'user_data' => $user_data,
        'post_title' => '',
        'post_slug' => '',
        'post_text' => '',
        'post_by' => '',
        'errors' => array()
      ];

      $this->view('posts/create', $data);
    }
  }

  public function post($slug = false) {
    if(!$slug) {
      redirect('posts');
    } else if(!$post = $this->postModel->getPost($slug)) {
      redirect(404);
    } else {
      $user_data = loggedIn() ? $this->userModel->getUser($_SESSION['user']) : false;
      $bookmark_data = $this->postModel->getBookmarkData($post->post_id);
      $bookmark_count = $this->postModel->getBookmarkCount($post->post_id);
      $comments = $this->postModel->getComments($post->post_id);
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $data = [
        'page_title' => $post->post_title,
        'user_data' => $user_data,
        'post' => $post,
        'bookmark_data' => $bookmark_data,
        'bookmark_count' => $bookmark_count,
        'comments' => $comments,
        'comment_text' => escape($_POST['comment_text']),
        'comment_post' => $post->post_id,
        'comment_by' => $user_data->user_id,
        'token' => $_POST['token'],
        'errors' => array()
      ];

      if(!check($data['token'], 'token')) {
        array_push($data['errors'], 'Token Invalid');
      } else if(empty($data['comment_text'])) {
        array_push($data['errors'], 'Enter a comment');
      }

      if(empty($data['errors'])) {
        if($this->postModel->comment($data)) {
          redirect('posts/post/' . $post->post_slug);
        } else {
          array_push($data['errors'], 'Unable to post comment');
        }
      } else {
        $this->view('posts/post', $data);
      }
    } else {
      $comments = $this->postModel->getComments($post->post_id);

      $data = [
        'page_title' => $post->post_title,
        'user_data' => $user_data,
        'post' => $post,
        'bookmark_data' => $bookmark_data,
        'bookmark_count' => $bookmark_count,
        'comments' => $comments,
        'errors' => array()
      ];

      $this->view('posts/post', $data);
    }
  }

  public function all() {
    $posts = $this->postModel->getPosts();

    if(loggedIn()) {
      $user_data = $this->userModel->getUser($_SESSION['user']);
    }

    $data = [
      'page_title' => 'All Posts',
      'posts' => $posts,
      'user_data' => $user_data
    ];

    $this->view('posts/all', $data);
  }

  public function edit($slug) {
    if(!loggedIn()) {
      redirect('users/login');
    } else {
      $user_data = $this->userModel->getUser($_SESSION['user']);
    }

    if(!$post = $this->postModel->getPost($slug)) {
      redirect(404);
    } else if($post->post_by !== $user_data->user_id) {
      redirect('posts');
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      switch($_POST) {
        case isset($_POST['edit']):
          if(!empty($_FILES['post_image']['name'])) {
            $data = [
              'post_title' => escape($_POST['post_title']),
              'post_slug' => escape(slug($_POST['post_title'])),
              'post_text' => escape($_POST['post_text']),
              'post_image' => basename($_FILES['post_image']['name']),
              'post_id' => $post->post_id,
              'token' => $_POST['token'],
              'errors' => array()
            ];

            $upload_dir = "../uploads/post-images/";
            $path = $upload_dir . $data['post_image'];
            $file_type = pathinfo($path, PATHINFO_EXTENSION);
            $allow_types = ['jpg', 'png', 'PNG', 'gif'];

            if(!check($data['token'], 'token')) {
              array_push($data['errors'], 'Token Invalid');
            } else if(empty($data['post_title']) || empty($data['post_text'])) {
              array_push($data['errors'], 'Enter a title and some text');
            } else if(!in_array($file_type, $allow_types)) {
              array_push($data['errors'], 'This file type is not allowed');
            } else if(!move_uploaded_file($_FILES['post_image']['tmp_name'], $path)) {
              array_push($data['errors'], 'Unable to upload image. Try again later.');
            }

            if(empty($data['errors'])) {
              if($this->postModel->editPost($data)) {
                flash('post_message', 'Post Updated Successfully');

                redirect('posts');
              } else {
                array_push($data['errors'], 'Unable to edit post');
              }
            } else {
              $this->view('posts/edit', $data);
            }
          } else {
            $data = [
              'post_title' => escape($_POST['post_title']),
              'post_slug' => escape(slug($_POST['post_title'])),
              'post_text' => escape($_POST['post_text']),
              'post_image' => $post->post_image,
              'post_id' => $post->post_id,
              'token' => $_POST['token'],
              'errors' => array()
            ];

            if(!check($data['token'], 'token')) {
              array_push($data['errors'], 'Token Invalid');
            } else if(empty($data['post_title']) || empty($data['post_text'])) {
              array_push($data['errors'], 'Enter a title and some text');
            }
            
            if(empty($data['errors'])) {
              if($this->postModel->editPost($data)) {
                flash('post_message', 'Post Updated Successfully');

                redirect('posts');
              } else {
                array_push($data['errors'], 'Unable to edit post');
              }
            } else {
              $this->view('posts/edit', $data);
            }
          }

          break;
        case isset($_POST['delete_post']):
          $data = [
            'page_title' => 'Edit Post: ' . $post->post_title,
            'post_title' => $post->post_title,
            'post_slug' => $post->post_slug,
            'post_text' => $post->post_text,
            'post_image' => $post->post_image,
            'token' => $_POST['delete-token'],
            'delete_errors' => array()
          ];

          $image_dir = "../uploads/post-images/";
          $file_to_delete = $image_dir . $post->post_image;
          
          if(!check($data['token'], 'delete-token')) {
            array_push($data['delete_errors'], 'Token Invalid');
          }

          if(empty($data['delete_errors'])) {
            if($this->postModel->deletePost($post->post_id)) {
              if(file_exists($file_to_delete)) {
                unlink($file_to_delete);
              }
              
              flash('post_message', 'Post Deleted');
  
              redirect('posts');
            } else {
              array_push($data['delete_errors'], 'Unable to delete post');
            }
          } else {
            $this->view('posts/edit', $data);
          }
          
          break;
        default:
          $this->view('posts/edit');

          break;
      }
    } else {
      $data = [
        'page_title' => 'Edit Post: ' . $post->post_title,
        'post_title' => $post->post_title,
        'post_slug' => $post->post_slug,
        'post_text' => $post->post_text,
        'post_image' => $post->post_image,
        'errors' => array()
      ];

      $this->view('posts/edit', $data);
    }
  }

  public function search() {
    if(!loggedIn()) {
      redirect('users/login');
    } else {
      $user_data = $this->userModel->getUser($_SESSION['user']);
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $keywords = isset($_POST['s']) ? escape($_POST['s']) : ' ';

      $users = $this->userModel->searchUsers($keywords);
      $posts = $this->postModel->searchPosts($keywords);
  
      $data = [
        'page_title' => 'Search: ' . $keywords,
        'user_data' => $user_data,
        'keywords' => $keywords,
        'users' => $users,
        'posts' => $posts
      ];
  
      $this->view('posts/search', $data);
    } else {
      $data = [
        'page_title' => 'Search',
        'user_data' => $user_data,
        'keywords' => '',
        'users' => '',
        'posts' => ''
      ];
  
      $this->view('posts/search', $data);
    }
  }

  public function bookmark($post) {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
      if(!loggedIn()) {
        redirect('users/login');
      } else if(!$post) {
        redirect('posts');
      } else {
        $user_data = $this->userModel->getUser($_SESSION['user']);
      }
  
      $bookmark = $this->postModel->getPost($post);
  
      if($bookmark) {
        $data = [
          'bookmarked_by' => $user_data->user_id,
          'bookmarked_post' => $bookmark->post_id
        ];
  
        if($this->postModel->bookmark($data)) {
          redirect('posts/post/' . $bookmark->post_slug);
        }
      } else {
        redirect('posts');
      }
    }
  }

  public function removebookmark($post) {
    if($_SERVER['REQUEST_METHOD'] == 'GET') {
      if(!loggedIn()) {
        redirect('users/login');
      } else if(!$post) {
        redirect('posts');
      } else {
        $user_data = $this->userModel->getUser($_SESSION['user']);
      }
  
      $bookmark = $this->postModel->getPost($post);
  
      if($bookmark) {
        $data = [
          'bookmarked_by' => $user_data->user_id,
          'bookmarked_post' => $bookmark->post_id
        ];
  
        if($this->postModel->removeBookmark($data)) {
          redirect('posts/post/' . $bookmark->post_slug);
        }
      } else {
        redirect('posts');
      }
    }
  }

  public function bookmarks() {
    if(!loggedIn()) {
      redirect('users/login');
    } else {
      $user_data = $this->userModel->getUser($_SESSION['user']);
    }

    $posts = $this->postModel->getUsersBookmarks($user_data->user_id);

    $data = [
      'page_title' => 'Your Bookmarks',
      'user_data' => $user_data,
      'posts' => $posts
    ];

    $this->view('posts/bookmarks', $data);
  }

  public function editComment($comment) {
    if(!loggedIn()) {
      redirect('users/login');
    } else if(!$comment) {
      redirect('posts');
    } else {
      $user_data = $this->userModel->getUser($_SESSION['user']);
    }

    $comment_data = $this->postModel->getComment($comment);

    if($comment_data->comment_by !== $user_data->user_id) {
      redirect('posts');
    }
    
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      switch($_POST) {
        case isset($_POST['edit_comment']):
          $data = [
            'comment_data' => $comment_data,
            'comment_text' => escape($_POST['comment_text']),
            'comment_id' => $comment_data->comment_id,
            'token' => $_POST['token'],
            'errors' => array()
          ];

          if(!check($data['token'], 'token')) {
            array_push($data['errors'], 'Token Invalid');
          } else if(empty($data['comment_text'])) {
            array_push($data['errors'], 'Enter some text');
          }

          if(empty($data['errors'])) {
            if($this->postModel->editComment($data)) {
              redirect('posts/post/' . $comment_data->post_slug);
            } else {
              array_push($data['errors'], 'Unable to edit comment');
            }
          } else {
            $this->view('posts/editcomment', $data);
          }

          break;
        case isset($_POST['delete_comment']):
          $data = [
            'comment_data' => $comment_data,
            'token' => $_POST['delete-token'],
            'delete_errors' => array()
          ];

          if(!check($data['token'], 'delete-token')) {
            array_push($data['delete_errors'], 'Token Invalid');
          }

          if(empty($data['delete_errors'])) {
            if($this->postModel->deleteComment($comment_data->comment_id)) {
              redirect('posts/post/' . $comment_data->post_slug);
            } else {
              array_push($data['delete_errors'], 'Unable to delete comment');
            }
          } else {
            $this->view('posts/editcomment', $data);
          }

          break;
        default:
          $data = [
            'page_title' => 'Edit Comment',
            'comment_data' => $comment_data,
            'errors' => array()
          ];

          $this->view('posts/editcomment', $data);

          break;
      }
    } else {
      $data = [
        'page_title' => 'Edit Comment',
        'comment_data' => $comment_data,
        'errors' => array()
      ];

      $this->view('posts/editcomment', $data);
    }
  }
}