<?php
include 'main.php';
// SQL query that will retrieve all the categories from the database ordered by the ID column
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id DESC');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Categories', 'categories')?>

<h2>Categories</h2>

<div class="links">
    <a href="category.php">Create Category</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Title</td>
                    <td>Description</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="4" style="text-align:center;">There are no categories</td>
                </tr>
                <?php else: ?>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?=$category['id']?></td>
                    <td><?=$category['title']?></td>
                    <td><?=$category['description']?></td>
                    <td><a href="category.php?id=<?=$category['id']?>" class="rj-action-edit">Edit</a></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
