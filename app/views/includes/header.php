<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="<?= base_url('public/css/style.css') ?>" rel="stylesheet">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&display=swap" rel="stylesheet">

  <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('public/img/favicon/apple-touch-icon.png') ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('public/img/favicon/favicon-32x32.png') ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('public/img/favicon/favicon-16x16.png') ?>">
  <link rel="manifest" href="<?= base_url('public/img/favicon/site.webmanifest') ?>">

  <script src="<?= base_url('public/js/main.js') ?>" defer></script>
  
  <title><?= isset($data['page_title']) ? SITE_NAME . ' - ' . $data['page_title'] : SITE_NAME ?></title>
</head>
<body>
  <div class="header">
    <span><a href="<?= base_url() ?>">Blog</a></span>

    <?php if(loggedIn()): ?>
      <nav>
        <ul>
          <li class="toggle-search"><img src="<?= base_url('public/img/search.svg') ?>" alt="Search"></li>
          <li><a href="<?= base_url('posts/create') ?>"><img src="<?= base_url('public/img/new.svg') ?>" alt="New Post"></a></li>
          <li><a href="<?= base_url('posts/all') ?>"><img src="<?= base_url('public/img/all.svg') ?>" alt="All Posts"></a></li>
          <li><a href="<?= base_url('posts/bookmarks') ?>"><img src="<?= base_url('public/img/bookmark-white.svg') ?>" alt="Bookmarks"></a></li>
          <li class="toggle-menu"><img src="<?= base_url('public/img/menu.svg') ?>" alt="Toggle Menu"></li>
        </ul>
      </nav>

      <div class="menu">
        <ul>
          <a href="<?= base_url('users/profile/') . $_SESSION['user'] ?>"><li>Your Profile</li></a>
          <a href="<?= base_url('users/update') ?>"><li>Update Profile</li></a>
          <a href="<?= base_url('users/logout') ?>"><li>Log Out</li></a>
        </ul>
      </div>

      <div class="search">
        <form action="<?= base_url('posts/search') ?>" method="POST">
          <div class="form-group">
            <input type="text" name="s" placeholder="Search" value="<?= isset($data['keywords']) ? $data['keywords'] : '' ?>">
          </div>
        </form>
      </div>
    <?php endif; ?>
  </div>