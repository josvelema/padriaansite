<?php
include 'main.php';
// Delete the selected media
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([ $_GET['delete'] ]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($media['thumbnail']) {
        unlink('../' . $media['thumbnail']);
    }
    unlink('../' . $media['filepath']);
    $stmt = $pdo->prepare('DELETE m, mc FROM media m LEFT JOIN media_categories mc ON mc.media_id = m.id WHERE m.id = ?');
    $stmt->execute([ $_GET['delete'] ]);
    header('Location: allmedia.php');
    exit;
}
// Approve the selected media
if (isset($_GET['approve'])) {
    $stmt = $pdo->prepare('UPDATE media SET approved = 1 WHERE id = ?');
    $stmt->execute([ $_GET['approve'] ]);
    header('Location: allmedia.php');
    exit;
}
// SQL query that will retrieve all the media from the database ordered by the ID column
$stmt = $pdo->prepare('SELECT * FROM media ORDER BY id DESC');
$stmt->execute();
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('All Media', 'allmedia')?>

<h2>All Media</h2>

<div class="links">
    <a href="media.php">Create Media</a>
</div>

<div class="content-block">
    <div class="table">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th class="responsive-hidden">Year</th>
                    <th class="responsive-hidden">fnr</th>
                    <th class="responsive-hidden">Description</th>
                    <th>Media</th>
                    <th class="responsive-hidden">Type</th>
                    <th>Approved</th>
                    <th class="responsive-hidden">Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($media)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no recent media files</td>
                </tr>
                <?php else: ?>
                <?php foreach ($media as $m): ?>
                <tr>
                    <td><?=htmlspecialchars($m['title'], ENT_QUOTES)?></td>
                    <td><?= $m['year'] ?></td>
                    <td><?= $m['fnr'] ?></td>

                    <td class="responsive-hidden"><?=nl2br(htmlspecialchars($m['description'], ENT_QUOTES))?></td>
                    <td><a href="../<?=$m['filepath']?>" target="_blank">View</a></td>
                    <td class="responsive-hidden"><?=$m['type']?></td>
                    <td><?=$m['approved']?'Yes':'No'?></td>
                    <td class="responsive-hidden"><?=date('F j, Y H:ia', strtotime($m['uploaded_date']))?></td>
                    <td>
                        <a href="media.php?id=<?=$m['id']?>" class="rj-action-edit">Edit</a>
                        <a href="allmedia.php?delete=<?=$m['id']?>" class="rj-action-del">Delete</a>
                        <?php if (!$m['approved']): ?>
                        <a href="allmedia.php?approve=<?=$m['id']?>">Approve</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>