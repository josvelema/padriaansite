<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();

// Retrieve all unique years from the media table
$stmt = $pdo->prepare('SELECT DISTINCT year FROM media ORDER BY year DESC');
$stmt->execute();
$years = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $year = isset($_GET['year']) ? $_GET['year'] : 'all';
// $category = isset($_GET['category']) ? $_GET['category'] : 'all';
// $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'year9_0';
// $type = isset($_GET['type']) ? $_GET['type'] : 'all';

$year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT) ?? 0;
$category = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT) ?? 0;
$term = trim(filter_input(INPUT_GET, 'term')) ?? '';

$sort_by = filter_input(INPUT_GET, 'sort_by') ?? 'year9_0';
$type = filter_input(INPUT_GET, 'type') ?? 'all';

$show = filter_input(INPUT_GET, 'show', FILTER_VALIDATE_INT) ?? 25;
$from = filter_input(INPUT_GET, 'from', FILTER_VALIDATE_INT) ?? 0;


// Retrieve the categories
$stmt = $pdo->prepare('SELECT * FROM categories WHERE is_private = 0 AND media_type = 0 ORDER BY title');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


$params = [];

// Prepare sort order
$sort_order = '';
switch ($sort_by) {
	case 'newest':
		$sort_order = 'uploaded_date DESC';
		break;
	case 'oldest':
		$sort_order = 'uploaded_date ASC';
		break;
	case 'a_to_z':
		$sort_order = 'title ASC';
		break;
	case 'z_to_a':
		$sort_order = 'title DESC';
		break;
	case 'year0_9':
		$sort_order = 'year ASC';
		break;
	case 'year9_0':
	default:
		$sort_order = 'year DESC';
		break;
}
// sort orrder without 





