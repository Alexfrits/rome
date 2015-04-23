
/*===================================================================
  FONCTION ACF (modifiée)
===================================================================*/

(function($) {

// force le redraw dans chrome (workaround pour les SVG)
function redraw (element){
  var n = document.createTextNode(' ');
  element.append(n);
  (function(){
    n.parentNode.removeChild(n);
  })();
}

var openWindow = 0;

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
  if ($marker.attr('data-img')) {
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
    // icon      : image
  });

  // add custom property to the gMaps marker object
  marker.cat = $marker.attr('data-cat');

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
      if (openWindow !== 0) { openWindow.close(); }
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

  // ajoute la classe active à tous les filtres et supprime leur couleur
  $('.gmcc__marker').attr('class', 'gmcc__marker active')
  .find('.gmcc__marker__bg').removeAttr('fill');
  $gmcc.find('.gmcc__filter').addClass('active');

  // Récup la liste de liens (filtres), les converti en controls et les positionne sur la map
  gMap.controls[google.maps.ControlPosition.RIGHT_CENTER].push(
    document.getElementById('gmcc_wrapper')
  );

    function drawCatMarkers (category, map) {
    // cache les markers dont la catégorie est la même que celle du bouton cliqué
    for (var i = 0; i < markers.length; i++) {
      if (markers[i].cat === category) {
        markers[i].setMap(map);
      }
    }
  }

  function clearCatMarkers (category) {
    // cache les markers dont la catégorie est la même que celle du bouton cliqué
    for (var i = 0; i < markers.length; i++) {
      if (markers[i].cat === category) {
        markers[i].setMap(null);
      }
    }
  }

  // attribution du click
  $gmcc.find('a').on('click', function (e) {
    e.preventDefault();

    $theFilter = $(this).parents('.gmcc__filter');
    $filterCat = $(this).attr('data-cat');
    $theIcon = $('#icon-' + $filterCat);

    if($theFilter.hasClass('active')) {

      // retire les classes 'active des éléments de DOM'
      $theFilter.removeClass('active');
      $theIcon.attr('class', 'gmcc__marker');
      // supprime les markers correspondant de la map
      clearCatMarkers($filterCat);
      // redraw les icones SVG (fix chrome)
      redraw($theIcon);
    } else {
      $theFilter.addClass('active');
      $theIcon.attr('class', 'gmcc__marker active');

      drawCatMarkers($filterCat, gMap);

      redraw($theIcon);
    }
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

  if($('.acf-map').length) {
    // initialisation des var inter-fonctions
    var markers = [];
    var map = {};

    $('.acf-map').each(function(){

      gMap = render_map($(this));

    });
    gmccInit();
  }
});
})(jQuery);
