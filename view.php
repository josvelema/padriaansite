<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  // Retrieve the media from the media table using the GET request ID (URL param)
  $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
  $stmt->execute([$_GET['id']]);
  $media = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$media) {
    exit('Media does not exist with this ID!');
  }
} else {
  exit('No ID specified!');
}

$given_id = is_numeric($_GET['id']) ? $_GET['id'] : exit('No ID specified!');

// check if media is available for sale and if so redirect to https://azorean-art.com/artwork.html?id=1
if ($media['art_status'] == 'available' || $media['art_status'] == 'for sale') {
  header('Location: https://azorean-art.com/artwork.html?id=' . $given_id);
  exit;
}

// check if fromAlbum is set in the URL, if so redirect to the album page
if (isset($_GET['fromAlbum']) && is_numeric($_GET['fromAlbum'])) {
  $toAlbum = $_GET['fromAlbum'];
}

?>

<?= template_header($media['title']) ?>
<?= template_header_other() ?>
<link rel="stylesheet" href="assets/css/gallery.css?v=2">

<?= template_nav() ?>



<main class="rj-black-bg-main">
  <div class="content home">
    <section>
      <div class="view-container">
        <article class="rj-gallery-card">
          <div class="card">
            <div class="card-overlay-content-button">+</div>
            <div class="card-overlay-content">
              <div class="card-header">
                <h3><?= $media['title'] ?></h3>
                <p>Catalogue nr : <?= $media['year'] ?> - <?= $media['fnr'] ?></p>
              </div>
              <div class="media-selection-container">
                <button class="img-btn" data-src="<?= ($media['type'] == 'image') ? $media['filepath'] : $media['thumbnail'] ?>"><i class="fa-solid fa-expand"></i></button>
                <!-- chek if description is not equal to '...' -->
                <?php if (!empty($media['description']) && trim($media['description']) != '...') : ?>
                  <button class="info-btn" data-info="<?= trim($media['description']) ?>" data-title="<?= $media['title'] ?>"><i class="fa-solid fa-circle-info"></i></i></button>
                <?php endif; ?>
                <!-- check if audio url is not empty -->
                <?php if (!empty($media['audio_url'])) : ?>
                  <button class="audio-btn" data-src="<?= urldecode($media['audio_url']) ?>"><i class="fa-solid fa-headphones"></i></button>
                <?php endif; ?>
                <!-- check if video url is not empty -->
                <?php if (!empty($media['video_url'])) : ?>
                  <button class="video-btn" data-src="<?= urldecode($media['video_url']) ?>"><i class="fa-solid fa-video"></i></button>
                <?php endif; ?>
              </div>
            </div>
            <div class="card-body">
              <?php if ($media['type'] == 'image') : ?>
                <div class="image-container">
                  <div class="img-wrapper">
                    <img src="<?= $media['thumbnail'] ?>" data-src="<?= $media['filepath'] ?>" alt="<?= $media['title'] ?>" class="lozad placeholder">
                  </div>
                </div>


              <?php elseif ($media['type'] == 'video') : ?>
                <div class="image-container">
                  <div class="img-wrapper">
                    <img src="<?= $media['thumbnail'] ?>" data-src="<?= $media['filepath'] ?>" alt="<?= $media['title'] ?>" class="lozad placeholder">
                    <div class="video-overlay">
                      <button class="video-btn" data-src="<?= $media['video_url'] ?>"><i class="fa-solid fa-play"></i></button>
                      <video src="<?= $media['filepath'] ?>" width="852" height="480" controls autoplay></video>

                    </div>
                  </div>
                <?php elseif ($media['type'] == 'audio') : ?>
                  <div class="image-container">
                    <div class="img-wrapper">
                      <img src="<?= $media['thumbnail'] ?>" data-src="<?= $media['thumbnail'] ?>" alt="<?= $media['title'] ?>" class="lozad placeholder" width="600">
                      <audio controls preload="metadata" class="rj-audio-player">
                        <?php
                        $filepath = htmlspecialchars($media['filepath']);
                        $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));

                        // Choose correct MIME type
                        $mime = 'audio/mpeg'; // default fallback
                        if ($ext === 'm4a') $mime = 'audio/mp4';
                        elseif ($ext === 'wav') $mime = 'audio/wav';
                        elseif ($ext === 'ogg') $mime = 'audio/ogg';

                        echo "<source src=\"$filepath\" type=\"$mime\">";
                        ?>
                        Your browser does not support the audio element.
                      </audio>
                    </div>
                  </div>

                <?php endif; ?>
                <div class="modal-container">
                  <div class="audio-modal-container">
                    <div class="audio-modal-content">
                      <audio class="audio-modal-audio" controls poster="assets\img\bginverted.jpg">
                        Your browser does not support the audio tag.
                      </audio>
                      <button class="audio-modal-close">Close</button>
                    </div>
                  </div>
                  <div class="video-modal-container">
                    <div class="video-modal-content">
                      <video class="video-modal-video" controls>
                        Your browser does not support the video tag.
                      </video>
                      <button class="video-modal-close">Close</button>
                    </div>
                  </div>
                  <div class="image-modal-container">
                    <div class="image-modal-content">
                      <img class="image-modal-image" src="" alt="">
                      <button class="image-modal-close">Close</button>
                    </div>
                  </div>
                  <div class="info-modal-container">
                    <div class="info-modal-content">
                      <h3 class="info-modal-title"></h3>
                      <pre class="info-modal-pre"></pre>
                      <button class="info-modal-close">Close</button>
                    </div>
                  </div>
                </div>

                </div>
            </div>
        </article>
        <p>
          <?php if ($media['type'] == 'image') : ?>
            <a href="gallery" class="rj-button">← Back to image gallery</a>
          <?php elseif ($media['type'] == 'audio') : ?>
            <a href="music<?= (isset($toAlbum)) ? '?album=' . $toAlbum : '' ?>" class="rj-button">← Back to music page</a>
          <?php else : ?>
            <a href="/" class="rj-button">← Back to home</a>
          <?php endif; ?>

        </p>
      </div>
    </section>





