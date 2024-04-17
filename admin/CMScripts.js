let aside = document.querySelector("aside"), main = document.querySelector("main"), header = document.querySelector("header");

let asideopen = false;
document.querySelector(".aside-toggle").onclick = function(event) {
    event.preventDefault();
    aside.classList.toggle("open")
    main.classList.toggle("full")
    header.classList.toggle("full")
};

