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
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'year';
$sort_by_sql = 'm.uploaded_date DESC';
$sort_by_sql = $sort_by == 'newest' ? 'm.uploaded_date DESC' : $sort_by_sql;
$sort_by_sql = $sort_by == 'oldest' ? 'm.uploaded_date ASC' : $sort_by_sql;
$sort_by_sql = $sort_by == 'a_to_z' ? 'm.title DESC' : $sort_by_sql;
$sort_by_sql = $sort_by == 'z_to_a' ? 'm.title ASC' : $sort_by_sql;
$sort_by_sql = $sort_by == 'year' ? 'm.year DESC' : $sort_by_sql;
// Get media by the type (ignore if set to all)
$type = isset($_GET['type']) ? $_GET['type'] : 'all';
$type_sql = $type != 'all' ? 'AND m.type = :type' : '';
//! Limit the amount of media on each page
$media_per_page = 6;
// The current pagination page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

if (isset($_POST['viewAll'])) {
	// MySQL query that selects all the media
	$viewingAll = true;
	$stmt = $pdo->prepare('SELECT * FROM media m ' . $category_sql . ' WHERE m.approved = 1 ' . $type_sql . ' ORDER BY ' . $sort_by_sql . ', fnr  ');

	// Check if the category is not set to all
	if ($category != 'all') $stmt->bindValue(':category', $category);
	// Execute the SQL
	$stmt->execute();
} else {
	$viewingAll = false;

	// MySQL query that selects all the media
	$stmt = $pdo->prepare('SELECT * FROM media m ' . $category_sql . ' WHERE m.approved = 1 ' . $type_sql . ' ORDER BY ' . $sort_by_sql . ', fnr LIMIT :page,:media_per_page');
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
}




$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of media
$stmt = $pdo->prepare('SELECT COUNT(*) FROM media m ' . $category_sql . ' WHERE m.approved = 1 ' . $type_sql);
if ($type != 'all') $stmt->bindValue(':type', $type);
if ($category != 'all') $stmt->bindValue(':category', $category);
$stmt->execute();
$total_media = $stmt->fetchColumn();
$last_page = ceil($total_media / $media_per_page);

// Set media properties below
$media_width = 300;
$media_height = 200;
?>

<?= template_header('Gallery') ?>
<?= template_header_other() ?>
<?= template_nav() ?>

<style>

.gallery-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 360px));
  grid-gap: 0.5em;
  place-content: center;
  place-items: center;
  
  max-width: 1200px;
}
/* Responsive styles */
@media (max-width: 768px) {
  .gallery-container {
    grid-template-columns: repeat(auto-fit, minmax(260px, 360px));
  }
}

/* @media (max-width: 576px) {
  .gallery-container {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    padding: 10px;
  } */



  .rj-gallery-card {
    width: 100%;
    max-width: 360px;
    margin: 0 auto;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 1px 2px 4px 0px #0008;
  }

  .rj-gallery-card .card-header {
    background-color: #333;
    color: #fff;
    padding: 16px;
    text-align: center;
  }

  .rj-gallery-card .card-body {
    padding: 16px;
  }

  .rj-gallery-card .image-container {
    position: relative;
    /* height: 0; */
    padding-bottom: 56.25%;
    overflow: hidden;
    border-radius: 10px;
  }

  .rj-gallery-card .image-container img,
  .rj-gallery-card  audio,
  .rj-gallery-card  video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
  max-width: 330px;
  max-height: 100%;
  cursor: pointer;

  }

  .loaded {
  opacity: 1;
  transition: opacity 0.5s ease-in-out 0.3s;
}


  .image-container .large-btn {
    position: absolute;
    /* inset: 0; */
    color: #ccdd00;
    text-shadow: 1px 1px 3px #0008, 0 -1px 3px #0008;
    left: 8px;
    bottom: 12px;
    /* background: black; */
    padding: 2px;
    /* border-radius: 100vw; */
}



  .rj-gallery-card .media-selection-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 10px 0;
  }

  .rj-gallery-card .media-selection-container button {
    padding: 8px;
    background-color: #fff;
    border-radius: 8px;
    margin: 0 10px;
    cursor: pointer;
  }

/* Style for modal container */
.audio-modal-container,
.video-modal-container,
.image-modal-container,
.info-modal-container {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.8);
}

.audio-modal-content,
  .video-modal-content,
  .image-modal-content,
  .info-modal-content {
    /* position: relative;
    margin: 10% auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    max-width: 90%;
    max-height: 90%;
    text-align: center; */
    display: grid;
    place-items: center;
    height: 100%;
  }

  .image-modal-content img {
    width: 98%;
    margin: 0 auto;
    height: 98%;
    object-fit: contain;
    box-shadow: 1px 2px 4px 0px #0008;
}

  .audio-modal-audio {
  max-width: 90%;
  max-height: 90%;
}

