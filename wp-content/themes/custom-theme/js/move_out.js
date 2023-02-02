console.log('mouve out loaded');

var wpcf7Elm = document.querySelector( '.wpcf7' );
 
wpcf7Elm.addEventListener( 'wpcf7submit', function( event ) {
  gtag('event', 'Move Out');
}, false );
