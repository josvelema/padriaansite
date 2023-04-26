<?php
include 'main.php';

if (isset($_POST['viewCat'])) {
    

    setcookie("viewing_cat",  $_POST['viewCat'], time() + 86400);

    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ? ');
    $stmt->bindParam(1, $_POST['viewCat'], PDO::PARAM_INT);
    $stmt->execute();
    $viewCat = $stmt->fetch(PDO::FETCH_ASSOC);
    $catTitle = $viewCat['title'];

    $stmt = $pdo->prepare('SELECT m.* FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = ? WHERE m.type = "image" ORDER BY m.id DESC ');

    $stmt->bindParam(1, $_POST['viewCat'], PDO::PARAM_INT);
    $stmt->execute();

    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $countMedia = $stmt->rowCount();
} else {
    setcookie("viewing_cat", 0, time() + 86400);

    $stmt = $pdo->prepare('SELECT * FROM media ORDER BY year,fnr DESC');
    $stmt->execute();
    $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $countMedia = $stmt->rowCount();
}

if (isset($_COOKIE['viewing_cat'])) {
    if ($_COOKIE['viewing_cat'] > 0) {
        $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ? ');
        $stmt->bindParam(1, $_COOKIE['viewing_cat'], PDO::PARAM_INT);
        $stmt->execute();
        $viewCat = $stmt->fetch(PDO::FETCH_ASSOC);
        $catTitle = $viewCat['title'];

        $stmt = $pdo->prepare('SELECT m.* FROM media m JOIN media_categories mc ON mc.media_id = m.id AND mc.category_id = ? WHERE m.type = "image" ORDER BY m.id DESC ');

        $stmt->bindParam(1, $_COOKIE['viewing_cat'], PDO::PARAM_INT);
        $stmt->execute();

        $media = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $countMedia = $stmt->rowCount();
    }
}


?>



<?= template_admin_header('MultiMedia', 'multimedia') ?>

<div class="rj-form-admin">
    <h2>Multimedia</h2>
    <p>Generate QR-codes/QR cards -  upload audio and video</p>
    <form action="multimedia.php" method='post'>
        <?php
        $stmt = $pdo->prepare('SELECT * FROM categories ORDER BY id DESC');
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="form-group">
          <select class="form-control" name="viewCat" id="">
              <option value=0>Select Category</option>
              <?php foreach ($categories as $category) : ?>
                  <option value=<?= $category['id'] ?>><?= $category['title'] ?></option>
              <?php endforeach; ?>
          </select>
          <input type="submit" name="submit" class="btn btn-success" value="View Category">
          <a href="multimedia.php" class="btn btn-primary">View All Media</a>
        </div>
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
                    <th>ID & view</th>
                    <th>Title</th>
                    <th>Year</th>
                    <th>FNR</th>
                    <th>QRcode</th>
                    <th>QRcard</th>
                    <th>Audio</th>
                    <th>Video</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($media)) : ?>
                    <tr>
                        <td colspan="8" style="text-align:center;">There are no recent media files</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($media as $m) : ?>
                      <tr data-media-id="<?= $m['id'] ?>">
                            <td>
                              
                              <a href="../view.php?id=<?= $m['id'] ?>" target="_blank" class="rj-table-link"><i class="fa-solid fa-eye"></i>
                              <?= $m['id'] ?></a>
                            </td>
                            <td><?= htmlspecialchars($m['title'], ENT_QUOTES) ?></td>
                            <td><?= $m['year'] ?></td>
                            <td><?= $m['fnr'] ?></td>
                            <td>
                    <?php if ($m['qr_url']) : ?>
                        <a href="../<?= $m['qr_url'] ?>" target="_blank" class="rj-table-link">View QR</a>
                    <?php else : ?>
                        <button onclick="generateQRCode(<?= $m['id'] ?>)" class="rj-table-btn">Make QR</button>
                    <?php endif; ?>
                </td>
                    <td>
                    <?php if ($m['qr_card_url']) : ?>
                        <a href="../<?= $m['qr_card_url'] ?>" target="_blank" class="rj-table-link">View QR Card</a>
                    <?php elseif ($m['qr_url']) : ?>
                        <button onclick="generateQrCardAndSave(<?= $m['id'] ?>, '<?= '../' . $m['qr_url'] ?>')" class="rj-table-btn">Make QR Card</button>
                    <?php else : ?>
                        <span>n/a</span>
                    <?php endif; ?>
                </td>
                    <td>
                    <?php if ($m['audio_url']) : ?>
                        <a href="../media/multimedia/audio/<?= $m['audio_url'] ?>" target="_blank" class="rj-table-link">View audio</a>
                    <?php else : ?>
                      <button onclick="openUploadModal(<?= $m['id'] ?>, 'audio')"  class="rj-table-btn">Upload Audio</button>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($m['video_url']) : ?>
                        <a href="../media/multimedia/video/<?= $m['video_url'] ?>" target="_blank" class="rj-table-link">View video</a>
                    <?php else : ?>
                      <button onclick="openUploadModal(<?= $m['id'] ?>, 'video')"  class="rj-table-btn">Upload Video</button>
                    <?php endif; ?>
                </td>
                        
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<style>
.rj-table-btn {
  background-color: #232332;
  border: none;
  border-radius: 12px;
  color: #f0f0f0;
  padding: 8px 16px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 16px;
  margin: 4px 2px;
  cursor: pointer;
}

