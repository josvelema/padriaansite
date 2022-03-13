window.onscroll = function () {
    var e = document.getElementById("scrolltop");
    if (!e) {
        e = document.createElement("a");
        e.id = "scrolltop";
        e.href = "#";
        document.body.appendChild(e);
    }
    e.style.display =
        document.documentElement.scrollTop > 300 ? "block" : "none";
    e.onclick = (ev) => {
        ev.preventDefault();
        document.documentElement.scrollTop = 0;
    };
};

if (window.location.pathname == "/pieter/index.php") {
    document.querySelector("#home").classList.add("rj-current-nav");
}
if (window.location.pathname == "/pieter/blog.php") {
    document.querySelector("#blog").classList.add("rj-current-nav");
}
if (window.location.pathname == "/pieter/science.php") {
    document.querySelector("#science").classList.add("rj-current-nav");
}
if (window.location.pathname == "/pieter/painting.php") {
    document.querySelector("#painting").classList.add("rj-current-nav");
}
if (window.location.pathname == "/pieter/music.php") {
    document.querySelector("#music").classList.add("rj-current-nav");
}
if (window.location.pathname == "/pieter/gallery.php") {
    document.querySelector("#gallery").classList.add("rj-current-nav");
}
if (window.location.pathname == "/pieter/contact.php") {
    document.querySelector("#contact").classList.add("rj-current-nav");
}

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
        let prev_ele = document.querySelector(
            '[data-id="' + media_meta.dataset.id + '"]'
        ).parentElement.previousElementSibling;
        // If the prev element exists, click it
        if (prev_ele) prev_ele.click();
    };

    prev_btn_sm.onclick = (event) => {
        event.preventDefault();
        // Determine the previous element (media)
        let prev_ele = document.querySelector(
            '[data-id="' + media_meta.dataset.id + '"]'
        ).parentElement.previousElementSibling;
        // If the prev element exists, click it
        if (prev_ele) prev_ele.click();
    };

    // Add the onclick event
    next_btn.onclick = (event) => {
        event.preventDefault();
        // Determine the next element (media)
        let next_ele = document.querySelector(
            '[data-id="' + media_meta.dataset.id + '"]'
        ).parentElement.nextElementSibling;
        // If the next element exists, click it
        if (next_ele) next_ele.click();
    };

    next_btn_sm.onclick = (event) => {
        event.preventDefault();
        // Determine the next element (media)
        let next_ele = document.querySelector(
            '[data-id="' + media_meta.dataset.id + '"]'
        ).parentElement.nextElementSibling;
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
                        parseInt(
                            media_popup.querySelector(".thumbs-up-count")
                                .innerHTML
                        ) + 1;
                    like_btn.classList.add("active");
                }
            });
    };
    // Add the onclick event
    dislike_btn.onclick = (event) => {
        event.preventDefault();
        // Use AJAX to update the value in the database
        fetch("like-dislike.php?id=" + media_meta.dataset.id + "&type=dislike")
            .then((res) => res.text())
            .then((data) => {
                // Increment the thumbs down value
                if (data.includes("success")) {
                    media_popup.querySelector(".thumbs-down-count").innerHTML =
                        parseInt(
                            media_popup.querySelector(".thumbs-down-count")
                                .innerHTML
                        ) + 1;
                    dislike_btn.classList.add("active");
                }
            });
    };
};
// Handle media preview
const media_preview = (file) => {
    let reader = new FileReader();
    reader.onload = () => {
        document.querySelector("#preview").style.display = "block";
        if (file.type.toLowerCase().includes("image")) {
            document.querySelector(
                "#preview"
            ).innerHTML = `<img src="${reader.result}" alt="preview" style="max-height:300px;max-width:100%;">`;
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
if (media_popup) {
    // Iterate the images and create the onclick events
    document.querySelectorAll(".media-list a").forEach((media_link) => {
        // If the user clicks the media
        media_link.onclick = (e) => {
            e.preventDefault();
            // Retrieve the meta data
            let media_meta = media_link.firstElementChild;
            // Retrieve the like/dislike status for the media
            let media_like_dislike_status = getCookieByName(
                "like_dislike_" + media_meta.dataset.id
            );
            // If the media type is an image
            if (media_meta.dataset.type == "image") {
                // Create new image object
                let img = new Image();
                // Image onload event
                img.onload = () => {
                    // <a href="#" class="thumbs-down
                    // ${media_like_dislike_status == 'disliked' ? ' active' : ''}
                    // "><i class="far fa-thumbs-down"></i></a>
                    // <span class="thumbs-down-count">${media_meta.dataset.dislikes}</span>

                    // Create the pop out image
                    media_popup.innerHTML = `
						<a href="#" class="prev"><i class="fas fa-angle-left fa-4x"></i></a>
						<div class="con">
						<div class="rj-close-popup">
						<i class="fa-solid fa-xmark"></i>
						</div>
							<h3>${
                                media_meta.dataset.title
                            }</h3><small style='margin-top:0.25em'>made in : ${
                        media_meta.dataset.year
                    } - nr. ${media_meta.dataset.fnr} </small>
							<p>${media_meta.alt}</p>
                            <div class="prevnext-small-media">
                            <a href="#" class="prev-small-media"><i class="fas fa-angle-left fa-4x"></i></a>
                        <a href="#" class="next-small-media"><i class="fas fa-angle-right fa-4x"></i></a>
                            </div>
							<img src="${img.src}" width="${img.width}" height="${img.height}" alt="">
							<div class="thumbs-up-down">
								<a href="#" class="thumbs-up${
                                    media_like_dislike_status == "liked"
                                        ? " active"
                                        : ""
                                }"><i class="far fa-thumbs-up"></i></a>
								<span class="thumbs-up-count">${media_meta.dataset.likes} </span> 
								
							</div>
						</div>
						<a href="#" class="next"><i class="fas fa-angle-right fa-4x"></i></a>

					`;
                    media_popup.style.display = "flex";
                    // Prevent portrait images from exceeding the window
                    let height =
                        media_popup.querySelector("img").getBoundingClientRect()
                            .top -
                        media_popup
                            .querySelector(".con")
                            .getBoundingClientRect().top;
                    media_popup.querySelector(
                        "img"
                    ).style.maxHeight = `calc(100vh - ${height + 150}px)`;
                    // Execute the media_mext_prev function
                    media_next_prev(media_meta);
                    // Execute the media_like_dislike function
                    media_like_dislike(media_meta);
                };
                // Set the image source
                img.src = media_meta.src;
            } else {
                // Determine the media type
                let type_ele = "";
                // If the media type is a video
                if (media_meta.dataset.type == "video") {
                    type_ele = `<video src="${media_meta.dataset.src}" width="852" height="480" controls autoplay></video>`;
                }
                // If the media type is a audio file
                if (media_meta.dataset.type == "audio") {
                    type_ele = `<audio src="${media_meta.dataset.src}" controls autoplay></audio>`;
                }
                // Populate the media
                media_popup.innerHTML = `
					<a href="#" class="prev"><i class="fas fa-angle-left fa-4x"></i></a>
					<div class="con">
						<h3>${media_meta.dataset.title}</h3>
						<p>${media_meta.dataset.description}</p>
						${type_ele}
						<div class="thumbs-up-down">
							<a href="#" class="thumbs-up${
                                media_like_dislike_status == "liked"
                                    ? " active"
                                    : ""
                            }"><i class="far fa-thumbs-up"></i></a>
							<span class="thumbs-up-count">${media_meta.dataset.likes}</span>
							<a href="#" class="thumbs-down${
                                media_like_dislike_status == "disliked"
                                    ? " active"
                                    : ""
                            }"><i class="far fa-thumbs-down"></i></a>
							<span class="thumbs-down-count">${media_meta.dataset.dislikes}</span>
						</div>
					</div>
					<a href="#" class="next"><i class="fas fa-angle-right fa-4x"></i></a>
				`;
                media_popup.style.display = "flex";
                // Execute the media_next_prev function
                media_next_prev(media_meta);
                // Execute the media_like_dislike function
                media_like_dislike(media_meta);
            }
        };
    });
    // Hide the image popup container, but only if the user clicks outside the image
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
                    upload_form.querySelector("#submit_btn").value =
                        request.responseText;
                } else {
                    // Output the errors
                    upload_form.querySelector("#submit_btn").disabled = false;
                    upload_form.querySelector("#submit_btn").value =
                        "Upload Media";
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
        upload_drop_zone.querySelector("p").innerHTML =
            upload_media.files[0].name;
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
