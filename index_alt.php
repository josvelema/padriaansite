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
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'newest';
$sort_by_sql = 'm.uploaded_date DESC';
$sort_by_sql = $sort_by == 'newest' ? 'm.uploaded_date DESC' : $sort_by_sql;
$sort_by_sql = $sort_by == 'oldest' ? 'm.uploaded_date ASC' : $sort_by_sql;
$sort_by_sql = $sort_by == 'a_to_z' ? 'm.title DESC' : $sort_by_sql;
$sort_by_sql = $sort_by == 'z_to_a' ? 'm.title ASC' : $sort_by_sql;
// Get media by the type (ignore if set to all)
$type = isset($_GET['type']) ? $_GET['type'] : 'all';
$type_sql = $type != 'all' ? 'AND m.type = :type' : '';
// Limit the amount of media on each page
$media_per_page = 6;
// The current pagination page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
// MySQL query that selects all the media
$stmt = $pdo->prepare('SELECT * FROM media m ' . $category_sql . ' WHERE m.approved = 1 ' . $type_sql . ' ORDER BY ' . $sort_by_sql . ' LIMIT :page,:media_per_page');
// Determine which page the user is on and bind the value into our SQL statement
$stmt->bindValue(':page', ((int)$current_page-1)*$media_per_page, PDO::PARAM_INT);
// How many media will show on each page
$stmt->bindValue(':media_per_page', $media_per_page, PDO::PARAM_INT);
// Check if the type is not set to all
if ($type != 'all') $stmt->bindValue(':type', $type);
// Check if the category is not set to all
if ($category != 'all') $stmt->bindValue(':category', $category);
// Execute the SQL
$stmt->execute();
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of media
$stmt = $pdo->prepare('SELECT COUNT(*) FROM media m ' . $category_sql . ' WHERE m.approved = 1 ' . $type_sql);
if ($type != 'all') $stmt->bindValue(':type', $type);
if ($category != 'all') $stmt->bindValue(':category', $category);
$stmt->execute();
$total_media = $stmt->fetchColumn();
// Set media properties below
$media_width = 320;
$media_height = 210;
?>
<?=template_header('Gallery Alt')?>

<div class="content home">

	<h2>Gallery</h2>

	<?php if ($category != 'all'): ?>
	<p><?=$categories[array_search($category, array_column($categories, 'id'))]['description']?></p>
	<?php else: ?>
	<p>Welcome to the gallery page! You can view the list of images, audios, and videos below.</p>
	<?php endif; ?>

	<div class="con">
		<a href="upload.php" class="upload-media">Upload Media</a>
		<form action="" method="get">
			<label for="category">Category:</label>
			<select id="category" name="category" onchange="this.form.submit()">
				<option value="all"<?=$sort_by=='all'?' selected':''?>>All</option>
				<?php foreach ($categories as $c): ?>
				<option value="<?=$c['id']?>"<?=$category==$c['id']?' selected':''?>><?=$c['title']?></option>
				<?php endforeach; ?>
			</select>
			<label for="sort_by">Sort By:</label>
			<select id="sort_by" name="sort_by" onchange="this.form.submit()">
				<option value="newest"<?=$sort_by=='newest'?' selected':''?>>Newest</option>
				<option value="oldest"<?=$sort_by=='oldest'?' selected':''?>>Oldest</option>
				<option value="a_to_z"<?=$sort_by=='a_to_z'?' selected':''?>>A-Z</option>
				<option value="z_to_a"<?=$sort_by=='z_to_a'?' selected':''?>>Z-A</option>
			</select>
			<label for="type">Type:</label>
			<select id="type" name="type" onchange="this.form.submit()">
				<option value="all"<?=$type=='all'?' selected':''?>>All</option>
				<option value="audio"<?=$type=='audio'?' selected':''?>>Audio</option>
				<option value="image"<?=$type=='image'?' selected':''?>>Image</option>
				<option value="video"<?=$type=='video'?' selected':''?>>Video</option>
			</select>
		</form>
	</div>

	<div class="media-list">
		<?php foreach ($media as $m): ?>
		<?php if (file_exists($m['filepath'])): ?>
		<a href="view.php?id=<?=$m['id']?>" style="width:<?=$media_width?>px;height:<?=$media_height?>px;">
			<?php if ($m['type'] == 'image'): ?>
			<img src="<?=$m['filepath']?>" alt="<?=$m['description']?>" width="<?=$media_width?>" height="<?=$media_height?>">
			<?php elseif ($m['type'] == 'video'): ?>
			<?php if (empty($m['thumbnail'])): ?>
			<span class="placeholder">
				<i class="fas fa-film fa-4x"></i>
				<?=$m['title']?>
			</span>
			<?php else: ?>
			<img src="<?=$m['thumbnail']?>" alt="<?=$m['description']?>" width="<?=$media_width?>" height="<?=$media_height?>">
			<?php endif; ?>
			<?php elseif ($m['type'] == 'audio'): ?>
			<?php if (empty($m['thumbnail'])): ?>
			<span class="placeholder">
				<i class="fas fa-music fa-4x"></i>
				<?=$m['title']?>
			</span>
			<?php else: ?>
			<img src="<?=$m['thumbnail']?>" alt="<?=$m['description']?>" width="<?=$media_width?>" height="<?=$media_height?>">
			<?php endif; ?>
			<?php endif; ?>
			<span class="description"><?=$m['description']?></span>
		</a>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>

	<div class="pagination">
	    <?php if ($current_page > 1): ?>
	    <a href="?page=<?=$current_page-1?>&sort_by=<?=$sort_by?>&category=<?=$category?>">Prev</a>
	    <?php endif; ?>
	    <div>Page <?=$current_page?></div>
	    <?php if ($current_page * $media_per_page < $total_media): ?>
	    <a href="?page=<?=$current_page+1?>&sort_by=<?=$sort_by?>&category=<?=$category?>">Next</a>
	    <?php endif; ?>
	</div>

</div>

<?=template_footer()?>
