<?php
class Post {
  private $db;

  public function __construct() {
    $this->db = new Database();
  }

  public function createPost($data) {
    $this->db->query(
      'INSERT INTO
        posts (post_title, post_slug, post_text, post_image, post_by)
      VALUES
        (:post_title, :post_slug, :post_text, :post_image, :post_by)'
    );

    $this->db->bind(':post_title', $data['post_title']);
    $this->db->bind(':post_slug', $data['post_slug']);
    $this->db->bind(':post_text', $data['post_text']);
    $this->db->bind(':post_image', $data['post_image']);
    $this->db->bind(':post_by', $data['post_by']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function getPosts() {
    $this->db->query(
      "SELECT
        *
      FROM
        posts
      LEFT JOIN
        users
      ON
        users.user_id = posts.post_by
      ORDER BY
        post_date
      DESC"
    );

    if($this->db->execute()) {
      return $this->db->results();
    }

    return false;
  }

  public function getHomepagePosts($user) {
    $this->db->query(
      "SELECT
        *
      FROM
        posts
      LEFT JOIN
        users
      ON
        users.user_id = posts.post_by
      WHERE
        (post_by = users.user_id AND users.user_id = $user)
      OR
        (post_by = users.user_id AND post_by
      IN
        (SELECT
          followed_id
        FROM
          follows
        WHERE
          follows.user_id = $user))
      ORDER BY
        post_date
      DESC"
    );

    if($this->db->execute()) {
      return $this->db->results();
    }

    return false;
  }

  public function getPost($post) {
    $this->db->query(
      "SELECT
        *
      FROM
        posts
      LEFT JOIN
        users
      ON
        users.user_id = posts.post_by
      WHERE
        post_id = ?
      OR
        post_slug = ?"
    );

    $this->db->bind(1, $post);
    $this->db->bind(2, $post);

    if($this->db->execute()) {
      return $this->db->single();
    }

    return false;
  }

  public function getUsersPosts($user) {
    $this->db->query(
      "SELECT
        *
      FROM
        posts
      LEFT JOIN
        users
      ON
        users.user_id = posts.post_by
      WHERE
        post_by = :post_by
      ORDER BY
        post_date
      DESC"
    );

    $this->db->bind(':post_by', $user);

    if($this->db->execute()) {
      return $this->db->results();
    }

    return false;
  }

  public function getUsersBookmarks($user) {
    $this->db->query(
      "SELECT
        *
      FROM
        posts
      LEFT JOIN
        bookmarks
      ON
        posts.post_id = bookmarks.bookmarked_post
      LEFT JOIN
        users
      ON
        posts.post_by = users.user_id
      WHERE
        posts.post_id = bookmarks.bookmarked_post
      AND
        bookmarks.bookmarked_by = $user
      ORDER BY
        post_date
      DESC"
    );

    if($this->db->execute()) {
      return $this->db->results();
    }

    return false;
  }

  public function editPost($data) {
    $this->db->query(
      'UPDATE
        posts
      SET
        post_title = :post_title,
        post_slug = :post_slug,
        post_text = :post_text,
        post_image = :post_image
      WHERE
        post_id = :post_id'
    );

    $this->db->bind(':post_title', $data['post_title']);
    $this->db->bind(':post_slug', $data['post_slug']);
    $this->db->bind(':post_text', $data['post_text']);
    $this->db->bind(':post_image', $data['post_image']);
    $this->db->bind(':post_id', $data['post_id']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function deletePost($post) {
    $this->db->query('DELETE FROM posts WHERE post_id = :post_id');

    $this->db->bind(':post_id', $post);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function searchPosts($keywords) {
    $this->db->query(
      "SELECT
        *
      FROM
        posts
      LEFT JOIN
        users
      ON
        users.user_id = posts.post_by
      WHERE
        post_title
      LIKE
        \"%" . $keywords . "%\"
      OR
        post_text
      LIKE
        \"%" . $keywords . "%\"
      ORDER BY
        post_date
      DESC"
    );

    if($this->db->execute()) {
      return $this->db->results();
    }

    return false;
  }

  public function bookmark($data) {
    $this->db->query('INSERT INTO bookmarks (bookmarked_by, bookmarked_post) VALUES (:bookmarked_by, :bookmarked_post)');

    $this->db->bind(':bookmarked_by', $data['bookmarked_by']);
    $this->db->bind(':bookmarked_post', $data['bookmarked_post']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function removeBookmark($data) {
    $this->db->query('DELETE FROM bookmarks WHERE bookmarked_by = :bookmarked_by AND bookmarked_post = :bookmarked_post');

    $this->db->bind(':bookmarked_by', $data['bookmarked_by']);
    $this->db->bind(':bookmarked_post', $data['bookmarked_post']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function getBookmarkCount($post) {
    $this->db->query("SELECT COUNT(*) AS number FROM bookmarks WHERE bookmarked_post = $post");

    if($this->db->execute()) {
      return $this->db->single();
    }

    return false;
  }

  public function getBookmarkData($post) {
    $this->db->query("SELECT * FROM bookmarks WHERE bookmarked_post = $post");

    if($this->db->execute()) {
      return $this->db->fetchArray();
    }

    return false;
  }

  public function comment($data) {
    $this->db->query('INSERT INTO comments (comment_text, comment_post, comment_by) VALUES (:comment_text, :comment_post, :comment_by)');

    $this->db->bind(':comment_text', $data['comment_text']);
    $this->db->bind(':comment_post', $data['comment_post']);
    $this->db->bind(':comment_by', $data['comment_by']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function getComments($post) {
    $this->db->query(
      "SELECT
        *
      FROM
        comments
      LEFT JOIN
        users
      ON
        users.user_id = comments.comment_by
      WHERE
        comment_post = :comment_post"
    );

    $this->db->bind(':comment_post', $post);

    if($this->db->execute()) {
      return $this->db->results();
    }

    return false;
  }

  public function getComment($comment) {
    $this->db->query(
      "SELECT
        *
      FROM
        comments
      LEFT JOIN
        posts
      ON
        posts.post_id = comments.comment_post
      WHERE
        comment_id = :comment_id"
    );

    $this->db->bind(':comment_id', $comment);

    if($this->db->execute()) {
      return $this->db->single();
    }

    return false;
  }

  public function editComment($data) {
    $this->db->query('UPDATE comments SET comment_text = :comment_text WHERE comment_id = :comment_id');

    $this->db->bind(':comment_text', $data['comment_text']);
    $this->db->bind(':comment_id', $data['comment_id']);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }

  public function deleteComment($comment) {
    $this->db->query('DELETE FROM comments WHERE comment_id = :comment_id');

    $this->db->bind(':comment_id', $comment);

    if($this->db->execute()) {
      return true;
    }

    return false;
  }
}