<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="posts">
  <h1 id="heading">All Posts</h1>
  
  <?php foreach($data['posts'] as $post): ?>
    <div class="post">
      <h3><a href="<?= base_url('posts/post/') . escape($post->post_slug) ?>"><?= escape($post->post_title) ?></a></h3>
      
      <?php if($post->user_username): ?>
        <h6>By <a href="<?= base_url('users/profile/') . escape($post->user_username) ?>"><?= escape($post->user_name) ?></a> on <?= formatDate(escape($post->post_date)) ?></h6>
      <?php else: ?>
        <h6>By [Deleted] on <?= formatDate(escape($post->post_date)) ?></h6>
      <?php endif; ?>
      
      <p>
        <?php if(strlen($post->post_text) > 150): ?>
          <?= substr(escape($post->post_text), 0, 150) . '...' ?>
        <?php else: ?>
          <?= escape($post->post_text) ?>
        <?php endif; ?>
      </p>

      <?php if(loggedIn() && $_SESSION['user'] === $post->user_username): ?>
        <h6><a href="<?= base_url('posts/edit/') . escape($post->post_slug) ?>">Edit</a></h6>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <div class="pagination">
    <ul>
      <?php for($x = 1; $x <= $data['pages']; $x++): ?>
        <li>
          <a href="?p=<?= $x ?>" <?php if($data['page'] === $x) echo 'class="selected"';?>><?= $x ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </div>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>