</main>


<script>
  // card overlay content button
  const cardOverlayContentButton = document.querySelectorAll('.card-overlay-content-button');
  const cardOverlayContent = document.querySelectorAll('.card-overlay-content');
  // const cardOverlayContentClose = document.querySelectorAll('.card-overlay-content-close');

  cardOverlayContentButton.forEach((button, index) => {
    button.addEventListener('click', () => {
      cardOverlayContent[index].classList.toggle('active');
      (button.textContent == '+') ? button.textContent = '-': button.textContent = '+';
    });
  });


  document.addEventListener('DOMContentLoaded', () => {
    //   const searchForm = document.getElementById('search-form');
    //   const searchInput = document.getElementById('search');
    //   const categorySelect = document.getElementById('category');

    //   searchForm.addEventListener('submit', (event) => {
    //     event.preventDefault();
    //     loadMedia(categorySelect.value, searchInput.value);
    //   });

    //   categorySelect.addEventListener('change', () => {
    //     loadMedia(categorySelect.value, searchInput.value);
    //   });

    //   async function loadMedia(category, search = '') {
    //     const response = await fetch('search.php', {
    //       method: 'POST',
    //       headers: {
    //         'Content-Type': 'application/x-www-form-urlencoded'
    //       },
    //       body: `category=${encodeURIComponent(category)}&search=${encodeURIComponent(search)}`
    //     });

    //     if (response.ok) {
    //       const resultsHtml = await response.text();
    //       const galleryContainer = document.querySelector('.gallery-container');
    //       galleryContainer.innerHTML = resultsHtml;
    //     }
    //   }

    // Load media initially
    // loadMedia(categorySelect.value);



    const observer = lozad(".lozad", {
      loaded: function(el) {
        el.classList.add("fade");
        console.log("loadewe");
      }
    });
    observer.observe();
  });
  // function loadMedia(category, search = '') {
  // const xhr = new XMLHttpRequest();
  // xhr.onreadystatechange = function() {
  //   if (this.readyState === 4 && this.status === 200) {
  //     document.getElementById('media-container').innerHTML = this.responseText;
  //   }
  // };

  // const url = `gallery.php?category=${category}&search=${encodeURIComponent(search)}`;
  // xhr.open('GET', url, true);
  // xhr.send();}
</script>

<?= template_footer() ?>