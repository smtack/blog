<?php
class User {
  private $db;

  public function __construct() {
    $this->db = new Database();
  }

  public function signUp($data) {
    $this->db->query(
      'INSERT INTO
        users (user_name, user_username, user_email, user_password)
      VALUES
        (:user_name, :user_username, :user_email, :user_password)'
    );
    
    $this->db->bind(':user_name', $data['user_name']);
    $this->db->bind(':user_username', $data['user_username']);
    $this->db->bind(':user_email', $data['user_email']);
    $this->db->bind(':user_password', $data['password_hash']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function logIn($username, $password) {
    if($this->db->exists('users', array('user_username' => $username))) {
      $this->db->query('SELECT * FROM users WHERE user_username = :user_username');

      $this->db->bind(':user_username', $username);
  
      $row = $this->db->single();
  
      $hash = $row->user_password;
  
      if(password_verify($password, $hash)) {
        return $row;
      }
    }

    return false;
  }

  public function getUser($user) {
    $this->db->query(
      "SELECT
        *
      FROM
        users
      WHERE
        user_id = :user_id
      OR
        user_username = :user_username"
    );

    $this->db->bind(':user_id', $user);
    $this->db->bind(':user_username', $user);

    return $this->db->single();
  }

  public function getProfileInfo($user, $profile) {
    $this->db->query(
      "SELECT
        post_count,
      IF
        (post_title IS NULL, 'No Posts', post_title)
      AS
        post_title, followers, following, followed
      FROM (SELECT COUNT(*) AS
        post_count
      FROM
        posts
      WHERE
        post_by = $profile
      ) AS PC LEFT JOIN (SELECT * FROM
        posts
      WHERE
        post_by = $profile
      ORDER BY
        post_date
      DESC LIMIT 1) AS P ON
        P.post_by = $profile
      JOIN (SELECT COUNT(*) AS
        followers
      FROM
        follows
      WHERE
        followed_id = $profile
      ) AS FD JOIN (SELECT COUNT(*) AS
        following
      FROM
        follows
      WHERE
        follows.user_id = $profile
      ) AS FP JOIN (SELECT COUNT(*) AS
        followed
      FROM
        follows
      WHERE
        followed_id = $profile
      AND
        follows.user_id = $user
      ) AS F2;"
    );

    if($this->db->execute()) {
      return $this->db->single();
    }

    return false;
  }

  public function updateProfile($data) {
    $this->db->query(
      'UPDATE
        users
      SET
        user_name = :user_name,
        user_username = :user_username,
        user_email = :user_email
      WHERE
        user_id = :user_id'
    );

    $this->db->bind(':user_id', $data['user_id']);
    $this->db->bind(':user_name', $data['user_name']);
    $this->db->bind(':user_username', $data['user_username']);
    $this->db->bind(':user_email', $data['user_email']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function uploadProfilePicture($data) {
    $this->db->query(
      'UPDATE
        users
      SET
        user_profile_picture = :user_profile_picture
      WHERE
        user_id = :user_id'
    );

    $this->db->bind(':user_id', $data['user_id']);
    $this->db->bind(':user_profile_picture', $data['user_profile_picture']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function changePassword($data) {
    $this->db->query(
      'UPDATE
        users
      SET
        user_password = :user_password
      WHERE
        user_id = :user_id'
    );

    $this->db->bind(':user_id', $data['user_id']);
    $this->db->bind(':user_password', $data['password_hash']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function deleteProfile($id) {
    $this->db->query('DELETE FROM users WHERE user_id = :user_id');

    $this->db->bind(':user_id', $id);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }
  
  public function searchUsers($keywords) {
    $this->db->query(
      "SELECT
        *
      FROM
        users
      WHERE
        user_name
      LIKE
        \"%" . $keywords . "%\"
      OR
        user_username
      LIKE
        \"%" . $keywords . "%\"
      ORDER BY
        user_joined
      DESC"
    );

    if($this->db->execute()) {
      return $this->db->results();
    }

    return false;
  }

  public function follow($data) {
    $this->db->query('INSERT INTO follows (user_id, followed_id) VALUES (:user_id, :followed_id)');

    $this->db->bind(':user_id', $data['user_id']);
    $this->db->bind(':followed_id', $data['followed_id']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function unfollow($data) {
    $this->db->query('DELETE FROM follows WHERE user_id = :user_id AND followed_id = :followed_id');

    $this->db->bind(':user_id', $data['user_id']);
    $this->db->bind(':followed_id', $data['followed_id']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }
}