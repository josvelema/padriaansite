<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
// Retrieve the categories
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY title');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Retrieve the requested category
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$category_sql = $category != 'all' ? 'JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category' : '';
// Sort by default is newest, feel free to change it..
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'newest';
$sort_by_sql = 'm.uploaded_date DESC';
$sort_by_sql = $sort_by == 'newest' ? 'm.uploaded_date DESC' : $sort_by_sql;
$sort_by_sql = $sort_by == 'oldest' ? 'm.uploaded_date ASC' : $sort_by_sql;
$sort_by_sql = $sort_by == 'a_to_z' ? 'm.title DESC' : $sort_by_sql;
$sort_by_sql = $sort_by == 'z_to_a' ? 'm.title ASC' : $sort_by_sql;
// Get media by the type (ignore if set to all)
$type = isset($_GET['type']) ? $_GET['type'] : 'all';
$type_sql = $type != 'all' ? 'AND m.type = :type' : '';
//! Limit the amount of media on each page
$media_per_page = 6;
// The current pagination page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
// MySQL query that selects all the media
$stmt = $pdo->prepare('SELECT * FROM media m ' . $category_sql . ' WHERE m.approved = 1 ' . $type_sql . ' ORDER BY ' . $sort_by_sql . ' LIMIT :page,:media_per_page');
// Determine which page the user is on and bind the value into our SQL statement
$stmt->bindValue(':page', ((int)$current_page - 1) * $media_per_page, PDO::PARAM_INT);
// How many media will show on each page
$stmt->bindValue(':media_per_page', $media_per_page, PDO::PARAM_INT);
// Check if the type is not set to all
if ($type != 'all') $stmt->bindValue(':type', $type);
// Check if the category is not set to all
if ($category != 'all') $stmt->bindValue(':category', $category);
// Execute the SQL
$stmt->execute();
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of media
$stmt = $pdo->prepare('SELECT COUNT(*) FROM media m ' . $category_sql . ' WHERE m.approved = 1 ' . $type_sql);
if ($type != 'all') $stmt->bindValue(':type', $type);
if ($category != 'all') $stmt->bindValue(':category', $category);
$stmt->execute();
$total_media = $stmt->fetchColumn();
// Set media properties below
$media_width = 320;
$media_height = 210;
?>
<?= template_header('Gallery') ?>
<main>
  <section class="rj-blog">

    <header class="rj-blog-header">
      <h1>Pieter Adriaans - blog</h1>
      <hr>
    </header>

    <div data-component class="blog-css-grid">

      <?php

      $per_page = 10;


      (isset($_GET['page'])) ? $page = $_GET['page'] : $page = "";

      ($page == "" || $page == 1) ? $page_1 = 0 : $page_1 = ($page * $per_page) - $per_page;

      $stmt = $pdo->query("SELECT * FROM posts WHERE post_status = 'published'");

      $count = $stmt->rowCount();

      if ($count < 1) {

        echo "<h1 class='text-center'>No posts available</h1>";
      } else {
        $count  = ceil($count / $per_page);
        $published = "published";
        //todo prepared stmt
        // $query = "SELECT * FROM posts LIMIT $page_1, $per_page";
        // $select_all_posts_query = mysqli_query($pdo, $query);

        $stmt = $pdo->prepare('SELECT * FROM posts WHERE post_status = ? LIMIT ? , ?');
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
          $post_content = substr($row['post_content'], 0, 250); //! truncated !!
          $post_status = $row['post_status'];
          $post_views = $row['post_views_count'];



      ?>
     

          <div data-component class="rj-blog-card">
            <div class="rj-blog-card-header">
              <a href="post.php?p_id=<?php echo $post_id; ?>" class="rj-card-title"><?php echo $post_title ?></a>
              <p>by : <?php echo $post_author ?> - <em><?php echo $post_date . "</em> - <small>" . $post_views; ?> views</small></p>
            </div>
            <div class="rj-blog-card-content">
              <a href="post.php?p_id=<?php echo $post_id; ?>">
                <img class="blog-image" src="images/<?php echo $post_image; ?>" alt="<?php echo $post_title ?>">
              </a>
                <p><?php echo $post_content ?></p>
            </div>
            <div class="rj-blog-card-footer">
              <a class="rj-btn-light" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
              <!-- <a href="https://moderncss.dev">Visit ModernCSS.dev</a> -->
            </div>
          </div>


      <?php }
      } ?>

    </div>
  </section>
</main>







<ul class="pager">

  <?php

  $number_list = array();


  for ($i = 1; $i <= $count; $i++) {
    echo ($i == $page) ? "<li><a class='active_link' href='index.php?page={$i}'>{$i}</a></li>"
      : "<li><a href='index.php?page={$i}'>{$i}</a></li>";
  }

  ?>
</ul>

</div>

<div class="media-popup"></div>

<?= template_footer() ?>