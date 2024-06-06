<?php
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: text/html; charset=utf-8');
include 'main.php';
// Default category values
$category = [
    'title' => '',
    'description' => '',
    'is_private' => 0,
    'is_for_sale' => 0
];
if (isset($_GET['id'])) {
    // Retrieve the category from the database
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing category
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the category
        $stmt = $pdo->prepare('UPDATE categories SET title = ?, description = ?, is_private = ?, is_for_Sale = ? WHERE id = ?');
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['is_private'], $_POST['is_for_sale'], $_GET['id']]);
        header('Location: categories.php');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Delete the category
        $stmt = $pdo->prepare('DELETE c, mc FROM categories c LEFT JOIN media_categories mc ON mc.category_id = c.id WHERE c.id = ?');
        $stmt->execute([$_GET['id']]);
        header('Location: categories.php');
        exit;
    }
} else {
    // Create a new category
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO categories (title,description,is_private,is_for_sale) VALUES (?,?,?,?)');
        $stmt->execute([$_POST['title'], $_POST['description'], $_POST['is_private'], $_POST['is_for_sale']]);
        header('Location: categories.php');
        exit;
    }
}
?>
<?= template_admin_header($page . ' Category', 'categories') ?>

<h2><?= $page ?> Category</h2>

<div class="content-block">

    <form action="" method="post" class="form responsive-width-100">

        <label for="title">Title</label>

        <input id="title" type="text" name="title" placeholder="Title" value="<?= $category['title'] ?>" required>

        <label for="description">Description</label>
        <textarea name="description" id="description" placeholder="Description ..."><?= $category['description'] ?></textarea>

        <label for="is_private">Private Category?</label>
        <select name="is_private" id="is_private">
            <option value="0" <?= $category['is_private'] == 0 ? ' selected' : '' ?>>No</option>
            <option value="1" <?= $category['is_private'] == 1 ? ' selected' : '' ?>>Yes</option>
        </select>
        <label for="is_private">Azorean Art sale category?</label>
        <select name="is_for_sale" id="is_private">
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