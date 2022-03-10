<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();


if (isset($_GET['p_id'])) {

  $the_post_id = $_GET['p_id'];


  //todo toPDO
  // $update_statement = mysqli_prepare($connection, "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = ?");
  // mysqli_stmt_bind_param($update_statement, "i", $the_post_id);
  // mysqli_stmt_execute($update_statement);

  $stmt = $pdo->prepare('UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = ?');
  $stmt->bindParam(1, $the_post_id, PDO::PARAM_INT);
  $stmt->execute();

  // $stmt2 = mysqli_prepare($connection , "SELECT post_title, post_author, post_date, post_image, post_content FROM posts WHERE post_id = ? AND post_status = ? ");
  // mysqli_stmt_bind_param($stmt2, "is", $the_post_id, $published);
  // mysqli_stmt_execute($stmt2);
  // mysqli_stmt_bind_result($stmt2, $post_title, $post_author, $post_date, $post_image, $post_content);
  // $stmt = $stmt2;

  $published = 'published';

  $stmt = $pdo->prepare('SELECT * FROM posts WHERE post_id = ? AND post_status = ? ');
  $stmt->bindParam(1, $the_post_id, PDO::PARAM_INT);
  $stmt->bindParam(2, $published, PDO::PARAM_STR);
  $stmt->execute();
  while ($row = $stmt->fetch()) {
    $post_title = $row['post_title'];
    $post_author = $row['post_author'];
    $post_date = $row['post_date'];
    $post_image = $row['post_image'];
    $post_content = $row['post_content'];
    $post_views = $row['post_views_count'];
  }

?>
  <?= template_header($post_title) ?>
  <?= template_nav() ?>



  <main class="rj-home">
    <section>
      <article class="rj-blog-card">
        <header class="rj-blog-header">
          <h1><?php echo $post_title; ?></h1>
          <p>by : <?php echo $post_author ?> - <em><?php echo $post_date . "</em> - <small>" . $post_views; ?> views</small></p>
        </header>
        <div class="rj-blog-card-content rj-post-column-reverse">
          <div class="rj-post-image">
            <img class="blog-image" src="images/<?php echo $post_image; ?>" alt="<?php echo $post_title ?>">

          </div>

          <p><?php echo $post_content; ?></p>
      </article>



      <?php
      $query = "SELECT * FROM comments WHERE comment_post_id = ? ";
      $query .= "AND comment_status = 'approved' ";
      $query .= "ORDER BY comment_id DESC ";
      // $select_comment_query = mysqli_query($connection, $query);
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(1, $the_post_id, PDO::PARAM_INT);
      $stmt->execute();
      // while ($row = mysqli_fetch_array($select_comment_query)) {
      $count = $stmt->rowCount();
      if ($count !== 0) {
        while ($row = $stmt->fetch()) {
          $comment_date   = $row['comment_date'];
          $comment_content = $row['comment_content'];
          $comment_author = $row['comment_author'];
      ?>
          <article class="rj-blog-card">
            <header class="rj-post-comment-header">
              <i class="fa-regular fa-message push-left"></i> <?php echo ucfirst($comment_author); ?>
              <small> <?php echo $comment_date; ?></small></p>
            </header>
            <div class="rj-post-comment">
              <?php echo $comment_content; ?>
            </div>
          </article>
          <?php
        }
      } else {
          ?>
          <article class="rj-blog-card">

          <header class="rj-post-comment-header">
            <h2>No comments , yet!</h2>
          </header>

          </article>
      <?php
      }
    } else {
      header("Location: index.php");
    }
      ?>


          </article>

          <div class="rj-comment-button">
            <a href="#commentform">comment</a>
          </div>


          <article class="rj-comment-form-wrapper">
            <div class="rj-post-comment-header rj-form-header">
              <h4>Leave a Comment:</h4>
            </div>
            <form action="#" method="POST" role="form" class="rj-comment-form">

              <div class="">
                <label for="Author">Author</label>
                <input type="text" name="comment_author" class="form-control" name="comment_author">
              </div>

              <div class="">
                <label for="Author">Email</label>
                <input type="email" name="comment_email" class="form-control" name="comment_email">
              </div>

              <div class="">
                <label for="comment">Your Comment</label>
                <textarea name="comment_content" class="form-control" rows="3"></textarea>
                <button type="submit" name="create_comment" class="rj-btn-light">Submit</button>
              </div>
            </form>
            </div>
          </article>



          <?php
          if (isset($_POST['create_comment'])) {

            $the_post_id = $_GET['p_id'];
            $comment_author = $_POST['comment_author'];
            $comment_email = $_POST['comment_email'];
            $comment_content = $_POST['comment_content'];


            if (!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {
              $query = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status,comment_date)";
              // $query .= "VALUES ($the_post_id ,'{$comment_author}', '{$comment_email}', '{$comment_content }', 'unapproved',now())";
              $query .= "VALUES (? , ? , ? , ? , 'unapproved' , now())";
              // $create_comment_query = mysqli_query($connection, $query);

              $stmt = $pdo->prepare($query);
              $stmt->bindParam(1, $the_post_id, PDO::PARAM_INT);
              $stmt->bindParam(2, $comment_author, PDO::PARAM_STR);
              $stmt->bindParam(3, $comment_email, PDO::PARAM_STR);
              $stmt->bindParam(4, $comment_content, PDO::PARAM_STR);
              $stmt->execute();
              ?>
              <script> commentForm.style.display = "none"; </script> 
              <?php
            }
          }
          ?>



          </div>
        </div>
      </section>
    </main>
    
    <hr>
    
    
    <div class="media-popup"></div>
    <script>
    let commentForm = document.querySelector(".rj-comment-form-wrapper");
    let commentBtn = document.querySelector(".rj-comment-button"); 

    commentBtn.addEventListener('click', function() {
      commentForm.style.display = "flex";
      commentBtn.style.display = "none";
    })


  </script>

  <?= template_footer() ?>