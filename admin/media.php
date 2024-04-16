<?php
include 'main.php';

include 'generate_thumbnail.php';

// check for refresh get
// $dt = time
// $refresh = $_GET['refresh'] ??
// Disable the default upload file size limits
ini_set('post_max_size', '0');
ini_set('upload_max_filesize', '0');
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


$params_to_check = ['viewCat', 'term', 'show', 'from', 'order_by', 'order_sort'];

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
        //! Handle media upload
        $media_id = md5(uniqid());
        if (isset($_FILES['media']) && !empty($_FILES['media']['tmp_name'])) {
            $media_type = '';
            $media_type = preg_match('/image\/*/', $_FILES['media']['type']) ? 'image' : $media_type;
            $media_type = preg_match('/audio\/*/', $_FILES['media']['type']) ? 'audio' : $media_type;
            $media_type = preg_match('/video\/*/', $_FILES['media']['type']) ? 'video' : $media_type;
            $media_parts = explode('.', $_FILES['media']['name']);
            $media_path = 'media/' . $media_type . 's/' . $media_id . '.' . end($media_parts);
            move_uploaded_file($_FILES['media']['tmp_name'], '../' . $media_path);
            if ($media_type == 'image') {
                $thumb = generateThumb('../' . $media_path);
            }
        }
    }


    // if (isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['tmp_name'])) {
    //     // Handle thumbnail upload
    //     $thumbnail_path = $media['thumbnail'];
    //     if (isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['tmp_name'])) {
    //         $thumbnail_parts = explode('.', $_FILES['thumbnail']['name']);
    //         $thumbnail_path = 'media/thumbnails/' . $media_id . '.' . end($thumbnail_parts);
    //         move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../' . $thumbnail_path);
    //     }
    // }


    if (isset($_GET['id'])) {

        // Update the media

        $stmt = $pdo->prepare('UPDATE media SET title = ?, description = ?, year = ? , fnr = ? , filepath = ?, type = ?,  approved = ?, art_material = ?, art_dimensions = ?, art_type = ?, art_status = ?, art_price = ?, thumbnail = ? WHERE id = ?');
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['year'], $_POST['fnr'], $media_path, $_POST['type'], $_POST['approved'], $_POST['art_material'], $_POST['art_dimensions'], $_POST['art_type'], $_POST['art_status'], $_POST['art_price'], $thumb, $_GET['id']]);
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
            <a href="../' . $media_path . '" target="_blank" class="rj-modal-btn">Preview media</a>
            </p>
            <p>
            <a href="' . $gotoPage . http_build_query($redirect_params) . '" class="rj-modal-btn">Go back to media page</a>
            </p>
            <p>
            <a href="#" onclick="closeModal()" class="rj-modal-btn">Close and stay on this page</a>
            </p>
        </div>
        
        ';

        // header('Location: allmedia.php');
        // exit;

        if (isset($_POST['delete'])) {
            // Redirect and delete the media
            header('Location: allmedia.php?delete=' . $_GET['id']);
            exit;
        }
    } else {
        // Create new media
        $page = 'Create';

        // add art_ fields 
        $stmt = $pdo->prepare('INSERT INTO media (title, description, year, fnr, filepath, type, approved, art_material, art_dimensions, art_type, art_status, art_price,thumbnail) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
        // inspectAndDie($stmt);
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['year'], $_POST['fnr'], $media_path, $_POST['type'], $_POST['approved'], $_POST['art_material'], $_POST['art_dimensions'], $_POST['art_type'], $_POST['art_status'], $_POST['art_price'], $thumb]);
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
            <a href="../' . $media_path . '" target="_blank" class="rj-modal-btn">Preview media</a>
            </p>
            <p>
            <a href="' . $gotoPage . http_build_query($redirect_params) . '" class="rj-modal-btn">Go back to media page</a>
            </p>
            <p>
            <a href="#" onclick="closeModal()" class="rj-modal-btn">Add another Media file</a>
            </p>
        </div>
        
        ';

        // header('Location: allmedia.php');
        // exit;

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

        <label for="description">Description</label>
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
                <input id="fnr" type="number" name="fnr" value="<?= $media['fnr'] ?>" min=0 max=99>
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
        <div class="form-group">
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
            <div class="form-group-item">
                <label for="art_status">Status</label>
                <select id="art_status" name="art_status" required>
                    <option value="available" <?= $media['art_status'] == 'available' ? ' selected' : '' ?>>Available for sale</option>
                    <option value="reserved" <?= $media['art_status'] == 'reserved' ? ' selected' : '' ?>>Reserved</option>
                    <option value="sold" <?= $media['art_status'] == 'sold' ? ' selected' : '' ?>>Sold</option>
                    <option value="not for sale" <?= $media['art_status'] == 'not for sale' ? ' selected' : '' ?>>Not for sale</option>
                </select>
            </div>
            <div class="form-group-item">
                <label for="art_price">Price</label>
                <input id="art_price" type="number" name="art_price" value="<?= $media['art_price'] ?>" min=0>
            </div>
        </div>

        <label for="media">Media</label>
        <input type="file" name="media" accept="audio/*,video/*,image/*">
        <div id="media-preview">
            <?php if ($page == "Edit") : ?>
                <?php if ($media['type'] == 'image') : ?>
                    <img src="../<?= $media['filepath'] ?>" alt="Media preview">
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

        <br>

        <div class="submit-btns">
            <?= ($page == 'Edit') ? ' <input type="submit" name="submit" value="Submit changes"> '
                : '  <input type="submit" name="submit" value="Create new media"> ' ?>
        </div>

    </form>

</div>
<script>
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
</script>

<?= template_admin_footer() ?>