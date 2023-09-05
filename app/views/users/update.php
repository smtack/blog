<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="form">
  <?php flash('user_message'); ?>
</div>

<div class="form">
  <h2>Update Profile</h2>

  <form action="<?php $self; ?>" method="POST">
    <div class="form-group">
      <?php if(!empty($data['errors'])): ?>
        <?php foreach($data['errors'] as $error): ?>
          <span class="error"><?=$error?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <input type="text" name="user_name" placeholder="Name" value="<?=escape($data['user_data']->user_name)?>">
    </div>
    <div class="form-group">
      <input type="text" name="user_username" placeholder="Username" value="<?=escape($data['user_data']->user_username)?>">
    </div>
    <div class="form-group">
      <input type="text" name="user_email" placeholder="Email" value="<?=escape($data['user_data']->user_email)?>">
    </div>
    <div class="form-group">
      <input type="hidden" name="token" value="<?=generate('token')?>">
      <input type="submit" name="update" value="Update">
    </div>
  </form>
</div>

<div class="form">
  <h2>Upload Profile Picture</h2>

  <form enctype="multipart/form-data" action="<?php $self; ?>" method="POST">
    <div class="form-group">
      <?php if(!empty($data['picture_errors'])): ?>
        <?php foreach($data['picture_errors'] as $error): ?>
          <span class="error"><?=$error?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <input type="file" name="profile_picture">
    </div>
    <div class="form-group">
      <input type="hidden" name="picture-token" value="<?=generate('picture-token')?>">
      <input type="submit" name="upload_profile_picture" value="Upload Profile Picture">
    </div>
  </form>
</div>

<div class="form">
  <h2>Change Password</h2>

  <form action="<?php $self; ?>" method="POST">
    <div class="form-group">
      <?php if(!empty($data['password_errors'])): ?>
        <?php foreach($data['password_errors'] as $error): ?>
          <span class="error"><?=$error?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <input type="password" name="user_password" placeholder="Current Password">
    </div>
    <div class="form-group">
      <input type="password" name="new_password" placeholder="New Password">
    </div>
    <div class="form-group">
      <input type="password" name="confirm_password" placeholder="Confirm Password">
    </div>
    <div class="form-group">
      <input type="hidden" name="password-token" value="<?=generate('password-token')?>">
      <input type="submit" name="change_password" value="Change Password">
    </div>
  </form>
</div>

<div class="form">
  <h2>Delete Profile</h2>

  <form action="<?php $self; ?>" method="POST">
    <div class="form-group">
      <?php if(!empty($data['delete_errors'])): ?>
        <?php foreach($data['delete_errors'] as $error): ?>
          <span class="error"><?=$error?></span>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="form-group">
      <input type="password" name="user_password" placeholder="Enter your Password">
    </div>
    <div class="form-group">
      <input type="hidden" name="delete-token" value="<?=generate('delete-token')?>">
      <input type="submit" name="delete_profile" value="Delete Profile">
    </div>
  </form>
</div>

<?php require_once VIEW_ROOT . '/includes/footer.php'; ?>