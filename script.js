window.onscroll = function () {
  var e = document.getElementById("scrolltop");
  if (!e) {
    e = document.createElement("a");
    e.id = "scrolltop";
    e.href = "#";
    document.body.appendChild(e);
  }
  e.style.display = document.documentElement.scrollTop > 300 ? "block" : "none";
  e.onclick = (ev) => {
    ev.preventDefault();
    document.documentElement.scrollTop = 0;
  };
};

// if (window.location.pathname == "/pieter/index.php") {
//     document.querySelector("#home").classList.add("rj-current-nav");
// }
// if (window.location.pathname == "/pieter/blog.php") {
//     document.querySelector("#blog").classList.add("rj-current-nav");
// }
// if (window.location.pathname == "/pieter/science.php") {
//     document.querySelector("#science").classList.add("rj-current-nav");
// }
// if (window.location.pathname == "/pieter/painting.php") {
//     document.querySelector("#painting").classList.add("rj-current-nav");
// }
// if (window.location.pathname == "/pieter/music.php") {
//     document.querySelector("#music").classList.add("rj-current-nav");
// }
// if (window.location.pathname == "/pieter/gallery.php") {
//     document.querySelector("#gallery").classList.add("rj-current-nav");
// }
// if (window.location.pathname == "/pieter/contact.php") {
//     document.querySelector("#contact").classList.add("rj-current-nav");
// }

// Container we'll use to show an image
let media_popup = document.querySelector(".media-popup");
// The file that is going to be uploaded
let upload_file;
// Upload form element
let upload_form = document.querySelector(".upload form");
// Upload media element
let upload_media = document.querySelector(".upload #media");
// Upload drop zone element
let upload_drop_zone = document.querySelector(".upload #drop_zone");
// Retrieve cookie by name; used to determine the like/dislike status
const getCookieByName = (name) => {
  let a = `; ${document.cookie}`.match(`;\\s*${name}=([^;]+)`);
  return a ? a[1] : "";
};
// Handle the next and previous buttons
const media_next_prev = (media_meta) => {
  // Retrieve the next and prev elements
  let prev_btn = media_popup.querySelector(".prev");
  let next_btn = media_popup.querySelector(".next");
  let prev_btn_sm = media_popup.querySelector(".prev-small-media");
  let next_btn_sm = media_popup.querySelector(".next-small-media");
  // Add the onclick event
  prev_btn.onclick = (event) => {
    event.preventDefault();
    // Determine the previous element (media)
    let prev_ele = document
      .querySelector('[data-id="' + media_meta.dataset.id + '"]')
      .closest(".rj-gallery-card")
      .previousElementSibling.querySelector(".card-body .tab .popup-selecter");
    // If the prev element exists, click it
    if (prev_ele) prev_ele.click();
  };

  prev_btn_sm.onclick = (event) => {
    event.preventDefault();
    // Determine the previous element (media)
    let prev_ele = document
      .querySelector('[data-id="' + media_meta.dataset.id + '"]')
      .closest(".rj-gallery-card")
      .previousElementSibling.querySelector(".card-body .tab .popup-selecter");
    // If the prev element exists, click it
    if (prev_ele) prev_ele.click();
  };

  // Add the onclick event
  next_btn.onclick = (event) => {
    event.preventDefault();
    // Determine the next element (media)

    let next_ele = document
      .querySelector('[data-id="' + media_meta.dataset.id + '"]')
      .closest(".rj-gallery-card")
      .nextElementSibling.querySelector(".card-body .tab .popup-selecter");
    // If the next element exists, click it
    if (next_ele) next_ele.click();
  };

  next_btn_sm.onclick = (event) => {
    event.preventDefault();
    // Determine the next element (media)
    let next_ele = document
      .querySelector('[data-id="' + media_meta.dataset.id + '"]')
      .closest(".rj-gallery-card")
      .nextElementSibling.querySelector(".card-body .tab .popup-selecter");
    // If the next element exists, click it
    if (next_ele) next_ele.click();
  };
};
// Handle the likes and dislikes
const media_like_dislike = (media_meta) => {
  // Retrieve the like and dislike elements
  let like_btn = media_popup.querySelector(".thumbs-up");
  let dislike_btn = media_popup.querySelector(".thumbs-down");
  // Add the onclick event
  like_btn.onclick = (event) => {
    event.preventDefault();
    // Use AJAX to update the value in the database
    fetch("like-dislike.php?id=" + media_meta.dataset.id + "&type=like")
      .then((res) => res.text())
      .then((data) => {
        // Increment the thumbs up value
        if (data.includes("success")) {
          media_popup.querySelector(".thumbs-up-count").innerHTML =
            parseInt(media_popup.querySelector(".thumbs-up-count").innerHTML) +
            1;
          like_btn.classList.add("active");
        }
      });
  };
  // Add the onclick event
  // dislike_btn.onclick = (event) => {
  //     event.preventDefault();
  //     // Use AJAX to update the value in the database
  //     fetch("like-dislike.php?id=" + media_meta.dataset.id + "&type=dislike")
  //         .then((res) => res.text())
  //         .then((data) => {
  //             // Increment the thumbs down value
  //             if (data.includes("success")) {
  //                 media_popup.querySelector(".thumbs-down-count").innerHTML =
  //                     parseInt(
  //                         media_popup.querySelector(".thumbs-down-count")
  //                             .innerHTML
  //                     ) + 1;
  //                 dislike_btn.classList.add("active");
  //             }
  //         });
  // };
};
// Handle media preview
const media_preview = (file) => {
  let reader = new FileReader();
  reader.onload = () => {
    document.querySelector("#preview").style.display = "block";
    if (file.type.toLowerCase().includes("image")) {
      document.querySelector(
        "#preview"
      ).innerHTML = `<img src="${reader.result}" alt="preview" >`;
      //! edited : inline style max-height:300px;max-width:100%;
    }
    if (file.type.toLowerCase().includes("audio")) {
      document.querySelector(
        "#preview"
      ).innerHTML = `<audio src="${reader.result}" controls style="width:100%"></audio>`;
    }
    if (file.type.toLowerCase().includes("video")) {
      document.querySelector(
        "#preview"
      ).innerHTML = `<video src="${reader.result}" style="max-height:300px;width:100%;" controls></video>`;
    }
  };
  reader.readAsDataURL(file);
};
// If the media popup element exists...

