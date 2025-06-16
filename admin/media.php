<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'main.php';

include 'generate_thumbnail.php';

// check for refresh get
$dt = time();
$refresh = $_GET['refresh'] ?? $dt;


// Default media values
$media = [
    'title' => '',
    'description' => '',
    'year' => 0,
    'fnr' => 0,
    'filepath' => '',
    'uploaded_date' => date('Y-m-d\TH:i:s'),
    'type' => '',
    'approved' => 1,
    'art_material' => '',
    'art_dimensions' => '',
    'art_type' => '',
    'art_status' => '',
    'art_price' => 0,
    'art_has_frame' => 0,
    'art_frame_price' => 0,
    'categories' => []
];

// Check if the media ID exists and set type of page 

if (isset($_GET['id'])) {
    $page = 'Edit';
    // Retrieve the media from the database
    $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    // Retrieve the categories
    $stmt = $pdo->prepare('SELECT c.title, c.id FROM media_categories mc JOIN categories c ON c.id = mc.category_id WHERE mc.media_id = ?');
    $stmt->execute([$_GET['id']]);
    $media['categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $page = 'Create';
}

// Retrieve all the categories from the database
$stmt = $pdo->query('SELECT * FROM categories');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Add categories to the database
function addCategories($pdo, $media_id)
{
    if (isset($_POST['categories_list'])) {
        $list = explode(',', $_POST['categories_list']);
        $in  = str_repeat('?,', count($list) - 1) . '?';
        $stmt = $pdo->prepare('DELETE FROM media_categories WHERE media_id = ? AND category_id NOT IN (' . $in . ')');
        $stmt->execute(array_merge([$media_id], $list));
        foreach ($list as $cat) {
            if (empty($cat)) continue;
            $stmt = $pdo->prepare('INSERT IGNORE INTO media_categories (media_id,category_id) VALUES (?,?)');
            $stmt->execute([$media_id, $cat]);
        }
    }
}


$params_to_check = ['viewCat', 'term', 'show', 'from', 'order_by', 'order_sort', 'refresh'];

// Initialize the redirect_params array
$redirect_params = [];

// Check if each parameter exists in the $_GET array
foreach ($params_to_check as $param) {
    if (isset($_GET[$param])) {
        // If it does, add it to the redirect_params array
        $redirect_params[$param] = $_GET[$param];
    }
}

$media_path = $media['filepath'];
$thumb = '';

$gotoPage = (isset($_GET['salesPage']) ? 'sales.php?' : 'allmedia.php?');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle file uploads
    if (isset($_FILES['media']) && !empty($_FILES['media']['tmp_name'])) {
        $media_file_id = md5(uniqid());
        $media_type = '';

        $media_type = preg_match('/image\/*/', $_FILES['media']['type']) ? 'image' : $media_type;
        $media_type = preg_match('/audio\/*/', $_FILES['media']['type']) ? 'audio' : $media_type;
        $media_type = preg_match('/video\/*/', $_FILES['media']['type']) ? 'video' : $media_type;

        $media_parts = explode('.', $_FILES['media']['name']);
        $media_path = 'media/' . $media_type . 's/' . $media_file_id . '.' . end($media_parts);

        move_uploaded_file($_FILES['media']['tmp_name'], '../' . $media_path);

        if ($media_type === 'image') {
            $thumb = generateThumb('../' . $media_path);
        } else {
            // If it's audio or video, check if a custom thumbnail was uploaded
            if (isset($_FILES['custom_thumbnail']) && $_FILES['custom_thumbnail']['error'] === UPLOAD_ERR_OK) {
                $tmpThumb = $_FILES['custom_thumbnail']['tmp_name'];

                if (file_exists($tmpThumb) && exif_imagetype($tmpThumb) !== false) {
                    $thumb_ext = pathinfo($_FILES['custom_thumbnail']['name'], PATHINFO_EXTENSION);
                    $thumb_filename = 'thumb_' . $media_file_id . '.' . $thumb_ext;
                    $thumb_path = 'media/thumbs/' . $thumb_filename;

                    if (!is_dir('../media/thumbs')) {
                        mkdir('../media/thumbs', 0755, true);
                    }

                    if (move_uploaded_file($tmpThumb, '../' . $thumb_path)) {
                        $thumb = $thumb_path;
                    } else {
                        die("Fout bij verplaatsen van thumbnail bestand.");
                    }
                } else {
                    die("Geüpload bestand is geen geldige afbeelding.");
                }
            }
        }
    } else {
        // keep the old file path
        $media_path = $media['filepath'];
        // keep the old thumbnail path
        $thumb = $media['thumbnail'];
    }

    if (isset($_GET['id'])) {

        // set the media id
        $media_id = $_GET['id'];
        // check if the art_has_frame is set to 0 and if so set the frame price to 0
        if ($_POST['art_has_frame'] == 0) {
            $_POST['art_frame_price'] = 0;
        }

        if ($_POST['type'] !== 'image' && isset($_FILES['custom_thumbnail']) && $_FILES['custom_thumbnail']['error'] === UPLOAD_ERR_OK) {
            $tmpThumb = $_FILES['custom_thumbnail']['tmp_name'];
            if (file_exists($tmpThumb) && exif_imagetype($tmpThumb) !== false) {
                $thumb_ext = pathinfo($_FILES['custom_thumbnail']['name'], PATHINFO_EXTENSION);
                $thumb_filename = 'thumb_' . md5(uniqid()) . '.' . $thumb_ext;
                $thumb_path = 'media/thumbs/' . $thumb_filename;

                if (!is_dir('../media/thumbs')) {
                    mkdir('../media/thumbs', 0755, true);
                }

                if (move_uploaded_file($tmpThumb, '../' . $thumb_path)) {
                    $thumb = $thumb_path;
                } else {
                    die("Fout bij het verplaatsen van de nieuwe thumbnail.");
                }
            }
        } elseif (empty($thumb) && empty($_FILES['media']['tmp_name']) && $_POST['type'] === 'image') {
            $thumb = generateThumb('../' . $media_path);
        }


        // Update the media
        $stmt = $pdo->prepare('UPDATE media SET title = ?, description = ?, year = ? , fnr = ? , filepath = ?, type = ?,  approved = ?, art_material = ?, art_dimensions = ?, art_type = ?, art_status = ?, art_price = ?,art_has_frame = ?, art_frame_price = ?, thumbnail = ? WHERE id = ?');
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['year'], $_POST['fnr'], $media_path, $_POST['type'], $_POST['approved'], $_POST['art_material'], $_POST['art_dimensions'], $_POST['art_type'], $_POST['art_status'], $_POST['art_price'], $_POST['art_has_frame'], $_POST['art_frame_price'], $thumb, $_GET['id']]);
        addCategories($pdo, $_GET['id']);
        // close the loading screen indicator
        echo '<script>document.querySelector(".loading-indicator").style.display = "none";</script>';
        echo '
        <label for="rj-modal" class="rj-modal-background"></label>
        <div class="rj-modal">
            <div class="modal-header">
                <h3>Media Updated!</h3>
                <label for="rj-modal">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAdVBMVEUAAABNTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU0N3NIOAAAAJnRSTlMAAQIDBAUGBwgRFRYZGiEjQ3l7hYaqtLm8vsDFx87a4uvv8fP1+bbY9ZEAAAB8SURBVBhXXY5LFoJAAMOCIP4VBRXEv5j7H9HFDOizu2TRFljedgCQHeocWHVaAWStXnKyl2oVWI+kd1XLvFV1D7Ng3qrWKYMZ+MdEhk3gbhw59KvlH0eTnf2mgiRwvQ7NW6aqNmncukKhnvo/zzlQ2PR/HgsAJkncH6XwAcr0FUY5BVeFAAAAAElFTkSuQmCC" width="16" height="16" alt="" onclick="closeModal()">
                </label>
            </div>
            <p>
            <a href="../view.php?id=' . $media_id . '" target="_blank" class="rj-modal-btn">Preview media</a>
            </p>
            <p>
            <a href="' . $gotoPage . http_build_query($redirect_params) . '" class="rj-modal-btn">Go back to media page</a>
            </p>
        </div>
        
        ';
    } else {
        // Create new media
        $page = 'Create';

        if ($_POST['art_has_frame'] == 0) {
            $_POST['art_frame_price'] = 0;
        }
        // add art_ fields 
        $stmt = $pdo->prepare('INSERT INTO media (title, description, year, fnr, filepath, type, approved, art_material, art_dimensions, art_type, art_status, art_price, art_has_frame, art_frame_price, thumbnail) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        // inspectAndDie($stmt);
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['year'], $_POST['fnr'], $media_path, $_POST['type'], $_POST['approved'], $_POST['art_material'], $_POST['art_dimensions'], $_POST['art_type'], $_POST['art_status'], $_POST['art_price'], $_POST['art_has_frame'], $_POST['art_frame_price'], $thumb]);
        $media_id = $pdo->lastInsertId();
        addCategories($pdo, $media_id);
        echo '
        <label for="rj-modal" class="rj-modal-background"></label>
        <div class="rj-modal">
            <div class="modal-header">
                <h3>Media Uploaded!</h3>
                <label for="rj-modal">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAdVBMVEUAAABNTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU0N3NIOAAAAJnRSTlMAAQIDBAUGBwgRFRYZGiEjQ3l7hYaqtLm8vsDFx87a4uvv8fP1+bbY9ZEAAAB8SURBVBhXXY5LFoJAAMOCIP4VBRXEv5j7H9HFDOizu2TRFljedgCQHeocWHVaAWStXnKyl2oVWI+kd1XLvFV1D7Ng3qrWKYMZ+MdEhk3gbhw59KvlH0eTnf2mgiRwvQ7NW6aqNmncukKhnvo/zzlQ2PR/HgsAJkncH6XwAcr0FUY5BVeFAAAAAElFTkSuQmCC" width="16" height="16" alt="" onclick="closeModal()">
                </label>
            </div>
            <p>
            <a href="../view.php?id=' . $media_id . '" target="_blank" class="rj-modal-btn">Preview media</a>
            </p>
            <p>
            <a href="' . $gotoPage . http_build_query($redirect_params) . '" class="rj-modal-btn">Go back to media page</a>
            </p>
        </div>
        
        ';
    }
}