if ($category > 0) {
	$conditions = ' WHERE m.approved = 1 ';

	$params = [
		'category' => $category,
		'term1' => '%' . $term . '%',
		'term2' => '%' . $term . '%'
	];

	if ($year > 0) {
		$params['year'] = $year;
		$conditions .= ' AND mc.category_id = :category AND m.year = :year AND (m.title LIKE :term1 OR m.description LIKE :term2)';
	} else {
		$conditions .= ' AND mc.category_id = :category AND (m.title LIKE :term1 OR m.description LIKE :term2)';
	}
	// count media id in catergories query for pagination
	$stmt = $pdo->prepare("SELECT COUNT(m.id) FROM media m JOIN media_categories mc ON mc.media_id = m.id" . $conditions);
	// inspectAndDie($stmt);
	$stmt->execute($params);
	$count = $stmt->fetchColumn();
	if ($count > 0) {
		$params['show'] = (int)$show;
		$params['from'] = (int)$from;
		$stmt = $pdo->prepare("SELECT m.* FROM media m JOIN media_categories mc ON mc.media_id = m.id " . $conditions . ' ORDER BY m.' . $sort_order . " LIMIT :show OFFSET :from");

		foreach ($params as $key => &$value) {
			if ($key == 'show' || $key == 'from') {
				$stmt->bindParam($key, $value, PDO::PARAM_INT);
			} else {
				$stmt->bindParam($key, $value);
			}
		}
		$stmt->execute();
		$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
} else {
	$conditions = ' WHERE approved = 1';

	if ($year > 0) {
		$params = [
			'year' => $year,
			'term1' => '%' . $term . '%',
			'term2' => '%' . $term . '%'

		];
		$conditions .= ' AND year = :year AND (title LIKE :term1 OR description LIKE :term2)';
	} else {
		$params = [
			'term1' => '%' . $term . '%',
			'term2' => '%' . $term . '%'
		];
		$conditions .= ' AND (title LIKE :term1 OR description LIKE :term2)';
	}
	// count media id in catergories query for pagination
	// $stmt = $pdo->prepare("SELECT COUNT(id) FROM media WHERE type='image' . $conditions ");
	$stmt = $pdo->prepare("SELECT COUNT(id) FROM media" . $conditions);
	// inspectAndDie($stmt);
	$stmt->execute($params);
	$count = $stmt->fetchColumn();
	if ($count > 0) {
		$params['show'] = (int)$show;
		$params['from'] = (int)$from;
		$stmt = $pdo->prepare("SELECT * FROM media " . $conditions . ' ORDER BY ' . $sort_order . " LIMIT :show OFFSET :from");
		// inspectAndDie($stmt);
		foreach ($params as $key => &$value) {
			if ($key == 'show' || $key == 'from') {
				$stmt->bindParam($key, $value, PDO::PARAM_INT);
			} else {
				$stmt->bindParam($key, $value);
			}
		}
		$stmt->execute();
		$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
}

if ($count > $show) {
	$total_pages = ceil($count / $show);
	$current_page = ceil($from / $show) + 1;
} else {
	$total_pages = 1;
	$current_page = 1;
}

// Get total media in db count
// $stmt = $pdo->prepare("SELECT COUNT(*) FROM media m" . $conditions);
// $stmt->execute($params);
// $count = $stmt->fetchColumn();
?>


<?= template_header('Gallery') ?>
<?= template_header_other() ?>
<link rel="stylesheet" href="assets/css/gallery.css?v=55">

<?= template_nav('gallery') ?>

<main class="rj-black-bg-main">
	<div class="content home">
		<header class="gallery-header">
			<div style="width: 100%;">
				<h1>Artworks Gallery</h1>
				<p> Follow Pieter’s development as a visual artist over time and place.
					You can view all the artworks in the gallery or filter them by year.
				</p>
			</div>
			<form action="" method="GET" class="gallery-form">

				<label for="category">Category:
					<select id="category" name="category" onchange="this.form.submit();">
						<option value=0 <?= $sort_by == 'all' ? ' selected' : '' ?>>All</option>
						<?php foreach ($categories as $c) : ?>
							<option value="<?= $c['id'] ?>" <?= $category == $c['id'] ? ' selected' : '' ?>><?= $c['title'] ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<div class="gallery-search">
					<input type="text" name="term" id="search" value="<?= htmlspecialchars($term) ?>" placeholder="enter search term" class="form-control">
					<!-- <input type="submit" value="" class="btn btn-primary"> -->
					<button type="submit"><i class="fa-solid fa-search"></i></button>

				</div>
				<div style="display: flex;gap: 1rem;">
					<label for="year">Year:
						<select id="year" name="year" onchange="this.form.submit();">
							<option value=0>All</option>
							<?php foreach ($years as $yearSingle) : ?>
								<option value="<?= $yearSingle['year'] ?>" <?= $year == $yearSingle['year'] ? ' selected' : '' ?>><?= $yearSingle['year'] ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<label for="sort_by">Sort By:
						<select id="sort_by" name="sort_by" onchange="this.form.submit()">
							<option value="year9_0" <?= $sort_by == 'year9_0' ? ' selected' : '' ?>>Year 10 > 1 </option>
							<option value="year0_9" <?= $sort_by == 'year0_9' ? ' selected' : '' ?>>Year 1 > 10 </option>
							<option value="newest" <?= $sort_by == 'newest' ? ' selected' : '' ?>>Newest</option>
							<option value="oldest" <?= $sort_by == 'oldest' ? ' selected' : '' ?>>Oldest</option>
							<option value="a_to_z" <?= $sort_by == 'a_to_z' ? ' selected' : '' ?>>A-Z</option>
							<option value="z_to_a" <?= $sort_by == 'z_to_a' ? ' selected' : '' ?>>Z-A</option>
						</select>
					</label>
					<!-- <label for="type">Type:</label> -->
					<select id="type" name="type" onchange="this.form.submit()" style="display: none;">
						<option value="all" <?= $type == 'all' ? ' selected' : '' ?>>All</option>
						<option value="audio" <?= $type == 'audio' ? ' selected' : '' ?>>Audio</option>
						<option value="image" <?= $type == 'image' ? ' selected' : '' ?>>Image</option>
						<option value="video" <?= $type == 'video' ? ' selected' : '' ?>>Video</option>
					</select>
				</div>

			</form>
			<div class="gallery-description">
				<?php if ($category != 0) : ?>
					<h3>Viewing <?= $categories[array_search($category, array_column($categories, 'id'))]['title'] ?> (<?= $count ?> results).</h3>
					<p><?= $categories[array_search($category, array_column($categories, 'id'))]['description'] ?> </p>
				<?php elseif (isset($_GET['year']) && $_GET['year'] != 0) : ?>
					<h3>Viewing the year <?= $_GET['year'] ?> (<?= $count ?> results).</h3>
				<?php else : ?>
					<h3>Viewing all <?= $count ?> results. (<?= $total_pages ?> pages)</h3>
				<?php endif; ?>
			</div>



		</header>

		<section>
			<div class="gallery-container">
				<?php if ($count > 0) : ?>
					<?php foreach ($media as $m) : ?>
						<!-- check if there is content in art_type , art_material and art_ dimensions and if so put in front of description -->
						<?php if ($m['type'] == 'image') : ?>
							<?php if (!empty($m['art_type']) || !empty($m['art_material']) || !empty($m['art_dimensions'])) : ?>
								<?php $m['description'] = (!empty($m['art_type']) ? $m['art_type'] . ', ' : '') . (!empty($m['art_material']) ? $m['art_material'] . ', ' : '') . (!empty($m['art_dimensions']) ? $m['art_dimensions'] . ', ' : '') . $m['description']; ?>
							<?php endif; ?>
							<article class="rj-gallery-card">
								<div class="card">
									<?php
									$is_for_sale = false;
									if ($m['art_status'] == 'available' || $m['art_status'] == 'for sale') :
										$is_for_sale = true;
									?>
										<div class="card-for-sale" data-media-id=<?= $m['id'] ?>>For sale</div>
									<?php endif; ?>
									<button class="card-overlay-content-button" title="More information">+</button>
									<div class="card-overlay-content">
										<div class="card-header">
											<h3><?= $m['title'] ?></h3>
											<p>Catalogue nr : <?= $m['year'] ?> - <?= $m['fnr'] ?></p>
										</div>
										<div class="media-selection-container">
											<button class="img-btn" data-src="<?= $m['filepath'] ?>" title="expand view"><i class="fa-solid fa-expand"></i></button>
											<!-- chek if description is not equal to '...' -->
											<?php if (!empty($m['description']) && trim($m['description']) != '...') : ?>
												<button class="info-btn" data-info="<?= trim($m['description']) ?>" data-title="<?= $m['title'] ?>" title="information"><i class="fa-solid fa-circle-info"></i></i></button>
											<?php endif; ?>
											<!-- check if audio url is not empty -->
											<?php if (!empty($m['audio_url'])) : ?>
												<button class="audio-btn" data-src="<?= urldecode($m['audio_url']) ?>" title="audio commentary"><i class="fa-solid fa-headphones"></i></button>
											<?php endif; ?>
											<!-- check if video url is not empty -->
											<?php if (!empty($m['video_url'])) : ?>
												<button class="video-btn" data-src="<?= urldecode($m['video_url']) ?>" title="video commentary"><i class="fa-solid fa-video"></i></button>
											<?php endif; ?>
											<?php if ($is_for_sale) : ?>
												<button class="for-sale-btn" data-media-id="<?= $m['id'] ?>" title="buy this artwork"><i class="fa-solid fa-euro"></i></button>
											<?php endif; ?>
										</div>
									</div>
									<div class="card-body">
										<?php if ($m['type'] == 'image') : ?>
											<div class="image-container">
												<div class="img-wrapper">
													<img src="<?= $m['thumbnail'] ?>" data-src="<?= $m['filepath'] ?>" alt="<?= $m['title'] ?>" class="lozad placeholder">

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
														<?php if ($is_for_sale) : ?>
															<button class="for-sale-btn" data-media-id="<?= $m['id'] ?>" title="buy this artwork"><i class="fa-solid fa-euro"></i> for sale</button>
														<?php endif; ?>
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
						<?php endif; ?>

					<?php endforeach; ?>
				<?php else : ?>
					<p>No media found in that year and/or category.
						You can view the year <a href="gallery?year=<?= $_GET['year'] ?>"><?= $_GET['year'] ?> </a> or the category <a href="gallery?category=<?= $_GET['category'] ?>"><?= $categories[array_search($category, array_column($categories, 'id'))]['title'] ?></a> or <a href="gallery">all media</a>.
					</p>
				<?php endif; ?>
			</div>
			<?php if ($total_pages > 1) : ?>
				<nav aria-label="Pagination">
					<ul class="rj-pagination">
						<li>
							<a class="rj-prev" href="#" id="rj-page-item-prev">
								<svg height="16" width="16" viewBox="0 0 1024 1024" fill="currentColor">
									<path d="M 874.69 495.527 C 874.69 484.23 865.522 475.061 854.224 475.061 L 249.45 475.061 L 437.534 286.978 C 445.526 278.986 445.526 266.03 437.534 258.038 C 433.533 254.048 428.294 252.042 423.064 252.042 C 417.825 252.042 412.586 254.037 408.585 258.038 L 185.576 481.048 C 181.738 484.885 179.579 490.094 179.579 495.517 C 179.579 500.951 181.738 506.149 185.576 509.987 L 408.595 733.016 C 416.587 741.008 429.552 741.008 437.544 733.016 C 445.536 725.014 445.536 712.059 437.544 704.067 L 249.471 515.993 L 854.224 515.993 C 865.522 515.993 874.69 506.835 874.69 495.527 Z"></path>
								</svg>
							</a>
						</li>

						<?php
						// Calculate the start and end page numbers
						$start = max(1, $current_page - 2);
						$end = min($total_pages, $start + 5);
						if ($total_pages > 6 && $current_page > 3) : ?>
							<li class="rj-page-item">
								<a class="rj-page-link" href="gallery?year=<?= $year ?>&category=<?= $category ?>&sort_by=<?= $sort_by ?>&type=<?= $type ?>&show=<?= $show ?>&from=<?= ($start - 2) * $show ?>&term=<?= $term ?>">...</a>
							</li>
						<?php endif;
						for ($i = $start; $i <= $end; $i++) : ?>
							<li class="rj-page-item <?= $i == $current_page ? 'active' : '' ?>">
								<a href="gallery?year=<?= $year ?>&category=<?= $category ?>&sort_by=<?= $sort_by ?>&type=<?= $type ?>&show=<?= $show ?>&from=<?= ($i - 1) * $show ?>&term=<?= $term ?>" class="rj-page-link"><?= $i ?></a>
							</li>
						<?php endfor;
						if ($total_pages > $current_page + 5) : ?>
							<li class="rj-page-item">
								<a class="rj-page-link" href="gallery?year=<?= $year ?>&category=<?= $category ?>&sort_by=<?= $sort_by ?>&type=<?= $type ?>&show=<?= $show ?>&from=<?= ($current_page + 3) * $show ?>&term=<?= $term ?>">...</a>
							</li>
						<?php endif; ?>
						<li>
							<a class="rj-next" href="#" id="rj-page-item-next">
								<svg height="16" width="16" viewBox="0 0 1024 1024" fill="currentColor">
									<path d="M 874.69 495.527 C 874.69 484.23 865.522 475.061 854.224 475.061 L 249.45 475.061 L 437.534 286.978 C 445.526 278.986 445.526 266.03 437.534 258.038 C 433.533 254.048 428.294 252.042 423.064 252.042 C 417.825 252.042 412.586 254.037 408.585 258.038 L 185.576 481.048 C 181.738 484.885 179.579 490.094 179.579 495.517 C 179.579 500.951 181.738 506.149 185.576 509.987 L 408.595 733.016 C 416.587 741.008 429.552 741.008 437.544 733.016 C 445.536 725.014 445.536 712.059 437.544 704.067 L 249.471 515.993 L 854.224 515.993 C 865.522 515.993 874.69 506.835 874.69 495.527 Z" transform="matrix(-1, 0, 0, -1, 1054.269043, 991.052002)"></path>
								</svg>
							</a>
						</li>
					</ul>
				</nav>
			<?php endif; ?>
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

	// gallery - for sale buttons
	const forSaleButtons = document.querySelectorAll('.card-for-sale');
	forSaleButtons.forEach(button => {
		button.addEventListener('click', () => {
			const mediaId = button.getAttribute('data-media-id');
			// open new window and pass media id
			window.open('view.php?id=' + mediaId, '_blank');

		});
	});

	// for sale buttons
	const forSaleBtns = document.querySelectorAll('.for-sale-btn');
	forSaleBtns.forEach(button => {
		button.addEventListener('click', () => {
			const mediaId = button.getAttribute('data-media-id');
			// open new window and pass media id
			window.open('view.php?id=' + mediaId, '_blank');
		});
	});





	function resetSelects(id) {
		if (id === 'year') {
			document.getElementById('category').selectedIndex = 0;
		} else if (id === 'category') {
			document.getElementById('year').selectedIndex = 0;
		}
	}

	// control pagination
	const pageItemPrev = document.getElementById('rj-page-item-prev');
	const pageItemNext = document.getElementById('rj-page-item-next');
	const pageItems = document.querySelectorAll('.pagination .rj-page-item');

	if (pageItemPrev) {
		pageItemPrev.addEventListener('click', function(e) {
			e.preventDefault();
			let prev = document.querySelector('.rj-pagination .active').previousElementSibling;
			if (prev) {
				prev.querySelector('a').click();
			}
		});
	}

	if (pageItemNext) {
		pageItemNext.addEventListener('click', function(e) {
			e.preventDefault();
			console.log('next');
			let next = document.querySelector('.rj-pagination .active').nextElementSibling;
			if (next) {
				next.querySelector('a').click();
			}
		});
	}
	document.addEventListener('DOMContentLoaded', () => {
		
		const observer = lozad(".lozad", {
			loaded: function(el) {
				el.classList.add("fade");
				console.log("loadewe");
			}
		});
		observer.observe();
	});
</script>

<?= template_footer() ?>