// if (media_popup) {
// console.log("popup exists")

// Iterate the images and create the onclick events
// document.querySelectorAll(".card-footer .popup-button[data-active-tab='image']").forEach((media_link) => {
//     console.log(media_link);
//     // If the user clicks the media
//     media_link.onclick = (e) => {
//         e.preventDefault();
//         console.log("iets clicked");
//         // Retrieve the meta data
//         let media_meta = media_link.closest(".card").querySelector(".card-body .gallery-img")
//         // Retrieve the like/dislike status for the media
//         let media_like_dislike_status = getCookieByName(
//             "like_dislike_" + media_meta.dataset.id
//         );
//         console.log(media_meta)
//         // If the media type is an image
//         if (media_meta.dataset.type == "image") {
//             // Create new image object
//             let img = new Image();
//             // Image onload event
//             img.onload = () => {
//                 // <a href="#" class="thumbs-down
//                 // ${media_like_dislike_status == 'disliked' ? ' active' : ''}
//                 // "><i class="far fa-thumbs-down"></i></a>
//                 // <span class="thumbs-down-count">${media_meta.dataset.dislikes}</span>

//                 // Create the pop out image
//                 media_popup.innerHTML = `
// 					<a href="#" class="prev"><i class="fas fa-angle-left fa-4x"></i></a>
// 					<div class="con">
// 					<div class="rj-close-popup">
// 					<i class="fa-solid fa-xmark"></i>
// 					</div>
// 						<h3>${
//                             media_meta.dataset.title
//                         }</h3><small style='margin-top:0.25em'>made in : ${
//                     media_meta.dataset.year
//                 } - nr. ${media_meta.dataset.fnr} </small>
// 						<p class="rj-popup-p">${media_meta.alt}</p>
//                         <div class="prevnext-small-media">
//                         <a href="#" class="prev-small-media"><i class="fas fa-angle-left fa-4x"></i></a>
//                     <a href="#" class="next-small-media"><i class="fas fa-angle-right fa-4x"></i></a>
//                         </div>
// 						<div class="rj-popup-img-wrap"><img src="${img.src}" alt=""></div>
// 						<div class="thumbs-up-down">
// 							<a href="#" class="thumbs-up${
//                                 media_like_dislike_status == "liked"
//                                     ? " active"
//                                     : ""
//                             }"><i class="far fa-thumbs-up"></i></a>
// 							<span class="thumbs-up-count">${media_meta.dataset.likes} </span>

