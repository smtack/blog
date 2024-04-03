<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="form">
  <h2>New Post</h2>

  <form enctype="multipart/form-data" action="<?php self() ?>" method="POST">
    <div class="form-group">
      <?php if(!empty($data['errors'])): ?>
        <?php foreach($data['errors'] as $error): ?>
          <span class="error"><?= $error ?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <input type="text" name="post_title" placeholder="Title">
    </div>
    <div class="form-group">
      <input type="file" name="post_image">
    </div>
    <div class="form-group">
      <textarea name="post_text" placeholder="Text"></textarea>
    </div>
    <div class="form-group">
      <input type="hidden" name="token" value="<?= generate('token') ?>">
      <input type="submit" name="create" value="Create Post">
    </div>
  </form>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>