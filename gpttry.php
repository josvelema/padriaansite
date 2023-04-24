<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();

// Search condition
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = $search ? ' AND (m.title LIKE :search OR m.description LIKE :search)' : '';

// Retrieve the categories
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY title');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retrieve the years
$stmt = $pdo->prepare('SELECT DISTINCT year FROM media ORDER BY year DESC');
$stmt->execute();
$years = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Retrieve the requested category
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$category_sql = $category != 'all' ? 'JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category' : '';

// Retrieve the requested year
$year = isset($_GET['year']) ? $_GET['year'] : 'all';
$year_sql = $year != 'all' ? ' AND m.year = :year' : '';

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
$media_per_page = 50;

// The current pagination page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Determine if the user is viewing all media or paginated media
$viewingAll = false;

// MySQL query that selects all the media
$sql = 'SELECT * FROM media m ' . $category_sql . ' WHERE m.approved = 1 ' . $type_sql . $search_sql . $year_sql . ' ORDER BY ' . $sort_by_sql . ', fnr';

// Check if the category is not set to all
if ($category != 'all') {
    $sql = $sql . ' LIMIT :page,:media_per_page';
} else {
    $viewingAll = true;
}

// Prepare the SQL statement and bind values if necessary
$stmt = $pdo->prepare($sql);

// Determine which page the user is on and bind the value into our SQL statement
if (!$viewingAll) {
    $stmt->bindValue(':page', ((int)$current_page - 1) * $media_per_page, PDO::PARAM_INT);
    // How many media will show on each page
    $stmt->bindValue(':media_per_page', $media_per_page, PDO::PARAM_INT);
}

// Check if the category is not set to all
if ($category != 'all') {
    $stmt->bindValue(':category', $category);
}

// Bind the values for the search condition if necessary
if ($search) {
$stmt->bindValue(':search', '%' . $search . '%');
}

// Bind the values for the requested year if necessary
if ($year != 'all') {
$stmt->bindValue(':year', $year);
}

// Bind the values for the requested media type if necessary
if ($type != 'all') {
$stmt->bindValue(':type', $type);
}

// Execute the SQL statement
$stmt->execute();

// Fetch the results from the query
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Retrieve the requested year
$year = isset($_GET['year']) ? $_GET['year'] : 'all';
$year_sql = '';
if ($year !== 'all') {
    $year_sql = ' AND m.year = :year';
}

// Retrieve the unique years
$stmt = $pdo->prepare('SELECT DISTINCT year FROM media WHERE approved = 1 ORDER BY year DESC');
$stmt->execute();
$years = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>
<?= template_header('Gallery') ?>
<?= template_header_other() ?>
<link rel="stylesheet" href="assets/css/gallery.css?v=5">
<?= template_nav() ?>
<main class="rj-black-bg-main">
  <div class="content home">
    <header>
      <h1>Artworks Gallery</h1>
      <p>Follow Pieter’s development as a visual artist over time and place. Use the “category" field and "year" field to make selections and "sort" for sorting options. New categories are under construction. </p>
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
        <label for="category">Category:</label>
        <select id="category" name="category" onchange="this.form.submit()">
          <option value="all" <?= $category == 'all' ? ' selected' : '' ?>>All</option>
          <?php foreach ($categories as $c) : ?>
            <option value="<?= $c['id'] ?>" <?= $category == $c['id'] ? ' selected' : '' ?>><?= $c['title'] ?></option>
          <?php endforeach; ?>
        </select>
        <label for="year">Year:</label>
        <select id="year" name="year" onchange="this.form.submit()">
          <option value="all" <?= $year == 'all' ? ' selected' : '' ?>>All</option>
          <?php foreach ($years as $y) : ?>
            <option value="<?= $y ?>" <?= $year == $y ? ' selected' : '' ?>><?= $y ?></option>
          <?php endforeach; ?>
        </select>
        <label for="sort_by">Sort By:</label>
        <select id="sort_by" name="sort_by" onchange="this.form.submit()">
          <option value="year0_9" <?= $sort_by == 'year0_9' ? ' selected' : '' ?>>Year 1 > 10</option>
          <option value="year9_0" <?= $sort_by == 'year9_0' ? ' selected' : '' ?>>Year 10 > 1</option>
          <option value="newest" <?= $sort_by == 'newest' ? ' selected' : '' ?>>Newest</option>
          <option value="oldest" <?= $sort_by == 'oldest' ? ' selected' : '' ?>>Oldest</option>
          <option value="a_to_z" <?= $sort_by == 'a_to_z' ? ' selected' : '' ?>>A-Z</option>
          <option value="z_to_a" <?= $sort_by == 'z_to_a' ? ' selected' : '' ?>>Z-A</option>
        </select>
      </form>
    </div>

    <div class="
