<?php
include 'main.php';
// Delete message
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare('DELETE FROM messages WHERE id = ?');
    $stmt->execute([ $_GET['delete'] ]);
    header('Location: messages.php');
    exit;
}
// SQL query that will retrieve all the messages from the database ordered by the ID column
$stmt = $pdo->prepare('SELECT * FROM messages ORDER BY id DESC');
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Messages', 'messages')?>

<h2>Messages</h2>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>From</td>
                    <td>Subject</td>
                    <td class="responsive-hidden">Message</td>
                    <td class="responsive-hidden">Date</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($messages)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no recent messages</td>
                </tr>
                <?php else: ?>
                <?php foreach ($messages as $message): ?>
                <tr class="<?=$message['is_read'] ? 'read' : 'not-read'?>">
                    <td><?=$message['email']?></td>
                    <td><?=mb_strimwidth(nl2br(htmlspecialchars($message['subject'], ENT_QUOTES)), 0, 100, '...')?></td>
                    <td class="responsive-hidden"><?=mb_strimwidth(nl2br(htmlspecialchars($message['msg'], ENT_QUOTES)), 0, 50, '...')?></td>
                    <td class="responsive-hidden"><?=date('F j, Y H:ia', strtotime($message['submit_date']))?></td>
                    <td>
                        <a href="message.php?id=<?=$message['id']?>">View</a>
                        <a href="messages.php?delete=<?=$message['id']?>" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
