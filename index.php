<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
// Retrieve the categories
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY title');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?= template_header('Home') ?>
<?= template_nav() ?>


<main class="rj-home">
  <header class="rj-home-header">
    <h1>Pieter Adriaans</h1>
    <div class="rj-home-header-img">
      <img src="assets/img/pieterheader.jpg" alt="banner of pieter">
      <a href="#about" class="rj-btn-light rj-home-btn-about">About</a>
    </div>
    <ul class="rj-social-list">
      <li class="rj-social-item"><a href="https://www.facebook.com/pieter.adriaans" target="_blank"><i class="fa-brands fa-facebook"></i>Pieter Adriaans</a></li>
      <li class="rj-social-item"><a href="http://www.youtube.com/user/pwadriaans/featured" target="_blank"><i class="fa-brands fa-youtube"></i>Youtube channel</a></li>
      <li class="rj-social-item"><a href="https://www.facebook.com/AtelierdeKaasfabriek" target="_blank"><i class="fa-brands fa-facebook"></i>Atelier de Kaasfabriek</a></li>
    </ul>
  </header>
  <?php
  $stmt = $pdo->prepare('SELECT * FROM posts ORDER BY post_id DESC LIMIT 1 ');

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

    <article class="rj-home-news">
    <div class="rj-news-card">
      <div class="rj-news-card-header">
      <h2><?php echo $post_title; ?></h2>
      <p>Posted on : <?php echo $post_date ?></p>
      </div>

      <div class="rj-news-card-content">
      <p><?php echo $post_content; ?></p>
      <img src="images/<?php echo $post_image?>" alt="<?php echo $post_title;?>">
      </div>

      <div class="rj-news-card-actions">
      <a class="rj-btn-light" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
      <a class="rj-btn-light" href="blog.php">All Posts <span class="glyphicon glyphicon-chevron-right"></span></a>

      </div>
    </div> 
    </article>
  <?php
  }
  ?>


  <div class="rj-flex-container">
    <article class="rj-about rj-unbreakable-text" id="about">
      <h3>About Pieter Adriaans</h3>
      <p>Works as a painter and musician at the Azores.
        Is professor of Learning and Adaptive Systems at the Universiteit van Amsterdam.
      </p>
      <ul class="rj-about-list">
        Special interest in:
        <li>Philosophy of Information</li>
        <li>Complexity theory</li>
        <li>Computational esthetics</li>
      </ul>

      <div class="rj-about-p-img">
        <img src="assets/img/kaasfabriek.png" alt="kaasfabriek logo">
        <p>
          Manages the non-profit organisation <a href="https://www.facebook.com/AtelierdeKaasfabriek" target="_blank">Atelier de Kaasfabriek</a> together with Rini Adriaans.
        </p>
      </div>
      <div class="rj-about-p-img">
        <p>Runs restaurant / music bar 'Manezinho' on Sao Jorge</p>
        
        <img src="assets/img/Manezinho Logo.jpg" alt="Manezinho logo">
      </div>

      
      </article>
  </div>


</main>



<?= template_footer() ?>