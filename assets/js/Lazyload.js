

console.log("laaad");
export default new IntersectionObserver((entries, observer) => {
  entries.forEach(function (entry) {
    if (entry.intersectionRatio > 0 || entry.isIntersecting) {
      console.log("intersss");
      const image = entry.target;
      observer.unobserve(image);

      if (image.hasAttribute('src')) {
        return;
      }

      const i = dom(image);
      i.css('visibility', 'hidden');

      // Image has not been loaded so load it
      const sourceUrl = image.getAttribute('data-src');
      console.log("hoi heeft data src");
      console.log(sourceUrl);
      image.setAttribute('src', sourceUrl);

      const starttime = Date.now();

      image.onload = () => {
        if (Date.now() - starttime < 100) {
            i.css({position: 'absolute', bottom: 4, visibility: 'visible'});
        } else {
          const t = Math.random() * 500;
          setTimeout( () => {
            const r = Math.random()*100-50;
            i.css({
              position: 'absolute',
              bottom: 300,
              visibility: 'visible',
              transform: `rotate(${r}deg)`
            });

            i.animate('bottom', 4, 550, 'easeInQuad');
            i.animate('rotate', 0, 550, 'easeInQuad');
          }, t);
        }
      }

      // Removing the observer
      observer.unobserve(image);
    }
  });
}, {
  rootMargin: '500px 0px',
  threshold: [0, 0.25, 0.5, 0.75, 1]
});