.rj-table-btn:hover,
.rj-table-btn:focus {
  background-color: #555555;

}

.rj-table-link {
  color: #223366;
  text-transform: uppercase;
}

.rj-table-link:hover,
.rj-table-link:focus {
  color: #000000;
}


  .modal {
  display: none;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 50%;
  text-align: center;
}

/* CSS */
.custom-modal {
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.5);
}

.custom-modal-content {
  background-color: #fff;
  margin: 15% auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

.custom-modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.custom-modal-close {
  color: #aaa;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

.custom-modal-close:hover,
.custom-modal-close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
#uploadForm {
    display: flex;
    flex-direction: column;
    padding: 0.5em;
    margin-block: 1em;
}

#uploadForm > *{
    margin-block: 0.5em;

}


</style>
<div class="delMediaModalWrap"></div>
<div id="qr-progress-modal" class="modal">
  <div class="modal-content">
    <h4>QR Code Generation</h4>
    <p id="qr-progress-message"></p>
  </div>
</div>

<!-- Upload Modal -->

<div id="uploadModal" class="custom-modal" style="display:none;">
  <div class="custom-modal-content">
    <div class="custom-modal-header">
      <h2 id="uploadModalTitle">Upload</h2>
      <span id="uploadModalClose" class="custom-modal-close">&times;</span>
    </div>
    <div class="custom-modal-body">
      <form id="uploadForm">
        <input type="hidden" id="uploadMediaId" name="media_id" value="">
        <input type="hidden" id="uploadType" name="type" value="">
        <input type="file" id="fileInput" name="file" required>
        <progress id="uploadProgress" value="0" max="100"></progress>
        <button type="button" id="uploadButton">Upload</button>
      </form>
    </div>
  </div>
</div>


<script src="generateBusinessCard.js"></script>

<script>


function generateQRCode(media_id) {
    let xhr = new XMLHttpRequest();

    // Show the progress modal with a message
    const progressModal = document.getElementById('qr-progress-modal');
    const progressMessage = document.getElementById('qr-progress-message');
    progressModal.style.display = 'block';
    progressMessage.textContent = 'Generating QR code, please wait...';

    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            console.log('response', this.responseText);

            // Show the success message and hide the modal after a short delay
            progressMessage.textContent = 'QR code generated successfully!';
            setTimeout(() => {
                progressModal.style.display = 'none';
            }, 2000);

            // Update media data on the page
            updateMediaData(media_id);
        }
    };
    xhr.open("GET", "qrcode.php?media_id=" + media_id, true);
    xhr.send();
}

