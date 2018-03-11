/**
 * Created by Mickey on 09/03/2018.
 */

/* JS NEW OBS MAP */

$(document).ready(function() {

  /* JS  MAP */
  $("#mapPanelButtonControl").click(function() {
    $("#mapPanelContainer").slideUp();
    $("#mapPanelButtonControl").fadeOut();
  });
  $("#mapPanelButtonSearch").click(function() {
    $("#mapPanelContainer").fadeIn();
    $("#mapPanelButtonControl").fadeIn();
  });
  $("#mapPanelButtonList").click(function() {
    $("#mapPanelContainer").fadeIn();
    $("#mapPanelButtonControl").fadeIn();
  });

  var myLatitude = $('#myLatInput');
  var myLongitude = $('#myLongInput');
  var mymap = L.map('mapContainer').setView([46.780, 3.001], 6);

  L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
      '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
      'Imagery © <a href="http://mapbox.com">Mapbox</a>',
    id: 'mapbox.streets'
  }).addTo(mymap);

  /*  mymap.locate({setView: true, maxZoom: 18});*/

  function onLocationFound(e) {
    var radius = e.accuracy / 2;

    myLatitude.val(e.latlng.lat);
    myLongitude.val(e.latlng.lng);

    L.marker(e.latlng).addTo(mymap)
      .bindPopup("Vous êtes dans un périmètre de " + radius + " metres de ce point").openPopup();

    L.circle(e.latlng, radius).addTo(mymap);
  }

  function onLocationError(e) {
    alert(e.message);
  }

  mymap.on('locationfound', onLocationFound);
  mymap.on('locationerror', onLocationError);

  var popupLocation = L.popup();

  function onMapClick(e) {
    popupLocation
      .setLatLng(e.latlng)
      .setContent("Vous avez cliqué aux coordonnées suivante: Latitude " + e.latlng.lat.toString() + " - Longitude " + e.latlng.lng.toString())
      .openOn(mymap);
  }

  mymap.on('click', onMapClick);

  mymap.on('click', function(e) {
    myLatitude.val(e.latlng.lat);
    myLongitude.val(e.latlng.lng);
  });

});
