<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="form">
  <h2>Edit Post</h2>

  <form enctype="multipart/form-data" action="<?php self() ?>" method="POST">
    <div class="form-group">
      <?php if(!empty($data['errors'])): ?>
        <?php foreach($data['errors'] as $error): ?>
          <span class="error"><?= $error ?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <input type="text" name="post_title" placeholder="Title" value="<?= escape($data['post_title']) ?>">
    </div>
    <div class="form-group">
      <input type="file" name="post_image">
    </div>
    <?php if($data['post_image']): ?>
      <div class="form-group">
        <img src="<?= UPLOAD_ROOT ?>/post-images/<?= escape($data['post_image']) ?>" alt="<?= escape($data['post_image']) ?>">
      </div>
    <?php endif; ?>
    <div class="form-group">
      <textarea name="post_text" placeholder="Text"><?= escape($data['post_text']) ?></textarea>
    </div>
    <div class="form-group">
      <input type="hidden" name="token" value="<?= generate('token') ?>">
      <input type="submit" name="edit" value="Edit Post">
    </div>
  </form>
</div>

<div class="form">
  <h2>Delete Post</h2>

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
      <input type="submit" name="delete_post" value="Delete Post">
    </div>
  </form>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>