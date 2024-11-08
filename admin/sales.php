<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: text/html; charset=utf-8');
include 'main.php';


$dt = time();
$viewCat = filter_input(INPUT_GET, 'viewCat', FILTER_VALIDATE_INT) ?? 0;

$refresh = filter_input(INPUT_GET, 'refresh', FILTER_VALIDATE_INT) ?? $dt;
$term = filter_input(INPUT_GET, 'term') ?? '';
$show = filter_input(INPUT_GET, 'show', FILTER_VALIDATE_INT) ?? 25;
$from = filter_input(INPUT_GET, 'from', FILTER_VALIDATE_INT) ?? 0;
$salesPage = true;


$count = 0;
$media = [];


// Approve the selected media
if (isset($_GET['approve'])) {
  $stmt = $pdo->prepare('UPDATE media SET approved = 1 WHERE id = ?');
  $stmt->execute([$_GET['approve']]);
  header('Location: sales.php?page=' . $page); // Redirect to the current page after approval
  exit;
}

$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id DESC');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Which columns the users can order by, add/remove from the array below.
$order_by_list = array('id', 'title', 'year', 'fnr', 'description', 'art_price', 'art_status');
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

  $catPrivate = $category['is_private'];
  // count query
  $stmt = $pdo->prepare('SELECT COUNT(m.id) FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category_id WHERE m.type = "image" AND (m.title LIKE :term1 OR m.description LIKE :term2 OR m.year LIKE :term3) AND art_status IS NOT NULL AND art_status <> "not for sale"');
  $stmt->execute($params);
  $count = $stmt->fetchColumn();
  if ($count > 0) {
    $params['show'] = (int)$show;
    $params['from'] = (int)$from;
    $stmt = $pdo->prepare('SELECT m.* FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category_id WHERE m.type = "image" AND (m.title LIKE :term1 OR m.description LIKE :term2 OR m.year LIKE :term3) AND art_status IS NOT NULL AND art_status <> "not for sale" ORDER BY ' . $order_by . ' ' . $order_sort . ' LIMIT :show OFFSET :from');

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
  $stmt = $pdo->prepare('SELECT COUNT(id) FROM media WHERE type = "image" AND (title LIKE :term1 OR description LIKE :term2 OR year LIKE :term3) AND art_status IS NOT NULL AND art_status <> "not for sale"');
  $stmt->execute($params);
  $count = $stmt->fetchColumn();
  if ($count > 0) {
    $params['show'] = (int)$show;
    $params['from'] = (int)$from;

    $stmt = $pdo->prepare('SELECT * FROM media WHERE type = "image" AND (title LIKE :term1 OR description LIKE :term2 OR year LIKE :term3) AND art_status IS NOT NULL AND art_status <> "not for sale" ORDER BY ' . $order_by . ' ' . $order_sort . ' LIMIT :show OFFSET :from');
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

$catTitle = $category['title'] ?? '';
$catDesc = $category['description'] ?? '';



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
  redirect('sales.php');
}
// Approve the selected media
if (isset($_GET['approve'])) {
  $stmt = $pdo->prepare('UPDATE media SET approved = 1 WHERE id = ?');
  $stmt->execute([$_GET['approve']]);
  redirect('sales.php');
}


template_admin_header('Sales Page', 'Sales Page')
?>
<section>
  <div class="table-results">
    <h2>
      Viewing <?= (isset($term) && $term != '') ? 'search results for <strong>"' . $term . '"</strong>' : 'All Media for sale' ?>
    </h2>
    <p><?= $count ?> media files found. </p>
    <p>viewing page <?= $current_page ?> of <?= $total_pages ?>.</p>
    <?php if ($count > 0) : ?>
      <div class="rj-action-td">
        <button class="btn btn-primary" id="generateHTML">generate Page</button>
        <span id="linkToResults"></span>
      </div>
    <?php endif; ?>
  </div>
  <div class="rj-table-ctrl">
    <form action="sales.php" method='GET' class="bulkOptionContainer">
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
  </div>



  <nav aria-label="Page navigation">

    <ul class="pagination">
      <li class="page-item"><a class="page-link" href="#" id="page-item-prev">Previous</a></li>
      <?php
      $start = max(1, $current_page - 7);
      $end = min($total_pages, $current_page + 7);
      for ($i = $start; $i <= $end; $i++) : ?>
        <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
          <a href="sales.php?viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($i - 1) * $show) ?>&order_by=<?= $order_by ?>&order_sort=<?= $order_sort ?>" class="page-link"><?= $i ?></a>
        </li>
      <?php endfor; ?>
      <?php if ($end < $total_pages) : ?>
        <li class="page-item">
          <a href="sales.php?viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($end) * $show) ?>&order_by=<?= $order_by ?>&order_sort=<?= $order_sort ?>" class="page-link">+<?= $total_pages - $end ?></a>
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
            <a href="sales.php?order_by=id&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>&viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($current_page - 1) * $show) ?>" class="th-link">
              <p>ID
                <i class="fa-solid fa-sort" class="i-th-link"></i>
              </p>
            </a>
          </th>
          <th>
            <a href="sales.php?order_by=title&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>&viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($current_page - 1) * $show) ?>" class="th-link">
              <p>title
                <i class="fa-solid fa-sort" class="th-link"></i>
              </p>
            </a>
          </th>
          <th class="td-items">
            <a href="sales.php?order_by=year&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>&viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($current_page - 1) * $show) ?>" class="th-link">
              <p>Year </p>
              <p>fNr. <i class="fa-solid fa-sort" class="th-link"></i></p>
              <p> Date </p>
            </a>
          </th>
          <th>
            <p>
              type
            </p>
            <p>
              materials
            </p>
            <p>
              dimensions
            </p>
          </th>
          <th>
            <a href="sales.php?order_by=art_status&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>&viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($current_page - 1) * $show) ?>" class="th-link">
              <p>status
                <i class="fa-solid fa-sort" class="th-link"></i>
              </p>
            </a>
          </th>
          <th>
            <a href="sales.php?order_by=art_price&order_sort=<?= $order_sort == 'ASC' ? 'DESC' : 'ASC' ?>&viewCat=<?= $viewCat ?>&term=<?= trim($term) ?>&show=<?= $show ?>&from=<?= (($current_page - 1) * $show) ?>" class="th-link">
              <p>price
                <i class="fa-solid fa-sort" class="th-link"></i>
              </p>
            </a>
          </th>
          <th class="td-items">
            <p>Frame</p>
            <p>+price</p>

          </th>
          <th>Make <br>QR/Factsheet</th>
          <th>View <br>QR/Factsheet</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($media)) : ?>
          <tr>
            <td colspan="8" style="text-align:center;">There are no recent media files</td>
          </tr>
        <?php else : ?>

          <script>
            let catTitle, catDesc;
            let media = <?= json_encode($media) ?>;
            let cat = <?= $viewCat ?>;
            if (cat > 0) {
              catTitle = `<?= $catTitle ?>`;
              catDesc = `<?= $catDesc ?>`;
            }
            console.log(media);
          </script>
          <?php foreach ($media as $key => $m) :
            $short_title = (strlen($m['title']) >= 44) ?
              "<span data-short-title=1>" . substr(htmlspecialchars($m['title'], ENT_QUOTES), 0, 44) . "<span class='rj-elips'>...</span>" . "</span>"
              :
              "<span data-short-title=0>" . $m['title'] . "</span>";
            // $short_desc = substr(nl2br(htmlspecialchars($m['description'], ENT_QUOTES)), 0, 100) . "<span style='color: red'>...</span>";
            // $short_desc = substr(htmlspecialchars($m['description'], ENT_QUOTES), 0, 100);
          ?>
            <tr>
              <td>
                <?php if ($m['type'] == 'image') : ?>
                  <a href="../view.php?id=<?= $m['id'] ?>" target="_blank" class="copy-link" id="copyLink<?= $key ?>">
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
              <td>
                <div class="td-items">
                  <div class="td-item"><?= $m['art_type'] ?? ' ' ?></div>
                  <div class="td-item"><?= $m['art_material'] ?></div>
                  <div class="td-item"><?= $m['art_dimensions'] ?></div>
                </div>
              </td>
              <td>
                <div class="td-items">
                  <div class="td-item"><?= $m['art_status'] ?></div>
                </div>
              </td>
              <td>
                <div class="td-items">
                  <div class="td-item"><?= number_format($m['art_price'], 2) ?></div>
                </div>
              </td>
              <td>
                <div class="td-items">
                  <div class="td-item"><?= ($m['art_has_frame']) ? '+ Frame' : 'no Frame' ?></div>
                  <div class="td-item"><?= number_format($m['art_frame_price']) ?></div>
                </div>
              </td>
              <td>
                <?php if (empty($m['qr_url'])) : ?>
                  <button class="btn btn--qr" onclick="generateQRCode(<?= $m['id'] ?>)" class="rj-table-btn">Make QR</button>

                <?php elseif (empty($m['qr_card_url']) && !empty($m['qr_url'])) : ?>
                  <button class="btn btn--qrcard" onclick="generateQrCardAndSave(<?= $m['id'] ?>, '<?= '../' . $m['qr_url'] ?>')">Make QR Card</button>
                <?php else : ?>
                  <button class="btn btn--fact" onclick="generateFactSheetAndSave(<?= $m['id'] ?>)">Make Factsheet</button>
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
              <td class="rj-action-td">
                <a href="media.php?id=<?= $m['id'] ?>&<?= http_build_query($get_params) ?>&salesPage=true" class="btn btn--edit">Edit</a>
                <?php if ($_SESSION['role'] == 'admin') : ?>
                  <a class="btn btn--del" onclick="deleteMediaModal(<?= $m['id'] ?>, '<?= http_build_query($get_params) ?>')">Delete</a>
                <?php endif;
                if (!$m['approved']) : ?>
                  <a href="sales.php?approve=<?= $m['id'] ?>" class="btn btn--edit">Approve</a>
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
<div id="qr-progress-modal" class="modal">
  <div class="modal-content">
    <h4 id="qr-progress-title">QR Code Generation</h4>
    <p id="qr-progress-message"></p>
  </div>
