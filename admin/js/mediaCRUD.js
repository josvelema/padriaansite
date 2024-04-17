// determin the current page
let currentPage = window.location.pathname;
console.log(currentPage);

let delModal = document.querySelector('.delMediaModalWrap');

let delModalMediaContent = `<label for="rj-modal" class="rj-modal-background"></label>
      <div class="rj-modal">
      <div class="modal-header">
      <h3>Confirm deletion</h3>
        <label for="rj-modal">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAMAAAAoLQ9TAAAAdVBMVEUAAABNTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU1NTU0N3NIOAAAAJnRSTlMAAQIDBAUGBwgRFRYZGiEjQ3l7hYaqtLm8vsDFx87a4uvv8fP1+bbY9ZEAAAB8SURBVBhXXY5LFoJAAMOCIP4VBRXEv5j7H9HFDOizu2TRFljedgCQHeocWHVaAWStXnKyl2oVWI+kd1XLvFV1D7Ng3qrWKYMZ+MdEhk3gbhw59KvlH0eTnf2mgiRwvQ7NW6aqNmncukKhnvo/zzlQ2PR/HgsAJkncH6XwAcr0FUY5BVeFAAAAAElFTkSuQmCC" width="16" height="16" alt="" onclick="closeDelMediaModal()">
        </label>
        </div>
        <p>
        Delete Media?<br>
        `;


function deleteMediaModal(id, getParams) {
    let media_id = id;
    let link = `<a href="`+ currentPage + `?delete=` + media_id + `&` + getParams + `" class="rj-modal-btn">Confirm</a><br><a href="allmedia.php" onClick="closeDelMediaModal()" class="rj-modal-btn">Cancel</a> </p></div>`
    console.log(link);
    delModal.innerHTML = delModalMediaContent + link;
}


function closeDelMediaModal() {
    delModal.style.display = "none";

}