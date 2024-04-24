// control pagination
const pageItemPrev = document.getElementById("page-item-prev");
const pageItemNext = document.getElementById("page-item-next");
const pageItems = document.querySelectorAll(".pagination .page-item");

pageItemPrev.addEventListener("click", function (e) {
  e.preventDefault();
  let prev = document.querySelector(
    ".pagination .active"
  ).previousElementSibling;
  if (prev) {
    prev.querySelector("a").click();
  }
});

pageItemNext.addEventListener("click", function (e) {
  e.preventDefault();
  let next = document.querySelector(".pagination .active").nextElementSibling;
  if (next) {
    next.querySelector("a").click();
  }
});
