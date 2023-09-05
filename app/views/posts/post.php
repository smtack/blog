<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="single-post">
  <h3><?=escape($data['post']->post_title)?></h3>

  <?php if($data['post']->user_username): ?>
    <h6>By <a href="<?=BASE_URL?>/users/profile/<?=escape($data['post']->user_username)?>"><?=escape($data['post']->user_name)?></a> on <?=formatDate(escape($data['post']->post_date))?></h6>
  <?php else: ?>
    <h6>By [Deleted] on <?=formatDate(escape($data['post']->post_date))?></h6>
  <?php endif; ?>
  
  <?php if($data['post']->post_image): ?>
    <img src="<?=BASE_URL?>/uploads/post-images/<?=escape($data['post']->post_image)?>" alt="<?=escape($data['post']->post_image)?>">
  <?php endif; ?>
  
  <p><?=escape($data['post']->post_text)?></p>

  <h4><?=$data['bookmark_count']->number . ' ' . ($data['bookmark_count']->number == 1 ? 'Bookmark' : 'Bookmarks')?></h4>

  <?php if(loggedIn() && $_SESSION['user'] === $data['post']->user_username): ?>
    <h6><a href="<?=BASE_URL?>/posts/edit/<?=escape($data['post']->post_slug)?>">Edit</a></h6>
  <?php endif; ?>

  <?php if(loggedIn() && $_SESSION['user'] !== $data['post']->user_username): ?>
    <?php if(!findValue($data['bookmark_data'], 'bookmarked_by', $data['user_data']->user_id)): ?>
      <a href="<?=BASE_URL?>/posts/bookmark/<?=escape($data['post']->post_id)?>"><img id="bookmark" src="<?=BASE_URL?>/public/img/bookmark.svg" alt="Bookmark"></a>
    <?php else: ?>
      <a href="<?=BASE_URL?>/posts/removebookmark/<?=escape($data['post']->post_id)?>"><img id="bookmark" src="<?=BASE_URL?>/public/img/bookmark-black.svg" alt="Bookmark"></a>
    <?php endif; ?>
  <?php endif; ?>

  <div class="comments">
    <h2>Comments</h2>

    <?php if(loggedIn()): ?>
      <form action="<?php $self; ?>" method="POST">
        <div class="form-group">
          <?php if(!empty($data['errors'])): ?>
            <?php foreach($data['errors'] as $error): ?>
              <span class="error"><?=$error?></span>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="form-group">
          <textarea name="comment_text" placeholder="Comment"></textarea>
        </div>
        <div class="form-group">
          <input type="hidden" name="token" value="<?=generate('token')?>">
          <input type="submit" name="post_comment" value="Comment">
        </div>
      </form>
    <?php endif; ?>

    <?php foreach($data['comments'] as $comment): ?>
      <div class="comment">
        <p><?=escape($comment->comment_text)?></p>

        <?php if($comment->user_username): ?>
          <h6>By <a href="<?=BASE_URL?>/users/profile/<?=escape($comment->user_username)?>"><?=escape($comment->user_name)?></a> on <?=formatDate(escape($comment->comment_date))?></h6>
        <?php else: ?>
          <h6>By [Deleted] on <?=formatDate(escape($comment->comment_date))?></h6>
        <?php endif; ?>

        <?php if(loggedIn() && $comment->user_username === $_SESSION['user']): ?>
          <h6><a href="<?=BASE_URL?>/posts/editcomment/<?=escape($comment->comment_id)?>">Edit</a></h6>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>