
/*===================================================================
  FONCTION ACF (modifiée)
===================================================================*/

(function($) {
    // var qui va contenir l'infowindow ouverte
  var openWindow = 0;
  var map = {};
  var markers = [];


/*  RENDER MAP
=====================================================================
*
*  This function will render a Google Map onto the selected jQuery element
*
*  @type  function
*  @date  8/11/2013
*  @since 4.3.0
*
*  @param $el (jQuery element)
*  @return  n/a
*/

function render_map( $el ) {

  // var
  $markers = $el.find('.marker');

  // vars
  var args = {
    zoom      : 16,
    center    : new google.maps.LatLng(0, 0),
    mapTypeId : google.maps.MapTypeId.ROADMAP,
  };


  // create map
  var map = new google.maps.Map( $el[0], args);

  // add a markers reference
  map.markers = [];

  // add markers
  $markers.each(function(){

    add_marker( $(this), map );

  });

  // center map
  center_map( map );
  return map;
}

/*  ADD MARKER
=====================================================================
*
*  This function will add a marker to the selected Google Map
*
*  @type  function
*  @date  8/11/2013
*  @since 4.3.0
*
*  @param $marker (jQuery element)
*  @param map (Google Map object)
*  @return  n/a
*/

function add_marker( $marker, map ) {

  // var
  var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );

  // définit si le marker a une icone custom
  var image = {};
  if ('data-img') {
    image = {
      url: $marker.attr('data-img'),
      size: new google.maps.Size(23, 30),
      origin: new google.maps.Point(0,0),
      anchor: new google.maps.Point(11, 15),
    };
  } else {
    image = null;
  }

  // create marker
  var marker = new google.maps.Marker({
    position  : latlng,
    map       : map,
    icon      : image
  });

  // add to array
  map.markers.push( marker );
  markers = map.markers;

  // if marker contains HTML, add it to an infoWindow
  if( $marker.html() )
  {
    // create info window
    var infowindow = new google.maps.InfoWindow({
      content   : $marker.html()
    });

    // show info window when marker is clicked
    google.maps.event.addListener(marker, 'click', function() {
      // si une infowindow est ouverte
      if (openWindow !== 0) {
        // on la ferme
        openWindow.close();
      }
      // on ouvre la nouvelle
      openWindow = infowindow;
      infowindow.open( map, marker );
    });
    // Quand on clique sur la carte, si une infowindow est ouverte, on la ferme
    google.maps.event.addListener(map, 'click', function () {
      if (openWindow !== 0) {
        openWindow.close();
      }
    });
  }
}


/*  CENTER MAP
=====================================================================
*
*  This function will center the map, showing all markers attached to this map
*
*  @type  function
*  @date  8/11/2013
*  @since 4.3.0
*
*  @param map (Google Map object)
*  @return  n/a
*/

function center_map( map ) {

  // vars
  var bounds = new google.maps.LatLngBounds();

  // loop through all markers and create bounds
  $.each( map.markers, function( i, marker ){

    var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

    bounds.extend( latlng );

  });

  // only 1 marker?
  if( map.markers.length == 1 )
  {
    // set center of map
      map.setCenter( bounds.getCenter() );
      map.setZoom( 16 );
  }
  else
  {
    // fit to bounds
    map.fitBounds( bounds );
  }
}

/*  affiche/cache les markers de la catégorie correspondante
===================================================================*/

// Sets the map on all markers in the array.
function setAllMap(map) {
  for (var i = 0; i < markers.length; i++) {
    markers[i].setMap(map);
  }
}

function gmccInit() {
  // Récupère l'élément de classe gmcc (google maps custom controls)
  $gmcc = $('.gmcc');
  $gmccLink = $('li.gmcc__filter');

  var checkboxContent = '<label><span>';

  $gmccLink.each(function (i) {
    checkboxContent += $(this).children('a').html();
    checkboxContent += '</span><input type="checkbox"></label>';
    $(this).replaceWith(checkboxContent);
  });


  // var checkbox = $gmcc.find('a').replaceWith(checkboxContent);

  gMap.controls[google.maps.ControlPosition.TOP_LEFT].push(
  document.getElementById('gmcc_wrapper'));

  $gmcc.find('a').on('click', function (e) {

   e.preventDefault();
   $filterCat = $(this).attr('data-cat');

     // EFFACER tous les repères qui ne sont pas de la catégorie cliquée
     setAllMap(null);

   // Boucle sur tous les markers (objets jQuery)
   $markers.each(function() {
     $markerCat = $(this).attr('data-cat');

     // vérifie la catégorie des markers par rapport à celle du bouton cliqué
     // si MÊME CATEGORIE, alors on affiche le marqueur
     if ($markerCat == $filterCat) {
         add_marker($(this), gMap);
     }
   });
 });
}

/*  DOCUMENT READY
=====================================================================
*
*  This function will render each map when the document is ready (page has loaded)
*
*  @type  function
*  @date  8/11/2013
*  @since 5.0.0
*
*  @param n/a
*  @return  n/a
*/

$(document).ready(function(){


  $('.acf-map').each(function(){

    gMap = render_map($(this));

  });
  gmccInit();

});


})(jQuery);
