<?php
include 'main.php';

// SQL query that will retrieve all the posts from the database ordered by the ID column
$stmt = $pdo->prepare('SELECT * FROM posts ORDER BY post_id DESC');
$stmt->execute();
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?= template_admin_header('All Media', 'allmedia') ?>

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

  <table class="table table-bordered table-hover">

    <div id="bulkOptionContainer" class="col-md-4">

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

    <table class="table table-bordered jostable">
      <thead>
        <tr>
          <th><input id="selectAllBoxes" type="checkbox"></th>
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

          echo "<tr>
       <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='" . $post_id . "'></td>

      <td>" . $post_id . '</td> 
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
          echo "<td><div class='rj-td-wrap'>" .  substr($post_content,0,128) . "</div></td>";


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

          echo "<td>{$post_views_count}<br>
          <a href='posts.php?reset={$post_id}' onClick=\" return confirm('Are you sure you want to reset views?'); \">reset</a></td>";
          // echo "<td><a href='../post.php?p_id={$post_id}';>View Post</a></td>";
          echo "<td>$count_comments<br><a href='post_comments.php?id=$post_id'>view</a></td>";
          echo "<td style='display: flex;justify-content: center;align-items: center;background: orange;'>";
          echo "<a href='editpost.php?&p_id={$post_id}' class='';>Edit</a></td>";
          echo "<td style='display: flex;justify-content: center;align-items: center;background: red;'>";
          echo "<a href='posts.php?delete={$post_id}' class=''; onClick=\" return confirm('Are you sure you want to delete?'); \">Delete</a></td>";

          echo "</tr>";
        };
        echo '
        </tbody>
        </table>
        </form>';

  

        if (isset($_POST['delete'])) {
          $stmt = $pdo->prepare('DELETE FROM posts WHERE post_id = ?');
          $stmt->bindParam(1, $post_id, PDO::PARAM_INT);
          $stmt->execute();

          header("Location: admin/posts.php");
        }


        if (isset($_GET['reset'])) {
          $stmt = $pdo->prepare('UPDATE posts SET post_views_count = 0 WHERE post_id = ? ');
          $stmt->bindParam(1, $post_id, PDO::PARAM_INT);
          $stmt->execute();

          header("Location: posts.php");
        }



        ?>
        <script>
          // let selAllBox = document.getElementById('selectAllBoxes');
          // let checkBoxes = document.getElementsByClassName('checkBoxes');
          // selAllBox.addEventListener('click', (e) => {
          //   if (selAllBox.checked) {
          //     checkBoxes.forEach(chkbox => {
          //       chkbox.defaultC = true;
          //     });
          //   } else {
          //     checkBoxes.forEach(chkbox => {
          //       chkbox.checked = false;
          //     });
          //   }
          // })
        </script>

        <!-- <script>
    


    $(document).ready(function(){


        $(".delete_link").on('click', function(){


            var id = $(this).attr("rel");

            var delete_url = "posts.php?delete="+ id +" ";


            $(".modal_delete_link").attr("href", delete_url);


            $("#myModal").modal('show');




        });



    });




  <?php if (isset($_SESSION['message'])) {

    unset($_SESSION['message']);
  }

  ?> -->



        </script>


        <?= template_admin_footer() ?>