function updateMediaData(media_id) {
    fetch('get_media.php?media_id=' + media_id)
        .then(response => response.json())
        .then(mediaData => {
            const mediaItem = document.querySelector(`tr[data-media-id="${media_id}"]`);

            // mediaItem.children[0].textContent = mediaData.title;
            // mediaItem.children[1].textContent = mediaData.year;
            // mediaItem.children[2].textContent = mediaData.fnr;
            // mediaItem.children[3].textContent = mediaData.description.replace(/\n/g, '<br>');

            // Update QR code cell
            const qrCodeCell = mediaItem.children[4];
            qrCodeCell.innerHTML = '';
            if (mediaData.qr_url) {
                const qrLink = document.createElement('a');
                qrLink.href = '../' + mediaData.qr_url;
                qrLink.target = '_blank';
                qrLink.textContent = 'View QR';
                qrCodeCell.appendChild(qrLink);
            } else {
                const qrButton = document.createElement('button');
                qrButton.onclick = () => generateQRCode(media_id);
                qrButton.textContent = 'Make QR';
                qrCodeCell.appendChild(qrButton);
            }

            // Update QR card cell
            const qrCardCell = mediaItem.children[5];
            qrCardCell.innerHTML = '';
            if (mediaData.qr_card_url) {
                const qrCardLink = document.createElement('a');
                qrCardLink.href = '../' + mediaData.qr_card_url;
                qrCardLink.target = '_blank';
                qrCardLink.textContent = 'View QR Card';
                qrCardCell.appendChild(qrCardLink);
            } else if (mediaData.qr_url) {
                const qrCardButton = document.createElement('button');
                qrCardButton.onclick = () => generateQrCardAndSave(media_id, '../' + mediaData.qr_url);
                qrCardButton.textContent = 'Make QR Card';
                qrCardCell.appendChild(qrCardButton);
            } else {
                const naText = document.createElement('span');
                naText.textContent = 'n/a';
                qrCardCell.appendChild(naText);
            }

            // update audio cell
            const audioCell = mediaItem.children[6];
            audioCell.innerHTML = '';
            if (mediaData.audio_url) {
                const audioLink = document.createElement('a');
                audioLink.href = '../media/multimedia/audio/' + mediaData.audio_url;
                audioLink.target = '_blank';
                audioLink.textContent = 'View Audio';
                audioLink.classList.add('rj-table-link');
                audioCell.appendChild(audioLink);
            } else {
                const audioButton = document.createElement('button');
                audioButton.onclick = () => openUploadModal(media_id, 'audio');
                audioButton.textContent = 'Upload Audio';
                audioButton.classList.add('rj-table-btn');
                audioCell.appendChild(audioButton);
            }
            // updaye video cell

            const videoCell = mediaItem.children[7];
            videoCell.innerHTML = '';
            if (mediaData.video_url) {
                const videoLink = document.createElement('a');
                videoLink.href = '../' + mediaData.video_url;
                videoLink.target = '_blank';
                videoLink.textContent = 'View Video';
                videoLink.classList.add('rj-table-link');
                videoCell.appendChild(videoLink);
            } else {
                const videoButton = document.createElement('button');
                videoButton.onclick = () => openUploadModal(media_id, 'video');
                videoButton.textContent = 'Upload Video';
                videoButton.classList.add('rj-table-btn');
                videoCell.appendChild(videoButton);
            }
        });
}

// JavaScript
function openUploadModal(media_id, type) {
  const modal = document.getElementById('uploadModal');
  const uploadButton = document.getElementById('uploadButton');
  const mediaIdInput = document.getElementById('uploadMediaId');
  const typeInput = document.getElementById('uploadType');

  // Set media ID and type
  mediaIdInput.value = media_id;
  typeInput.value = type;

  // Set the modal title based on the type
  const title = type.charAt(0).toUpperCase() + type.slice(1) + ' Upload';
  document.getElementById('uploadModalTitle').textContent = title;

  // Open the modal
  modal.style.display = 'block';

  // Add click event listener for upload button
  uploadButton.addEventListener('click', function uploadHandler() {
    uploadMultimediaFile(media_id, type);

    // Remove the event listener to prevent multiple uploads on subsequent modal opens
    uploadButton.removeEventListener('click', uploadHandler);
  });

  // Add click event listener for close button
  const closeButton = document.getElementById('uploadModalClose');
  closeButton.addEventListener('click', function closeHandler() {
    // Close the modal
    modal.style.display = 'none';

    // Remove the event listener to prevent multiple closings on subsequent modal opens
    closeButton.removeEventListener('click', closeHandler);
  });
}

function uploadMultimediaFile(media_id, type) {
  const fileInput = document.getElementById('fileInput');
  const uploadProgress = document.getElementById('uploadProgress');
  const modal = document.getElementById('uploadModal');

  if (fileInput.files.length === 0) {
    alert('Please select a file to upload.');
    return;
  }

  const file = fileInput.files[0];
  const formData = new FormData();
  formData.append('media_id', media_id);
  formData.append('type', type);
  formData.append('file', file);

  const xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function () {
  if (this.readyState === 4) {
    if (this.status === 200) {
      console.log('response', this.responseText);
      updateMediaData(media_id);
      modal.style.display = 'none';
      resetUploadModal();
    } else {
      console.error('Error:', this.status, this.responseText);
    }
  }
};


  xhr.upload.onprogress = function (event) {
    if (event.lengthComputable) {
      const percentComplete = (event.loaded / event.total) * 100;
      uploadProgress.value = percentComplete;
    }
  };

  xhr.open('POST', 'upload_multimedia.php', true);
  xhr.send(formData);
  updateMediaData(media_id);

}

function resetUploadModal() {
  const fileInput = document.getElementById('fileInput');
  const uploadProgress = document.getElementById('uploadProgress');

  fileInput.value = '';
  uploadProgress.value = 0;
}


</script>



<?= template_admin_footer() ?>