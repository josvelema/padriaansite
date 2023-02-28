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


<?= template_nav() ?>

<style>
  .rj-container-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1rem;
}

.gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 0.5rem;
    box-shadow: 0 0 0.5rem rgba(0, 0, 0, 0.3);
}

.gallery-media-container {
    position: relative;
}

.gallery-img {
    display: block;
    width: 100%;
}

.gallery-img-fullscreen {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 3rem;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

.gallery-img-fullscreen:hover {
    opacity: 0.8;
}

.gallery-audio-player,
.gallery-video-player {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.gallery-audio-player audio,
.gallery-video-player video {
    display: block;
    max-width: 100%;
    max-height: 100%;
    margin: auto;
}

.gallery-audio-player-controls,
.gallery-video-player-controls {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 2rem;
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
}

.gallery-audio-player-progress-container,
.gallery-video-player-progress-container {
    width: 100%;
    height: 0.5rem;
    background-color: rgba(255, 255, 255, 0.3);
    margin: 0 0.5rem;
    position: relative;
}

.gallery-audio-player-progress-bar,
.gallery-video-player-progress-bar {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    background-color: white;
    width: 0;
}

.gallery-audio-player-time-elapsed,
.gallery-video-player-time-elapsed,
.gallery-audio-player-time-total,
.gallery-video-player-time-total {
    margin: 0 0.5rem;
}

.gallery-media-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    opacity: 0;
    transition: opacity 0.2s ease-in-out;
}

.gallery-item:hover .gallery-media-overlay {
    opacity: 1;
}

.gallery-info-container {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 1rem;
}

.gallery-title {
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.gallery-media-selection-container {
    display: flex;
    justify-content: center;
    margin-top: 0.5rem;
}

.gallery-media-selection-container button {
    background-color: white;
    color: black;
    border: none;
    padding: 0.5rem 1rem;
    margin: 0 0.5rem;
    border-radius: 0.25rem;
    cursor: pointer;
}

.gallery-media-selection-container button.active {
    background-color: black;
    color: white;
}

.gallery-media-popup {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 999;
}

.gallery-media-popup-content {
    position: relative;
    background-color: white;
    padding: 1rem;
    max-width: 90%;
    max-height: 90%;
    overflow: auto;
}

.gallery-media-popup-content img {
    max-width: 100%;
    max-height: 100%;
}

.gallery-media-popup-content .close-btn {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    font-size: 2rem;
    cursor: pointer;
    color: rgba(0, 0, 0, 0.5);
}

.gallery-media-popup-content .close-btn:hover {
    color: black;
}

.gallery-media-popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 998;
    cursor: pointer;
}

</style>

<main class="rj-black-bg-main">
	<div class="content home gallery">

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
    <section class="rj-gallery">
            <div class="rj-container-gallery">
                <?php foreach ($media as $m) : ?>
                    <div class="gallery-item">
                        <div class="gallery-media-container">
                            <?php if ($m['type'] === 'image') : ?>
                                <img src="<?= $m['filepath'] ?>" alt="<?= $m['title'] ?>" class="gallery-img">
                                <div class="gallery-img-fullscreen" data-src="<?= $m['filepath'] ?>">
                                    <i class="fas fa-search-plus"></i>
                                </div>
                            <?php elseif ($m['type'] === 'audio') : ?>
                                <audio src="<?= $m['audio_url'] ?>"></audio>
                                <div class="gallery-audio-player">
                                    <div class="gallery-audio-player-controls">
                                        <button class="gallery-audio-player-play-button">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <div class="gallery-audio-player-progress-container">
                                            <div class="gallery-audio-player-progress-bar"></div>
                                        </div>
                                        <span class="gallery-audio-player-time-elapsed">0:00</span>
                                        <span class="gallery-audio-player-time-total"></span>
                                    </div>
                                </div>
                            <?php elseif ($m['type'] === 'video') : ?>
                                <video src="<?= $m['video_url'] ?>" poster="<?= $m['thumbnail_url'] ?>"></video>
                                <div class="gallery-video-player">
                                    <div class="gallery-video-player-controls">
                                        <button class="gallery-video-player-play-button">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <div class="gallery-video-player-progress-container">
                                            <div class="gallery-video-player-progress-bar"></div>
                                        </div>
                                        <span class="gallery-video-player-time-elapsed">0:00</span>
                                        <span class="gallery-video-player-time-total"></span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="gallery-media-overlay"></div>

                        </div>

                        <div class="gallery-info-container">
                            <div class="gallery-title"><?= $m['title'] ?></div>
                            <div class="gallery-media-selection-container">
                                <button class="gallery-media-selection-audio">Audio</button>
                                <button class="gallery-media-selection-video">Video</button>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

<div class="media-popup"></div>

<script>
  // Select all the media selection buttons
const mediaSelectionButtons = document.querySelectorAll('.media-selection-btn');

// Select all the fullscreen buttons
const fullscreenButtons = document.querySelectorAll('.gallery-media-fullscreen');

// Add a click event listener to each media selection button
mediaSelectionButtons.forEach((button) => {
  button.addEventListener('click', () => {
    // Remove the active class from all buttons
    mediaSelectionButtons.forEach((btn) => {
      btn.classList.remove('active');
    });

    // Add the active class to the clicked button
    button.classList.add('active');

    // Select the media container for the clicked button
    const mediaContainer = button.closest('.rj-gallery-item').querySelector('.gallery-media-container');

    // Hide all media containers
    mediaContainer.parentElement.querySelectorAll('.gallery-media-container').forEach((container) => {
      container.style.display = 'none';
    });

    // Show the media container for the clicked button
    mediaContainer.style.display = 'block';
  });
});

// Add a click event listener to each fullscreen button
fullscreenButtons.forEach((button) => {
  button.addEventListener('click', () => {
    // Select the image and video sources
    const imageSource = button.closest('.rj-gallery-item').querySelector('.rj-gallery-item-image').getAttribute('src');
    const videoSource = button.closest('.rj-gallery-item').querySelector('video source').getAttribute('src');
    const audioSource = button.closest('.rj-gallery-item').querySelector('audio source').getAttribute('src');

    // Create the popup container and content
    const popup = document.createElement('div');
    popup.classList.add('gallery-media-popup');

    const content = document.createElement('div');
    content.classList.add('gallery-media-popup-content');

    const closeBtn = document.createElement('i');
    closeBtn.classList.add('fas', 'fa-times', 'close-btn');
    content.appendChild(closeBtn);

    // Create the media element and append it to the content
    let mediaElement;
    if (button.closest('.rj-gallery-item').querySelector('.media-selection-btn.active').getAttribute('data-media-type') === 'image') {
      mediaElement = document.createElement('img');
      mediaElement.setAttribute('src', imageSource);
    } else if (button.closest('.rj-gallery-item').querySelector('.media-selection-btn.active').getAttribute('data-media-type') === 'video') {
      mediaElement = document.createElement('video');
      const source = document.createElement('source');
      source.setAttribute('src', videoSource);
      mediaElement.appendChild(source);
      mediaElement.setAttribute('controls', 'controls');
    } else if (button.closest('.rj-gallery-item').querySelector('.media-selection-btn.active').getAttribute('data-media-type') === 'audio') {
      mediaElement = document.createElement('audio');
      const source = document.createElement('source');
      source.setAttribute('src', audioSource);
      mediaElement.appendChild(source);
      mediaElement.setAttribute('controls', 'controls');
    }

    content.appendChild(mediaElement);
    popup.appendChild(content);

    // Create the overlay and append it to the body
    const overlay = document.createElement('div');
    overlay.classList.add('gallery-media-popup-overlay');
    document.body.appendChild(overlay);

    // Append the popup to the body
    document.body.appendChild(popup);

    // Add a click event listener to the close button
    closeBtn.addEventListener('click', () => {
      popup.remove();
      overlay.remove();
    });

    // Add a click event listener to the overlay
    overlay.addEventListener('click', () => {
      popup.remove();
      overlay.remove();
    });
  });
});

</script>

<?= template_footer() ?>