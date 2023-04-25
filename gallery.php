<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();

// Retrieve all unique years from the media table
$stmt = $pdo->prepare('SELECT DISTINCT year FROM media ORDER BY year DESC');
$stmt->execute();
$years = $stmt->fetchAll(PDO::FETCH_ASSOC);

$year = isset($_GET['year']) ? $_GET['year'] : 'all';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'year9_0';
$type = isset($_GET['type']) ? $_GET['type'] : 'all';

// Retrieve the categories
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY title');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pagination settings
$media_per_page = 32;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Prepare query conditions
$conditions = ' WHERE m.approved = 1 ';
$conditions .= ($year != 'all') ? ' AND m.year = :year' : '';
$conditions .= ($type != 'all') ? ' AND m.type = :type' : '';
$conditions .= ($category != 'all') ? ' AND EXISTS (SELECT 1 FROM media_categories mc WHERE mc.media_id = m.id AND mc.category_id = :category)' : '';

// Prepare sort order
$sort_order = ' ORDER BY ';
switch ($sort_by) {
    case 'newest':
        $sort_order .= 'm.uploaded_date DESC';
        break;
    case 'oldest':
        $sort_order .= 'm.uploaded_date ASC';
        break;
    case 'a_to_z':
        $sort_order .= 'm.title ASC';
        break;
    case 'z_to_a':
        $sort_order .= 'm.title DESC';
        break;
    case 'year0_9':
        $sort_order .= 'm.year ASC';
        break;
    case 'year9_0':
        $sort_order .= 'm.year DESC';
        break;
    default:
        $sort_order .= 'm.uploaded_date ASC';
}

// Get total media count
$stmt = $pdo->prepare("SELECT COUNT(*) FROM media m" . $conditions);
if ($year != 'all') $stmt->bindValue(':year', $year);
if ($type != 'all') $stmt->bindValue(':type', $type);
if ($category != 'all') $stmt->bindValue(':category', $category);
$stmt->execute();
$total_media = $stmt->fetchColumn();

// Get media data
$offset = ($current_page - 1) * $media_per_page;
$stmt = $pdo->prepare("SELECT * FROM media m" . $conditions . $sort_order . " LIMIT :offset, :limit");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $media_per_page, PDO::PARAM_INT);
if ($year != 'all') $stmt->bindValue(':year', $year);
if ($type != 'all') $stmt->bindValue(':type', $type);
if ($category != 'all') $stmt->bindValue(':category', $category);
$stmt->execute();
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);

$last_page = ceil($total_media / $media_per_page);

// Set media properties below
$media_width = 300;
$media_height = 200;

$viewingAll = ($total_media <= $media_per_page);

function generatePaginationLink($label, $type, $current_page, $sort_by, $category) {
  $page = $type === 'prev' ? $current_page - 1 : $current_page + 1;
  $arrow_transform = $type === 'prev' ? '' : 'matrix(-1, 0, 0, -1, 1054.269043, 991.052002)';
  $content = $type === 'prev' ? '<span>' . $label . '</span>' : '';

  return '
    <a class="rj-' . $type . '" href="?page=' . $page . '&sort_by=' . $sort_by . '&category=' . $category . '">
      ' . $content . '
      <svg height="16" width="16" viewBox="0 0 1024 1024">
        <path d="M 874.69 495.527 C 874.69 484.23 865.522 475.061 854.224 475.061 L 249.45 475.061 L 437.534 286.978 C 445.526 278.986 445.526 266.03 437.534 258.038 C 433.533 254.048 428.294 252.042 423.064 252.042 C 417.825 252.042 412.586 254.037 408.585 258.038 L 185.576 481.048 C 181.738 484.885 179.579 490.094 179.579 495.517 C 179.579 500.951 181.738 506.149 185.576 509.987 L 408.595 733.016 C 416.587 741.008 429.552 741.008 437.544 733.016 C 445.536 725.014 445.536 712.059 437.544 704.067 L 249.471 515.993 L 854.224 515.993 C 865.522 515.993 874.69 506.835 874.69 495.527 Z" transform="' . $arrow_transform . '"></path>
      </svg>
      ' . ($type === 'next' ? '<span>' . $label . '</span>' : '') . '
    </a>';
}


?>


<?= template_header('Gallery') ?>
<?= template_header_other() ?>
<link rel="stylesheet" href="assets/css/gallery.css?v=5">

<?= template_nav() ?>