.video-modal-video {
  max-width: 90%;
  max-height: 90%;
}

  .audio-modal-close,
  .video-modal-close,
  .image-modal-close,
  .info-modal-close {
    position: absolute;
    top: 0;
    right: 0;
    padding: 10px;
    background-color: #fff;
    border: none;
    cursor: pointer;
  }
  
  
  /* CSS style for the temporary image */
/* .loading-image {
  background-image: url('assets/svg/pieterBg.svg');
  background-repeat: no-repeat;
  background-size: contain;

} */


img {
  transition: opacity 0.5s ease-in-out;
}

</style>

<main class="rj-black-bg-main">
	<div class="content home">

		<h2>Gallery</h2>
		<p> Follow Pieter’s development as a visual artist over time and place. Use the "view all’ button to get access to the whole data base and “category" field to make selections. New categories are under construction. </p>
		<hr>

		<?php

		?>
		<?php if ($category != 'all') : ?>
			<p>Viewing <?= $categories[array_search($category, array_column($categories, 'id'))]['title'] ?> (<?= $total_media ?> media files).</p>
			<p><?= $categories[array_search($category, array_column($categories, 'id'))]['description'] ?> </p>
		<?php else : ?>
			<p>Viewing all <?= $total_media ?> media files. (<?= $last_page ?> pages)</p>
		<?php endif; ?>

		<div class="con">
			<form action="" method="post"><input type="submit" value="View All" name="viewAll"></form>
			<form action="" method="get">
				<label for="category">Category:</label>
				<select id="category" name="category" onchange="this.form.submit()">
					<option value="all" <?= $sort_by == 'all' ? ' selected' : '' ?>>All</option>
					<?php foreach ($categories as $c) : ?>
						<option value="<?= $c['id'] ?>" <?= $category == $c['id'] ? ' selected' : '' ?>><?= $c['title'] ?></option>
					<?php endforeach; ?>
				</select>
				<label for="sort_by">Sort By:</label>
				<select id="sort_by" name="sort_by" onchange="this.form.submit()">
					<option value="newest" <?= $sort_by == 'newest' ? ' selected' : '' ?>>Newest</option>
					<option value="oldest" <?= $sort_by == 'oldest' ? ' selected' : '' ?>>Oldest</option>
					<option value="a_to_z" <?= $sort_by == 'a_to_z' ? ' selected' : '' ?>>A-Z</option>
					<option value="z_to_a" <?= $sort_by == 'z_to_a' ? ' selected' : '' ?>>Z-A</option>
					<option value="year" <?= $sort_by == 'year' ? ' selected' : '' ?>>year</option>

				</select>
				<!-- <label for="type">Type:</label> -->
				<select id="type" name="type" onchange="this.form.submit()" style="display: none;">
					<option value="all" <?= $type == 'all' ? ' selected' : '' ?>>All</option>
					<option value="audio" <?= $type == 'audio' ? ' selected' : '' ?>>Audio</option>
					<option value="image" <?= $type == 'image' ? ' selected' : '' ?>>Image</option>
					<option value="video" <?= $type == 'video' ? ' selected' : '' ?>>Video</option>
				</select>
			</form>
		</div>
    <section>
            <div class="gallery-container">

            <?php foreach ($media as $m) : ?>
    <article class="rj-gallery-card">
      <div class="card">
        <div class="card-header">
          <h3><?= $m['title'] ?></h3>
          <p>Catalogue number: <?= $m['year'] ?> - <?= $m['fnr'] ?></p>
        </div>

        <div class="card-body">
          <?php if ($m['type'] == 'image') : ?>
            <div class="media-selection-container">
              <button class="audio-btn" data-src="media/multimedia/audio/audio-1034-2016-26.mp3">Audio <i class="fa-solid fa-headphones"></i></button>
              <button class="video-btn" data-src="<?= urldecode($m['video_url']) ?>">Video <i class="fa-solid fa-video"></i></button>
              <button class="info-btn" data-info="<?= trim($m['description']) ?>">Info <i class="fa-solid fa-video"></i></button>
            </div>

            <div class="image-container">
              <img data-src="<?= $m['filepath'] ?>" alt="<?= $m['title'] ?>" data-placeholder="assets\img\bginverted.jpg">
              <div class="large-btn">
                <i class="fas fa-search-plus"></i>
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

          <pre class="description">
            <?= $m['description'] ?>
          </pre>

        </div>
      </div>
    </article>
  <?php endforeach; ?>

    <script>
