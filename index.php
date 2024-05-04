<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
// Retrieve the categories
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY title');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM media ORDER BY id DESC LIMIT 1');
$stmt->execute();
$mediaLast = $stmt->fetch(PDO::FETCH_ASSOC);
?>


<?= template_header('Home') ?>
<?= template_nav('home') ?>


<main class="rj-home">
  <!-- <header class="rj-home-header">

    <div class="rj-home-hero">

      <div class="rj-cta-main">
        <p>This page is dedicated to the works of the philosopher, scientist, painter, musician Pieter Adriaans (1955). It is intended as a digital archive that over time will give an extensive overview of his production.</p>

      </div>
    </div>

  </header> -->
  <header class="rj-home-header">
    <div class="hero-title">
      <h1>Pieter Adriaans</h1>
      <h2>Philosopher, Painter, Musician</h2>
      <p>This page is dedicated to the works of the philosopher, scientist, painter, musician Pieter Adriaans (1955). It is intended as a digital archive that over time will give an extensive overview of his production.</p>
    </div>

  </header>

  <div class="last-image" style="display:none; ">
    <last-image-info>
      <h3><?= $mediaLast['title'] ?></h3>
      <small><?= $mediaLast['year'] ?> - <?= $mediaLast['fnr'] ?> </small>
    </last-image-info>
    <div class="last-image-container" style="background-image: url(<?= $mediaLast['filepath'] ?>)">

    </div>
  </div>

  <section class="rj-about-section">

    <article class="rj-about-article">
      <img src="assets/img/pieterheaderlogo.png" alt="pieter adriaans logo" class="rj-home-pieter-letters">

      <img src="assets/img/aboutpieterprofiel.JPG" alt="Photo Pieter">
      <ul class="rj-social-list">
        <li class="rj-social-item"><a href="https://www.facebook.com/pieter.adriaans" target="_blank"><i class="fa-brands fa-facebook"></i>Pieter Adriaans</a></li>
        <li class="rj-social-item"><a href="http://www.youtube.com/user/pwadriaans/featured" target="_blank"><i class="fa-brands fa-youtube"></i>Youtube channel</a></li>
        <li class="rj-social-item"><a href="https://www.facebook.com/AtelierdeKaasfabriek" target="_blank"><i class="fa-brands fa-facebook"></i>Atelier de Kaasfabriek</a></li>
      </ul>
      <div class="rj-about-text">
        <p>
          Creating a balance between art, science and business, that has been the lifelong ambition of philosopher/painter/entrepreneur Pieter Adriaans. Art, because it is, apart from love, one of the richest fulfillments life can offer, science because we have an innate urge to understand the world around us, and business because one needs a sound basis to chase one’s dreams.
        </p>
        <p>
          Pieter Willem Adriaans (1955) grew up in Holland in the sixties and in his early teens got caught in the turmoil of the emerging counterculture that raged through the complacent Dutch society of the time. This experience is documented in his work and has been a continuous inspiration ever since. At the age of fourteen he decided that enough was enough, the fun was over, and it was time to get proper schooling and seriously work on his skills as an artist. One lucky day, a year later, he met his future spouse Rini and asked her to marry him that same evening. They have been together for more than fifty years.
        </p>
        <p>
          Forsaking official art school, because it had little to offer for his ambitions as a figurative painter, he studied analytical philosophy and (some) mathematics for seven years at Leiden University.
        </p>
        <p>
          He started a career in the software business and founded his own company Syllogic with partner Dolf Zantinge in the early nineties. At the same time, he wrote a thesis on machine learning and obtained a PhD in theoretical computer science at the University of Amsterdam. Syllogic developed into one of the leading companies in Artificial Intelligence and data mining in the world and was sold to Perot systems by the end of the decade. Based on these achievements, Pieter was offered a chair in Artificial Intelligence at the University of Amsterdam, which he held until his retirement from university in 2021.
        </p>
        <p>
          At the turn of the century while on a solo transatlantic crossing in his yacht the Syllogic Sailing lab, Pieter was caught in a fierce and challenging storm that forced him to seek refuge in the port of Horta in the Azores. Both he and Rini, who flew into Horta to assist him with the repairs, fell in love with the islands and decided to buy a house in the village of Manadas on the island of São Jorge. The isolated society of the islands, with its rich history and astonishing nature has been a constant inspiration ever since.
        </p>
        <p>
          Over the next two decades Pieter honed his skills as a painter and a philosopher. He became one of the world’s leading experts on philosophy of information. He used information theory to study human cognition and creativity. In his studio in Santo Antonio he conducted extensive research into innovative techniques for drawing and painting. In 2013 he published a book ‘Painting for the brain’ in Dutch that explained his findings for a lay audience.
        </p>
        <hr>
        <p>
          Currently, Pieter and Rini continue to live on São Jorge Island where they run the<a href="http://www.artrestaurantmanezinho.com" target="_blank"> Art Restaurant Manezinho</a>, and a cultural center, <a href="https://www.facebook.com/AtelierdeKaasfabriek" target="_blank">Atelier de Kaasfabriek</a>. Here Pieter keeps the dream of balance between science, art and business alive:
        </p>

      </div>
      <div class="rj-about-logo-container">
        <a href="https://www.facebook.com/AtelierdeKaasfabriek" target="_blank"><img src="assets/img/kaasfabriek.png" alt="kaasfabriek logo" class="rj-kaas-logo"></a>
        <a href="http://www.artrestaurantmanezinho.com" target="_blank"><img src="assets/img/Manezinho Logo.jpg" alt="manezinhos logo" class="rj-mz"></a>
      </div>
      <blockquote class="rj-about-quote">
        In the morning I am a philosopher, in the afternoon a painter and in the evening you can often find me singing and playing my guitar in Manezinho.
      </blockquote>
    </article>
  </section>

  <section class="blog-css-grid">
    <?php
    $stmt = $pdo->prepare('SELECT * FROM posts ORDER BY post_id DESC LIMIT 2 ');

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



      <div class="rj-blog-card">

        <h2 class="rj-card-title"><?php echo $post_title; ?></h2>
        <img src="images/<?php echo $post_image ?>" class="rj-card-img">
        <article class="rj-blog-card-content">

          <pre><?php echo trim($post_content); ?> </pre>
          <span>Posted on : <?php echo $post_date ?></span>

          <div class="rj-flex-container-row">
            <a class="rj-btn-light" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>
            <a class="rj-btn-light" href="blog.php">All Posts <span class="glyphicon glyphicon-chevron-right"></span></a>
          </div>

        </article>
      </div>

    <?php
    }
    ?>

  </section>
  </section>



