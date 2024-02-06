let aside = document.querySelector("aside"), main = document.querySelector("main"), header = document.querySelector("header");
console.log('jos')
let asideopen = false;
document.querySelector(".aside-toggle").onclick = function(event) {
    event.preventDefault();
    aside.classList.toggle("open")
    main.classList.toggle("full")
    header.classList.toggle("full")
};


// if (aside.classList.contains("open")) {
        
//   main.classList.remove("full");
//   header.classList.remove("full");
// } else {
  
//   main.classList.add("full");
//   header.classList.add("full");
// }



// document.querySelector(".aside-toggle").onclick = function(event) {
//   event.preventDefault();
//   let aside = document.querySelector("aside"), main = document.querySelector("main"), header = document.querySelector("header");
//   aside.classList.toggle("open")
  
//   if (aside.classList.contains("open")) {
      
//       main.classList.remove("full");
//       header.classList.remove("full");
//   } else {
      
//       main.classList.add("full");
//       header.classList.add("full");
//   }
// };
