<?php
include 'main.php';
// Retrieve the message based on the ID
$stmt = $pdo->prepare('SELECT * FROM messages WHERE id = ?');
$stmt->execute([ $_GET['id'] ]);
$message = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$message) {
    exit('Invalid ID!');
}
if (isset($_GET['unread'])) {
    // Mark as unread
    $stmt = $pdo->prepare('UPDATE messages SET is_read = 0 WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    header('Location: messages.php');
    exit;
}
// Mark as read
$stmt = $pdo->prepare('UPDATE messages SET is_read = 1 WHERE id = ?');
$stmt->execute([ $_GET['id'] ]);
// Convert the JSON string to assoc array
$extra = json_decode($message['extra'], true);
?>
<?=template_admin_header(htmlspecialchars($message['subject'], ENT_QUOTES), 'messages')?>

<h2><?=htmlspecialchars($message['subject'], ENT_QUOTES)?></h2>
<p>By <strong><?=$message['email']?></strong> on <?=date('F j, Y H:ia', strtotime($message['submit_date']))?></p>

<div class="content-block">
    <div class="message"><?=nl2br(htmlspecialchars($message['msg'], ENT_QUOTES))?></div>
    <div class="extras">
        <?php foreach($extra as $k => $v): ?>
        <div class="extra">
            <h3><?=htmlspecialchars(ucwords(str_replace('_', ' ', $k)), ENT_QUOTES)?></h3>
            <?php if (preg_match('[.jpg|.png|.webp|.gif|.bmp|.jpeg|.tif]', strtolower($v))): ?>
            <?php foreach(explode(',', rtrim($v, ',')) as $img): ?>
            <img src="../<?=htmlspecialchars($img, ENT_QUOTES)?>" width="32" height="32">
            <?php endforeach; ?>
            <?php else: ?>
            <p><?=htmlspecialchars($v, ENT_QUOTES)?></p>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="links">
    <a href="mailto:<?=$message['email']?>?subject=<?=urlencode($message['subject'])?>">Reply</a>
    <a href="message.php?id=<?=$message['id']?>&unread=true">Mark as Unread</a>
    <a class="alt" href="messages.php?delete=<?=$message['id']?>">Delete</a>
</div>

<?=template_admin_footer()?>