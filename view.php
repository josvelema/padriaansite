<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
if (isset($_GET['id'])) {
    // Retrieve the media from the media table using the GET request ID (URL param)
    $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$media) {
        exit('Media does not exist with this ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?=template_header($media['title'])?>
<?= template_nav() ?>


<div class="content view">

	<h2><?=$media['title']?></h2>

	<p><?=$media['description']?></p>

    <?php if ($media['type'] == 'image'): ?>
    <img src="<?=$media['filepath']?>" alt="<?=$media['description']?>">
    <?php elseif ($media['type'] == 'video'): ?>
    <video src="<?=$media['filepath']?>" width="852" height="480" controls autoplay></video>
    <?php elseif ($media['type'] == 'audio'): ?>
    <audio src="<?=$media['filepath']?>" controls autoplay></audio>
    <?php endif; ?>

</div>

<?=template_footer()?>
