<?php
include 'functions.php';
// Connect to MySQL
$pdo = pdo_connect_mysql();
if (isset($_GET['id'])) {
    // Retrieve the media from the media table using the GET request ID (URL param)
    $stmt = $pdo->prepare('SELECT * FROM media WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $media = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$media) {
        exit('Media does not exist with this ID!');
    }
} else {
    exit('No ID specified!');
}
?>

<?= template_header($media['title']) ?>
<?= template_header_other() ?>

<?= template_nav() ?>

<style>

    /* Style for media selection container */
.media-selection-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 50px;
}

.media-selection-container button {
  padding: 10px;
  background-color: #fff;
  border: none;
  cursor: pointer;
  margin: 0 10px;
}

.image-container {
  display: flex;
  justify-content: center;
  align-items: center;
  /* height: 100vh; */
  position: relative;
}

.image-container .large-btn {
    position: absolute;
    /* inset: 0; */
    color: #ccdd00;
    text-shadow: 1px 1px 3px #0008, 0 -1px 3px #0008;
    left: 8px;
    bottom: 12px;
    /* background: black; */
    padding: 2px;
    /* border-radius: 100vw; */
}

.image-container img {
  max-width: 330px;
  max-height: 100%;
  cursor: pointer;
}

/* Style for modal container */
#audio-modal-container,
#video-modal-container,
#image-modal-container {
  display: none;
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.8);
}

#audio-modal-content,
#video-modal-content,
#image-modal-content {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

#image-modal-content img {
    width: 98%;
    margin: 0 auto;
    height: 98%;
    object-fit: contain;
}

#audio-modal-audio {
  max-width: 90%;
  max-height: 90%;
}

#video-modal-video {
  max-width: 90%;
  max-height: 90%;
}

#audio-modal-close,
#video-modal-close,
#image-modal-close {
  position: absolute;
  top: 0;
  right: 0;
  padding: 10px;
  background-color: #fff;
  border: none;
  cursor: pointer;
}
</style>

<main class="rj-black-bg-main">
    <div class="gallery">
        <section class="rj-gallery">
            <div class="rj-container-gallery">

                <article class="rj-gallery-card">
                    <div class="card">
                        <div class="card-header">
                            <h3><?= $media['title'] ?></h3>
                            <p>Catologue number: <?= $media['year'] ?> - <?= $media['fnr'] ?></p>
                        </div>


                        <div class="card-body">
                            <?php if ($media['type'] == 'image') : ?>
                                <div class="media-selection-container">
                                <button class="audio-btn" data-src="<?= urldecode($media['audio_url'] )?>">Audio</button>
  <button class="video-btn" data-src="media/videos/sample.mp4">Video</button>
</div>

                                <div class="image-container">
                                    <img src="<?= $media['filepath'] ?>" alt="<?= $media['title'] ?>" class="gallery-img">
                                    <div class="large-btn">
                                    <i class="fas fa-search-plus"></i>
                                    </div>
                                </div>
                                
                            <?php elseif ($media['type'] == 'video') : ?>
                                <video src="<?= $media['filepath'] ?>" width="852" height="480" controls autoplay></video>
                            <?php elseif ($media['type'] == 'audio') : ?>
                                <audio src="<?= $media['filepath'] ?>" controls autoplay></audio>
                            <?php endif; ?>

                            <pre class="description">
                        <?= $media['description'] ?>
                    </pre>

                        </div>


                    </div>
                </article>
            </div>
        </section>

    </div>
    <div id="audio-modal-container">
  <div id="audio-modal-content">
    <audio id="audio-modal-audio" controls poster="assets\img\bginverted.jpg">
      Your browser does not support the audio tag.
    </audio>
    <button id="audio-modal-close">Close</button>
  </div>
</div>

<div id="video-modal-container">
  <div id="video-modal-content">
    <video id="video-modal-video" controls>
      Your browser does not support the video tag.
    </video>
    <button id="video-modal-close">Close</button>
  </div>
</div>


<div id="image-modal-container">
  <div id="image-modal-content">
    <img id="image-modal-image" src="" alt="">
    <button id="image-modal-close">Close</button>
  </div>
</div>


</main>

<script>
// Get modal elements
const audioModalContainer = document.getElementById('audio-modal-container');
const audioModalAudio = document.getElementById('audio-modal-audio');
const audioModalClose = document.getElementById('audio-modal-close');

const videoModalContainer = document.getElementById('video-modal-container');
const videoModalVideo = document.getElementById('video-modal-video');
const videoModalClose = document.getElementById('video-modal-close');

const imageModalContainer = document.getElementById('image-modal-container');
const imageModalImage = document.getElementById('image-modal-image');
const imageModalClose = document.getElementById('image-modal-close');

// Get all media selection buttons
const audioBtn = document.querySelector('.audio-btn');
const videoBtn = document.querySelector('.video-btn');

// Get all image containers
const imageContainers = document.querySelectorAll('.image-container');

// Add click event listener to audio button
audioBtn.addEventListener('click', () => {
  // Get the audio source from the button data attribute
  const audioSource = audioBtn.getAttribute('data-src');

  // Set the audio source and display the audio modal
  audioModalAudio.setAttribute('src', audioSource);
  audioModalContainer.style.display = 'block';
});

// Add click event listener to video button
videoBtn.addEventListener('click', () => {
  // Get the video source from the button data attribute
  const videoSource = videoBtn.getAttribute('data-src');

  // Set the video source and display the video modal
  videoModalVideo.setAttribute('src', videoSource);
  videoModalContainer.style.display = 'block';
});

// Add click event listener to each image container
imageContainers.forEach((container) => {

  
  container.addEventListener('click', () => {
    // Get the image source from the clicked container
    const imageSource = container.querySelector('img').getAttribute('src');

    // Set the modal image source and display the modal
    imageModalImage.setAttribute('src', imageSource);
    imageModalContainer.style.display = 'block';
  });
  

});

// Add click event listener to the audio modal close button
audioModalClose.addEventListener('click', () => {
  // Stop the audio and hide the audio modal
  audioModalAudio.pause();
  audioModalAudio.currentTime = 0;
  audioModalContainer.style.display = 'none';
});

// Add click event listener to the video modal close button
videoModalClose.addEventListener('click', () => {
  // Stop the video and hide the video modal
  videoModalVideo.pause();
  videoModalVideo.currentTime = 0;
  videoModalContainer.style.display = 'none';
});

// Add click event listener to the image modal close button
imageModalClose.addEventListener('click', () => {
  // Hide the image modal
  imageModalContainer.style.display = 'none';
});

</script>

<?= template_footer() ?>