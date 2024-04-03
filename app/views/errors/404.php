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
  </div>
  
  <div class="error-message">
    <h1>404 NOT FOUND</h1>

    <p><a href="<?= base_url() ?>">Back to Homepage</a></p>
  </div>
</body>
</html>