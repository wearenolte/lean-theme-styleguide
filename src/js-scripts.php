<script>
  document.querySelectorAll( '.js-collapse' ).forEach( function ( collapseContainer ) {
    collapseContainer.querySelector( '.js-collapse-button' ).addEventListener( 'click', function () {
      var content = collapseContainer.querySelector( '.js-collapse-content' )

      if ( content.classList.contains( 'sg-hidden' ) ) {
        content.classList.remove( 'sg-hidden' )
      } else {
        content.classList.add( 'sg-hidden' )
      }
    } )
  } )
</script>