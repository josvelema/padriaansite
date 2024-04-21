const CANVAS_WIDTH = 420;
const CANVAS_HEIGHT = 280;
const qrCodeSize = 150;

function wrapText(ctx, text, x, y, maxWidth, lineHeight) {
  const words = text.split(' ');
  let line = '';

  for (let n = 0; n < words.length; n++) {
    const testLine = line + words[n] + ' ';
    const metrics = ctx.measureText(testLine);
    const testWidth = metrics.width;
    if (testWidth > maxWidth && n > 0) {
      ctx.fillText(line, x, y);
      line = words[n] + ' ';
      y += lineHeight;
    } else {
      line = testLine;
    }
  }
  ctx.fillText(line, x, y);
}

async function fetchMediaData(mediaId) {
  const response = await fetch(`fetch_media_data.php?mediaId=${mediaId}`);
  const mediaData = await response.json();
  return mediaData;
}

function createBusinessCard(cardData) {
  return new Promise((resolve) => {
    const canvas = document.createElement('canvas');
    canvas.width = CANVAS_WIDTH;
    canvas.height = CANVAS_HEIGHT;
    const ctx = canvas.getContext('2d');

    // Background color
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, CANVAS_WIDTH, CANVAS_HEIGHT);

    // Draw QR code image
    const qrCodeImage = new Image();
    qrCodeImage.src = cardData.qrCodeUrl;
    qrCodeImage.onload = () => {
      // ctx.drawImage(qrCodeImage, 20, 50, 128, 128);
      ctx.drawImage(qrCodeImage, 20, (CANVAS_HEIGHT - qrCodeSize) / 2 + 10, qrCodeSize, qrCodeSize);
      resolve(canvas.toDataURL('image/png'));
    };
    // Text styling
    ctx.fillStyle = '#000000';
    ctx.font = '20px Arial';

    // Draw media title
    // ctx.fillText(cardData.title, 180, 100);
    // Draw media title
  ctx.fillStyle = '#000000';
  ctx.font = 'bold 20px HELVETICA';
  ctx.textAlign = 'left';
  ctx.textBaseline = 'middle';
  const titleMaxWidth = CANVAS_WIDTH - 40;
  wrapText(ctx, cardData.title, 20, 30, titleMaxWidth, 24);


  // ctx.fillText(cardData.title, qrCodeSize + 40, 50);


    // Draw media year
    // ctx.fillText(cardData.year, 180, 140);
    // Draw media year and fnr
  ctx.font = '18px Arial';
  ctx.fillText(`Year: ${cardData.year} / nr.  ${cardData.fnr}`, qrCodeSize + 40, CANVAS_HEIGHT / 2 + 20);

    // Draw media dbId
    ctx.fillText(`Cat. ID: ${cardData.dbId}`, qrCodeSize + 40, CANVAS_HEIGHT / 2 + 60);

    // draw website url
    ctx.font = '16px Arial';
    ctx.fillText('pieter-adriaans.com', 20, CANVAS_HEIGHT - 20);

  });
}

async function saveImageOnServer(imageDataUrl, mediaId) {
  const formData = new FormData();
  formData.append('imageDataUrl', imageDataUrl);
  formData.append('mediaId', mediaId);

  await fetch('save_qr_card.php', {
    method: 'POST',
    body: formData,
  });
}

async function generateQrCardAndSave(mediaId, qrCodeUrl) {

  progressModal.style.display = 'block';
  progressTitle.textContent = 'QR info Card Generation';
  progressMessage.textContent = 'Generating Card with QR code, please wait...';
  const mediaData = await fetchMediaData(mediaId);

  const cardData = {
    title: mediaData.title,
    year: mediaData.year,
    fnr: mediaData.fnr,
    qrCodeUrl: qrCodeUrl,
    dbId: mediaId,
  };

  // Generate business card and save it
  const imageDataUrl = await createBusinessCard(cardData);
  await saveImageOnServer(imageDataUrl, mediaId);
  progressMessage.textContent = 'QR info Card generated successfully!';
  setTimeout(() => {
    progressModal.style.display = 'none';
     // refresh the page
    location.reload();
  }, 2000);
  
}