?>
<?= template_admin_header($page . ' Media', 'allmedia') ?>

<h2><?= $page ?> Media</h2>
<div class="loading-indicator" style="display: none;">
    <p>Loading</p>
    <img src="../assets/img/loader-2.png" alt="loader">
</div>

<div class="content-block">


    <form action="" method="post" class="form responsive-width-100" enctype="multipart/form-data" data-page="<?= $page ?>">
        <label for="title">Title</label>
        <input id="title" type="text" name="title" placeholder="Title" value="<?= htmlspecialchars($media['title'], ENT_QUOTES) ?>" required>

        <label for="description">Description | Total characters: <span id="charCount"></span></label>
        <textarea id="description" name="description" placeholder="Description ..."><?= htmlspecialchars($media['description'], ENT_QUOTES) ?></textarea>

        <!-- <label for="uploaded_date">Uploaded Date</label>
        <input id="uploaded_date" type="datetime-local" name="uploaded_date" value="<?= date('Y-m-d\TH:i:s', strtotime($media['uploaded_date'])) ?>" required> -->

        <div class="form-group">
            <div class="form-group-item">
                <label for="year">Year of production</label>
                <input id="year" type="number" name="year" value="<?= $media['year'] ?>" min=1900 max=2058>
            </div>
            <div class="form-group-item">
                <label for="fnr">Follow-up number (if none default is 0)</label>
                <input id="fnr" type="number" name="fnr" value="<?= $media['fnr'] ?>" min=0 max=9999>
            </div>

            <div class="form-group-item">

                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="image" <?= $media['type'] == 'image' ? ' selected' : '' ?>>Image</option>
                    <option value="audio" <?= $media['type'] == 'audio' ? ' selected' : '' ?>>Audio</option>
                    <option value="video" <?= $media['type'] == 'video' ? ' selected' : '' ?>>Video</option>
                </select>
            </div>
            <div class="form-group-item">
                <label for="approved">Approved</label>
                <select id="approved" name="approved" required>
                    <option value="0" <?= $media['approved'] == 0 ? ' selected' : '' ?>>No</option>
                    <option value="1" <?= $media['approved'] == 1 ? ' selected' : '' ?>>Yes</option>
                </select>
            </div>
        </div>
        <div class="form-group" id="art_fields">
            <div class="form-group-item">
                <label for="art_material">Material</label>
                <input id="art_material" type="text" name="art_material" value="<?= htmlspecialchars($media['art_material'], ENT_QUOTES) ?>" placeholder="Oil on canvas">
            </div>
            <div class="form-group-item">
                <label for="art_dimensions">Dimensions</label>
                <input id="art_dimensions" type="text" name="art_dimensions" value="<?= htmlspecialchars($media['art_dimensions'], ENT_QUOTES) ?>" placeholder="Length * Width">
            </div>
            <div class="form-group-item">
                <label for="art_type">Type</label>
                <input id="art_type" type="text" name="art_type" value="<?= htmlspecialchars($media['art_type'], ENT_QUOTES) ?>" placeholder="Painting, Drawing, etc">
            </div>
        </div>
        <div class="form-group" id="art_sale_fields">
            <div class="form-group-item">
                <label for="art_status">Status</label>
                <select id="art_status" name="art_status">
                    <option value="available" <?= $media['art_status'] == 'available' ? ' selected' : '' ?>>Available for sale</option>
                    <option value="reserved" <?= $media['art_status'] == 'reserved' ? ' selected' : '' ?>>Reserved</option>
                    <option value="sold" <?= $media['art_status'] == 'sold' ? ' selected' : '' ?>>Sold</option>
                    <option value="not for sale" <?= $media['art_status']  == 'not for sale' || $media['art_status'] == NULL ? ' selected' : '' ?>>Not for sale</option>
                </select>
            </div>
            <div class="form-group-item">
                <label for="art_price">Price</label>
                <input id="art_price" type="number" name="art_price" value="<?= $media['art_price'] ?>" min=0>
            </div>
            <div class="form-group-item">
                <label for="art_has_frame">Has frame</label>
                <select id="art_has_frame" name="art_has_frame">
                    <option value="0" <?= $media['art_has_frame'] == 0 ? ' selected' : '' ?>>No</option>
                    <option value="1" <?= $media['art_has_frame'] == 1 ? ' selected' : '' ?>>Yes</option>
                </select>
            </div>
            <div class="form-group-item">
                <?php if ($media['art_has_frame'] == 1) : ?>
                    <label for="art_frame_price" id="frame_price_label">Price for frame </label>
                    <input id="art_frame_price" type="number" name="art_frame_price" value="<?= $media['art_frame_price'] ?>" min=0>
                <?php else : ?>
                    <label for="art_frame_price" id="frame_price_label">No frame </label>
                    <input id="art_frame_price" type="number" name="art_frame_price" value="<?= $media['art_frame_price'] ?>" min=0 style="display:none;">

                <?php endif; ?>

            </div>
        </div>

        <label for="media">Media</label>
        <input type="file" name="media" accept="audio/*,video/*,image/*">
        <div id="custom-thumbnail-wrapper" style="display: none;">
            <label for="custom_thumbnail">Thumbnail voor audio/video</label>
            <input type="file" name="custom_thumbnail" id="custom_thumbnail" accept="image/*">
        </div>


        <div id="media-preview">
            <?php if ($page == "Edit") : ?>
                <?php if ($media['type'] == 'image') :
                    // get filesize
                    $filesize = convert_filesize(filesize('../' . $media['filepath']));
                    // dimensions 
                    $dimensions = getimagesize('../' . $media['filepath']);
                    // inspectAndDie($dimensions);
                ?>
                    <div class="">
                        <p>Current image (<?= $filesize ?>)
                            <br>
                            <?= $dimensions[0] . ' x ' . $dimensions[1] ?>
                        </p>
                        <img src="../<?= $media['filepath'] ?>" alt="Media preview">
                    </div>
                <?php elseif ($media['type'] == 'audio') : ?>
                    <audio controls>
                        <source src="../<?= $media['filepath'] ?>" type="audio/ogg">
                        <source src="../<?= $media['filepath'] ?>" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                <?php elseif ($media['type'] == 'video') : ?>
                    <video controls>
                        <source src="../<?= $media['filepath'] ?>" type="video/mp4">
                        <source src="../<?= $media['filepath'] ?>" type="video/ogg">
                        Your browser does not support the video element.
                    </video>
                <?php endif; ?>
                <!-- check if there is a thumbnail and if so show it -->
                <?php if (!empty($media['thumbnail'])) :
                    // get filesize
                    $filesize_thumb = convert_filesize(filesize('../' . $media['thumbnail']));
                ?>
                    <div class="preview-thumb">
                        <p>Current thumbnail (<?= $filesize_thumb ?>)</p>
                        <img src="../<?= $media['thumbnail'] ?>" alt="Thumbnail" width="320" height="180">
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <label for="add_categories">Categories</label>
        <div class="form-group">
            <div class="form-group-item">
                <select name="add_categories" id="add_categories" style="" multiple>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?= $cat['id'] ?>"><?= $cat['title'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button id="add_selected_categories" style="width:min(90%,44ch); background: #99aa22; color: #f0f0f0;">Add</button>
            </div>
            <div class="form-group-item">
                <select name="categories" style="" multiple>
                    <?php foreach ($media['categories'] as $cat) : ?>
                        <option value="<?= $cat['id'] ?>"><?= $cat['title'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button id="remove_selected_categories" style="width:min(90%,44ch);background: #aa4400; color: #f0f0f0;">Remove</button>
            </div>
        </div>
        <input type="hidden" name="categories_list" value="<?= implode(',', array_column($media['categories'], 'id')) ?>">
        <div class="submit-btns">
            <?= ($page == 'Edit') ? ' <input type="submit" name="submit" value="Submit changes"> '
                : '  <input type="submit" name="submit" value="Create new media"> ' ?>
        </div>
    </form>

</div>
<script>
    // character count
    let description = document.querySelector("#description");
    let charCount = document.querySelector("#charCount");
    let factsheetMax = 420;

    // set the initial character count
    if (description.value.length > factsheetMax) {
        charCount.style.color = "orangered";
        charCount.textContent = description.value.length + " (max " + factsheetMax + " characters for factsheet)";
    } else {
        charCount.style.color = "initial";
        charCount.textContent = description.value.length;
    }

    description.addEventListener("input", function() {
        if (description.value.length > factsheetMax) {
            charCount.style.color = "orangered";
            charCount.textContent = description.value.length + " (max " + factsheetMax + " characters for factsheet)";
        } else {
            charCount.style.color = "initial";
            charCount.textContent = description.value.length;
        }
    });



    // frame price label
    let frame_price_label = document.querySelector("#frame_price_label");

    // check if there is a frame and if so show the frame price
    document.querySelector("select[name='art_has_frame']").onchange = function() {
        let framePrice = document.querySelector("input[name='art_frame_price']");
        if (this.value == 1) {
            framePrice.style.display = "block";
            frame_price_label.textContent = "Price for frame";
        } else {
            framePrice.style.display = "none";
            frame_price_label.textContent = "No frame";
        }
    };


    // Add an event listener to the form submission
    document.querySelector('form').addEventListener('submit', function() {
        // Show the loading indicator when the form is submitted
        document.querySelector('.loading-indicator').style.display = 'flex';
    });

    modalBg = document.querySelector('.rj-modal-background');
    modal = document.querySelector('.rj-modal');

    function closeModal() {
        modalBg.style.display = "none";
        modal.style.display = "none";

    }

    document.querySelector("#remove_selected_categories").onclick = function(e) {
        e.preventDefault();
        document.querySelectorAll("select[name='categories'] option").forEach(function(option) {
            if (option.selected) {
                let list = document.querySelector("input[name='categories_list']").value.split(",");
                list.splice(list.indexOf(option.value), 1);
                document.querySelector("input[name='categories_list']").value = list.join(",");
                option.remove();
            }
        });
    };
    document.querySelector("#add_selected_categories").onclick = function(e) {
        e.preventDefault();
        document.querySelectorAll("select[name='add_categories'] option").forEach(function(option) {
            if (option.selected) {
                let list = document.querySelector("input[name='categories_list']").value.split(",");
                if (!list.includes(option.value)) {
                    list.push(option.value);
                }
                // prevent duplicates
                list = list.filter(function(value, index, self) {
                    return self.indexOf(value) === index;
                });
                document.querySelector("input[name='categories_list']").value = list.join(",");
                document.querySelector("select[name='categories']").add(option.cloneNode(true));

            }
        });
    };
    let typeOfpage = document.querySelector("form").getAttribute("data-page");
    // preview media
    document.querySelector("input[name='media']").onchange = function(e) {


        let preview = document.querySelector("#media-preview");
        preview.innerHTML = "";
        let file = e.target.files[0];
        let reader = new FileReader();
        reader.onload = function(e) {
            let media = document.createElement("img");
            media.src = e.target.result;
            preview.appendChild(media);
        };
        reader.readAsDataURL(file);

    };

    function toggleCustomThumbnail() {
        const typeSelect = document.getElementById('type');
        const wrapper = document.getElementById('custom-thumbnail-wrapper');
        const artFields = document.getElementById('art_fields');
        const artSaleFields = document.getElementById('art_sale_fields');
        const selectedType = typeSelect.value;

        if (selectedType === 'audio' || selectedType === 'video') {
            wrapper.style.display = 'block';
            artFields.style.display = 'none';
            artSaleFields.style.display = 'none';

        } else {
            wrapper.style.display = 'none';
            artFields.style.display = 'flex';
            artSaleFields.style.display = 'flex';
        }
    }

    // Bij laden van de pagina
    document.addEventListener('DOMContentLoaded', toggleCustomThumbnail);

    // Bij wijziging van het type
    document.getElementById('type').addEventListener('change', toggleCustomThumbnail);
</script>

<?= template_admin_footer() ?>