// 						</div>
// 					</div>
// 					<a href="#" class="next"><i class="fas fa-angle-right fa-4x"></i></a>

// 				`;
//                 media_popup.style.display = "flex";
//                 // Prevent portrait images from exceeding the window

//                 window.addEventListener('resize', function(e) {

//                     if(screen.height > screen.width) {
//                         let height =
//                         media_popup.querySelector("img").getBoundingClientRect()
//                             .top -
//                         media_popup
//                             .querySelector(".con")
//                             .getBoundingClientRect().top;
//                     media_popup.querySelector(
//                         "img"
//                     ).style.maxHeight = `calc(100vh - ${height + 150}px)`;

//                     }else {
//                         console.log("jow");

//                         media_popup.querySelector(
//                             "img"
//                         ).style.maxHeight = `unset`;
//                         let popupCon = document.querySelector(".media-popup .con");

//                         popupCon.style="overflow-y: scroll";
//                     }
//                 })

//                 // Execute the media_mext_prev function
//                 media_next_prev(media_meta);
//                 // Execute the media_like_dislike function
//                 media_like_dislike(media_meta);
//             };
//             // Set the image source
//             img.src = media_meta.src;
//         } else {
//             // Determine the media type
//             let type_ele = "";
//             // If the media type is a video
//             if (media_meta.dataset.type == "video") {
//                 type_ele = `<video src="${media_meta.dataset.src}" width="852" height="480" controls autoplay></video>`;
//             }
//             // If the media type is a audio file
//             if (media_meta.dataset.type == "audio") {
//                 type_ele = `<audio src="${media_meta.dataset.src}" controls autoplay></audio>`;
//             }
//             // Populate the media
//             media_popup.innerHTML = `
// 				<a href="#" class="prev"><i class="fas fa-angle-left fa-4x"></i></a>
// 				<div class="con">
// 					<h3>${media_meta.dataset.title}</h3>
// 					<p>${media_meta.dataset.description}</p>
// 					${type_ele}
// 					<div class="thumbs-up-down">
// 						<a href="#" class="thumbs-up${
//                             media_like_dislike_status == "liked"
//                                 ? " active"
//                                 : ""
//                         }"><i class="far fa-thumbs-up"></i></a>
// 						<span class="thumbs-up-count">${media_meta.dataset.likes}</span>
// 						<a href="#" class="thumbs-down${
//                             media_like_dislike_status == "disliked"
//                                 ? " active"
//                                 : ""
//                         }"><i class="far fa-thumbs-down"></i></a>
// 						<span class="thumbs-down-count">${media_meta.dataset.dislikes}</span>
// 					</div>
// 				</div>
// 				<a href="#" class="next"><i class="fas fa-angle-right fa-4x"></i></a>
// 			`;
//             media_popup.style.display = "flex";
//             // Execute the media_next_prev function
//             media_next_prev(media_meta);
//             // Execute the media_like_dislike function
//             media_like_dislike(media_meta);
//         }
//     };
// });

