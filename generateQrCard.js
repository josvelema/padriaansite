console.log("generateQrCard.js loaded");

import { toPng, toJpeg, toBlob, toPixelData, toSvg } from 'html-to-image';

// import * as htmlToImage from './node_modules/html-to-image/dist/index.js';

async function generateQrCard(mediaData) {
const { qrCodeImageUrl, mediaTitle, mediaYear, mediaFnr } = mediaData;

const htmlContent = `
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Business Card</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
        
                .business-card {
                    display: flex;
                    align-items: center;
                    width: 400px;
                    border: 1px solid #000;
                    padding: 20px;
                    border-radius: 5px;
                }
        
                .qr-code {
                    width: 100px;
                    height: 100px;
                    margin-right: 20px;
                }
        
                .media-details {
                    line-height: 1.6;
                }
        
                .media-title {
                    font-size: 18px;
                    font-weight: bold;
                }
        
                .media-year,
                .media-fnr {
                    font-size: 14px;
                }
            </style>
        </head>
        <body>
            <div class="business-card">
                <div class="qr-code">
                    <!-- Replace the src attribute value with the actual QR code URL -->
                    <img src="localhost/pieter/media/codes/2023-04-19-2.png" alt="QR Code">
                </div>
                <div class="media-details">
                    <div class="media-title">
                        <!-- Replace with the actual media title -->
                        ${mediaTitle} - ${qrCodeImageUrl}
                    </div>
                    <div class="media-year">
                        <!-- Replace with the actual media year -->
                        Year: ${mediaYear}
                    </div>
                    <div class="media-fnr">
                        <!-- Replace with the actual media fnr -->
                        Cat ID : ${mediaFnr}
                    </div>
                </div>
            </div>
        </body>
        </html>
    `;

const container = document.createElement("div");
container.innerHTML = htmlContent;
document.body.appendChild(container);

try {
    const imageDataUrl = await toPng(container);
    document.body.removeChild(container);
    return imageDataUrl;
} catch (error) {
    console.error("Failed to generate business card image:", error);
    document.body.removeChild(container);
    throw error;
}
}
