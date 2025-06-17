<?php
include 'main.php';
// SQL query that will retrieve all the categories from the database ordered by the ID column
$stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id DESC');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?= template_admin_header('Categories', 'categories') ?>

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
                    <th>Media type</th>
                    <th>Cover image</th>
                    <th>QR</th>
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
                            <td><?= $category['id'] ?></td>
                            <td><?= $category['title'] ?></td>
                            <td>
                                <!-- if Description has more than 200 crop  -->
                                <!-- if Description has more than 200 crop -->
                                <?php
                                $desc = htmlspecialchars($category['description'], ENT_QUOTES, 'UTF-8');
                                echo strlen($desc) > 200 ? substr($desc, 0, 200) . '...' : $desc;
                                ?>

                            </td>
                            <td>
                                <?php if ($category['media_type'] == 0): ?>
                                    Image
                                <?php elseif ($category['media_type'] == 1): ?>
                                    Audio
                                <?php elseif ($category['media_type'] == 2): ?>
                                    Video
                                <?php else: ?>
                                    Other
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($category['cat_image'])): ?>
                                    <img src="../<?= $category['cat_image'] ?>" alt="Category Image" style="max-width: 100px; max-height: 100px;">
                                <?php else: ?>
                                    No image
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($category['qr_url'])): ?>
                                    <a href="<?= $category['qr_url'] ?>" download>View QR</a>
                                <?php else: ?>
                                    <button onclick="generateCategoryQR(<?= $category['id'] ?>)">Generate QR</button>

                                <?php endif; ?>
                            </td>
                            <td><?= $category['is_private']  ? 'Yes' : 'No' ?></td>
                            <td><?= $category['is_for_sale']  ? 'Yes' : 'No' ?></td>
                            <td><a href="category.php?id=<?= $category['id'] ?>" class="rj-action-edit">Edit</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="qr-progress-modal" class="modal">
    <div class="modal-content">
        <h4 id="qr-progress-title">QR Code Generation</h4>
        <p id="qr-progress-message"></p>
    </div>
</div>


<script src="js/multimedia.js"></script>
<?= template_admin_footer() ?>