<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
// Retrieve the categories

// $stmt = $pdo->prepare('SELECT * FROM categories ORDER BY title');
// $stmt->execute();
// $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
// $category_sql = 'JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category';


$stmt = $pdo->prepare('SELECT * FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = 11 WHERE m.approved = 1 AND m.type = "image" ORDER BY m.year DESC ');
$stmt->execute();

$media = $stmt->fetchAll(PDO::FETCH_ASSOC);

$media_width = 300;
$media_height = 200;
?>
<?= template_header('For Sale') ?>
<?= template_header_other() ?>

<?= template_nav() ?>

<main class="rj-black-bg-main">
	<div class="content home">

		<h2>For sale</h2>
		<article class="rj-forsale">

			<p>
				On this page you find a small selection of works that are for sale. My output is not high, so I do not have much work to sell.
				The demand is much higher than my production and I have to make choices what to sell to whom. 
				Most works I currently make are on commission and prices are negotiated separately with the buyer. In principle we can ship works all over the world.
				If you are interested send an email with the code of the work via the contact page, or to pieter@pieter-adriaans.com.
			</p>
			<p>If you are interested send an email with the code of the work via the <a href="contact.php">contact</a> page, or to pieter@pieter-adriaans.com.</p>
		</article>

		<article class="rj-gallery">
			<div class="rj-container-gallery">

				<div class="media-list">
					<?php foreach ($media as $m) : ?>
						<?php if (file_exists($m['filepath'])) : ?>
							<a href="#" style="width:<?= $media_width ?>px;height:<?= $media_height ?>px;">
								<?php if ($m['type'] == 'image') : ?>
									<img src="<?= $m['filepath'] ?>" alt="<?= $m['description'] ?>" data-id="<?= $m['id'] ?>" data-title="<?= $m['title'] ?>" data-year="<?= $m['year'] ?>" data-fnr="<?= $m['fnr'] ?>" data-type="<?= $m['type'] ?>" data-likes="<?= $m['likes'] ?>" data-dislikes="<?= $m['dislikes'] ?>" width="<?= $media_width ?>" height="<?= $media_height ?>" style="object-fit: contain;">
								<?php elseif ($m['type'] == 'video') : ?>

									<?php if (empty($m['thumbnail'])) : ?>
										<span class="placeholder" data-src="<?= $m['filepath'] ?>" data-id="<?= $m['id'] ?>" data-title="<?= $m['title'] ?>" data-description="<?= $m['description'] ?>" data-type="<?= $m['type'] ?>" data-likes="<?= $m['likes'] ?>" data-dislikes="<?= $m['dislikes'] ?>">
											<i class="fas fa-film fa-4x"></i>
											<?= $m['title'] ?>
										</span>
									<?php else : ?>
										<img src="<?= $m['thumbnail'] ?>" alt="<?= $m['description'] ?>" data-src="<?= $m['filepath'] ?>" data-id="<?= $m['id'] ?>" data-title="<?= $m['title'] ?>" data-description="<?= $m['description'] ?>" data-type="<?= $m['type'] ?>" data-likes="<?= $m['likes'] ?>" data-dislikes="<?= $m['dislikes'] ?>" width="<?= $media_width ?>" height="<?= $media_height ?>">
									<?php endif; ?>

								<?php elseif ($m['type'] == 'audio') : ?>

									<?php if (empty($m['thumbnail'])) : ?>
										<span class="placeholder" data-src="<?= $m['filepath'] ?>" data-id="<?= $m['id'] ?>" data-title="<?= $m['title'] ?>" data-description="<?= $m['description'] ?>" data-type="<?= $m['type'] ?>" data-likes="<?= $m['likes'] ?>" data-dislikes="<?= $m['dislikes'] ?>">
											<i class="fas fa-music fa-4x"></i>
											<?= $m['title'] ?>
										</span>
									<?php else : ?>
										<img src="<?= $m['thumbnail'] ?>" alt="<?= $m['description'] ?>" data-src="<?= $m['filepath'] ?>" data-id="<?= $m['id'] ?>" data-title="<?= $m['title'] ?>" data-description="<?= $m['description'] ?>" data-type="<?= $m['type'] ?>" data-likes="<?= $m['likes'] ?>" data-dislikes="<?= $m['dislikes'] ?>" width="<?= $media_width ?>" height="<?= $media_height ?>">
									<?php endif; ?>

								<?php endif; ?>
								<span class="description"><?= $m['title'] ?></span>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</article>

		<div class="pagination">
			<?php if (!$viewingAll) {
				if ($current_page > 1) : ?>

					<a class="rj-prev" href="?page=<?= $current_page - 1 ?>&sort_by=<?= $sort_by ?>&category=<?= $category ?>">
						<svg height="16" width="16" viewBox="0 0 1024 1024">
							<path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
						</svg>
						<span>Prev</span>
					</a>
				<?php endif; ?>
				<div class="rj-current-page">
					Page <?= $current_page ?> of <?= $last_page ?>
				</div>
				<?php if ($current_page * $media_per_page < $total_media) : ?>
					<a class="rj-next" href="?page=<?= $current_page + 1 ?>&sort_by=<?= $sort_by ?>&category=<?= $category ?>">
						<span>Next</span>
						<svg height="16" width="16" viewBox="0 0 1024 1024">
							<path d="M 874.69 495.527 C 874.69 484.23 865.522 475.061 854.224 475.061 L 249.45 475.061 L 437.534 286.978 C 445.526 278.986 445.526 266.03 437.534 258.038 C 433.533 254.048 428.294 252.042 423.064 252.042 C 417.825 252.042 412.586 254.037 408.585 258.038 L 185.576 481.048 C 181.738 484.885 179.579 490.094 179.579 495.517 C 179.579 500.951 181.738 506.149 185.576 509.987 L 408.595 733.016 C 416.587 741.008 429.552 741.008 437.544 733.016 C 445.536 725.014 445.536 712.059 437.544 704.067 L 249.471 515.993 L 854.224 515.993 C 865.522 515.993 874.69 506.835 874.69 495.527 Z" transform="matrix(-1, 0, 0, -1, 1054.269043, 991.052002)"></path>
						</svg>
					</a>
			<?php endif;
			} else {
				echo '<a href="#top">Back to top</a>';
			} ?>
		</div>

	</div>
</main>

<div class="rj-btn-test">





</div>
<div class="media-popup"></div>

<?= template_footer() ?>