// If the media popup element exists...
if (media_popup) {
  // Iterate the media links and create the onclick events
  document
    .querySelectorAll(".card-footer .popup-button")
    .forEach((media_link) => {
      // If the user clicks the media
      media_link.onclick = (e) => {
        e.preventDefault();

        // Retrieve the meta data
        let media_meta = media_link
          .closest(".card")
          .querySelector(".card-body .gallery-img");

        // Determine the active tab
        let active_tab = media_link.getAttribute("data-active-tab");

        // Retrieve the like/dislike status for the media
        let media_like_dislike_status = getCookieByName(
          "like_dislike_" + media_meta.dataset.id
        );

        // Create the popup content based on the active tab
        let popup_content = "";
        switch (active_tab) {
          case "image":
            // Create new image object
            let img = new Image();

            // Image onload event
            img.onload = () => {
              // Create the pop out image
              popup_content = `
                            <a href="#" class="prev"><i class="fas fa-angle-left fa-4x"></i></a>
                            <div class="con">
                                <div class="rj-close-popup">
                                    <i class="fa-solid fa-xmark"></i>
                                </div>
                                <h3>${media_meta.dataset.title}</h3>
                                <small style='margin-top:0.25em'>made in : ${
                                  media_meta.dataset.year
                                } - nr. ${media_meta.dataset.fnr} </small>
                                <p class="rj-popup-p">${media_meta.alt}</p>
                                <div class="prevnext-small-media">
                                    <a href="#" class="prev-small-media"><i class="fas fa-angle-left fa-4x"></i></a>
                                    <a href="#" class="next-small-media"><i class="fas fa-angle-right fa-4x"></i></a>
                                </div>
                                <div class="rj-popup-img-wrap"><img src="${
                                  img.src
                                }" alt=""></div>
                                <div class="thumbs-up-down">
                                    <a href="#" class="thumbs-up${
                                      media_like_dislike_status == "liked"
                                        ? " active"
                                        : ""
                                    }"><i class="far fa-thumbs-up"></i></a>
                                    <span class="thumbs-up-count">${
                                      media_meta.dataset.likes
                                    } </span> 
                                    <a href="#" class="thumbs-down${
                                      media_like_dislike_status == "disliked"
                                        ? " active"
                                        : ""
                                    }"><i class="far fa-thumbs-down"></i></a>
                                    <span class="thumbs-down-count">${
                                      media_meta.dataset.dislikes
                                    }</span>
                                </div>
                            </div>
                            <a href="#" class="next"><i class="fas fa-angle-right fa-4x"></i></a>
                        `;
              media_popup.style.display = "flex";
              media_popup.innerHTML = popup_content;

              // Prevent portrait images from exceeding the window
              window.addEventListener("resize", function (e) {
                if (screen.height > screen.width) {
                  let height =
                    media_popup.querySelector("img").getBoundingClientRect()
                      .top -
                    media_popup.querySelector(".con").getBoundingClientRect()
                      .top;
                  media_popup.querySelector(
                    "img"
                  ).style.maxHeight = `calc(100vh - ${height + 150}px)`;
                } else {
                  media_popup.querySelector("img").style.maxHeight = `unset`;
                  let popupCon = document.querySelector(".media-popup .con");
                  popupCon.style = "overflow-y: scroll";
                }
              });

              // Execute the media_next_prev function
              media_next_prev(media_meta);
              // Execute the media_like_dislike function
              media_like_dislike(media_meta);
            };
            // Set the image source
            img.src = media_meta.src;
            break;
          case "info":
            popup_content = `
                        <a href="#" class="prev"><i class="fas fa-angle-left fa-4x"></i></a>
                        <div class="con">
                            <div class="rj-close-popup">
                                <i class="fa-solid fa-xmark"></i>
                            </div>
                            <h3>${media_meta.dataset.title}</h3>
                            <p class="rj-popup-p">${
                              media_meta.dataset.description
                            }</p>
                            <div class="thumbs-up-down">
                                <a href="#" class="thumbs-up${
                                  media_like_dislike_status == "liked"
                                    ? " active"
                                    : ""
                                }"><i class="far fa-thumbs-up"></i></a>
                                <span class="thumbs-up-count">${
                                  media_meta.dataset.likes
                                } </span> 
                                <a href="#" class="thumbs-down${
                                  media_like_dislike_status == "disliked"
                                    ? " active"
                                    : ""
                                }"><i class="far fa-thumbs-down"></i></a>
                                <span class="thumbs-down-count">${
                                  media_meta.dataset.dislikes
                                }</span>
                            </div>
                        </div>
                        <a href="#" class="next"><i class="fas fa-angle-right fa-4x"></i></a>
                    `;
            media_popup.style.display = "flex";
            media_popup.innerHTML = popup_content;

            // Execute the media_next_prev function
            media_next_prev(media_meta);
            // Execute the media_like_dislike function
            media_like_dislike(media_meta);
            break;
          case "video":
            // Create video element
            let video = document.createElement("video");
            video.setAttribute("controls", "");
            video.setAttribute("autoplay", "");

            // Create source element
            let source = document.createElement("source");
            source.setAttribute("src", media_meta.dataset.src);
            source.setAttribute("type", "video/mp4");

            // Append the source element to the video element
            video.appendChild(source);

            // Create the popup video
            popup_content = `
                        <a href="#" class="prev"><i class="fas fa-angle-left fa-4x"></i></a>
                        <div class="con">
                            <div class="rj-close-popup">
                                <i class="fa-solid fa-xmark"></i>
                            </div>
                            <h3>${media_meta.dataset.title}</h3>
                            <p class="rj-popup-p">${
                              media_meta.dataset.description
                            }</p>
                            <div class="rj-popup-video-wrap">${
                              video.outerHTML
                            }</div>
                            <div class="thumbs-up-down">
                                <a href="#" class="thumbs-up${
                                  media_like_dislike_status == "liked"
                                    ? " active"
                                    : ""
                                }"><i class="far fa-thumbs-up"></i></a>
                                <span class="thumbs-up-count">${
                                  media_meta.dataset.likes
                                } </span> 
                                <a href="#" class="thumbs-down${
                                  media_like_dislike_status == "disliked"
                                    ? " active"
                                    : ""
                                }"><i class="far fa-thumbs-down"></i></a>
                                <span class="thumbs-down-count">${
                                  media_meta.dataset.dislikes
                                }</span>
                            </div>
                        </div>
                        <a href="#" class="next"><i class="fas fa-angle-right fa-4x"></i></a>
                    `;
            media_popup.style.display = "flex";
            media_popup.innerHTML = popup_content;

            // Execute the media_next_prev function
            media_next_prev(media_meta);
            // Execute the media_like_dislike function
            media_like_dislike(media_meta);
            break;
          default:
            break;
        }
      };
    });

  // Hide the image popup container, if the user clicks the close btn or outside the popup
  media_popup.onclick = (e) => {
    if (
      e.target.className == "media-popup" ||
      e.target.className == "fa-solid fa-xmark" ||
      e.target.className == "rj-close-popup"
    ) {
      media_popup.style.display = "none";
      media_popup.innerHTML = "";
    }
  };
}
// Check whether the upload form element exists, which basically means the user is on the upload page
if (upload_form) {
  // Upload form submit event
  upload_form.onsubmit = (event) => {
    event.preventDefault();
    // Create a new FormData object and retrieve data from the upload form
    let upload_form_date = new FormData(upload_form);
    // Append the upload file
    upload_form_date.append("media", upload_file);
    // Create a new AJAX request
    let request = new XMLHttpRequest();
    // POST request
    request.open("POST", upload_form.action);
    // Add the progress event
    request.upload.addEventListener("progress", (event) => {
      // Update the submit button with the current upload progress in percent format
      upload_form.querySelector("#submit_btn").value =
        "Uploading... " +
        "(" +
        ((event.loaded / event.total) * 100).toFixed(2) +
        "%)";
      // Disable the submit button
      upload_form.querySelector("#submit_btn").disabled = true;
    });
    // Check if the upload is complete or if there are any errors
    request.onreadystatechange = () => {
      if (request.readyState == 4 && request.status == 200) {
        // Upload is complete
        if (request.responseText.includes("Complete")) {
          // Output the successful response
          upload_form.querySelector("#submit_btn").value = request.responseText;
        } else {
          // Output the errors
          upload_form.querySelector("#submit_btn").disabled = false;
          upload_form.querySelector("#submit_btn").value = "Upload Media";
          document.querySelector(".upload .msg").innerHTML =
            request.responseText;
        }
      }
    };
    // Send the request
    request.send(upload_form_date);
  };
  // On media change, display the thumbnail form element, but only if the media type is either a video or image
  upload_media.onchange = () => {
    if (
      upload_media.files[0].type.toLowerCase().includes("audio") ||
      upload_media.files[0].type.toLowerCase().includes("video")
    ) {
      document
        .querySelectorAll(".thumbnail")
        .forEach((el) => (el.style.display = "flex"));
    } else {
      document
        .querySelectorAll(".thumbnail")
        .forEach((el) => (el.style.display = "none"));
    }
    // Add the filename to the drop zone
    upload_drop_zone.querySelector("p").innerHTML = upload_media.files[0].name;
    // Show preview
    media_preview(upload_media.files[0]);
  };
  // On drag and drop media file, do the same as the above code, but in addition, update the media file variable
  upload_drop_zone.ondrop = (event) => {
    event.preventDefault();
    if (
      event.dataTransfer.items &&
      event.dataTransfer.items[0].kind === "file"
    ) {
      // Get file
      let file = event.dataTransfer.items[0].getAsFile();
      // Make sure the file is an audio, video, or image
      if (
        file.type.toLowerCase().includes("audio") ||
        file.type.toLowerCase().includes("video") ||
        file.type.toLowerCase().includes("image")
      ) {
        upload_file = file;
        upload_drop_zone.querySelector("p").innerHTML = file.name;
      }
      if (
        file.type.toLowerCase().includes("audio") ||
        file.type.toLowerCase().includes("video")
      ) {
        document
          .querySelectorAll(".thumbnail")
          .forEach((el) => (el.style.display = "flex"));
      } else {
        document
          .querySelectorAll(".thumbnail")
          .forEach((el) => (el.style.display = "none"));
      }
      // Show preview
      media_preview(file);
    }
  };
  // Dragover drop zone event
  upload_drop_zone.ondragover = (event) => {
    event.preventDefault();
    // Update the element style; add CSS class
    upload_drop_zone.classList.add("dragover");
  };
  // Dragleave drop zone event
  upload_drop_zone.ondragleave = (event) => {
    event.preventDefault();
    // Update the element style; remove CSS class
    upload_drop_zone.classList.remove("dragover");
  };
  // Click drop zone event
  upload_drop_zone.onclick = (event) => {
    event.preventDefault();
    // Click the media file upload element, which will show the open file dialog
    document.querySelector(".upload #media").click();
  };
}

