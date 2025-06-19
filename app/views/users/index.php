<?php require_once VIEW_ROOT . '/includes/header.php'; ?>

<div class="posts">
    <?php foreach($data['users'] as $user): ?>
        <?php $profileInfo = $this->userModel->getProfileInfo($data['userData']->user_id, $user->user_id); ?>

        <div class="user-info">
            <?php if($user->user_profile_picture): ?>
                <img src="<?= UPLOAD_ROOT ?>/profile-pictures/<?= escape($user->user_profile_picture) ?>" alt="<?= escape($user->user_profile_picture) ?>">
            <?php endif; ?>
            
            <a href="<?= base_url('users/profile/') . escape($user->user_username) ?>"><h1><?= escape($user->user_name) ?></h1></a>
            <a href="<?= base_url('users/profile/') . escape($user->user_username) ?>"><h3>@<?= escape($user->user_username) ?></h3></a>

            <?php if(isset($_SESSION['user']) && $_SESSION['user'] !== $user->user_username): ?>
                <a href="<?= base_url('users/') ?><?= $profileInfo->followed ? 'unfollow' : 'follow'?>/<?= escape($user->user_id) ?>"><button><?= $profileInfo->followed ? 'Unfollow' : 'Follow' ?></button></a>
            <?php endif; ?>

            <span>Joined on <?= formatDate(escape($user->user_joined)) ?></span>

            <?php if(loggedIn()): ?>
                <span>
                    <?= $profileInfo->post_count . '' ?><?= ($profileInfo->post_count == 1) ? ' Post' : ' Posts'; ?> |
                    <?= 'Following ' . $profileInfo->following; ?> |
                    <?= $profileInfo->followers . '' ?><?= ($profileInfo->followers == 1) ? ' Follower' : ' Followers'; ?>
                </span>
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