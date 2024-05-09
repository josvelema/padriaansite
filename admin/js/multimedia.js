const progressModal = document.getElementById('qr-progress-modal');
const progressMessage = document.getElementById('qr-progress-message');
const progressTitle = document.getElementById('qr-progress-title');

// function generateQRCode(media_id) {
//   let xhr = new XMLHttpRequest();

//   progressModal.style.display = 'block';
//   progressTitle.textContent = 'QR Code Generation';
//   progressMessage.textContent = 'Generating QR code, please wait...';

//   xhr.onreadystatechange = function() {
//     if (this.readyState == 4 && this.status == 200) {
//       console.log('response', this.responseText);

//       // Show the success message and hide the modal after a short delay
//       progressMessage.textContent = 'QR code generated successfully!';
//       setTimeout(() => {
//         progressModal.style.display = 'none';
//         location.reload();
//       }, 2000);

//       // Update media data on the page
//       // updateMediaData(media_id);
//     }
//   };
//   xhr.open("GET", "qrcode.php?media_id=" + media_id, true);
//   xhr.send();
// }

async function generateQRCode(media_id) {
  progressModal.style.display = 'block';
  progressTitle.textContent = 'QR Code Generation';
  progressMessage.textContent = 'Generating QR code, please wait...';

  // call to qrcode.php
  const response = await fetch('qrcode.php?media_id=' + media_id);
  // get response message
  const message = await response.text();
  console.log('response', message);

  // Show the success message and hide the modal after a short delay
  progressMessage.textContent = 'QR code generated successfully!';
  setTimeout(() => {
    progressModal.style.display = 'none';
    location.reload();
  }, 2000);
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
  xhr.onreadystatechange = function() {
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


  xhr.upload.onprogress = function(event) {
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