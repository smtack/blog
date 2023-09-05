<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="posts">
  <?php flash('user_message'); ?>
  <?php flash('post_message'); ?>

  <?php if(!$data['posts']): ?>
    <h2>Welcome to Blog. <a href="<?=BASE_URL?>/posts/create">Make a post</a> or <a href="<?=BASE_URL?>/posts/all">Browse</a>.</h2>
  <?php else: ?>
    <?php foreach($data['posts'] as $post): ?>
      <div class="post">
        <h3><a href="<?=BASE_URL?>/posts/post/<?=escape($post->post_slug)?>"><?=escape($post->post_title)?></a></h3>
        
        <?php if($post->user_username): ?>
          <h6>By <a href="<?=BASE_URL?>/users/profile/<?=escape($post->user_username)?>"><?=escape($post->user_name)?></a> on <?=formatDate(escape($post->post_date))?></h6>
        <?php else: ?>
          <h6>By [Deleted] on <?=formatDate(escape($post->post_date))?></h6>
        <?php endif; ?>
        
        <p>
          <?php if(strlen($post->post_text) > 150): ?>
            <?=substr(escape($post->post_text), 0, 150) . '...'?>
          <?php else: ?>
            <?=escape($post->post_text)?>
          <?php endif; ?>
        </p>

        <?php if(loggedIn() && $_SESSION['user'] === $post->user_username): ?>
          <h6><a href="<?=BASE_URL; ?>/posts/edit/<?=escape($post->post_slug)?>">Edit</a></h6>
        <?php endif; ?>
      </div>  
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>