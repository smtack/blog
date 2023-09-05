<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="form">
  <h2>Log In</h2>
  
  <form action="<?php $self; ?>" method="POST">
    <div class="form-group">
      <?php if(!empty($data['errors'])): ?>
        <?php foreach($data['errors'] as $error): ?>
          <span class="error"><?=$error?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <input type="text" name="user_username" placeholder="Username">
    </div>
    <div class="form-group">
      <input type="password" name="user_password" placeholder="Password">
    </div>
    <div class="form-group">
      <input type="hidden" name="token" value="<?=generate('token')?>">
      <input type="submit" name="login" value="Log In">
    </div>
    <div class="form-group">
      <p>Don't have an account? <a href="<?=BASE_URL?>/users">Sign Up</a></p>
    </div>
  </form>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>