</main>

<script>
  // // Get the modal
  // var modal = document.getElementById("myModal");

  // // Get the button that opens the modal
  // var btn = document.querySelector(".rj-btn-light");

  // // Get the <span> element that closes the modal
  // var span = document.getElementsByClassName("rj-close")[0];

  // // When the user clicks the button, open the modal 
  // btn.onclick = function() {
  //   modal.style.display = "block";
  //   console.log("clicked");
  // }

  // // When the user clicks on <span> (x), close the modal
  // span.onclick = function() {
  //   modal.style.display = "none";

  // }

  // // When the user clicks anywhere outside of the modal, close it
  // window.onclick = function(event) {
  //   console.log(event.target);
  //   if (event.target == modal) {
  //     modal.style.display = "none";
  //   }
  // }

  // // When the user clicks anywhere outside of the modal, close it
  // window.onclick = function(event) {
  //   console.log(event.target);
  //   if (event.target == modal) {
  //     modal.style.display = "none";
  //   }
  // }
  const dropdownToggle = document.querySelector('.dropdown-toggle');
  const dropdownMenu = document.querySelector('.dropdown-menu');

  dropdownToggle.addEventListener('click', function() {
    const expanded = dropdownToggle.getAttribute('aria-expanded') === 'true';
    dropdownToggle.setAttribute('aria-expanded', !expanded);
    console.log("dropclick");
  });

  window.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown')) {
      dropdownToggle.setAttribute('aria-expanded', 'false');
    }
  });



  window.addEventListener('click', function(event) {
    if (!event.target.closest('.dropdown')) {
      dropdownToggle.setAttribute('aria-expanded', 'false');
    }
  });
</script>

<?= template_footer() ?>