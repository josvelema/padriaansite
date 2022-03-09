
let articles = ["main", "biblio", "biblioInfo", "talksEtc","projects", "patents", "fundamentals"];
let visibleId = null;

function show(id) {
  if (visibleId !== id) {
    visibleId = id;

  }
  hide();
}

function hide() {
  let div, i, id;
  for (i = 0; i < articles.length; i++) {
    id = articles[i];
    div = document.getElementById(id);
    if (visibleId === id) {
      div.style.display = "block";
    } else {
      div.style.display = "none";
    }
  }
}