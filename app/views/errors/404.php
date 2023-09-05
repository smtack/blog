<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="<?=BASE_URL?>/public/css/style.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:wght@400;700&display=swap" rel="stylesheet">
  <link rel="apple-touch-icon" sizes="180x180" href="<?=BASE_URL?>/public/img/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?=BASE_URL?>/public/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?=BASE_URL?>/public/img/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?=BASE_URL?>/public/img/favicon/site.webmanifest">
  <script src="<?=BASE_URL?>/public/js/main.js" defer></script>
  <title><?=isset($data['page_title']) ? 'Blog - ' . $data['page_title'] : 'Blog'?></title>
</head>
<body>
  <div class="header">
    <span><a href="<?=BASE_URL?>">Blog</a></span>
  </div>
  
  <div class="error-message">
    <h1>404 NOT FOUND</h1>
    <p><a href="/">Back to Homepage</a></p>
  </div>
</body>
</html>