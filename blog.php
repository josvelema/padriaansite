<?php
include 'functions.php';

$currentPage = "blog";
// Connect to MySQL
$pdo = pdo_connect_mysql();
// Retrieve the categories
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY title');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Retrieve the requested category

// The current pagination page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

?>
<?= template_header('Blog') ?>
<!-- <link rel="stylesheet" href="assets/css/blog.css"> -->
<?= template_nav('blog') ?>


<?php

$per_page = 6;


(isset($_GET['page'])) ? $page = $_GET['page'] : $page = "";

($page == "" || $page == 1) ? $page_1 = 0 : $page_1 = ($page * $per_page) - $per_page;

$stmt = $pdo->query("SELECT * FROM posts WHERE post_status = 'published' ORDER BY post_id DESC");

$count = $stmt->rowCount();
$total_posts = $count;

if ($count < 1) {

  echo "<h1 class='text-center'>No posts available</h1>";
} else {
  $count  = ceil($count / $per_page);
  $published = "published";
?>

  <main class="rj-blog-main">
    <section class="rj-blog">

      <header class="rj-blog-header">
        <h2>Pieter Adriaans - Blog </h2>
        <small>total posts: 
          <?= $total_posts . ' | viewing page ' . $current_page . ' of ' . $count ;?>
        </small>

          <ul class="rj-pager">
            <span>page </span>
            <?php

            $number_list = array();


            for ($i = 1; $i <= $count; $i++) {
              echo ($i == $page) ? "<li><a class='rj-active-page' href='blog.php?page={$i}'>{$i}</a></li>"
                : "<li><a href='blog.php?page={$i}'>{$i}</a></li>";
            }

            ?>

          </ul>
          <hr>
      </header>

      <div data-component class="blog-css-grid">


        <?php
        //todo prepared stmt
        // $query = "SELECT * FROM posts LIMIT $page_1, $per_page";
        // $select_all_posts_query = mysqli_query($pdo, $query);

        $stmt = $pdo->prepare('SELECT * FROM posts WHERE post_status = ? ORDER BY post_id DESC LIMIT ? , ?');
        $stmt->bindParam(1, $published, PDO::PARAM_STR);
        $stmt->bindParam(2, $page_1, PDO::PARAM_INT);
        $stmt->bindParam(3, $per_page, PDO::PARAM_INT);
        $stmt->execute();

        // while ($row = mysqli_fetch_assoc($select_all_posts_query)) {
        while ($row = $stmt->fetch()) {
          $post_id = $row['post_id'];
          $post_title = $row['post_title'];
          $post_author = $row['post_author'];
          $post_date = $row['post_date'];
          $post_image = $row['post_image'];
          $post_content = substr($row['post_content'], 0, 250) . "..."; //! truncated !!
          $post_status = $row['post_status'];
          $post_views = $row['post_views_count'];



        ?>


          <div data-component class="rj-blog-card">
            <div class="rj-blog-card-header">
              <a href="post.php?p_id=<?= $post_id; ?>" class="rj-card-title"><?= $post_title ?></a>
              <p>by : <?= $post_author ?> - <em><?= $post_date . "</em> - <small>" . $post_views; ?> views</small></p>
            </div>
            <div class="rj-blog-card-content">
              <a href="post.php?p_id=<?= $post_id; ?>">
                <img class="blog-image" src="images/<?= $post_image; ?>" alt="<?= $post_title ?>">
              </a>
              <?= "<pre>" . trim($post_content) . "</pre>"; ?>

            </div>
            <div class="rj-blog-card-footer">
              <a class="rj-btn-light" href="post.php?p_id=<?= $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
              <!-- <a href="https://moderncss.dev">Visit ModernCSS.dev</a> -->
            </div>
          </div>


      <?php }
      } ?>

      </div>

      <ul class="rj-pager">
        <span>Page </span>
        <?php

        $number_list = array();


        for ($i = 1; $i <= $count; $i++) {
          echo ($i == $page) ? "<li><a class='rj-active-page' href='blog.php?page={$i}'>{$i}</a></li>"
            : "<li><a href='blog.php?page={$i}'>{$i}</a></li>";
        }

        ?>

      </ul>

    </section>
  </main>








  </div>

  <div class="media-popup"></div>

  <?= template_footer() ?>