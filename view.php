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
<link rel="stylesheet" href="assets/css/gallery.css?v=2">

<?= template_nav() ?>



<main class="rj-black-bg-main">
    <div class="content home">
    <section>
            <div class="gallery-container">

           
    <article class="rj-gallery-card">
                    <div class="card">
                        <div class="card-header">
                            <h3><?= $media['title'] ?></h3>
                            <p>Catalogue number: <?= $media['year'] ?> - <?= $media['fnr'] ?></p>
                        </div>


                        <div class="card-body">
                        <?php if ($media['type'] == 'image') : ?>
									<div class="media-selection-container">
										<button class="img-btn" data-src="<?= $media['filepath'] ?>"><i class="fa-solid fa-expand"></i></button>
										 <!-- chek if description is not equal to '...' -->
										<?php if (trim($media['description']) != '...') : ?>
											<button class="info-btn" data-info="<?= trim($media['description']) ?>" data-title="<?= $media['title'] ?>"><i class="fa-solid fa-circle-info"></i></i></button>
										<?php endif; ?>
										
										 <!-- check if audio url is not empty -->
										<?php if (!empty($media['audio_url'])) : ?>
											<button class="audio-btn" data-src="<?= urldecode($media['audio_url']) ?>"><i class="fa-solid fa-headphones"></i></button>
										<?php endif; ?>
										
										<!-- check if video url is not empty -->
										<?php if (!empty($media['video_url'])) : ?>
											<button class="video-btn" data-src="<?= urldecode($media['video_url']) ?>"><i class="fa-solid fa-video"></i></button>
										<?php endif; ?>
									

            </div>
            <div class="image-container">
              <div class="img-wrapper">
               <img src="assets\img\bginverted.jpg" data-src="<?= $media['filepath'] ?>" alt="<?= $media['title'] ?>" data-placeholder="assets\img\bginverted.jpg" class="lozad placeholder">
              </div>
            </div>

                                
                                <div class="modal-container">
              <div class="audio-modal-container">
                <div class="audio-modal-content">
                  <audio class="audio-modal-audio" controls poster="assets\img\bginverted.jpg">
                    Your browser does not support the audio tag.
                  </audio>
                  <button class="audio-modal-close">Close</button>
                </div>
              </div>

              <div class="video-modal-container">
                <div class="video-modal-content">
                  <video class="video-modal-video" controls>
                    Your browser does not support the video tag.
                  </video>
                  <button class="video-modal-close">Close</button>
                </div>
              </div>

              <div class="image-modal-container">
                <div class="image-modal-content">
                  <img class="image-modal-image" src="" alt="">
                  <button class="image-modal-close">Close</button>
                </div>
              </div>

              <div class="info-modal-container">
                <div class="info-modal-content">
									<h3 class="info-modal-title"></h3>
                  <pre class="info-modal-pre"></pre>
                  <button class="info-modal-close">Close</button>
                </div>
              </div>
            </div>

          <?php elseif ($media['type'] == 'video') : ?>
            <video src="<?= $media['filepath'] ?>" width="852" height="480" controls autoplay></video>
          <?php elseif ($media['type'] == 'audio') : ?>
            <audio src="<?= $media['filepath'] ?>" controls autoplay></audio>
          <?php endif; ?>


        </div>
      </div>
    </article>
            </div>
        </section>

    </div>


</main>


<script>
document.addEventListener('DOMContentLoaded', () => {
//   const searchForm = document.getElementById('search-form');
//   const searchInput = document.getElementById('search');
//   const categorySelect = document.getElementById('category');

//   searchForm.addEventListener('submit', (event) => {
//     event.preventDefault();
//     loadMedia(categorySelect.value, searchInput.value);
//   });

//   categorySelect.addEventListener('change', () => {
//     loadMedia(categorySelect.value, searchInput.value);
//   });

//   async function loadMedia(category, search = '') {
//     const response = await fetch('search.php', {
//       method: 'POST',
//       headers: {
//         'Content-Type': 'application/x-www-form-urlencoded'
//       },
//       body: `category=${encodeURIComponent(category)}&search=${encodeURIComponent(search)}`
//     });

//     if (response.ok) {
//       const resultsHtml = await response.text();
//       const galleryContainer = document.querySelector('.gallery-container');
//       galleryContainer.innerHTML = resultsHtml;
//     }
//   }

  // Load media initially
  // loadMedia(categorySelect.value);



		const observer = lozad(".lozad", {
			loaded: function(el) {
				el.classList.add("fade");
				console.log("loadewe");
			}
		});
		observer.observe();
	});
	// function loadMedia(category, search = '') {
  // const xhr = new XMLHttpRequest();
  // xhr.onreadystatechange = function() {
  //   if (this.readyState === 4 && this.status === 200) {
  //     document.getElementById('media-container').innerHTML = this.responseText;
  //   }
  // };

  // const url = `gallery.php?category=${category}&search=${encodeURIComponent(search)}`;
  // xhr.open('GET', url, true);
  // xhr.send();}



</script>



<?= template_footer() ?>