<?php
include 'main.php';
// disable the code execution time limit
set_time_limit(0);
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
    'thumbnail' => '',
    'approved' => 1,
    'categories' => []
];
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
if (isset($_GET['id'])) {
    // Retrieve the media from the database
    $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    // Retrieve the categories
    $stmt = $pdo->prepare('SELECT c.title, c.id FROM media_categories mc JOIN categories c ON c.id = mc.category_id WHERE mc.media_id = ?');
    $stmt->execute([$_GET['id']]);
    $media['categories'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
//! Handle media upload
$media_id = md5(uniqid());
$media_path = $media['filepath'];
if (isset($_FILES['media']) && !empty($_FILES['media']['tmp_name'])) {
    $media_type = '';
    $media_type = preg_match('/image\/*/', $_FILES['media']['type']) ? 'image' : $media_type;
    $media_type = preg_match('/audio\/*/', $_FILES['media']['type']) ? 'audio' : $media_type;
    $media_type = preg_match('/video\/*/', $_FILES['media']['type']) ? 'video' : $media_type;
    $media_parts = explode('.', $_FILES['media']['name']);
    $media_path = 'media/' . $media_type . 's/' . $media_id . '.' . end($media_parts);
    move_uploaded_file($_FILES['media']['tmp_name'], '../' . $media_path);
}
// Handle thumbnail upload

$thumbnail_path = $media['thumbnail'];


if (isset($_FILES['thumbnail']) && !empty($_FILES['thumbnail']['tmp_name'])) {
    $thumbnail_parts = explode('.', $_FILES['thumbnail']['name']);
    $thumbnail_path = 'media/thumbnails/' . $media_id . '.' . end($thumbnail_parts);
    move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../' . $thumbnail_path);
}
if (isset($_GET['id'])) {
    // ID param exists, edit an existing media
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        //todo Update the media
        $stmt = $pdo->prepare('UPDATE media SET title = ?, description = ?, year = ? , fnr = ? , filepath = ?, type = ?, thumbnail = ?, approved = ? WHERE id = ?');
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['year'], $_POST['fnr'], $media_path, $_POST['type'], $thumbnail_path, $_POST['approved'], $_GET['id']]);
        addCategories($pdo, $_GET['id']);

        // Show the loading spinner and progress bar during media editing
        echo '<div class="loading-spinner">Updating Media...</div>';
        echo '<div class="progress-bar-container"><div class="progress-bar"></div></div>';

        // Redirect to allmedia.php after 3 seconds
        echo '<script>
                setTimeout(function() { 
                    window.location.href = "allmedia.php"; 
                }, 3000);
              </script>';

        exit;
    }
    if (isset($_POST['delete'])) {
        // Redirect and delete the media
        header('Location: allmedia.php?delete=' . $_GET['id']);
        exit;
    }
} else {
    // Create new media
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO media (title,description,year,fnr,filepath,type,thumbnail,approved) VALUES (?,?,?,?,?,?,?,?)');
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['year'], $_POST['fnr'], $media_path, $_POST['type'], $thumbnail_path, $_POST['approved']]);
        $title = str_replace(" ", "-", $_POST['title']);
        $media_id = $pdo->lastInsertId();
        // . "-" . $_POST['year'] . "-" . $_POST['fnr'] . "-" . $title;
        addCategories($pdo, $media_id);

        // Show the loading spinner and progress bar during media creation
        echo '<div class="loading-spinner">Creating Media...</div>';
        echo '<div class="progress-bar-container"><div class="progress-bar"></div></div>';

        // Redirect to allmedia.php after 3 seconds
        echo '<script>
         setTimeout(function() { 
             window.location.href = "allmedia.php"; 
         }, 3000);
       </script>';

        exit;
    }
}
?>
<?= template_admin_header($page . ' Media', 'allmedia') ?>

<h2><?= $page ?> Media</h2>