// Get modal elements
const imageModalClose = document.querySelectorAll(".image-modal-close");
const infoModalClose = document.querySelectorAll(".info-modal-close");
const audioModalClose = document.querySelectorAll(".audio-modal-close");
const videoModalClose = document.querySelectorAll(".video-modal-close");

// Get all media selection buttons
const imgBtns = document.querySelectorAll(".img-btn");
const infoBtns = document.querySelectorAll(".info-btn");
const audioBtns = document.querySelectorAll(".audio-btn");
const videoBtns = document.querySelectorAll(".video-btn");

// Get all modal containers
const imageModalContainers = document.querySelectorAll(
  ".image-modal-container"
);
const infoModalContainers = document.querySelectorAll(".info-modal-container");
const audioModalContainers = document.querySelectorAll(
  ".audio-modal-container"
);
const videoModalContainers = document.querySelectorAll(
  ".video-modal-container"
);

// Get all image containers
const imageContainers = document.querySelectorAll(".image-container");

// Intersection observer for lazy loading
// const options = {
//   rootMargin: '50px 0px',
//   threshold: 0.01
// };

// Add event listeners to each image container
imageContainers.forEach((container, index) => {
  // Get the media selection buttons and modal containers that correspond to this card
  const audioBtn = audioBtns[index];
  const videoBtn = videoBtns[index];
  const imgBtn = imgBtns[index];
  const infoBtn = infoBtns[index];
  const audioModalContainer = audioModalContainers[index];
  const videoModalContainer = videoModalContainers[index];
  const imageModalContainer = imageModalContainers[index];
  const infoModalContainer = infoModalContainers[index];

  //   const img = container.querySelector('img');

  // Get the image source from the data-src attribute
  //   const imgSrc = img.getAttribute('data-src');

  //   // Set the image source to the data-src attribute and add a load event listener
  //   img.setAttribute('src', imgSrc);
  //   img.addEventListener('load', () => {
  //     // Remove the data-src attribute to prevent lazy loading again
  //     img.removeAttribute('data-src');
  //   });

  // container.addEventListener('click', () => {
  //   // Get the image source from the clicked container
  //   const imageSource = img.getAttribute('src');

  //   // Set the modal image source and display the modal
  //   imageModalContainer.querySelector('img').setAttribute('src', imageSource);
  //   imageModalContainer.style.display = 'block';
  // });
  if (imgBtn) {
    imgBtn.addEventListener("click", () => {
      // Get the image source from the clicked container
      const imageSource = imgBtn.getAttribute("data-src");

      // Set the modal image source and display the modal
      imageModalContainer.querySelector("img").setAttribute("src", imageSource);
      imageModalContainer.style.display = "block";
    });
  }

  // Add click event listener to audio button
  if (audioBtn) {
    audioBtn.addEventListener("click", () => {
      // Get the audio source from the button data attribute
      const audioSource = audioBtn.getAttribute("data-src");

      // Set the audio source and display the audio modal
      audioModalContainer
        .querySelector("audio")
        .setAttribute("src", audioSource);
      audioModalContainer.style.display = "block";
    });
  }

  // Add click event listener to video button
  if (videoBtn) {
    videoBtn.addEventListener("click", () => {
      // Get the video source from the button data attribute
      const videoSource = videoBtn.getAttribute("data-src");

      // Set the video source and display the video modal
      videoModalContainer
        .querySelector("video")
        .setAttribute("src", videoSource);
      videoModalContainer.style.display = "block";
    });
  }
  // Add click event listener to info button
  if (infoBtn) {
    infoBtn.addEventListener("click", () => {
      // Get the video source from the button data attribute
      const infoSource = infoBtn.getAttribute("data-info");
      const infoTitle = infoBtn.getAttribute("data-title");

      infoModalContainer.querySelector("h3").innerText = infoTitle;
      infoModalContainer.querySelector("pre").innerText = infoSource;
      infoModalContainer.style.display = "block";
    });
  }
});

