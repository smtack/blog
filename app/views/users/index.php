<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="form">
  <h2>Sign Up</h2>
  
  <form action="<?php $self; ?>" method="POST">
    <div class="form-group">
      <?php if(!empty($data['errors'])): ?>
        <?php foreach($data['errors'] as $error): ?>
          <span class="error"><?=$error?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <input type="text" name="user_name" placeholder="Name">
    </div>
    <div class="form-group">
      <input type="text" name="user_username" placeholder="Username">
    </div>
    <div class="form-group">
      <input type="text" name="user_email" placeholder="Email">
    </div>
    <div class="form-group">
      <input type="password" name="user_password" placeholder="Password">
    </div>
    <div class="form-group">
      <input type="password" name="confirm_password" placeholder="Confirm Password">
    </div>
    <div class="form-group">
      <input type="hidden" name="token" value="<?=generate('token')?>">
      <input type="submit" name="signup" value="Sign Up">
    </div>
    <div class="form-group">
      <p>Already have an account? <a href="<?=BASE_URL?>/users/login">Log In</a></p>
    </div>
  </form>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>