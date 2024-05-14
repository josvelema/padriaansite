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
    <div class="jostable">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Private</th>
                    <th>Sale <br> category</th>
                    <th>Actions</th>
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
                    <td><?=$category['is_private']  ? 'Yes' : 'No'?></td>
                    <td><?=$category['is_for_sale']  ? 'Yes' : 'No'?></td>
                    <td><a href="category.php?id=<?=$category['id']?>" class="rj-action-edit">Edit</a></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
