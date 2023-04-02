<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

$search = $_POST['search'];
$mediaFilesQuery = "SELECT * FROM media WHERE title LIKE :search OR year LIKE :search ORDER BY id DESC";
$stmt = $pdo->prepare($mediaFilesQuery);
$stmt->bindValue(':search', '%' . $search . '%');
$stmt->execute();
$mediaFiles = $stmt->fetchAll();

$conn = null;

// Display search results
 foreach ($mediaFiles as $m) : ?>
  <article class="rj-gallery-card">
    <div class="card">
      <div class="card-header">
        <h3><?= $m['title'] ?></h3>
        <p>Catalogue nr : <?= $m['year'] ?> - <?= $m['fnr'] ?></p>
      </div>

      <div class="card-body">
        <?php if ($m['type'] == 'image') : ?>
          <div class="media-selection-container">
            <button class="img-btn" data-src="<?= $m['filepath'] ?>"><i class="fa-solid fa-expand"></i></button>
            <button class="info-btn" data-info="<?= trim($m['description']) ?>" data-title="<?= $m['title'] ?>"><i class="fa-solid fa-circle-info"></i></i></button>
            <button class="audio-btn" data-src="<?= urldecode($m['audio_url']) ?>"><i class="fa-solid fa-headphones"></i></button>
            <button class="video-btn" data-src="<?= urldecode($m['video_url']) ?>"><i class="fa-solid fa-video"></i></button>

          </div>

          <div class="image-container">
            <div class="img-wrapper">
              <img src="assets/img/bginverted.jpg" data-src="<?= $m['filepath'] ?>" alt="<?= $m['title'] ?>" class="lozad placeholder">
            </div>
          </div>

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

        <?php elseif ($m['type'] == 'video') : ?>
          <video src="<?= $m['filepath'] ?>" width="852" height="480" controls autoplay></video>
        <?php elseif ($m['type'] == 'audio') : ?>
          <audio src="<?= $m['filepath'] ?>" controls autoplay></audio>
        <?php endif; ?>


      </div>
    </div>
  </article>
        
<?php endforeach; ?>