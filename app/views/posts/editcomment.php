<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="form">
  <h2>Edit Comment</h2>

  <form action="<?php self() ?>" method="POST">
    <div class="form-group">
      <?php if(!empty($data['errors'])): ?>
        <?php foreach($data['errors'] as $error): ?>
          <span class="error"><?= $error ?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <textarea name="comment_text" placeholder="Comment"><?= escape($data['comment_data']->comment_text) ?></textarea>
    </div>
    <div class="form-group">
      <input type="hidden" name="token" value="<?= generate('token') ?>">
      <input type="submit" name="edit_comment" value="Edit Comment">
    </div>
  </form>
</div>

<div class="form">
  <h2>Delete Comment</h2>

  <form action="<?php self() ?>" method="POST">
    <div class="form-group">
      <?php if(!empty($data['delete_errors'])): ?>
        <?php foreach($data['delete_errors'] as $error): ?>
          <span class="error"><?= $error ?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <input type="hidden" name="delete-token" value="<?= generate('delete-token') ?>">
      <input type="submit" name="delete_comment" value="Delete Comment">
    </div>
  </form>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>