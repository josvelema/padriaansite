<?php
include 'main.php';


// SQL query that will retrieve all the posts from the database ordered by the ID column
$stmt = $pdo->prepare('SELECT * FROM posts ORDER BY post_id DESC');
$stmt->execute();
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['delete'])) {
  $stmt = $pdo->prepare('DELETE p, pc FROM posts p LEFT JOIN comments pc ON pc.comment_post_id = p.post_id WHERE p.post_id = ?');
  $stmt->bindParam(1, $_GET['delete'], PDO::PARAM_INT);
  $stmt->execute();

  header("Location: posts.php");
}


if (isset($_GET['reset'])) {
  $stmt = $pdo->prepare('UPDATE posts SET post_views_count = 0 WHERE post_id = ? ');
  $stmt->bindParam(1, $_GET['reset'], PDO::PARAM_INT);
  $stmt->execute();

  header("Location: posts.php");
}

?>

<?= template_admin_header('All posts', 'posts') ?>

<h2>All Posts</h2>

<?php
// include("delete_modal.php");
if (isset($_POST['checkBoxArray'])) {



  foreach ($_POST['checkBoxArray'] as $postValueId) {

    $bulk_options = $_POST['bulk_options'];

    switch ($bulk_options) {
      case 'published':
        // $query = "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id = {$postValueId}  ";
        // $update_to_published_status = mysqli_query($connection, $query);
        // confirmQuery($update_to_published_status);

        $stmt = $pdo->prepare('UPDATE posts SET post_status = ? WHERE post_id = ?');
        $stmt->bindParam(1, $bulk_options, PDO::PARAM_STR);
        $stmt->bindParam(2, $postValueId, PDO::PARAM_STR);
        $stmt->execute();

        // while ($row = $stmt->fetch()) {
        //   $post_title = $row['post_title'];
        //   $post_author = $row['post_author'];
        //   $post_date = $row['post_date'];
        //   $post_image = $row['post_image'];
        //   $post_content = $row['post_content'];
        //   $post_views = $row['post_views_count'];
        // }
        break;
      case 'draft':
        $stmt = $pdo->prepare('UPDATE posts SET post_status = ? WHERE post_id = ?');
        $stmt->bindParam(1, $bulk_options, PDO::PARAM_STR);
        $stmt->bindParam(2, $postValueId, PDO::PARAM_STR);
        $stmt->execute();
        break;



      case 'delete':
        // $query = "DELETE FROM posts WHERE post_id = {$postValueId}  ";
        $stmt = $pdo->prepare('DELETE FROM posts  WHERE post_id = ?');
        $stmt->bindParam(1, $postValueId, PDO::PARAM_STR);
        $stmt->execute();
        break;
      case 'clone':

        $stmt = $pdo->prepare('SELECT * FROM posts WHERE post_id = ? ');
        $stmt->bindParam(1, $postValueId, PDO::PARAM_STR);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
          $post_category_id   = $row['post_category_id'];
          $post_title         = $row['post_title'];
          $post_author        = $row['post_author'];
          $post_date          = $row['post_date'];
          $post_image         = $row['post_image'];
          $post_content       = $row['post_content'];
          $post_tags          = $row['post_tags'];
          $post_status        = $row['post_status'];
        }

        $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_date,post_image,post_content,post_tags,post_status) ";
        $query .= "VALUES(? , ? , ? , now() , ? , ? , ? , ?') ";
        // $query .= "VALUES({$post_category_id},'{$post_title}','{$post_author}',now(),'{$post_image}','{$post_content}','{$post_tags}', '{$post_status}') ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(1, $post_category_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $post_title, PDO::PARAM_STR);
        $stmt->bindParam(3, $post_author, PDO::PARAM_STR);
        $stmt->bindParam(4, $post_image, PDO::PARAM_STR);
        $stmt->bindParam(5, $post_content, PDO::PARAM_STR);
        $stmt->bindParam(6, $post_tags, PDO::PARAM_STR);
        $stmt->bindParam(7, $post_status, PDO::PARAM_STR);
        $stmt->execute();
        break;
    }
  }
}




