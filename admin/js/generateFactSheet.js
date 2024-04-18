const FACT_CANVAS_WIDTH = 595;
const FACT_CANVAS_HEIGHT = 842;

const imageWidth = 400;
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

async function fetchMediaData(mediaId) {
  const response = await fetch(`fetch_media_data.php?mediaId=${mediaId}`);
  const mediaData = await response.json();
  return mediaData;
}

function createFactSheet(sheetData) {
  return new Promise((resolve) => {
    const canvas = document.createElement("canvas");
    canvas.width = FACT_CANVAS_WIDTH;
    canvas.height = FACT_CANVAS_HEIGHT;
    const ctx = canvas.getContext("2d");
    const x = canvas.width / 2;
    // image dimensions for landscape orientation

    // Background color
    ctx.fillStyle = "#ffffff";
    ctx.fillRect(0, 0, FACT_CANVAS_WIDTH, FACT_CANVAS_HEIGHT);

    // draw border
    // ctx.strokeStyle = "#7777";
    // ctx.lineWidth = 1;
    // ctx.strokeRect(10, 10, FACT_CANVAS_WIDTH - 20, FACT_CANVAS_HEIGHT - 20);

    // draw title text
    ctx.font = "24px Helvetica";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "center";

    // wrap and draw title text
    const titleMaxWidth = FACT_CANVAS_WIDTH - 40;
    fact_text_wrap(ctx, sheetData.title, x, 32, titleMaxWidth, 24);
    // draw main image
    const mainImg = new Image();
    mainImg.src = "../" + sheetData.imagePath;
    mainImg.onload = () => {
      const aspectRatio = mainImg.width / mainImg.height;
      let imageWidth, imageHeight;

      if (mainImg.height > mainImg.width) {
        // If the image is portrait-oriented
        imageHeight = Math.min(300, FACT_CANVAS_HEIGHT - 150); // Set max height
        imageWidth = imageHeight * aspectRatio;
      } else {
        // If the image is landscape-oriented
        imageWidth = Math.min(400, FACT_CANVAS_WIDTH - 40); // Set max width
        imageHeight = imageWidth / aspectRatio;
      }

      const centerImageX = (FACT_CANVAS_WIDTH - imageWidth) / 2;
      ctx.drawImage(mainImg, centerImageX, 80, imageWidth, imageHeight);
      resolve(canvas.toDataURL("image/png"));
    };

    // draw border around image
    ctx.strokeStyle = "#2a2a2a";
    ctx.lineWidth = 3;
    // set border left of the middle
    let borderX = (FACT_CANVAS_WIDTH - imageWidth) / 2 - 15;

    ctx.strokeRect(borderX, 60, 430, 330);

    if (sheetData.description.length > 650) {
      sheetData.description = sheetData.description.substring(0, 650) + "...";
    }

    // draw description text
    ctx.font = "14px Helvetica";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "left";

    let lorem = `Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim minima explicabo velit aliquam, magni veniam ea cupiditate minus sequi laudantium repellendus harum nesciunt reprehenderit id quos quasi possimus optio dolorum? Doloribus aspernatur enim omnis dolore libero. In, veniam veritatis? Laudantium, id soluta minima quod doloribus animi nihil! Harum, rerum dolorem ipsam culpa necessitatibus quibusdam deleniti dolorum, quaerat quas iure tenetur. Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum, dolor provident enim nemo veritatis possimus sunt quasi. Vitae omnis provident quas, exercitationem dicta voluptatum aliquid in? Tempora repellat iure molestias?`;
    if (sheetData.description.length > 430) {
      sheetData.description = sheetData.description.substring(0, 430) + "...";
    }

    // fact_text_wrap(ctx, sheetData.description, x, 420, 555, 20);
    fact_text_wrap(ctx, sheetData.description, 40, 415, 550, 20);

    // draw price text and format in euro currency
    ctx.font = "18px Helvetica";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "right";

    let rightX = 555;

    const price = parseFloat(sheetData.art_price).toFixed(2);
    ctx.fillText(`Price: €${price}`, rightX, 540);

    ctx.font = "16px Helvetica";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "left";
    ctx.fillText(
      `Material: ${sheetData.art_material}`,
      40,
      545
    );
    ctx.font = "16px Helvetica";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "left";
    ctx.fillText(
      `Dimensions: ${sheetData.art_dimensions}`,
      40,
      570
    );
    // add horizontal ruler

    ctx.beginPath();
    ctx.moveTo(20, 580);
    ctx.lineTo(575, 580);
    ctx.lineWidth = 1;
    ctx.strokeStyle = "#777";
    ctx.stroke();

    // draw disclaimer image
    const disclaimerImg = new Image();
    disclaimerImg.src = "../assets/img/factSheetDisclaimer.png";
    disclaimerImg.onload = () => {
      ctx.drawImage(disclaimerImg, 20, 590, 551, 221);
    };

    // draw year and fnr text
    ctx.font = "16px Helvetica";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "center";
    ctx.fillText(
      `Year: ${sheetData.year} - follow up number: ${sheetData.fnr} - ID: ${sheetData.mediaId} `,
      x,
      826
    );
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
