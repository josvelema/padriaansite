<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: text/html; charset=utf-8');
include 'main.php';

// Default category values
$category = [
    'title' => '',
    'description' => '',
    'media_type' => 0,
    'cat_image' => '',
    'is_private' => 0,
    'is_for_sale' => 0
];

function uploadCategoryImage($file)
{
    $uploadDir = 'media/categories/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    if ($file['error'] === UPLOAD_ERR_OK && is_uploaded_file($file['tmp_name'])) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $safeName = uniqid('cat_', true) . '.' . $ext;
        $targetPath = $uploadDir . $safeName;
        $fullPath = "../" . $targetPath;
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return $targetPath;
        }
    }
    return null;
}

if (isset($_GET['id'])) {
    // Retrieve the category from the database
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    $page = 'Edit';

    if (isset($_POST['submit'])) {
        $cat_image_path = $category['cat_image']; // Default to existing image

        // Upload new image if provided
        if (isset($_FILES['cat_image']) && $_FILES['cat_image']['error'] === UPLOAD_ERR_OK) {
            $uploaded = uploadCategoryImage($_FILES['cat_image']);
            if ($uploaded) {
                $cat_image_path = $uploaded;
            }
        }

        $stmt = $pdo->prepare('UPDATE categories SET title = ?, description = ?, media_type = ?, cat_image = ?, is_private = ?, is_for_sale = ? WHERE id = ?');
        $stmt->execute([
            $_POST['title'],
            $_POST['description'],
            $_POST['media_type'],
            $cat_image_path,
            $_POST['is_private'],
            $_POST['is_for_sale'],
            $_GET['id']
        ]);
        header('Location: categories.php');
        exit;
    }

    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare('DELETE c, mc FROM categories c LEFT JOIN media_categories mc ON mc.category_id = c.id WHERE c.id = ?');
        $stmt->execute([$_GET['id']]);
        header('Location: categories.php');
        exit;
    }
} else {
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $cat_image_path = '';
        if (isset($_FILES['cat_image']) && $_FILES['cat_image']['error'] === UPLOAD_ERR_OK) {
            $uploaded = uploadCategoryImage($_FILES['cat_image']);
            if ($uploaded) {
                $cat_image_path = $uploaded;
            }
        }

        $stmt = $pdo->prepare('INSERT INTO categories (title, description, media_type, cat_image, is_private, is_for_sale) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $_POST['title'],
            $_POST['description'],
            $_POST['media_type'],
            $cat_image_path,
            $_POST['is_private'],
            $_POST['is_for_sale']
        ]);
        header('Location: categories.php');
        exit;
    }
}
?>
<?= template_admin_header($page . ' Category', 'categories') ?>

<h2><?= $page ?> Category</h2>

<div class="content-block">

    <form action="" method="post" enctype="multipart/form-data" class="form responsive-width-100">
        <label for="title">Title</label>
        <input id="title" type="text" name="title" placeholder="Title" value="<?= htmlspecialchars($category['title']) ?>" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" placeholder="Description ..."><?= htmlspecialchars($category['description']) ?></textarea>

        <label for="image">Category (cover) image</label>
        <input type="file" name="cat_image" id="image" accept="image/*">

        <?php if (!empty($category['cat_image'])) : ?>
            <div style="margin: 10px 0;">
                <img src="../<?= htmlspecialchars($category['cat_image']) ?>" alt="Current Image" style="max-width: 200px;">
            </div>
        <?php endif; ?>

        <label for="media_type">Media type in category</label>
        <select name="media_type" id="media_type">
            <option value="0" <?= $category['media_type'] == 0 ? ' selected' : '' ?>>Image</option>
            <option value="1" <?= $category['media_type'] == 1 ? ' selected' : '' ?>>Audio</option>
            <option value="2" <?= $category['media_type'] == 2 ? ' selected' : '' ?>>Video</option>
        </select>

        <label for="is_private">Private Category?</label>
        <select name="is_private" id="is_private">
            <option value="0" <?= $category['is_private'] == 0 ? ' selected' : '' ?>>No</option>
            <option value="1" <?= $category['is_private'] == 1 ? ' selected' : '' ?>>Yes</option>
        </select>

        <label for="is_for_sale">Azorean Art sale category?</label>
        <select name="is_for_sale" id="is_for_sale">
            <option value="0" <?= $category['is_for_sale'] == 0 ? ' selected' : '' ?>>No</option>
            <option value="1" <?= $category['is_for_sale'] == 1 ? ' selected' : '' ?>>Yes</option>
        </select>

        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit') : ?>
                <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>

</div>

<?= template_admin_footer() ?>