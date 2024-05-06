<?php
include 'main.php';
// Get the directory size
function dirSize($directory) {
    $size = 0;
    foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file){
        $size+=$file->getSize();
    }
    return $size;
}
// Retrieve all media uploaded on the current day
$stmt = $pdo->prepare('SELECT * FROM media WHERE cast(uploaded_date as DATE) = cast(now() as DATE) ORDER BY uploaded_date DESC');
$stmt->execute();
$media = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of media
$stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM media');
$stmt->execute();
$media_total = $stmt->fetchColumn();
// Media awaiting approval
$stmt = $pdo->prepare('SELECT * FROM media WHERE approved = 0 ORDER BY uploaded_date DESC');
$stmt->execute();
$media_awaiting_approval = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Dashboard', 'dashboard')?>

<h2>Dashboard</h2>

<div class="dashboard">
    <div class="content-block stat">    
        <div>
            <h3>Today's Media</h3>
            <p><?=number_format(count($media))?></p>
        </div>
        <i class="fas fa-image"></i>
    </div>

    <div class="content-block stat">
        <div>
            <h3>Awaiting Approval</h3>
            <p><?=number_format(count($media_awaiting_approval))?></p>
        </div>
        <i class="fas fa-clock"></i>
    </div>

    <div class="content-block stat">
        <div>
            <h3>Total Media</h3>
            <p><?=number_format($media_total)?></p>
        </div>
        <i class="fas fa-comments"></i>
    </div>

    <div class="content-block stat">
        <div>
            <h3>Total Size</h3>
            <p><?=convert_filesize(dirSize('../media'))?></p>
        </div>
        <i class="fas fa-file-alt"></i>
    </div>
</div>

<h2>Today's Media</h2>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>Title</td>
                    <td class="responsive-hidden">Description</td>
                    <td>Media</td>
                    <td class="responsive-hidden">Type</td>
                    <td>Approved</td>
                    <td class="responsive-hidden">Date</td>
                    <td>Actions</td>
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
                    <td class="responsive-hidden"><?=nl2br(htmlspecialchars($m['description'], ENT_QUOTES))?></td>
                    <td><a href="../<?=$m['filepath']?>" target="_blank">View</a></td>
                    <td class="responsive-hidden"><?=$m['type']?></td>
                    <td><?=$m['approved']?'Yes':'No'?></td>
                    <td class="responsive-hidden"><?=date('F j, Y H:ia', strtotime($m['uploaded_date']))?></td>
                    <td>
                        <a href="media.php?id=<?=$m['id']?>">Edit</a>
                        <a href="allmedia.php?delete=<?=$m['id']?>">Delete</a>
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

<h2 style="margin-top:40px">Awaiting Approval</h2>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>Title</td>
                    <td class="responsive-hidden">Description</td>
                    <td>Media</td>
                    <td class="responsive-hidden">Type</td>
                    <td>Approved</td>
                    <td class="responsive-hidden">Date</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($media_awaiting_approval)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no media files awaiting approval</td>
                </tr>
                <?php else: ?>
                <?php foreach ($media_awaiting_approval as $m): ?>
                <tr>
                    <td><?=htmlspecialchars($m['title'], ENT_QUOTES)?></td>
                    <td class="responsive-hidden"><?=nl2br(htmlspecialchars($m['description'], ENT_QUOTES))?></td>
                    <td><a href="../<?=$m['filepath']?>" target="_blank">View</a></td>
                    <td class="responsive-hidden"><?=$m['type']?></td>
                    <td><?=$m['approved']?'Yes':'No'?></td>
                    <td class="responsive-hidden"><?=date('F j, Y H:ia', strtotime($m['uploaded_date']))?></td>
                    <td>
                        <a href="media.php?id=<?=$m['id']?>">Edit</a>
                        <a href="allmedia.php?delete=<?=$m['id']?>">Delete</a>
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
