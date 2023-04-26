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
    <!-- <h1>Pieter Adriaans</h1> -->
    <!-- <div class="rj-home-header-img">
      <img src="assets/img/pieterheader.jpg" alt="banner of pieter">
      <a href="#about" class="rj-btn-light rj-home-btn-about">About</a>
    </div> -->
    
    <div class="rj-home-hero">
      
      <div class="rj-cta-main">
        <p>This page is dedicated to the works of the philosopher, scientist, painter, musician Pieter Adriaans (1955). It is intended as a digital archive that over time will give an extensive overview of his production.</p>
        
      </div>
    </div>
    <img src="assets/img/pieterheaderlogo.png" alt="pieter adriaans logo">
    <ul class="rj-social-list">
      <li class="rj-social-item"><a href="https://www.facebook.com/pieter.adriaans" target="_blank"><i class="fa-brands fa-facebook"></i>Pieter Adriaans</a></li>
      <li class="rj-social-item"><a href="http://www.youtube.com/user/pwadriaans/featured" target="_blank"><i class="fa-brands fa-youtube"></i>Youtube channel</a></li>
      <li class="rj-social-item"><a href="https://www.facebook.com/AtelierdeKaasfabriek" target="_blank"><i class="fa-brands fa-facebook"></i>Atelier de Kaasfabriek</a></li>
    </ul>
  </header>
  <!-- <article class="rj-cta-main">
    
    This page is dedicated to the works of the philosopher, scientist, painter, musician Pieter Adriaans (1955). It is intended as a digital archive that over time will give an extensive overview of his production.
      
   
  </article> -->
  <!-- <section class=" rj-news-section"> -->
        <div class="blog-css-grid">
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

</div>
      </section>
      <div class="rj-about-logo-container">
        <a href="https://www.facebook.com/AtelierdeKaasfabriek" target="_blank"><img src="assets/img/kaasfabriek.png" alt="kaasfabriek logo" class="rj-kaas-logo"></a>
        <a href="http://wwww.artrestaurantmanezinho.com" target="_blank"><img src="assets/img/Manezinho Logo.jpg" alt="manezinhos logo" class="rj-mz">
      </div>


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
  
        dropdownToggle.addEventListener('click', function () {
          const expanded = dropdownToggle.getAttribute('aria-expanded') === 'true';
          dropdownToggle.setAttribute('aria-expanded', !expanded);
          console.log("dropclick");
        });
  
        window.addEventListener('click', function (event) {
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