<div class="content-block">

    <form action="" method="post" class="form responsive-width-100" enctype="multipart/form-data">
    <div class="loading-spinner">Creating Media...</div>
        <div class="progress-bar-container"><div class="progress-bar"></div></div>


        <label for="title">Title</label>
        <input id="title" type="text" name="title" placeholder="Title" value="<?= htmlspecialchars($media['title'], ENT_QUOTES) ?>" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" placeholder="Description ..."><?= htmlspecialchars($media['description'], ENT_QUOTES) ?></textarea>

        <!-- <label for="uploaded_date">Uploaded Date</label>
        <input id="uploaded_date" type="datetime-local" name="uploaded_date" value="<?= date('Y-m-d\TH:i:s', strtotime($media['uploaded_date'])) ?>" required> -->

        <label for="year">Year of production</label>
        <input id="year" type="number" name="year" value="<?= $media['year'] ?>" min=1900 max=2058>

        <label for="fnr">Follow-up number (if none default is 0)</label>
        <input id="fnr" type="number" name="fnr" value="<?= $media['fnr'] ?>" min=0 max=99>


        <label for="type">Type</label>
        <select id="type" name="type" required>
            <option value="image" <?= $media['type'] == 'image' ? ' selected' : '' ?>>Image</option>
            <option value="audio" <?= $media['type'] == 'audio' ? ' selected' : '' ?>>Audio</option>
            <option value="video" <?= $media['type'] == 'video' ? ' selected' : '' ?>>Video</option>
        </select>

        <label for="approved">Approved</label>
        <select id="approved" name="approved" required>
            <option value="0" <?= $media['approved'] == 0 ? ' selected' : '' ?>>No</option>
            <option value="1" <?= $media['approved'] == 1 ? ' selected' : '' ?>>Yes</option>
        </select>
        <img src="../<?= $media['filepath'] ?>" alt="<?= $media['title'] ?>" style="display: block; width: min(100%, 200px);">
        <label for="media">Media</label>
        <input type="file" name="media" accept="audio/*,video/*,image/*">

        <label for="thumbnail">Thumbnail</label>
        <input type="file" name="thumbnail" accept="image/*">

        <label for="add_categories">Categories</label>
        <div style="display:flex;flex-flow:wrap;">
            <select name="add_categories" id="add_categories" style="width:50%;" multiple>
                <?php foreach ($categories as $cat) : ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['title'] ?></option>
                <?php endforeach; ?>
            </select>
            <select name="categories" style="width:50%;" multiple>
                <?php foreach ($media['categories'] as $cat) : ?>
                    <option value="<?= $cat['id'] ?>"><?= $cat['title'] ?></option>
                <?php endforeach; ?>
            </select>
            <button id="add_selected_categories" style="width:50%;;background-color: #33aa22; color: #f0f0f0;">Add</button>
            <button id="remove_selected_categories" style="width:50%;;background-color: #aa5622;color: #f0f0f0;">Remove</button>
            <input type="hidden" name="categories_list" value="<?= implode(',', array_column($media['categories'], 'id')) ?>">
        </div>

        <br>

        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit') : ?>
                <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>

    </form>

</div>
<script>
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
                document.querySelector("input[name='categories_list']").value = list.join(",");
                document.querySelector("select[name='categories']").add(option.cloneNode(true));
            }
        });
    };
</script>
<script>
    // Function to display the loading spinner during file upload
    function showLoadingSpinner() {
        const loadingSpinner = document.querySelector('.loading-spinner');
        loadingSpinner.style.display = 'block';
    }

    // Function to hide the loading spinner after file upload completion
    function hideLoadingSpinner() {
        const loadingSpinner = document.querySelector('.loading-spinner');
        loadingSpinner.style.display = 'none';
    }

    // Function to update the progress bar during file upload
    function updateProgressBar(progress) {
        const progressBar = document.querySelector('.progress-bar');
        progressBar.style.width = progress + '%';
    }


    // Show the loading spinner during media creation/editing
    showLoadingSpinner();
</script>

<?= template_admin_footer() ?>