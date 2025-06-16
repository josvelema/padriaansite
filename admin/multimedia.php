<?php
include 'main.php';
$viewCat = filter_input(INPUT_GET, 'viewCat', FILTER_VALIDATE_INT) ?? 0;

$term = filter_input(INPUT_GET, 'term') ?? '';
$show = filter_input(INPUT_GET, 'show', FILTER_VALIDATE_INT) ?? 25;
$from = filter_input(INPUT_GET, 'from', FILTER_VALIDATE_INT) ?? 0;


$count = 0;
$media = [];

$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id DESC');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($viewCat > 0) {

  $params = [
    'category_id' => $viewCat,
    'term1' => '%' . $term . '%',
    'term2' => '%' . $term . '%'
  ];
  // get category title and is_private
  $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = :category_id');
  $stmt->execute(['category_id' => $viewCat]);
  $category = $stmt->fetch(PDO::FETCH_ASSOC);
  $catTitle = $category['title'];
  $catDesc = $category['description'];
  $catPrivate = $category['is_private'];
  // count query
  $stmt = $pdo->prepare('SELECT COUNT(m.id) FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category_id WHERE m.title LIKE :term1 OR m.description LIKE :term2');
  $stmt->execute($params);
  $count = $stmt->fetchColumn();
  if ($count > 0) {
    $params['show'] = (int)$show;
    $params['from'] = (int)$from;
    $stmt = $pdo->prepare('SELECT m.* FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category_id WHERE m.title LIKE :term1 OR m.description LIKE :term2 ORDER BY m.id DESC LIMIT :show OFFSET :from');

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
    'term2' => '%' . $term . '%'
  ];

  // count query 
  $stmt = $pdo->prepare('SELECT COUNT(id) FROM media WHERE title LIKE :term1 OR description LIKE :term2');
  $stmt->execute($params);
  $count = $stmt->fetchColumn();
  if ($count > 0) {
    $params['show'] = (int)$show;
    $params['from'] = (int)$from;

    $stmt = $pdo->prepare('SELECT * FROM media WHERE title LIKE :term1 OR description LIKE :term2 ORDER BY id DESC LIMIT :show OFFSET :from');
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
// http_build_query(array_

template_admin_header('MultiMedia', 'multimedia') ?>

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
  <form action="multimedia.php" method='GET' class="bulkOptionContainer">
    <label for="selectCat">Select category to view</label>
    <select class="form-control" name="viewCat" id="selectCat">
      <option value="0">All Categories</option>
      <?php foreach ($categories as $category) : ?>
        <option value=<?= $category['id'] ?> <?= $category['id'] == $viewCat ? 'selected' : '' ?>><?= $category['title'] ?></option>
      <?php endforeach; ?>
    </select>
    <input type="text" name="term" id="search" value="<?= htmlspecialchars($term) ?>" placeholder="enter search term" class="form-control">
    <input type="submit" value="Search" class="btn btn-primary">
  </form>

  <div class="rj-btn-grid">
    <a href="multimedia.php" class="btn btn-primary">View All Media</a>
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
        <a href="multimedia.php?viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($i - 1) * $show) ?>" class="page-link"><?= $i ?></a>
      </li>
    <?php endfor; ?>
    <?php if ($end < $total_pages) : ?>
      <li class="page-item">
        <a href="multimedia.php?viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($end) * $show) ?>" class="page-link">+<?= $total_pages - $end ?></a>
      </li>
    <?php endif; ?>
    <li class="page-item"><a class="page-link" href="#" id="page-item-next">Next</a></li>
  </ul>
</nav>


<div class="content-block">

  <table class="jostable">
    <thead>
      <tr>
        <th>ID & view</th>
        <th>Title</th>
        <th>
          <p>Year</p>
          <p>FNR</p>
          <p>date</p>
        </th>
        <th>sale status</th>

        <th>Make <br>QR(card)</th>
        <th>View <br>QR(card)</th>
        <th>Audio</th>
        <th>Video</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($media)) : ?>
        <tr>
          <td colspan="8" style="text-align:center;">There are no recent media files</td>
        </tr>
      <?php else : ?>
        <?php foreach ($media as $m) : ?>
          <tr data-media-id="<?= $m['id'] ?>">
            <td>
              <?php if ($m['type'] == 'image') : ?>
                <a href="../view.php?id=<?= $m['id'] ?>" target="_blank" class="copy-link" id="copyLink<?= $key ?>">
                  <img src="../<?= $m['thumbnail'] ?>" alt="<?= $m['title'] ?>" loading="lazy">
                </a>
                <span class="rj-tooltip" id="rj-tooltip<?= $key ?>">Copied!</span>
              <?php endif; ?>
            </td>
            </td>
            <td><?= htmlspecialchars($m['title'], ENT_QUOTES) ?></td>
            <td>
              <div class="td-items">
                <div class="td-item"><?= $m['year'] ?></div>
                <div class="td-item"><?= $m['fnr'] ?></div>
                <div class="td-item"><small><?= date('d/m/y', strtotime($m['uploaded_date'])) ?></small></div>
              </div>
            </td>
            <td>
              <div class="td-items">
                <div class="td-item"><?= $m['art_status']  ?></div>
              </div>
            <td>
              <?php if (empty($m['qr_url'])) : ?>
                <button class="btn btn--qr" onclick="generateQRCode(<?= $m['id'] ?>)" class="rj-table-btn">Make QR</button>
              <?php else : ?>
                <button class="btn btn--qrcard" onclick="generateQrCardAndSave(<?= $m['id'] ?>, '<?= '../' . $m['qr_url'] ?>')">Make QR Card</button>
              <?php endif; ?>
            </td>
            <td>
              <?php if (empty($m['qr_url'])) : ?>
                <span class="btn--square btn--qr"><i class="fa-solid fa-ban"></i> QR-code </span>
              <?php else : ?>
                <a href="../<?= $m['qr_url'] ?>" target="_blank" class="btn btn--qr"><i class="fa-solid fa-eye"></i> QR</a>
              <?php endif; ?>
              <?php if (empty($m['qr_card_url'])) : ?>
                <span class="btn--square btn--qrcard"><i class="fa-solid fa-ban"></i> QR Card </span>
              <?php else : ?>
                <a href="../<?= $m['qr_card_url'] ?>" target="_blank" class="btn btn--qrcard"><i class="fa-solid fa-eye"></i> QR Card</a>
              <?php endif; ?>
              <?php if (empty($m['factsheet_url'])) : ?>
                <span class="btn--square btn--fact"><i class="fa-solid fa-ban"></i> Factsheet </span>
              <?php else : ?>
                <a href="../<?= $m['factsheet_url'] ?>" target="_blank" class="btn btn--fact"><i class="fa-solid fa-eye"></i> Factsheet</a>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($m['audio_url']) : ?>
                <a href="../<?= $m['audio_url'] ?>" target="_blank" class="rj-table-link">View audio</a>
              <?php else : ?>
                <button onclick="openUploadModal(<?= $m['id'] ?>, 'audio')" class="rj-table-btn">Upload Audio</button>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($m['video_url']) : ?>
                <a href="../<?= $m['video_url'] ?>" target="_blank" class="rj-table-link">View video</a>
              <?php else : ?>
                <button onclick="openUploadModal(<?= $m['id'] ?>, 'video')" class="rj-table-btn">Upload Video</button>
              <?php endif; ?>
            </td>

          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
