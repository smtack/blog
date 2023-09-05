<?php
class Pages extends Controller {
  public function index() {
    if(loggedIn()) {
      redirect('posts');
    }

    $data = [
      'page_title' => 'Welcome',
      'title' => 'Welcome to Blog',
      'description' => 'A Blogging Website'
    ];

    $this->view('pages/index', $data);
  }

  public function about() {
    $data = [
      'page_title' => 'About',
      'title' => 'About'
    ];

    $this->view('pages/about', $data);
  }
}