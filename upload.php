<?php
include 'functions.php';
// Remove the maximum script execution time as uploading video files will take some time...
set_time_limit(0);
// The output message
$msg = '';
// Check if user has uploaded new media file

//todo add year followupnr 
if (isset($_FILES['media'], $_POST['title'], $_POST['description'])) {
	// Check if uploaded media exists
	if (empty($_FILES['media']['tmp_name'])) {
		exit('Please select a media file!');
	}
	// Media file type (image/audio/video)
	$type = '';
	$type = preg_match('/image\/*/',$_FILES['media']['type']) ? 'image' : $type;
	$type = preg_match('/audio\/*/',$_FILES['media']['type']) ? 'audio' : $type;
	$type = preg_match('/video\/*/',$_FILES['media']['type']) ? 'video' : $type;
	// The directory where media files will be stored
	$target_dir = 'media/' . $type . 's/';

	// Unique media ID

	//todo change name convention to year-followupnr-title(description)-uni . rand() 
	$media_id = md5(uniqid());
	// Media parts (name, extension)
	$media_parts = explode('.', $_FILES['media']['name']);
	// The path of the new uploaded media file
	$media_path = $target_dir . $media_id . '.' . end($media_parts);
	// Set the max upload file size for each media type (measured in bytes):
	$image_max_size = image_max_size;
	$audio_max_size = audio_max_size;
	$video_max_size = video_max_size;
	// Check to make sure the media file is valid
	if (empty($type)) {
		$msg = 'Unsupported media format!';
	} else if (!empty($_FILES['media']['tmp_name'])) {
		if (file_exists($media_path)) {
			$msg = 'Media already exists! Please choose another or rename that file.';
		} else if ($_FILES['media']['size'] > ${$type . '_max_size'}) {
			$msg = ucfirst($type) . ' file size too large! Please choose a file with a size less than ' . convert_filesize(${$type . '_max_size'}) . '.';
		} else {
			// Everything checks out now we can move the uploaded media file
			move_uploaded_file($_FILES['media']['tmp_name'], $media_path);
			// Compress image
			if (image_quality < 100) {
				compress_image($media_path, image_quality);
			}
			// Fix image orientation
			if (correct_image_orientation) {
				correct_image_orientation($media_path);
			}
			// Resize image
			if (image_max_width != -1 || image_max_height != -1) {
				resize_image($media_path, image_max_width, image_max_height);
			}
			// Check thumbnail input
			$thumbnail_path = '';
			if (isset($_FILES['thumbnail']) && preg_match('/image\/*/',$_FILES['thumbnail']['type'])) {
				if ($_FILES['thumbnail']['size'] > $image_max_size) {
					exit('Thumbnail size too large! Please choose a file with a size less than ' . convert_filesize($image_max_size) . '.');
				} else {
					$thumbnail_parts = explode('.', $_FILES['thumbnail']['name']);
					$thumbnail_path = 'media/thumbnails/' . $media_id . '.' . end($thumbnail_parts);
					move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnail_path);
				}
			}
			// Connect to MySQL
			$pdo = pdo_connect_mysql();
			// Check if approval is required
			$approved = approval_required ? 0 : 1;
			// Insert media info into the database (title, description, media file path, date added, and media type)
			$stmt = $pdo->prepare('INSERT INTO media (title, description, filepath, uploaded_date, type, thumbnail, approved) VALUES (?, ?, ?, ?, ?, ?, ?)');
	        $stmt->execute([ $_POST['title'], $_POST['description'], $media_path, date('Y-d-m H:i:s'), $type, $thumbnail_path, $approved ]);
			// Output msg
			if ($approved) {
				$msg = 'Upload Complete';
			} else {
				$msg = 'Your uploaded media file is awaiting approval!';
			}
		}
	} else {
		$msg = 'Please upload a media file!';
	}
	exit($msg);
}
?>
<?=template_header('Upload Media')?>

<div class="content upload">

	<h2>Upload Media</h2>

	<form action="" method="post" enctype="multipart/form-data">

		<div id="drop_zone">
			<p>Click or drag and drop an image, video, or audio file ...</p>
		</div>

		<input type="file" name="media" accept="audio/*,video/*,image/*" id="media">

		<div id="preview"></div>

		<input type="text" name="title" id="title" placeholder="Title" required>

		<textarea name="description" id="description" placeholder="Description"></textarea>

		<label for="thumbnail" class="thumbnail">Thumbnail</label>
		<input type="file" name="thumbnail" accept="image/*" id="thumbnail" class="thumbnail">

	    <input type="submit" value="Upload Media" name="submit" id="submit_btn">

	</form>

	<p class="msg"></p>

</div>

<?=template_footer()?>
