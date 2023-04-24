<?php

include 'main.php';


if (isset($_POST['mediaId']) && isset($_POST['imageDataUrl'])) {
    $mediaId = (int)$_POST['mediaId'];
    $imageDataUrl = $_POST['imageDataUrl'];

    // Decode the base64 image data
    $imageData = base64_decode(substr($imageDataUrl, strpos($imageDataUrl, ',') + 1));

    // Set a filename for the generated image
    $imageName = date('Y-m-d') . '-' . $mediaId . '-qr-card.png';
    $imagePath = '../media/qrcards/' . $imageName;

    // Save the image to the server
    file_put_contents($imagePath, $imageData);

    // Update the media record in the database with the new image URL
    $stmt = $pdo->prepare("UPDATE media SET qr_card_url = ? WHERE id = ?");
    $stmt->bindParam("si", $imageName, $mediaId);
    $stmt->execute();


    echo json_encode(['success' => true]);  
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>
