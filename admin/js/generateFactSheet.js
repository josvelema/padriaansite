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

    // current date in dd/mm/yyyy format
    const date = new Date();
    const dateString = `Artwork information sheet  ${date.getDate()}-${
      date.getMonth() + 1
    }-${date.getFullYear()}`;

    // draw date text
    ctx.font = "12px Times New Roman";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "center";
    ctx.fillText(dateString, x, 35);

    // draw title text
    ctx.font = "18px Times New Roman";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "center";

    // wrap and draw title text
    const titleMaxWidth = FACT_CANVAS_WIDTH - 40;
    fact_text_wrap(ctx, sheetData.title, x, 60, titleMaxWidth, 18);
    // draw main image
    const mainImg = new Image();
    mainImg.src = "../" + sheetData.imagePath;
    const mainImgPromise = new Promise((resolve) => {
      mainImg.onload = () => {
        let centerImageX;

        const aspectRatio = mainImg.width / mainImg.height;
        if (aspectRatio < 1.1 && aspectRatio > 0.9) {
          // If the image is square prevent max width and height
          centerImageX = (FACT_CANVAS_WIDTH - 300) / 2;
          ctx.drawImage(mainImg, centerImageX, 90, 300, 300);
          resolve(canvas.toDataURL("image/png"));
        } else {
          console.log("aspect ratio", aspectRatio);
          let imageWidth, imageHeight;

          if (mainImg.height > mainImg.width) {
            // If the image is portrait-oriented
            imageHeight = Math.min(300, 320); // Set max height
            imageWidth = imageHeight * aspectRatio;
          } else {
            // If the image is landscape-oriented
            imageWidth = Math.min(400, 420); // Set max width
            imageHeight = imageWidth / aspectRatio;
          }
          centerImageX = (FACT_CANVAS_WIDTH - imageWidth) / 2;
          ctx.drawImage(mainImg, centerImageX, 90, imageWidth, imageHeight);
          resolve(canvas.toDataURL("image/png"));
        }
      };
    });

    // draw border around image
    // ctx.strokeStyle = "#2a2a2a";
    // ctx.lineWidth = 3;
    // set border left of the middle
    // let borderX = (FACT_CANVAS_WIDTH - imageWidth) / 2 - 15;

    // ctx.strokeRect(borderX, 60, 430, 330);

    // draw description text
    ctx.font = "12px Times New Roman";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "left";

    let lorem = `Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim minima explicabo velit aliquam, magni veniam ea cupiditate minus sequi laudantium repellendus harum nesciunt reprehenderit id quos quasi possimus optio dolorum? Doloribus aspernatur enim omnis dolore libero. In, veniam veritatis? Laudantium, id soluta minima quod doloribus animi nihil! Harum, rerum dolorem ipsam culpa necessitatibus quibusdam deleniti dolorum, quaerat quas iure tenetur. Lorem ipsum dolor sit amet consectetur adipisicing elit. Illum, dolor provident enim nemo veritatis possimus sunt quasi. Vitae omnis provident quas, exercitationem dicta voluptatum aliquid in? Tempora repellat iure molestias?`;
    if (sheetData.description.length > 430) {
      sheetData.description = sheetData.description.substring(0, 430) + "...";
    }

    // fact_text_wrap(ctx, sheetData.description, x, 420, 555, 20);
    fact_text_wrap(ctx, sheetData.description, 40, 415, 530, 16);

    // draw price text and format in euro currency
    ctx.font = "12px Times New Roman";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "right";

    let rightX = 555;

    const price = parseFloat(sheetData.art_price).toFixed(2);
    ctx.fillText(`Price: €${price}`, rightX, 540);

    // check if the artwork has a frame and add the frame price if the price is not 0
    if (sheetData.art_has_frame == 1 && sheetData.art_frame_price != 0) {
      const framePrice = parseFloat(sheetData.art_frame_price).toFixed(2);
      ctx.fillText(`Frame Price: €${framePrice}`, rightX, 570);
    }
    
    // if it has frame and the price is 0 then display that the frame is included
    if (sheetData.art_has_frame == 1 && sheetData.art_frame_price == 0) {
      ctx.fillText(`Frame: Included`, rightX, 570);
    }

    ctx.font = "12px Times New Roman";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "left";
    ctx.fillText(`Material: ${sheetData.art_material}`, 40, 540);
    ctx.font = "12px Times New Roman";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "left";
    ctx.fillText(`Dimensions: ${sheetData.art_dimensions}`, 40, 570);
    let disclaimer = `We ship all over the world. We take responsibility for the integrity of the work up to delivery at destination. Payment needs to be made in advance.  Various methods of payment are available. In case of loss of the work a full refund will be made. Conditions of shipment are dependent on the size and nature of the individual work.  The price of the work described in this factsheet is valid on the date of issue, but can be changed without notice. If you are interested please contact us: `;
    ctx.font = "10px Times New Roman";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "left";
    fact_text_wrap(ctx, disclaimer, 40, 595, 530, 16);

    // add qr code
    const qrImg = new Image();

    qrImg.src = "../" + sheetData.qr_card_url;
    const qrImgPromise = new Promise((resolve) => {
      qrImg.onload = () => {
        ctx.drawImage(qrImg, 30, 670, 210, 140);
        resolve(canvas.toDataURL("image/png"));
      };
      qrImg.onerror = (error) => {
        console.error("Error loading QR Card image:", error);
        PromiseRejectionEvent(error);
      };
    });

    // contact details

    let contact = `Adriaans & Van Kerchove, Lda.

    Nif(BTW/Vat): 516357417   
    
    NIB: 0045.8061.40337554888.85
    
    IBAN:PT50 0045.8061.40337554888
    
    Tel: 0031 654234459 
    
    www.pieter-adriaans.com
    
    www.facebook.com/pieter.adriaans
    
    email: pieter@pieter-adriaans.com
    `;
    ctx.font = "10px Times New Roman";
    ctx.fillStyle = "#000000";
    ctx.textAlign = "center";
    fact_text_wrap(ctx, contact, x + 23, 690, 160, 16);

    // logo kaasfabriek
    const logoImg = new Image();
    logoImg.src = "../assets/img/kaasfabriekSmall.png";
    const logoImgPromise = new Promise((resolve) => {
      logoImg.onload = () => {
        ctx.drawImage(logoImg, x + 105, 670, 165, 129);
        resolve(canvas.toDataURL("image/png"));
      };
    });

    Promise.all([mainImgPromise, qrImgPromise, logoImgPromise]).then(() => {
      resolve(canvas.toDataURL("image/png"));
    });
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
  progressModal.style.display = "block";
  progressTitle.textContent = "Factsheet Generation";
  progressMessage.textContent = "Generating factsheet, please wait...";

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
    art_has_frame: mediaData.art_has_frame,
    art_frame_price : mediaData.art_frame_price,
    art_type: mediaData.art_type,
    art_price: mediaData.art_price,
    qr_card_url: mediaData.qr_card_url,
  };

  const imageDataUrl = await createFactSheet(cardData);
  await saveFactsheetOnServer(imageDataUrl, mediaId);
  // updateMediaData(mediaId);
  console.log("Fact sheet generated and saved successfully");
  // Show the success message and hide the modal after a short delay
  progressMessage.textContent = "Factsheet generated successfully!";
  setTimeout(() => {
    progressModal.style.display = 'none';
     // refresh the page
    location.reload();
  }, 2000);
}
