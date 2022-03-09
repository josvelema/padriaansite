var NumberHandler = new Class( {
  
  id: null,
  linksId: 'homeHeaderLinks',
  currentSlide: [0,0],
  flexApp: null,
  
  initialize: function ( id, linksId )
  {
    this.id = id;
    this.linksId = linksId;
    document.addEvent( 'domready', this.boot.bind(this) );
  },
  
  boot: function () {
    var appName = this.id;
    this.flexApp = (navigator.appName.indexOf ("Microsoft") !=-1) ? window[appName] : document[appName];
  },
  
  slideListener: function( slideshow, slide, token )
  {
    this.currentSlide = [slideshow, slide];
    var target = $(this.linksId).getElement( '[rel=headerLink_' + slideshow + '_' + slide + ']' );
    if ( target ) {
      $(this.linksId).getElements('a.current').removeClass( 'current' );
      $(target).addClass( 'current' );
    }
  },
  
  jump: function ( slideshow, slide ) {
    if ( this.currentSlide[0] != slideshow || this.currentSlide[1] != slide ) {
      this.flexApp.jump( slideshow, slide );
    }
  }
} );