/**
 * Created by wilfriedcottineau on 08/03/2018.
 */

/* JS NEW OBS MAP */

$(document).ready(function() {

    var myNewObsLat = $('#ornito_observationbundle_watching_latitude');
    var myNewObsLng = $('#ornito_observationbundle_watching_longitude');
    var geoButtonElt = $('#newObsGeolocalisatorButtonForm');
    var myObsMap = L.map('newObsMap').setView([46.780, 3.001], 5);

    // Display map
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 25,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(myObsMap);
    myObsMap.attributionControl.setPrefix('NAO');

    // Set Lat + Lon fields form & display a marker on map when you are found
    function newObsOnLocationFound(e) {
        var myObsMapRadius = e.accuracy / 2;

        L.marker(e.latlng).addTo(myObsMap)
            .bindPopup("<center>Vous êtes dans un périmètre de </br>" + myObsMapRadius + " mètres de ce point </center>\n<center>You are within a </br>" + myObsMapRadius + " meters radius from here.</center>").openPopup();
        L.circle(e.latlng, myObsMapRadius).addTo(myObsMap);
        myNewObsLat.val(e.latlng.lat);
        myNewObsLng.val(e.latlng.lng);
    }

    // If available, get geolocation on call or return a display message
    function getLocationLeaflet() {
        myObsMap.on('locationfound', newObsOnLocationFound);
        myObsMap.on('locationerror', newObsOnLocationError);
        myObsMap.locate({setView: true, maxZoom: 16});
    }

    // The error message when geolocation is not available
    function newObsOnLocationError(e) {
        alert('Erreur de géolocalisation: l\'utilisateur a refusé la géolocalisation.\n' +e.message);
    }

    var newObsPopupLocation = L.popup();

    // Set Lat + Lon fields form & display a popup on map
    function newObsOnMapClick(e) {
        newObsPopupLocation
            .setLatLng(e.latlng)
            .setContent("<center>Vous avez cliqué à :<center><center>You clicked the map at :<center>" + e.latlng.toString())
            .openOn(myObsMap);
        myNewObsLat.val(e.latlng.lat);
        myNewObsLng.val(e.latlng.lng);
    }

    // Events called on map & button click
    myObsMap.on('click', newObsOnMapClick);
    geoButtonElt.on('click', getLocationLeaflet);
});