?>




<form action="" method='post'>

  <table class="jostable>

    <div id=" bulkOptionContainer" class="col-md-4">

    <select class="form-control" name="bulk_options" id="">
      <option value="">Select Bulk operation</option>
      <option value="published">Publish</option>
      <option value="draft">Draft</option>
      <option value="delete">Delete</option>
      <option value="clone">Clone</option>
    </select>

    </div>
    <hr>


    <div class="col-xs-4">

      <input type="submit" name="submit" class="btn btn-success" value="Apply bulk operation">

      <a class="btn btn-primary push-right" href="addpost.php">Add New Post</a>

    </div>
</form>

<table class="jostable">
  <thead>
    <tr>
      <!-- <th><input id="selectAllBoxes" type="checkbox"></th>         -->
      <th><input type="checkbox" onClick="toggle(this)" /></th>
      <th>id</th>
      <th>author</th>
      <th>title</th>
      <th>cat</th>
      <th>status</th>
      <th>date</th>
      <th>image</th>
      <th>content</th>
      <th>tags</th>
      <th>views</th>
      <th>comment_<br>count</th>
      <th>actions</th>
    </tr>
  </thead>
  <tbody>


    <?php
    $stmt = $pdo->query('SELECT * FROM posts ORDER BY post_id DESC ');
    foreach ($stmt as $row) {

      $post_id            = $row['post_id'];
      $post_author        = $row['post_author'];
      $post_title         = $row['post_title'];
      $post_category_id   = $row['post_category_id'];
      $post_status        = $row['post_status'];
      $post_date          = $row['post_date'];
      $post_image         = $row['post_image'];
      $post_content       = $row['post_content'];
      $post_tags          = $row['post_tags'];
      $post_views_count   = $row['post_views_count'];
      $post_comment_count = $row['post_comment_count'];

      echo "<tr>"
    ?>
      <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'></td>

      </td>

    <?php

      echo '<td>' . $post_id . '</td> 
      <td>' . $post_author . '</td> 
      <td>' . $post_title . '</td>';

      // $query = "SELECT * FROM categories WHERE cat_id = {$post_category_id} ";
      // $select_categories_id = mysqli_query($connection,$query);  
      // while($row = mysqli_fetch_assoc($select_categories_id)) {
      $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
      $stmt->bindParam(1, $post_category_id, PDO::PARAM_INT);
      $stmt->execute();
      while ($row = $stmt->fetch()) {
        $cat_id = $row['id'];
        $cat_title = $row['title'];
        echo "<td>$cat_title</td>";
      }
      echo "<td>$post_status</td>";
      echo "<td>$post_date</td>";
      echo "<td><img width='100' src='../images/$post_image' alt='image'></td>";
      //todo truncate
      echo "<td><div class='rj-td-wrap'>" .  substr($post_content, 0, 128) . "</div></td>";


      //! comment count
      // $query = "SELECT * FROM comments WHERE comment_post_id = $post_id";
      // $send_comment_query = mysqli_query($connection, $query);
      // $row = mysqli_fetch_array($send_comment_query);
      // $comment_id = $row['comment_id'];
      // $count_comments = mysqli_num_rows($send_comment_query);

      $stmt = $pdo->prepare('SELECT * FROM comments WHERE comment_post_id = ?');
      $stmt->bindParam(1, $post_id, PDO::PARAM_INT);
      $stmt->execute();
      $count_comments = $stmt->rowCount();

      echo "<td>$post_tags</td>";

      echo "<td>{$post_views_count}<br>";
      echo "<a href='#' onClick='resetViewsModal($post_id)'>reset</a></td>";
      // echo "<td><a href='../post.php?p_id={$post_id}';>View Post</a></td>";
      echo "<td>$count_comments</td>";
      echo "<td class='rj-action-td'>";
      echo "<a href='editpost.php?&p_id={$post_id}' class='rj-action-edit';>Edit</a>";

      echo "<a href='#' onClick='deleteModal($post_id)' class='rj-action-del'>Delete</a></td>";

      echo "</tr>";
    };
    echo '
        </tbody>
        </table>
        </form>';

    ?>

    <div class="delModalWrap"></div>
    <div class="resetViewsModalWrap"></div>




    <script>
      function toggle(source) {
        checkboxes = document.getElementsByName('checkBoxArray[]');
        for (var i = 0, n = checkboxes.length;; i++) {
          checkboxes[i].checked = source.checked;
        }
      }

      modalBg = document.querySelector('.rj-modal-background');
      modal = document.querySelector('.rj-modal');
      delModal = document.querySelector('.delModalWrap');
      resetModal = document.querySelector('.resetViewsModalWrap');


      let delModalContent = `<label for="rj-modal" class="rj-modal-background"></label>
          <div class="rj-modal">
          <div class="modal-header">
          <h3>Confirm deletion</h3>
            <label for="rj-modal">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAdVBMVEUAAABNTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU0N3NIOAAAAJnRSTlMAAQIDBAUGBwgRFRYZGiEjQ3l7hYaqtLm8vsDFx87a4uvv8fP1+bbY9ZEAAAB8SURBVBhXXY5LFoJAAMOCIP4VBRXEv5j7H9HFDOizu2TRFljedgCQHeocWHVaAWStXnKyl2oVWI+kd1XLvFV1D7Ng3qrWKYMZ+MdEhk3gbhw59KvlH0eTnf2mgiRwvQ7NW6aqNmncukKhnvo/zzlQ2PR/HgsAJkncH6XwAcr0FUY5BVeFAAAAAElFTkSuQmCC" width="16" height="16" alt="" onclick="closeDelModal()">
            </label>
            </div>
            <p>
            Delete post?<br>
            `;

      let resetViewsContent = `<label for="rj-modal" class="rj-modal-background"></label>
          <div class="rj-modal">
          <div class="modal-header">
          <h3>Confirm reset</h3>
            <label for="rj-modal">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAdVBMVEUAAABNTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU0N3NIOAAAAJnRSTlMAAQIDBAUGBwgRFRYZGiEjQ3l7hYaqtLm8vsDFx87a4uvv8fP1+bbY9ZEAAAB8SURBVBhXXY5LFoJAAMOCIP4VBRXEv5j7H9HFDOizu2TRFljedgCQHeocWHVaAWStXnKyl2oVWI+kd1XLvFV1D7Ng3qrWKYMZ+MdEhk3gbhw59KvlH0eTnf2mgiRwvQ7NW6aqNmncukKhnvo/zzlQ2PR/HgsAJkncH6XwAcr0FUY5BVeFAAAAAElFTkSuQmCC" width="16" height="16" alt="" onclick="closeResetModal()" style="cursor: pointer">
            </label>
            </div>
            <p>
            reset Views?<br>
            `;


      function deleteModal(id) {
        let post_id = id;
        let link = `<a href="posts.php?delete=` + post_id + `" class="rj-modal-btn">Confirm</a><br><a href="posts.php" onClick="closeDelModal()" class="rj-modal-btn">Cancel</a> </p></div>`
        document.querySelector(".delModalWrap").innerHTML = delModalContent + link;
      }

      function resetViewsModal(id) {
        let post_id = id;
        let link = `<a href="posts.php?reset=` + post_id + `" class="rj-modal-btn">Confirm</a><br><a href="posts.php" onClick="closeResetModal()" class="rj-modal-btn">Cancel</a> </p></div>`
        document.querySelector(".resetViewsModalWrap").innerHTML = resetViewsContent + link;
      }

      function closeDelModal() {
        delModal.style.display = "none";

      }

      function closeResetModal() {
        resetModal.style.display = "none";

      }
    </script>






    <?php if (isset($_SESSION['message'])) {

      unset($_SESSION['message']);
    }

    ?>


    <?= template_admin_footer() ?>