// Add click event listener to the audio modal close buttons
audioModalClose.forEach((closeBtn) => {
  closeBtn.addEventListener("click", () => {
    // Stop the audio and hide the audio modal
    const audioModalAudio = closeBtn.parentNode.querySelector("audio");
    audioModalAudio.pause();
    audioModalAudio.currentTime = 0;
    closeBtn.parentNode.parentNode.style.display = "none";
  });
});

// Add click event listener to the video modal close buttons
videoModalClose.forEach((closeBtn) => {
  closeBtn.addEventListener("click", () => {
    // Stop the video and hide the video modal
    const videoModalVideo = closeBtn.parentNode.querySelector("video");
    videoModalVideo.pause();
    videoModalVideo.currentTime = 0;
    closeBtn.parentNode.parentNode.style.display = "none";
  });
});

// Add click event listener to the image modal close buttons
imageModalClose.forEach((closeBtn) => {
  closeBtn.addEventListener("click", () => {
    // Hide the image modal
    closeBtn.parentNode.parentNode.style.display = "none";
  });
});

// Add click event listener to the info modal close buttons
infoModalClose.forEach((closeBtn) => {
  closeBtn.addEventListener("click", () => {
    // Hide the info modal
    closeBtn.parentNode.parentNode.style.display = "none";
  });
});

