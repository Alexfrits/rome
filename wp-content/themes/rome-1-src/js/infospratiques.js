(function($) {

  /*
  *   comme le script est chargé au début du doc,
  *   on doit attendre que les éléments du dom soit chargés
  *   avant de pouvoir interagir dessus
  */

  $(document).ready(function () {
    $gmcc = $('.gmcc');
    
    $gmcc.find('a').on('click', function (e) {
      e.preventDefault();
      console.log(this);
    });
  });
})(jQuery);