<main class="rj-black-bg-main">
	<div class="content home">
		<header>
			<h1>Artworks Gallery</h1>

			<p> Follow Pieter’s development as a visual artist over time and place. Use the “category" field to make selections and "sort" for sorting options. New categories are under construction. </p>


			<?php

			?>
			<?php if ($category != 'all') : ?>
				<h2>Viewing <?= $categories[array_search($category, array_column($categories, 'id'))]['title'] ?> (<?= $total_media ?> media files).</h2>
				<p><?= $categories[array_search($category, array_column($categories, 'id'))]['description'] ?> </p>
				<hr>
			<?php else : ?>
				<h2>Viewing all <?= $total_media ?> media files. (<?= $last_page ?> pages)</h2>
			<?php endif; ?>
		</header>
		<div class="con">


			<form action="" method="get">
			<label for="year">Year:</label>
<select id="year" name="year" onchange="this.form.submit()">
	<option value="all">All</option>
	<?php foreach ($years as $year) : ?>
		<option value="<?= $year['year'] ?>" <?= isset($_GET['year']) && $_GET['year'] == $year['year'] ? ' selected' : '' ?>><?= $year['year'] ?></option>
	<?php endforeach; ?>
</select>
<!-- </form>
<form action="" method="get"> -->

				<label for="category">Category:</label>
				<select id="category" name="category" onchange="this.form.submit()">
					<option value="all" <?= $sort_by == 'all' ? ' selected' : '' ?>>All</option>
					<?php foreach ($categories as $c) : ?>
						<option value="<?= $c['id'] ?>" <?= $category == $c['id'] ? ' selected' : '' ?>><?= $c['title'] ?></option>
					<?php endforeach; ?>
				</select>
				<label for="sort_by">Sort By:</label>
				<select id="sort_by" name="sort_by" onchange="this.form.submit()">
					<option value="year9_0" <?= $sort_by == 'year9_0' ? ' selected' : '' ?>>Year 10 > 1 </option>
					<option value="year0_9" <?= $sort_by == 'year0_9' ? ' selected' : '' ?>>Year 1 > 10 </option>
					<option value="newest" <?= $sort_by == 'newest' ? ' selected' : '' ?>>Newest</option>
					<option value="oldest" <?= $sort_by == 'oldest' ? ' selected' : '' ?>>Oldest</option>
					<option value="a_to_z" <?= $sort_by == 'a_to_z' ? ' selected' : '' ?>>A-Z</option>
					<option value="z_to_a" <?= $sort_by == 'z_to_a' ? ' selected' : '' ?>>Z-A</option>

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
								<p>Catalogue nr : <?= $m['year'] ?> - <?= $m['fnr'] ?></p>
							</div>

							<div class="card-body">
								<?php if ($m['type'] == 'image') : ?>
									<div class="media-selection-container">
										<button class="img-btn" data-src="<?= $m['filepath'] ?>"><i class="fa-solid fa-expand"></i></button>
										 <!-- chek if description is not equal to '...' -->
										<?php if (trim($m['description']) != '...') : ?>
											<button class="info-btn" data-info="<?= trim($m['description']) ?>" data-title="<?= $m['title'] ?>"><i class="fa-solid fa-circle-info"></i></i></button>
										<?php endif; ?>
										
										 <!-- check if audio url is not empty -->
										<?php if (!empty($m['audio_url'])) : ?>
											<button class="audio-btn" data-src="<?= urldecode($m['audio_url']) ?>"><i class="fa-solid fa-headphones"></i></button>
										<?php endif; ?>
										
										<!-- check if video url is not empty -->
										<?php if (!empty($m['video_url'])) : ?>
											<button class="video-btn" data-src="<?= urldecode($m['video_url']) ?>"><i class="fa-solid fa-video"></i></button>
										<?php endif; ?>
									

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
		</section>

<div class="pagination">
<?php if (!$viewingAll) {
  if ($current_page > 1) {
    echo generatePaginationLink('Prev', 'prev', $current_page, $sort_by, $category);
  }
  ?>
  <div class="rj-current-page">
    Page <?= $current_page ?> of <?= $last_page ?>
  </div>
  <?php if ($current_page * $media_per_page < $total_media) {
    echo generatePaginationLink('Next', 'next', $current_page, $sort_by, $category);
  }
} else {
  echo '<a href="#top">Back to top</a>';
} ?>
</div>



</main>


<script>
document.addEventListener('DOMContentLoaded', () => {

		const observer = lozad(".lozad", {
			loaded: function(el) {
				el.classList.add("fade");
				// console.log("loadewe");
			}
		});
		observer.observe();
	});



</script>

<?= template_footer() ?>