// const lazyImages = document.querySelectorAll('[data-imgsrc]');

// const options = {
//   rootMargin: '300px 0px',
//   threshold: [0, 1]
// };

// let jos = 0;
// const observer = new IntersectionObserver(entries => {
//   entries.forEach(entry => {
//     if (entry.isIntersecting) {
// 			jos++;
// 			console.log('intersect ' + jos );
//       const image = entry.target;
//       const src = image.dataset.imgsrc;

//       // Create a new image element to act as the placeholder
//       const placeholder = new Image();
//       placeholder.src = 'assets\\img\\bginverted.jpg';
//       placeholder.classList.add('lazy-image-placeholder');
//       image.parentElement.insertBefore(placeholder, image);

//       // Hide the real image and show the placeholder until the real image is loaded
//       image.style.opacity = '0';
//       placeholder.style.opacity = '1';
//       const imgLoad = new Image();
//       imgLoad.src = src;
//       imgLoad.addEventListener('load', () => {
//         image.src = src;
//         image.style.opacity = '1';
//         placeholder.style.opacity = '0';
//         setTimeout(() => placeholder.remove(), 750);
//         image.classList.add('lazy-image-loaded');
//       });

//       observer.unobserve(image);
// 			image.classList.add('loaded')
//     }
//   });
// }, options);

// lazyImages.forEach(image => {
//   observer.observe(image);
// });
