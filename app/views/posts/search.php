<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="posts">
  <div class="search-nav">
    <ul>
      <li class="toggle-posts"><a href="#">Posts</a></li>
      <li class="toggle-users"><a href="#">Users</a></li>
    </ul>

    <h1 id="heading">Search: <?= $data['keywords'] ?></h1>
  </div>
</div>

<div class="posts posts-results">
  <?php if(!$data['posts']): ?>
    <h2>No Posts Found</h2>
  <?php else: ?>
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
  <?php endif; ?>
</div>

<div class="posts users-results">
  <?php if(!$data['users']): ?>
    <h2>No Users Found</h2>
  <?php else: ?>
    <?php foreach($data['users'] as $user): ?>
      <div class="post">
        <h3><a href="<?= base_url('users/profile/') . escape($user->user_username) ?>"><?= escape($user->user_name) ?></a></h3>
        <h6><?= escape($user->user_username) ?></h6>
        <h6>Joined on <?= formatDate(escape($user->user_joined)) ?></h6>

        <span>
          <?php $profile_info = $this->userModel->getProfileInfo($data['user_data']->user_id, $user->user_id); ?>
          <?= $profile_info->post_count . ' ' ?><?= ($profile_info->post_count == 1) ? ' Post' : ' Posts' ?> |
          <?= 'Following ' . $profile_info->following ?> |
          <?= $profile_info->followers . ' ' ?><?= ($profile_info->followers == 1) ? ' Follower' : ' Followers' ?>
        </span>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>