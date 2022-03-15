<?php
include 'main.php';

if (isset($_GET['approve'])) {

  $stmt = $pdo->prepare("UPDATE comments SET comment_status = 'approved' WHERE comment_id = ?");
  $stmt->bindParam(1, $_GET['approve'], PDO::PARAM_INT);
  $stmt->execute();
  header("Location: comments.php");

}

if (isset($_GET['unapprove'])) {
  $stmt = $pdo->prepare("UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = ?");
  $stmt->bindParam(1, $_GET['unapprove'], PDO::PARAM_INT);
  $stmt->execute();
  header("Location: comments.php");

}


if (isset($_GET['delete'])) {
  
  $stmt = $pdo->prepare("DELETE FROM comments WHERE comment_id = ?");
  $stmt->bindParam(1, $_GET['delete'], PDO::PARAM_INT);
  $stmt->execute();
  header("Location: comments.php");
}

?>
<?= template_admin_header('Comments', 'comments') ?>

<h2>Comments</h2>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>id</th>
      <th>author</th>
      <th>Comment</th>
      <th>Email</th>
      <th>Status</th>
      <th>Response to</th>
      <th>Date</th>
      <th>(un)approve</th>
    </tr>
  </thead>

  <tbody>

    <?php
    $stmt = $pdo->query('SELECT * FROM comments ORDER BY comment_id DESC ');
    foreach ($stmt as $row) {

      $comment_id = $row['comment_id'];
      $comment_post_id = $row['comment_post_id'];
      $comment_author = $row['comment_author'];
      $comment_email = $row['comment_email'];
      $comment_date = $row['comment_date'];
      // $comment_image = $row['comment_image'];
      $comment_content = $row['comment_content'];
      // $comment_tags = $row['comment_tags'];
      // $comment_comment_count = $row['comment_comment_count'];
      $comment_status = $row['comment_status'];

      echo "<tr>";
      echo "<td>{$comment_id}</td>";
      echo "<td>{$comment_author}</td>";
      echo "<td><div class='rj-td-wrap'>{$comment_content}</div></td>";
      echo "<td>{$comment_email}</td>";
      echo "<td>{$comment_status}</td>";

      $stmt = $pdo->prepare("SELECT * FROM posts WHERE post_id = ?");
      $stmt->bindParam(1, $comment_post_id, PDO::PARAM_INT);
      $stmt->execute();
      foreach ($stmt as $row) {

        // $select_post_id_query = mysqli_query($conn, $query);
        // while ($row = mysqli_fetch_assoc($select_post_id_query)) 
        // {
        $post_id = $row['post_id'];
        $post_title = $row['post_title'];

        echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a> <br>Post ID: " . $comment_post_id ." </td>";
      }

      // echo "<td>{$comment_post_id}</td>";

      echo "<td>{$comment_date}</td>";



      echo "<td >";
      echo "<a href='comments.php?approve={$comment_id}' class='';>Approve</a>";

      echo " / ";
      echo "<a href='comments.php?unapprove={$comment_id}' class='';>Unapprove</a></td>";



      echo "<td>";
      echo "<a href='comments.php?delete={$comment_id}' class='rj-action-del';>Delete</a></td>";

      echo "</tr>";
    }


    ?>

  </tbody>
</table>

<?php if (isset($_SESSION['message'])) {

  unset($_SESSION['message']);
}

?>


</script>


<?= template_admin_footer() ?>