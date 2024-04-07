const FACT_CANVAS_WIDTH = 595;
const FACT_CANVAS_HEIGHT = 842;

const imageWidth = 350;
const imageHeight = 300;

function fact_text_wrap(ctx, text, x, y, maxWidth, lineHeight) {
  const words = text.split(" ");
  let line = "";

  for (let n = 0; n < words.length; n++) {
    const testLine = line + words[n] + " ";
    const metrics = ctx.measureText(testLine);
    const testWidth = metrics.width;
    if (testWidth > maxWidth && n > 0) {
      ctx.fillText(line, x, y);
      line = words[n] + " ";
      y += lineHeight;
    } else {
      line = testLine;
    }
  }
  ctx.fillText(line, x, y);
}

// async function fetchMediaData(mediaId) {
//   const response = await fetch(`fetch_media_data.php?mediaId=${mediaId}`);
//   const mediaData = await response.json();
//   return mediaData;
// }

function createFactSheet(sheetData) {
  return new Promise((resolve) => {
    const canvas = document.createElement("canvas");
    canvas.width = FACT_CANVAS_WIDTH;
    canvas.height = FACT_CANVAS_HEIGHT;
    const ctx = canvas.getContext("2d");

    // Background color
    ctx.fillStyle = "#ffffff";
    ctx.fillRect(0, 0, FACT_CANVAS_WIDTH, FACT_CANVAS_HEIGHT);

    // draw title text
    ctx.font = "24px Arial";
    ctx.fillStyle = "#000000";
    // wrap and draw title text
    const titleMaxWidth = FACT_CANVAS_WIDTH - 40;
    fact_text_wrap(ctx, sheetData.title, 20, 25, titleMaxWidth, 24);
    // draw main image
    const mainImg = new Image();
    mainImg.src = "../" + sheetData.imagePath;
    mainImg.onload = () => {
      // ctx.drawImage(mainImg, 20, 50, 128, 128);
      // ctx.drawImage(mainImg, 20, (FACT_CANVAS_HEIGHT - imageWidth) / 2 + 10, imageWidth, imageHeight);
      // a
      // ctx.drawImage(mainImg, 20, 50, imageWidth, imageHeight);
      // draw image and scale it to fit the canvas , and keep aspect ratio
      const aspectRatio = mainImg.width / mainImg.height;
      const scaledWidth = imageWidth;
      const scaledHeight = scaledWidth / aspectRatio;
      ctx.drawImage(mainImg, 20, 100, scaledWidth, scaledHeight);
      resolve(canvas.toDataURL("image/png"));
    };

    // draw year and fnr text
    ctx.font = "14px Arial";
    ctx.fillStyle = "#000000";
    ctx.fillText(`Year: ${sheetData.year} - follow up number: ${sheetData.fnr} - ID: ${sheetData.mediaId} `, 20, 380);

    // draw type, material, dimensions text 
    ctx.font = "14px Arial";
    ctx.fillStyle = "#000000";
    ctx.fillText(`Type: ${sheetData.art_type} - Material: ${sheetData.art_material} - Dimensions: ${sheetData.art_dimensions}`, 20, 420);


    // draw price text and format in euro currency
    ctx.font = "18px Arial";
    ctx.fillStyle = "#000000";

    const price = parseFloat(sheetData.art_price).toFixed(2);
    ctx.fillText(`Price: €${price}`, 20, 460);
    // add horizontal ruler

    ctx.beginPath();
    ctx.moveTo(20, 480);
    ctx.lineTo(575, 480);
    ctx.stroke();
    

    // draw description text
    ctx.font = "16px Arial";
    ctx.fillStyle = "#000000";
    fact_text_wrap(ctx, sheetData.description, 20, 500, 555, 20);
  });
}

async function saveFactsheetOnServer(imageDataUrl, mediaId) {
  const formData = new FormData();
  formData.append("imageDataUrl", imageDataUrl);
  formData.append("mediaId", mediaId);

  await fetch("save_factsheet.php", {
    method: "POST",
    body: formData,
  });
}

async function generateFactSheetAndSave(mediaId) {
  const mediaData = await fetchMediaData(mediaId);

  const cardData = {
    mediaId: mediaData.id,
    title: mediaData.title,
    description: mediaData.description,
    year: mediaData.year,
    fnr: mediaData.fnr,
    imagePath: mediaData.filepath,
    art_material: mediaData.art_material,
    art_dimensions: mediaData.art_dimensions,
    art_type: mediaData.art_type,
    art_price: mediaData.art_price,
  };

  const imageDataUrl = await createFactSheet(cardData);
  await saveFactsheetOnServer(imageDataUrl, mediaId);
  updateMediaData(mediaId);
}
