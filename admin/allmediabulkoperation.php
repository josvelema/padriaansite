<?php
include 'main.php';

// Delete the selected media
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([$_GET['delete']]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($media['thumbnail']) {
        unlink('../' . $media['thumbnail']);
    }
    unlink('../' . $media['filepath']);
    $stmt = $pdo->prepare('DELETE m, mc FROM media m LEFT JOIN media_categories mc ON mc.media_id = m.id WHERE m.id = ?');
    $stmt->execute([$_GET['delete']]);
    header('Location: allmedia.php');
    exit;
}
// Approve the selected media
if (isset($_GET['approve'])) {
    $stmt = $pdo->prepare('UPDATE media SET approved = 1 WHERE id = ?');
    $stmt->execute([$_GET['approve']]);
    header('Location: allmedia.php');
    exit;
}
// SQL query that will retrieve all the media from the database ordered by the ID column

if (isset($_POST['viewCat'])) {
    // if ($_POST['viewCat'] !== 0) {
    $stmt = $pdo->prepare('SELECT title FROM categories WHERE id = ? ');
    $stmt->bindParam(1, $_POST['viewCat'], PDO::PARAM_INT);
    $stmt->execute();
    $catTitle = $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT * FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = ? ORDER BY m.id DESC ');
    // JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = :category' : 
    $stmt->bindParam(1, $_POST['viewCat'], PDO::PARAM_INT);
    $stmt->execute();
    // var_dump("<pre>",$stmt,"</pre>");
    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $countMedia = $stmt->rowCount();
    // }
} else {

    $stmt = $pdo->prepare('SELECT * FROM media ORDER BY id DESC');
    $stmt->execute();
    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $countMedia = $stmt->rowCount();
}

?>


<?= template_admin_header('All Media', 'allmedia') ?>

<div class="links">
    <a href="media.php">Create Media</a>
</div>
<div class="rj-form-admin">
    <form action="" method='post'>
        <?php
        $stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id DESC');
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <select class="form-control" name="viewCat" id="">
            <option value=0>Select Category</option>
            <?php foreach ($categories as $category) : ?>
                <option value=<?= $category['id'] ?>><?= $category['title'] ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" name="submit" class="btn btn-success" value="View Category">
        <a href="allmedia.php" class="btn btn-primary">View All Media</a>
    </form>
</div>

<h2>Viewing
    <?php
    if (isset($catTitle)) {
        echo " " . $catTitle . " - " . $countMedia . " media files.";
    } else {
        echo " all media." . " - " . $countMedia . " media files.";
    }
    ?>
</h2>


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
                <?php if (empty($media)) : ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">There are no recent media files</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($media as $m) : ?>
                        <tr>
                            <td><?= htmlspecialchars($m['title'], ENT_QUOTES) ?></td>
                            <td><?= $m['year'] ?></td>
                            <td><?= $m['fnr'] ?></td>

                            <td class="responsive-hidden"><?= nl2br(htmlspecialchars($m['description'], ENT_QUOTES)) ?></td>
                            <td><a href="../<?= $m['filepath'] ?>" target="_blank">View</a></td>
                            <td class="responsive-hidden"><?= $m['type'] ?></td>
                            <td><?= $m['approved'] ? 'Yes' : 'No' ?></td>
                            <td class="responsive-hidden"><?= date('F j, Y H:ia', strtotime($m['uploaded_date'])) ?></td>
                            <td class="rj-action-td">
                                <a href="media.php?id=<?= $m['id'] ?>" class="rj-action-edit">Edit</a>
                                <a href="#" class="rj-action-del" onclick="deleteMediaModal(<?= $m['id'] ?>)">Delete</a>
                                <?php if (!$m['approved']) : ?>
                                    <a href="allmedia.php?approve=<?= $m['id'] ?>">Approve</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="delMediaModalWrap"></div>

<script>
    delMediaModalWrap = document.querySelector('.delMediaModalWrap');

    let delModalMediaContent = `<label for="rj-modal" class="rj-modal-background"></label>
          <div class="rj-modal">
          <div class="modal-header">
          <h3>Confirm deletion</h3>
            <label for="rj-modal">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAdVBMVEUAAABNTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU0N3NIOAAAAJnRSTlMAAQIDBAUGBwgRFRYZGiEjQ3l7hYaqtLm8vsDFx87a4uvv8fP1+bbY9ZEAAAB8SURBVBhXXY5LFoJAAMOCIP4VBRXEv5j7H9HFDOizu2TRFljedgCQHeocWHVaAWStXnKyl2oVWI+kd1XLvFV1D7Ng3qrWKYMZ+MdEhk3gbhw59KvlH0eTnf2mgiRwvQ7NW6aqNmncukKhnvo/zzlQ2PR/HgsAJkncH6XwAcr0FUY5BVeFAAAAAElFTkSuQmCC" width="16" height="16" alt="" onclick="closeDelModal()">
            </label>
            </div>
            <p>
            Delete Media?<br>
            `;


    function deleteMediaModal(id) {
        let media_id = id;
        let link = `<a href="allmedia.php?delete=` + media_id + `" class="rj-modal-btn">Confirm</a><br><a href="allmedia.php" onClick="closeDelMediaModal()" class="rj-modal-btn">Cancel</a> </p></div>`
        document.querySelector(".delMediaModalWrap").innerHTML = delModalMediaContent + link;
    }


    function closeDelMediaModal() {
        delModal.style.display = "none";

    }
</script>



<?= template_admin_footer() ?>