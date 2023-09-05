<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="posts">
  <div class="user-info">
    <?php if($data['profile']->user_profile_picture): ?>
      <img src="<?=BASE_URL; ?>/uploads/profile-pictures/<?=escape($data['profile']->user_profile_picture)?>" alt="<?=escape($data['profile']->user_profile_picture)?>">
    <?php endif; ?>

    <h1><?=escape($data['profile']->user_name)?>'s Posts</h1>

    <?php if(isset($_SESSION['user']) && $_SESSION['user'] !== $data['profile']->user_username): ?>
      <a href="<?=BASE_URL; ?>/users/<?=$data['profile_info']->followed ? 'unfollow' : 'follow'?>/<?=escape($data['profile']->user_id)?>"><button><?=$data['profile_info']->followed ? 'Unfollow' : 'Follow'?></button></a>
    <?php endif; ?>

    <span>Joined on <?=formatDate(escape($data['profile']->user_joined))?></span>

    <?php if(loggedIn()): ?>
      <span>
        <?=$data['profile_info']->post_count . ''?><?=($data['profile_info']->post_count == 1) ? ' Post' : ' Posts'; ?> |
        <?='Following ' . $data['profile_info']->following; ?> |
        <?=$data['profile_info']->followers . ''?><?=($data['profile_info']->followers == 1) ? ' Follower' : ' Followers'; ?>
      </span>
    <?php endif; ?>
  </div>
  
  <?php if(!$data['posts']): ?>
    <h2><?=escape($data['profile']->user_name)?> hasn't made a post yet.</h2>
  <?php else: ?>
    <?php foreach($data['posts'] as $post): ?>
      <div class="post">
        <h3><a href="<?=BASE_URL?>/posts/post/<?=escape($post->post_slug)?>"><?=escape($post->post_title)?></a></h3>
        <h6>By <?=escape($post->user_name)?> on <?=formatDate(escape($post->post_date))?></h6>

        <p>
          <?php if(strlen($post->post_text) > 150): ?>
            <?=substr(escape($post->post_text), 0, 150) . '...'?>
          <?php else: ?>
            <?=escape($post->post_text)?>
          <?php endif; ?>
        </p>

        <?php if(loggedIn() && $_SESSION['user'] === $post->user_username): ?>
          <h6><a href="<?=BASE_URL?>/posts/edit/<?=escape($post->post_slug)?>">Edit</a></h6>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>