<style>
  .rj-table-btn {
    background-color: #232332;
    border: none;
    border-radius: 12px;
    color: #f0f0f0;
    padding: 8px 16px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
  }

  .rj-table-btn:hover,
  .rj-table-btn:focus {
    background-color: #555555;

  }

  .rj-table-link {
    color: #223366;
    text-transform: uppercase;
  }

  .rj-table-link:hover,
  .rj-table-link:focus {
    color: #000000;
  }



  /* CSS */
  .custom-modal {
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
  }

  .custom-modal-content {
    background-color: #fff;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
  }

  .custom-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .custom-modal-close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
  }

  .custom-modal-close:hover,
  .custom-modal-close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
  }

  #uploadForm {
    display: flex;
    flex-direction: column;
    padding: 0.5em;
    margin-block: 1em;
  }

  #uploadForm>* {
    margin-block: 0.5em;

  }
</style>
<div class="delMediaModalWrap"></div>
<div id="qr-progress-modal" class="modal">
  <div class="modal-content">
    <h4 id="qr-progress-title">QR Code Generation</h4>
    <p id="qr-progress-message"></p>
  </div>
</div>

<!-- Upload Modal -->

<div id="uploadModal" class="custom-modal" style="display:none;">
  <div class="custom-modal-content">
    <div class="custom-modal-header">
      <h2 id="uploadModalTitle">Upload</h2>
      <span id="uploadModalClose" class="custom-modal-close">&times;</span>
    </div>
    <div class="custom-modal-body">
      <form id="uploadForm">
        <input type="hidden" id="uploadMediaId" name="media_id" value="">
        <input type="hidden" id="uploadType" name="type" value="">
        <input type="file" id="fileInput" name="file" required>
        <progress id="uploadProgress" value="0" max="100"></progress>
        <button type="button" id="uploadButton">Upload</button>
      </form>
    </div>
  </div>
</div>

<script src="js/generateBusinessCard.js"></script>
<script src="js/generateFactsheet.js"></script>
<script src="js/multimedia.js"></script>
<script src="js/pagination.js"></script>
<script>
  document.getElementById('selectCat').addEventListener('change', function() {
    this.form.submit();
  });
</script>

<?= template_admin_footer() ?>