<?php
include 'main.php';

// SQL query that will retrieve all the posts from the database ordered by the ID column
// $stmt = $pdo->prepare('SELECT * FROM posts ORDER BY post_id DESC');
// $stmt->execute();
// $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?= template_admin_header('Edit Post', 'allmedia') ?>

<h2>Edit post</h2>


<?php

if (isset($_GET['p_id'])) {
  $post_id = $_GET['p_id'];

  $stmt = $pdo->prepare('SELECT * FROM posts WHERE post_id = ? ');
  $stmt->bindParam(1, $post_id, PDO::PARAM_INT);
  $stmt->execute();

  while ($row = $stmt->fetch()) {
    $post_id            = $row['post_id'];
    $post_author          = $row['post_author'];
    $post_title         = $row['post_title'];
    $post_category_id   = $row['post_category_id'];
    $post_status        = $row['post_status'];
    $post_image         = $row['post_image'];
    $post_content       = $row['post_content'];
    $post_url           = $row['post_url'];

    $post_tags          = $row['post_tags'];
    $post_comment_count = $row['post_comment_count'];
    $post_date          = $row['post_date'];
  }
}

if (isset($_POST['update_post'])) {


  $post_author         =  $_POST['post_user'];
  $post_title          =  $_POST['post_title'];
  $post_category_id    =  $_POST['post_category'];
  $post_status         =  $_POST['post_status'];
  $post_image          =  $_FILES['image']['name'];
  $post_image_temp     =  $_FILES['image']['tmp_name'];
  $post_content        =  $_POST['post_content'];
  $post_url            =  $_POST['post_url'];

  $post_tags           =  $_POST['post_tags'];

  move_uploaded_file($post_image_temp, "../images/$post_image");

  if (empty($post_image)) {

    $stmt = $pdo->prepare('SELECT * FROM posts WHERE post_id = ? ');
    $stmt->bindParam(1, $post_id, PDO::PARAM_INT);
    $stmt->execute();


    while ($row = $stmt->fetch()) {

      $post_image = $row['post_image'];
    }
  }


  $query = "UPDATE posts SET ";
  $query .= "post_title  = ? , ";
  $query .= "post_category_id = ? , ";
  $query .= "post_date   = now(), ";
  $query .= "post_author = ? , ";
  $query .= "post_status = ? , ";
  $query .= "post_tags   = ? , ";
  $query .= "post_content= ? , ";
  $query .= "post_url    = ? , ";
  $query .= "post_image  = ? ";
  $query .= "WHERE post_id = ? ";


  $stmt = $pdo->prepare($query);
  $stmt->bindParam(1, $post_title, PDO::PARAM_STR);
  $stmt->bindParam(2, $post_category_id, PDO::PARAM_INT);
  $stmt->bindParam(3, $post_author, PDO::PARAM_STR);
  $stmt->bindParam(4, $post_status, PDO::PARAM_STR);
  $stmt->bindParam(5, $post_tags, PDO::PARAM_STR);
  $stmt->bindParam(6, $post_content, PDO::PARAM_STR);
  $stmt->bindParam(7, $post_url, PDO::PARAM_STR);

  $stmt->bindParam(8, $post_image, PDO::PARAM_STR);
  $stmt->bindParam(9, $post_id, PDO::PARAM_INT);
  $stmt->execute();

  echo "<p class='bg-success'>Post Updated. <a href='../post.php?p_id={$post_id}'>View Post </a> or <a href='posts.php'>Go back to posts</a></p>";
}



?>






<form action="" method="post" enctype="multipart/form-data">


  <div class="form-group">
    <label for="title">Post Title</label>
    <input value="<?php echo htmlspecialchars(stripslashes($post_title)); ?>" type="text" class="form-control" name="post_title">
  </div>

  <div class="form-group">
    <label for="categories">Categories</label>
    <select name="post_category" id="">

      <?php
      $stmt = $pdo->query('SELECT * FROM categories');
      while ($row = $stmt->fetch()) {
        $cat_id = $row['id'];
        $cat_title = $row['title'];
        if ($cat_id == $post_category_id) {


          echo "<option selected value='{$cat_id}'>{$cat_title}</option>";
        } else {

          echo "<option value='{$cat_id}'>{$cat_title}</option>";
        }
      }
      ?>


    </select>

  </div>

  <div class="form-group">
    <label for="users">Users</label>
    <select name="post_user" id="">


      <?php echo "<option value=" . $post_author . ">" . $post_author . "</option>"; ?>

    </select>

  </div>
  <div class="form-group">
    <select name="post_status" id="">

      <option value='<?php echo $post_status ?>'><?php echo $post_status; ?></option>
      <?php
      echo ($post_status == 'published') ? "<option value='draft'>Draft</option>"
        : "<option value='published'>Publish</option>";
      ?>
    </select>
  </div>

  <div class="form-group">

    <img width="100" src="../images/<?php echo $post_image; ?>" alt="">
    <input type="file" name="image">
  </div>

  <div class="form-group">
    <label for="post_tags">Post Tags</label>
    <input value="<?php echo $post_tags; ?>" type="text" class="form-control" name="post_tags">
  </div>

  <div class="form-group">
    <label for="post_content">Post Content</label>
    <textarea class="form-control " name="post_content" id="body" cols="30" rows="10">
      <?php echo trim($post_content); ?>
    </textarea>
  </div>


  <div class="form-group">
    <label for="post_url">reference URL</label>
    <input value="<?php echo $post_url; ?>" type="text" class="form-control" name="post_url">
  </div>

  <div class="form-group">
    <input class="btn btn-primary" type="submit" name="update_post" value="Update Post">
  </div>


</form>

<?= template_admin_footer() ?>