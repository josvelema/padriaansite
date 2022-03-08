<?php
include 'main.php';

// SQL query that will retrieve all the posts from the database ordered by the ID column
$stmt = $pdo->prepare('SELECT * FROM posts ORDER BY post_id DESC');
$stmt->execute();
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?= template_admin_header('All Media', 'allmedia') ?>

<h2>All Media</h2>

<?php
if (isset($_POST['create_post'])) {

  $post_title        = ($_POST['title']);
  $post_category_id  = ($_POST['post_category']);
  $post_status       = ($_POST['post_status']);

  $post_image        = ($_FILES['image']['name']);
  $post_image_temp   = ($_FILES['image']['tmp_name']);


  $post_tags         = ($_POST['post_tags']);
  $post_content      = ($_POST['post_content']);
  $post_date         = (date('d-m-y'));


  move_uploaded_file($post_image_temp, "../images/$post_image");

  // $query = "INSERT INTO posts(post_category_id, post_title, post_user, post_date,post_image,post_content,post_tags,post_status) ";

  // $query .= "VALUES({$post_category_id},'{$post_title}','{$post_user}',now(),'{$post_image}','{$post_content}','{$post_tags}', '{$post_status}') "; 

  // $create_post_query = mysqli_query($connection, $query);  
  //todo 1 user ???
  $pieter = "Pieter";
  $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date,post_image,post_content,post_tags,post_status) ";
  $query .= "VALUES(? , ? , ? , now() , ? , ? , ? , ?) ";

  $stmt = $pdo->prepare($query);
  $stmt->bindParam(1, $post_category_id, PDO::PARAM_INT);
  $stmt->bindParam(2, $post_title, PDO::PARAM_STR);
  $stmt->bindParam(3, $pieter, PDO::PARAM_STR);
  $stmt->bindParam(4, $post_image, PDO::PARAM_STR);
  $stmt->bindParam(5, $post_content, PDO::PARAM_STR);
  $stmt->bindParam(6, $post_tags, PDO::PARAM_STR);
  $stmt->bindParam(7, $post_status, PDO::PARAM_STR);
  $stmt->execute();

  $the_post_id = $pdo->lastInsertId();

  echo "<p class='bg-success'>Post Created. <a href='../post.php?p_id={$the_post_id}'>View Post </a></p>";
}




?>

<form action="" method="post" enctype="multipart/form-data">


  <div class="form-group">
    <label for="title">Post Title</label>
    <input type="text" class="form-control" name="title">
  </div>

  <div class="form-group">
    <label for="category">Category</label>
    <select name="post_category" id="">

      <?php
      $stmt = $pdo->query('SELECT * FROM categories');
      while ($row = $stmt->fetch()) {
        $cat_id = $row['id'];
        $cat_title = $row['title'];
        echo "<option value='$cat_id'>{$cat_title}</option>";
      }
      ?>
    </select>
  </div>
  <!-- <div class="form-group">
      <label for="users">Users</label>
      <select name="post_user" id="">
      <?php
      //  echo "<option value='{$post_author}'>{$post_author}</option>" ; 
      ?> 
      </select>
      </div> -->

  <div class="form-group">
    <select name="post_status" id="">
      <option value="draft">Post Status</option>
      <option value="published">Published</option>
      <option value="draft">Draft</option>
    </select>
  </div>
  <div class="form-group">
    <label for="post_image">Post Image</label>
    <input type="file" name="image">
  </div>

  <div class="form-group">
    <label for="post_tags">Post Tags</label>
    <input type="text" class="form-control" name="post_tags">
  </div>

  <div class="form-group">
    <label for="post_content">Post Content</label>
    <textarea class="form-control " name="post_content" id="body" cols="30" rows="10">
        </textarea>
  </div>



  <div class="form-group">
    <input class="btn btn-primary" type="submit" name="create_post" value="Publish Post">
  </div>


</form>

<?= template_admin_footer() ?>