<?php
include 'functions.php';
$pdo = pdo_connect_mysql();

$page = 'Music';

// Check of er een specifieke album-ID is opgegeven
$album_id = isset($_GET['album']) ? (int)$_GET['album'] : null;

if ($album_id) {
  // Haal albumgegevens op
  $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ? AND media_type = 1');
  $stmt->execute([$album_id]);
  $album = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($album) {
    // Haal bijbehorende audio media op
    $stmt = $pdo->prepare('SELECT m.* FROM media m
            JOIN media_categories mc ON mc.media_id = m.id
            WHERE mc.category_id = ? AND m.type = "audio"
            ORDER BY m.fnr ASC');
    $stmt->execute([$album_id]);
    $tracks = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
} else {
  // Geen album gekozen, haal alle audio-albums op
  $stmt = $pdo->prepare('
    SELECT c.*, COUNT(m.id) AS track_count
    FROM categories c
    LEFT JOIN media_categories mc ON mc.category_id = c.id
    LEFT JOIN media m ON m.id = mc.media_id AND m.type = "audio"
    WHERE c.media_type = 1
    GROUP BY c.id
    ORDER BY c.id DESC
');
  $stmt->execute();
  $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?= template_header('Music') ?>
<?= template_header_other() ?>
<?= template_nav('music') ?>

<main class="rj-black-bg-main">
  <header class="rj-music">
    <h1>Music</h1>
    <img src="assets/img/musicbanner.jpg" alt="Music banner">
  </header>
  <?php if ($album_id && isset($album)): ?>
    <p>viewing <?= htmlspecialchars($album['title']) ?> album <a href="music.php"> Back to all albums</a>
    </p>

  <?php else: ?>
    <p>
      Explore our collection of music albums. Click on an album to listen. <br>
    </p>
  <?php endif; ?>





  <section class="rj-music-content">
    <?php if ($album_id && isset($album)): ?>
      <div class="rj-music-album">
        <h2><?= htmlspecialchars($album['title']) ?></h2>
        <div class="rj-music-album-wrap">
          <?php if (!empty($album['cat_image'])): ?>
            <img src="<?= htmlspecialchars($album['cat_image']) ?>" alt="<?= htmlspecialchars($album['title']) ?>">
          <?php endif; ?>
        </div>
        <p><?= nl2br(htmlspecialchars($album['description'])) ?></p>

        <?php if (!empty($tracks)): ?>
          <div class="rj-music-tracklist">
            <h4>
              Tracklist - <?= count($tracks) ?> track<?= count($tracks) > 1 ? 's' : '' ?>
            </h4>
            <ul class="rj-track-list">
              <?php foreach ($tracks as $track): ?>
                <li class="rj-track-item">
                  <div class="rj-music-btns">
                    <strong>
                      <?= htmlspecialchars($track['title']) ?>
                    </strong>
                    <div class="rj-flex-wrap">
                      <a href="view?id=<?= htmlspecialchars($track['id']) ?>&fromAlbum=<?= $album_id ?>" class="rj-music-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-info-circle">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                          <path d="M12 9h.01" />
                          <path d="M11 12h1v4h1" />
                        </svg>
                      </a>

                      <a href="<?= htmlspecialchars($track['filepath']) ?>" class="rj-music-btn" download="<?= htmlspecialchars($track['title']) ?>" title="Download track">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-download">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                          <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                          <path d="M7 11l5 5l5 -5" />
                          <path d="M12 4l0 12" />
                        </svg>
                      </a>
                    </div>
                  </div>
                  <br>
                  <audio controls preload="metadata" class="rj-audio-player">
                    <?php
                    $filepath = htmlspecialchars($track['filepath']);
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

                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php else: ?>
          pp>No tracks found in this album.</p>
        <?php endif; ?>

        <p style="margin-top: 2em;">
          <a href="music.php" class="rj-btn-light">← Back to all albums</a>
        </p>
      </div>

    <?php else: ?>
      <div class="rj-music-categories">
        <?php if (empty($categories)): ?>
          <p>No music available.</p>
        <?php else: ?>
          <?php foreach ($categories as $category): ?>
            <article class="rj-music-category">
              <h2><?= htmlspecialchars($category['title']) ?></h2>
              <p><strong><?= $category['track_count'] ?> <?= $category['track_count'] == 1 ? 'track' : 'tracks' ?></strong></p>

              <div class="rj-music-content-wrap">
                <?php if (!empty($category['cat_image'])): ?>
                  <img src="<?= htmlspecialchars($category['cat_image']) ?>" alt="<?= htmlspecialchars($category['title']) ?>">
                <?php endif; ?>

                <!-- max description of 500 characters -->
                <p><?= nl2br(htmlspecialchars(substr($category['description'], 0, 500))) ?><?php if (strlen($category['description']) > 500) echo '...'; ?></p>

              </div>
              <p>
                <a href="music.php?album=<?= htmlspecialchars($category['id']) ?>" class="rj-btn-light">View Music</a>
              </p>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </section>
</main>

<?= template_footer() ?>