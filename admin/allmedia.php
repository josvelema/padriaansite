<?php
include 'main.php';

$viewCat = filter_input(INPUT_GET, 'viewCat', FILTER_VALIDATE_INT) ?? 0;
$term = filter_input(INPUT_GET, 'term') ?? '';
$show = filter_input(INPUT_GET, 'show', FILTER_VALIDATE_INT) ?? 25;
$from = filter_input(INPUT_GET, 'from', FILTER_VALIDATE_INT) ?? 0;
$refresh = filter_input(INPUT_GET, 'refresh', FILTER_VALIDATE_INT) ?? 0;
$count = 0;
$media = [];

$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id DESC');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);




// Which columns the users can order by, add/remove from the array below.
$order_by_list = array('id', 'title', 'year', 'fnr', 'description');
// Order by which column if specified (default to id)
$order_by = isset($_GET['order_by']) && in_array($_GET['order_by'], $order_by_list) ? $_GET['order_by'] : 'id';
// Sort by ascending or descending if specified (default to ASC)
$order_sort = isset($_GET['order_sort']) && $_GET['order_sort'] == 'ASC' ? 'ASC' : 'DESC';
if ($viewCat > 0) {

    $params = [
        'category_id' => $viewCat,
        'term1' => '%' . $term . '%',
        'term2' => '%' . $term . '%',
        'term3' => '%' . $term . '%'

    ];
    // get category title and is_private
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :category_id');
    $stmt->execute(['category_id' => $viewCat]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    $catTitle = $category['title'];
    $catDesc = $category['description'];
    $catPrivate = $category['is_private'];
    // count query
    $stmt = $pdo->prepare('SELECT COUNT(m.id) FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category_id WHERE m.type = "image" AND (m.title LIKE :term1 OR m.description LIKE :term2 OR m.year LIKE :term3)');
    $stmt->execute($params);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        $params['show'] = (int)$show;
        $params['from'] = (int)$from;
        $stmt = $pdo->prepare('SELECT m.* FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category_id WHERE m.type = "image" AND (m.title LIKE :term1 OR m.description LIKE :term2 OR m.year LIKE :term3) ORDER BY ' . $order_by . ' ' . $order_sort . ' LIMIT :show OFFSET :from');

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
    $params = [
        'term1' => '%' . $term . '%',
        'term2' => '%' . $term . '%',
        'term3' => '%' . $term . '%'
    ];

    // count query 
    $stmt = $pdo->prepare('SELECT COUNT(id) FROM media WHERE type = "image" AND (title LIKE :term1 OR description LIKE :term2 OR year LIKE :term3)');
    $stmt->execute($params);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        $params['show'] = (int)$show;
        $params['from'] = (int)$from;

        $stmt = $pdo->prepare('SELECT * FROM media WHERE type = "image" AND (title LIKE :term1 OR description LIKE :term2 OR year LIKE :term3) ORDER BY ' . $order_by . ' ' . $order_sort . ' LIMIT :show OFFSET :from');
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
// get current get parameters into string
$get_params = $_GET;

// Delete the selected media
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([$_GET['delete']]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($media['thumbnail']) {
        unlink('../' . $media['thumbnail']);
    }
    unlink('../' . $media['filepath']);
    $stmt = $pdo->prepare('DELETE m, mc FROM media m LEFT JOIN media_categories mc ON mc.media_id = m.id WHERE m.id = ?');
    $stmt->execute([$_GET['delete']]);
    redirect('allmedia.php');
}
// Approve the selected media
if (isset($_GET['approve'])) {
    $stmt = $pdo->prepare('UPDATE media SET approved = 1 WHERE id = ?');
    $stmt->execute([$_GET['approve']]);
    redirect('allmedia.php');
}

template_admin_header('Media Gallery\'s', 'MediaGallery')
?>
<section>
    <div class="table-results">
        <h2>
            <?php if ($viewCat > 0) : ?>
                Viewing <?= (isset($term) && $term != '') ? 'search results for <strong>"' . $term . '</strong>" in ' . $catTitle  : 'Category: ' . $catTitle ?>
            <?php else : ?>
                Viewing <?= (isset($term) && $term != '') ? 'search results for <strong>"' . $term . '"</strong>' : 'All Media' ?>
            <?php endif; ?>
            <?php if ($viewCat > 0 && $catPrivate) : ?>
                <span class="rj-private-cat">Private</span>
            <?php endif; ?>
        </h2>
        <p><?= $count ?> media files found. </p>
        <p>viewing page <?= $current_page ?> of <?= $total_pages ?>.</p>
    </div>


    <div class="rj-table-ctrl">
        <form action="allmedia.php" method='GET' class="bulkOptionContainer">
            <label for="selectCat">Select category to view</label>
            <select class="form-control" name="viewCat" id="selectCat">
                <option value="0">All Categories</option>
                <?php foreach ($categories as $category) : ?>
                    <option value=<?= $category['id'] ?> <?= $category['id'] == $viewCat ? 'selected' : '' ?>><?= $category['title'] ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="term" id="search" value="<?= htmlspecialchars($term) ?>" placeholder="Search in title,description or year" class="form-control">
            <input type="submit" value="Search" class="btn btn-primary">
        </form>

        <div class="rj-btn-grid">
            <a href="allmedia.php" class="btn btn-primary">View All Media</a>
            <a href="media.php" class="btn">Create Media</a>
        </div>
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item"><a class="page-link" href="#" id="page-item-prev">Previous</a></li>
            <?php
            $start = max(1, $current_page - 7);
            $end = min($total_pages, $current_page + 7);
            for ($i = $start; $i <= $end; $i++) : ?>
                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                    <a href="allmedia.php?viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($i - 1) * $show) ?>&order_by=<?= $order_by ?>&order_sort=<?= $order_sort ?>" class="page-link"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($end < $total_pages) : ?>
                <li class="page-item">
                    <a href="allmedia.php?viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($end) * $show) ?>&order_by=<?= $order_by ?>&order_sort=<?= $order_sort ?>" class="page-link">+<?= $total_pages - $end ?></a>
                </li>
            <?php endif; ?>
            <li class="page-item"><a class="page-link" href="#" id="page-item-next">Next</a></li>
        </ul>
    </nav>

    <div class="table-container">

        <table class="jostable">
            <thead>
                <tr>
                    <th>
                        <a href="allmedia.php?order_by=id&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>&viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($current_page - 1) * $show) ?>" class="th-link">
                            <p>ID
                                <i class="fa-solid fa-sort" class="i-th-link"></i>
                            </p>
                        </a>
                    </th>
                    <th>
                        <a href="allmedia.php?order_by=title&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>&viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($current_page - 1) * $show) ?>" class="th-link">
                            <p>title
                                <i class="fa-solid fa-sort" class="th-link"></i>
                            </p>
                        </a>
                    </th>
                    <th class="td-items">
                        <a href="allmedia.php?order_by=year&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>&viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($current_page - 1) * $show) ?>" class="th-link">
                            <p>Year </p>
                            <p>fNr. <i class="fa-solid fa-sort" class="th-link"></i></p>
                            <p> Date </p>
                        </a>
                    </th>
                    <th class="responsive-hidden">
                        <a href="allmedia.php?order_by=description&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>&viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($current_page - 1) * $show) ?>" class="th-link">
                            <p> Description
                                <i class="fa-solid fa-sort"></i>
                            </p>
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($media)) : ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">There are no recent media files</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($media as $key => $m) :
                        $short_title = (strlen($m['title']) >= 44) ?
                            "<span data-short-title=1>" . substr(htmlspecialchars($m['title'], ENT_QUOTES), 0, 44) . "<span class='rj-elips'>...</span>" . "</span>"
                            :
                            "<span data-short-title=0>" . $m['title'] . "</span>";

                        // $short_desc = substr(nl2br(htmlspecialchars($m['description'], ENT_QUOTES)), 0, 100) . "<span style='color: red'>...</span>";
                        $short_desc = substr(htmlspecialchars($m['description'], ENT_QUOTES), 0, 100);
                    ?>
                        <tr>
                            <td>
                                <?php if ($m['type'] == 'image') : ?>
                                    <a href="../view.php?id=<?= $m['id'] ?>?refresh=<?= $refresh ?>" target="_blank" class="copy-link" id="copyLink<?= $key ?>">
                                        <img src="../<?= $m['thumbnail'] ?>" alt="<?= $m['title'] ?>" loading="lazy">
                                    </a>
                                    <span class="rj-tooltip" id="rj-tooltip<?= $key ?>">Copied!</span>

                                <?php endif; ?>
                            </td>


                            <td>
                                <span class="q-edit-text" data-titleSpan-id=<?= (int) $m['id'] ?>><?= $short_title ?></span>
                                <span class="edit-icon" data-id="<?= $m['id'] ?>" data-field="title" data-content="<?= $m['title'] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ballpen" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M14 6l7 7l-4 4" />
                                        <path d="M5.828 18.172a2.828 2.828 0 0 0 4 0l10.586 -10.586a2 2 0 0 0 0 -2.829l-1.171 -1.171a2 2 0 0 0 -2.829 0l-10.586 10.586a2.828 2.828 0 0 0 0 4z" />
                                        <path d="M4 20l1.768 -1.768" />
                                    </svg>
                                </span>
                            </td>
                            <td>
                                <div class="td-items">
                                    <div class="td-item"><?= $m['year'] ?></div>
                                    <div class="td-item"><?= $m['fnr'] ?></div>
                                    <div class="td-item"><small><?= date('d/m/y', strtotime($m['uploaded_date'])) ?></small></div>
                                </div>
                            </td>
                            <td class="responsive-hidden">
                                <?= (strlen($m['description']) >= 100) ?
                                    "<span data-short-content=1>" . $short_desc . "<span class='rj-elips'>...</span>" . "</span>" :
                                    "<span data-short-content=0>" . $m['description'] . "</span>"
                                ?>
                            </td>
                            <td class="rj-action-td">
                                <a href="media.php?id=<?= $m['id'] ?>&<?= http_build_query($get_params) ?>" class="btn btn--edit">Edit</a>
                                <?php if ($_SESSION['role'] == 'admin') : ?>
                                    <a class="btn btn--del" onclick="deleteMediaModal(<?= $m['id'] ?>, '<?= http_build_query($get_params) ?>')">Delete</a>
                                <?php endif; ?>
                                <?php if (!$m['approved']) : ?>
                                    <a href="allmedia.php?approve=<?= $m['id'] ?>" class="btn btn--edit">Approve</a>
                                <?php endif; ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</section>




<div class="delMediaModalWrap"></div>
<div class="editModalWrap"></div>

</section>
<script src="js/mediaCRUD.js"></script>
<script src="js/pagination.js"></script>
<script>
    // select category
    document.getElementById('selectCat').addEventListener('change', function() {
        this.form.submit();
    });
</script>
<?= template_admin_footer() ?>