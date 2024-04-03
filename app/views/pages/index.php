<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="posts">
  <h2><?= $data['title'] ?></h2>

  <p><?= $data['description'] ?></p>

  <br>

  <p><a href="<?= base_url('users') ?>">Sign Up</a> or <a href="<?= base_url('users/login') ?>">Log In</a></p>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>