// Get modal elements
const audioModalClose = document.querySelectorAll('.audio-modal-close');
const videoModalClose = document.querySelectorAll('.video-modal-close');
const imageModalClose = document.querySelectorAll('.image-modal-close');
const infoModalClose = document.querySelectorAll('.info-modal-close');

// Get all media selection buttons
const audioBtns = document.querySelectorAll('.audio-btn');
const videoBtns = document.querySelectorAll('.video-btn');
const infoBtns = document.querySelectorAll('.info-btn');

// Get all modal containers
const audioModalContainers = document.querySelectorAll('.audio-modal-container');
const videoModalContainers = document.querySelectorAll('.video-modal-container');
const imageModalContainers = document.querySelectorAll('.image-modal-container');
const infoModalContainers = document.querySelectorAll('.info-modal-container');

// Get all image containers
const imageContainers = document.querySelectorAll('.image-container');

// Intersection observer for lazy loading
const options = {
  rootMargin: '50px 0px',
  threshold: 0.01
};

// Add event listeners to each image container
imageContainers.forEach((container, index) => {
  // Get the media selection buttons and modal containers that correspond to this card
  const audioBtn = audioBtns[index];
  const videoBtn = videoBtns[index];
  const infoBtn = infoBtns[index];
  const audioModalContainer = audioModalContainers[index];
  const videoModalContainer = videoModalContainers[index];
  const imageModalContainer = imageModalContainers[index];
  const infoModalContainer = infoModalContainers[index];

  const img = container.querySelector('img');

  // Get the image source from the data-src attribute
  const imgSrc = img.getAttribute('data-src');

  // Set the image source to the data-src attribute and add a load event listener
  img.setAttribute('src', imgSrc);
  img.addEventListener('load', () => {
    // Remove the data-src attribute to prevent lazy loading again
    img.removeAttribute('data-src');
  });

  container.addEventListener('click', () => {
    // Get the image source from the clicked container
    const imageSource = img.getAttribute('src');

    // Set the modal image source and display the modal
    imageModalContainer.querySelector('img').setAttribute('src', imageSource);
    imageModalContainer.style.display = 'block';
  });

  // Add click event listener to audio button
  audioBtn.addEventListener('click', () => {
    // Get the audio source from the button data attribute
    const audioSource = audioBtn.getAttribute('data-src');

    // Set the audio source and display the audio modal
    audioModalContainer.querySelector('audio').setAttribute('src', audioSource);
    audioModalContainer.style.display = 'block';
  });

  // Add click event listener to video button
  videoBtn.addEventListener('click', () => {
    // Get the video source from the button data attribute
    const videoSource = videoBtn.getAttribute('data-src');

    // Set the video source and display the video modal
    videoModalContainer.querySelector('video').setAttribute('src', videoSource);
    videoModalContainer.style.display = 'block';
  });

  // Add click event listener to info button
  infoBtn.addEventListener('click', () => {
    // Get the video source from the button data attribute
    const infoSource = infoBtn.getAttribute('data-info');

    infoModalContainer.querySelector('pre').innerText = infoSource;
    infoModalContainer.style.display = 'block';
  });
});


// Add click event listener to the audio modal close buttons
audioModalClose.forEach((closeBtn) => {
  closeBtn.addEventListener('click', () => {
    // Stop the audio and hide the audio modal
    const audioModalAudio = closeBtn.parentNode.querySelector('audio');
    audioModalAudio.pause();
    audioModalAudio.currentTime = 0;
    closeBtn.parentNode.parentNode.style.display = 'none';
  });
});

// Add click event listener to the video modal close buttons
videoModalClose.forEach((closeBtn) => {
  closeBtn.addEventListener('click', () => {
    // Stop the video and hide the video modal
    const videoModalVideo = closeBtn.parentNode.querySelector('video');
    videoModalVideo.pause();
    videoModalVideo.currentTime = 0;
    closeBtn.parentNode.parentNode.style.display = 'none';
  });
});

// Add click event listener to the image modal close buttons
imageModalClose.forEach((closeBtn) => {
  closeBtn.addEventListener('click', () => {
    // Hide the image modal
    closeBtn.parentNode.parentNode.style.display = 'none';
  });
});

// Add click event listener to the info modal close buttons
infoModalClose.forEach((closeBtn) => {
  closeBtn.addEventListener('click', () => {
    // Hide the info modal
    closeBtn.parentNode.parentNode.style.display = 'none';
  });
});




    </script>
<script src="assets/js/Lazyload.js" type="module"></script>

<?= template_footer() ?>