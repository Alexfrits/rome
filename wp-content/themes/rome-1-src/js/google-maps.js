(function () {

  // Déclaration des var hors de la fct init
  var map;
  var poly;

  /*===================================================================
    1. INITIALISATION
  ===================================================================*/

  /*  MAP
  ===================================================================*/

  // initialise la map
  function initialize() {
    var romeLatLng = [
      new google.maps.LatLng(41.897934, 12.484843),  // position de la carte et du premier marker
      new google.maps.LatLng(41.89343, 12.475) // position du 2e marker
    ];
    var mapOptions = {
      center: romeLatLng[0],
      zoom: 14
    };
    // création d'une nouvelle instance de carte 
    var map = new google.maps.Map(document.getElementById('map-canvas'),
        mapOptions);


    /*  Marker
    ===================================================================*/
    // ajout du Marker
    var marker = new google.maps.Marker({
        position: romeLatLng[0],
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,  // animation de largage du marker au chargement
        title:"Hello World!"
    });

    marker = new google.maps.Marker({
        position: romeLatLng[1],
        map: map,
        draggable: true,
        animation: google.maps.Animation.DROP,  // animation de largage du marker au chargement
        title:"nouveau marker"
    });

    /*
    // quand on clique sur marker, rebondit
    google.maps.event.addListener(marker, 'click', toggleBounce);

    // fonction qui fait rebondir
    function toggleBounce() {
      if (marker.getAnimation() !== null) {
        marker.setAnimation(null);
      } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);
      }
    }
    */

    // quand on clique sur le marker, ouvre l'info window
    google.maps.event.addListener(marker, 'click', function () {
      infowindow.open(map, marker);
    });

    /*  Info window
    ===================================================================*/
    
    var contentString = 'test';

    var infowindow = new google.maps.InfoWindow({
      content: contentString
    });

    /*  Polyline
    ===================================================================*/

  //   // tracer une ligne entre plusieurs points
  //   var romeShape = new google.maps.Polyline({
  //     path: romeLatLng,
  //     geodesic: true,
  //     strokeColor: '#FFF',
  //     strokeOpacity: 1.0,
  //     strokeWeight: 2
  //   });

  //   romeShape.setMap(map);

  // }

  // initialisation du polyline utilisé dans l'ajout de points au clic
   var polyOptions = {
    strokeColor: '#0ff',
    strokeOpacity: 1.0,
    strokeWeight: 3
  };
  poly = new google.maps.Polyline(polyOptions);
  poly.setMap(map);

  // Add a listener for the click event
  google.maps.event.addListener(map, 'click', addLatLng);
}

  // charge la map une fois que la fenêtre a fini de tout charger
  google.maps.event.addDomListener(window, 'load', initialize);


  /*===================================================================
    2. AJOUT DE POINTS AU CLIC
  ===================================================================*/
  


})();