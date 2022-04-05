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
    <img src="assets/img/pieterheaderlogo.png" alt="pieter adriaans logo">
    <!-- <h1>Pieter Adriaans</h1> -->
    <!-- <div class="rj-home-header-img">
      <img src="assets/img/pieterheader.jpg" alt="banner of pieter">
      <a href="#about" class="rj-btn-light rj-home-btn-about">About</a>
    </div> -->
    <ul class="rj-social-list">
      <li class="rj-social-item"><a href="https://www.facebook.com/pieter.adriaans" target="_blank"><i class="fa-brands fa-facebook"></i>Pieter Adriaans</a></li>
      <li class="rj-social-item"><a href="http://www.youtube.com/user/pwadriaans/featured" target="_blank"><i class="fa-brands fa-youtube"></i>Youtube channel</a></li>
      <li class="rj-social-item"><a href="https://www.facebook.com/AtelierdeKaasfabriek" target="_blank"><i class="fa-brands fa-facebook"></i>Atelier de Kaasfabriek</a></li>
    </ul>
  </header>
  <section class="rj-news-section">
  <div class="rj-home-news">
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



      <div class="rj-news-card">

        <h2><?php echo $post_title; ?></h2>
        <img src="images/<?php echo $post_image ?>" class="rj-card-img">
        <article>

          <pre><?php echo trim($post_content); ?> </pre>
          <span>Posted on : <?php echo $post_date ?></span>

          <div class="rj-flex-container-row">
            <a class="rj-btn-light" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
            <a class="rj-btn-light" href="blog.php">All Posts <span class="glyphicon glyphicon-chevron-right"></span></a>
          </div>

        </article>
      </div>
      <div class="rj-about-hero">
        <!-- <img src="assets/img/heroimg201519.jpg" alt=""> -->
        <p>
          This page is dedicated to the works of the philosopher, scientist, painter, musician Pieter Adriaans (1955). It is intended as a digital archive that over time will give an extensive overview of his production.

        </p>
      </div>
  </div>
<?php
    }
?>


<!-- <div class="rj-about-wrapper">

    <article class="rj-about rj-unbreakable-text" id="about">
      <div class="rj-about-img">
        <img src="assets/img/manezhinos.jpeg" alt="Manezhinos">
        <blockquote>
          In the morning I’m a philosopher, in the afternoon a painter and in the evening a musician.
        </blockquote>

      </div>
      <p style="text-align: justify;">After a life dedicated to art, science and business, the adventurer, businessman, philosopher
        and artist, Pieter Adriaans retired to the isolated island of São Jorge on the Azores where he
        runs the non-profit organization Atelier de Kaasfabriek dedicated to the promotion of the
        arts.<br> Works of Pieter can be seen in Grand Café Manezinho, in Urzelina at the south coast of
        the island. Here he exhibits his paintings, plays his music with friends.

      </p>
    </article>
  </div> -->
  </section>
<div class="rj-about-logo-container">
  <img src="assets/img/kaasfabriek.png" alt="kaasfabriek logo" class="rj-about-logo">
  <img src="assets/img/Manezinho Logo.jpg" alt="manezinhos logo" class="rj-mz">
</div>


</main>



<?= template_footer() ?>