</div>

</section>

<script>
  // select category
  document.getElementById('selectCat').addEventListener('change', function() {
    this.form.submit();
  });

  if (document.getElementById('generateHTML')) {
    document.getElementById('generateHTML').addEventListener('click', function() {
      generateHTMLPage(media);
      console.log('generate page');
    });
  }
  let generateHTMLPage = (media) => {

    // get current date in Dec 12 2021 format
    let date = new Date().toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });

    // date for filename in ddmmyy
    let dateForFile = new Date().toLocaleDateString('en-US', {
      year: '2-digit',
      month: '2-digit',
      day: '2-digit'
    }).replace(/\//g, '');

    let linkToResults = document.getElementById('linkToResults');
    let header = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=0.5"><title>Art for Sale</title><link rel="stylesheet" href="https://pieter-adriaans.com/assets/css/availableArtstyle.css"></head>';
    // let header = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=0.65"><title>Art for Sale</title><link rel="stylesheet" href="http://localhost/pieter/assets/css/availableArtstyle.css"></head>';
    let body = '';
    if (catTitle) {
      body += `<body><main><header><div><h1>${catTitle}</h1><h2>Available for sale</h2><p>${date}</p></div><div class="image-wrap"><img src="https://pieter-adriaans.com/assets/img/kaasfabriekSmall.png" alt="" srcset=""></div></header><div class="media-container">`;
    } else {
      body += `<body><main><header><div><h1>Available Art for Sale</h1></div><div class="image-wrap"><img src="https://pieter-adriaans.com/assets/img/kaasfabriekSmall.png" alt="" srcset=""></div></header><div class="media-container">`;
    }

    let disclaimer = `</div></main>
    <footer>
    <p>We ship all over the world. Various methods of payment are available.
      The price of the work in this factsheet is valid on the date of issue, but can be changed without notice.
      lf you are interested please contact us.</p>
    <div class="contact-info">
      <p>Date: ${date}
        <br> Tel: +31 654 234 459
        <br> Tel: +351 964 643 610
      </p>
      <p>
        pieter-adriaans.com<br>
        facebook.com/pieter.adriaans<br>
        email: pieter@pieter-adriaans.com<br>
      </p>

    </div>
  </footer>`;
    let footer = disclaimer + `</body></html>`;





    media.forEach((m) => {

      let img = `<img src="http://pieter-adriaans.com/${m.thumbnail}" alt="${m.title}" loading="lazy">`;
      let title = `<h2>${m.title}</h2>`;
      let idYearFnr = `<li><small>Cat.ID: </small> ${m.id}-${m.year}-${m.fnr}</li>`;

      let type = `<li><small>Type: </small>${m.art_type}</li>`;
      let materials = `<li><small>Materials: </small>${m.art_material}</li>`;
      let dimensions = `<li><small>W * H: </small>${m.art_dimensions}</li>`;
      // let price = `<p>Price: ${m.art_price}</p>`;
      // format price in euros
      let price = '';
      if (m.art_price > 0) {
        price = `<li>Price: &euro;&nbsp;${m.art_price.toLocaleString('en-US')}</li>`;
      }

      let frame = `<li>${m.art_has_frame > 0 ? 'with frame' : 'no frame'}</li>`;
      let framePrice;
      if (m.art_has_frame && m.art_frame_price > 0) {
        framePrice = `<li>+ &euro;&nbsp;${m.art_frame_price.toLocaleString('en-US')}</li>`;
      } else {
        framePrice = '';
      }
      let qr = `<img src="https://pieter-adriaans.com${m.qr_url}" alt="qr code for ${m.title}" loading="lazy">`;
      // let qrCard = `<a href="../${m.qr_card_url}" target="_blank" class="btn btn--qrcard"><i class="fa-solid fa-eye"></i> QR Card</a>`;
      // let fact = `<a href="../${m.factsheet_url}" target="_blank" class="btn btn--fact"><i class="fa-solid fa-eye"></i> Factsheet</a>`;

      let mediaItem = `<div class="media-item"><div class="img-wrap">${img}</div><div class="media-info"><div class="info-header">${title}<ul>${type}${materials}${dimensions}${idYearFnr}</ul></div><div class="info-price"><ul>${price}${frame}${framePrice}</ul></div></div><div class="qr">${qr}</div></div>`;

      body += mediaItem;
    });

    let html = header + body + footer;
    let blob = new Blob([html], {
      type: 'text/html'
    });
    let url = URL.createObjectURL(blob);
    let a = document.createElement('a');
    let downloadA = document.createElement('a');
    a.href = url;
    downloadA.href = url;
    // add class
    a.classList.add('btn');
    downloadA.classList.add('btn');
    a.target = '_blank';
    a.textContent = 'view page';
    downloadA.textContent = 'download page';
    downloadA.download = `available-art-${dateForFile}.html`;
    // a.download = 'art-for-sale.html';
    linkToResults.appendChild(a);
    linkToResults.appendChild(downloadA);
    linkToResults.style.display = 'flex';
  }
</script>

<script src="js/generateBusinessCard.js"></script>
<script src="js/generateFactSheet.js"></script>
<script src="js/multimedia.js"></script>

<script src="js/mediaCRUD.js"></script>
<script src="js/pagination.js"></script>
<?= template_admin_footer() ?>