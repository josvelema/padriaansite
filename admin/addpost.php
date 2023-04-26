<?php
include 'main.php';

// SQL query that will retrieve all the posts from the database ordered by the ID column
$stmt = $pdo->prepare('SELECT * FROM posts ORDER BY post_id DESC');
$stmt->execute();
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?= template_admin_header('Add Blog Post', 'posts') ?>

<h2>Add Blog Post</h2>

<?php
if (isset($_POST['create_post'])) {

  $post_title        = ($_POST['title']);
  // $post_category_id  = ($_POST['post_category']);
  // $post_status       = ($_POST['post_status']);
  $post_status       = "published";


  $post_image        = ($_FILES['image']['name']);
  $post_image_temp   = ($_FILES['image']['tmp_name']);


  $post_tags         = ($_POST['post_tags']);
  $post_content      = ($_POST['post_content']);
  $post_url      = ($_POST['post_url']);

  $post_date         = (date('d-m-y'));


  move_uploaded_file($post_image_temp, "../images/$post_image");

  // $query = "INSERT INTO posts(post_category_id, post_title, post_user, post_date,post_image,post_content,post_tags,post_status) ";

  // $query .= "VALUES({$post_category_id},'{$post_title}','{$post_user}',now(),'{$post_image}','{$post_content}','{$post_tags}', '{$post_status}') "; 

  // $create_post_query = mysqli_query($connection, $query);  
  //todo 1 user ???
  $pieter = "Pieter";
  $query = "INSERT INTO posts(post_title, post_author, post_date,post_image,post_content,post_url,post_tags,post_status) ";
  $query .= "VALUES(? , ? , now() , ? , ? , ? , ? , ?) ";

  $stmt = $pdo->prepare($query);
 
  $stmt->bindParam(1, $post_title, PDO::PARAM_STR);
  $stmt->bindParam(2, $pieter, PDO::PARAM_STR);
  $stmt->bindParam(3, $post_image, PDO::PARAM_STR);
  $stmt->bindParam(4, $post_content, PDO::PARAM_STR);
  $stmt->bindParam(5, $post_url, PDO::PARAM_STR);
  $stmt->bindParam(6, $post_tags, PDO::PARAM_STR);
  $stmt->bindParam(7, $post_status, PDO::PARAM_STR);
  $stmt->execute();

  $the_post_id = $pdo->lastInsertId();

  
  echo '
  
  <label for="rj-modal" class="rj-modal-background"></label>
<div class="rj-modal">
	<div class="modal-header">
		<h3>Post Added to blog!</h3>
        <label for="rj-modal">
        	<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAdVBMVEUAAABNTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU0N3NIOAAAAJnRSTlMAAQIDBAUGBwgRFRYZGiEjQ3l7hYaqtLm8vsDFx87a4uvv8fP1+bbY9ZEAAAB8SURBVBhXXY5LFoJAAMOCIP4VBRXEv5j7H9HFDOizu2TRFljedgCQHeocWHVaAWStXnKyl2oVWI+kd1XLvFV1D7Ng3qrWKYMZ+MdEhk3gbhw59KvlH0eTnf2mgiRwvQ7NW6aqNmncukKhnvo/zzlQ2PR/HgsAJkncH6XwAcr0FUY5BVeFAAAAAElFTkSuQmCC" width="16" height="16" alt="" onclick="closeModal()">
        </label>
    </div>
    ' ;

  if ($post_status == 'published') {

    echo '
    <p>
    <a href="../post.php?p_id=' . $the_post_id . '">View Post on site</a>
    </p>
    '; 
  };
  echo '
    <p>
    <a href="posts.php">Go back to all posts table</a>
    </p>
    <p>
    <a href="#" onclick="closeModal()">Close and stay on this page</a>
    </p>
</div>
  
  ';

}




?>

<form action="" method="post" enctype="multipart/form-data" class="form responsive-width-100">


  <div class="form-group">
    <label for="title">Post Title</label>
    <input type="text" class="form-control" name="title">
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
    <label for="post_url">URL for reference </label>
    <input type="text" class="form-control" name="post_url">
  </div>



  <div class="form-group">
    <input class="btn btn-primary" type="submit" name="create_post" value="Publish Post">
  </div>


</form>

<script>
  modalBg = document.querySelector('.rj-modal-background');
  modal = document.querySelector('.rj-modal');

  function closeModal() {
    modalBg.style.display = "none";
    modal.style.display = "none";

  }


</script>

<?= template_admin_footer() ?>