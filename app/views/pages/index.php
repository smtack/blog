<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="posts">
  <h2><?=$data['title']?></h2>
  <p><?=$data['description']?></p>
  <br>
  <p><a href="<?=BASE_URL?>/users">Sign Up</a> or <a href="<?=BASE_URL?>/users/login">Log In</a></p>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>