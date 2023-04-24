async function generateBusinessCardImage(mediaData) {
  const canvas = document.createElement('canvas');
  canvas.width = 600;
  canvas.height = 400;

  const context = canvas.getContext('2d');

  // Draw the background image
  const backgroundImage = await loadImage('media/business_card_background.png');
  context.drawImage(backgroundImage, 0, 0, canvas.width, canvas.height);

  // Draw the QR code image
  const qrCodeImage = await loadImage(mediaData.qrCodeImageUrl);
  context.drawImage(qrCodeImage, 20, 20, 100, 100);

  // Draw the media title
  context.font = 'bold 24px Arial';
  context.fillStyle = '#000';
  context.fillText(mediaData.mediaTitle, 140, 40);

  // Draw the media year
  context.font = 'bold 24px Arial';
  context.fillStyle = '#000';
  context.fillText(mediaData.mediaYear, 140, 80);

  // Draw the media FNR
  context.font = 'bold 24px Arial';
  context.fillStyle = '#000';
  context.fillText(mediaData.mediaFnr, 140, 120);

  // Draw the media image
  const mediaImage = await loadImage(mediaData.mediaImageUrl);
  context.drawImage(mediaImage, 20, 140, 560, 240);

  return canvas.toDataURL('image/png');
}

function loadImage(imageUrl) {
  return new Promise((resolve, reject) => {
      const image = new Image();
      image.addEventListener('load', () => resolve(image));
      image.addEventListener('error', (error) => reject(error));
      image.src = imageUrl;
  });
}



