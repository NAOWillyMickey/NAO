/**
 * Created by wilfriedcottineau on 09/03/2018.
 */

/* JS EDIT OBS MAP */

$(document).ready(function() {

    var myNewObsLat = $('#ornito_observationbundle_watching_latitude');
    var myNewObsLng = $('#ornito_observationbundle_watching_longitude');
    var lat = document.getElementById("ornito_observationbundle_watching_latitude").value;
    var lon = document.getElementById("ornito_observationbundle_watching_longitude").value;
    var geoButtonElt = $('#newObsGeolocalisatorButtonForm');
    var resetButtonElt = $('#newObsGeolocalisatorReset');
    // Replace comma by a dot in latitude and longitude for leaflet
    var latitude = lat.replace(/,/g, '.');
    var longitude = lon.replace(/,/g, '.');

    var myObsMap = L.map('newObsMap').setView([latitude, longitude], 16);

    // Display map
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 20,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(myObsMap);
    myObsMap.attributionControl.setPrefix('NAO');

    // Create a marker object and add one at the position where a bird was seen
    var marker = L.marker();
    var circle = L.circle();
    marker.setLatLng([latitude, longitude]).addTo(myObsMap).bindPopup("<center>Vous étiez là!</center><center>You were here!</center>").openPopup();

    // Reset location with the first latitude & longitude
    function resetLocation() {
        marker.setLatLng([latitude, longitude]).addTo(myObsMap)
            .bindPopup("<center>Vous étiez là!</center><center>You were here!</center>").openPopup();
        myNewObsLat.val(latitude);
        myNewObsLng.val(longitude);
        myObsMap.removeLayer(newObsPopupLocation);
    }

    // Set Lat + Lon fields form & display a marker on map when you are found
    function newObsOnLocationFound(e) {
        var myObsMapRadius = e.accuracy / 2;

        marker.setLatLng(e.latlng).addTo(myObsMap)
            .bindPopup("<center>Vous êtes dans un périmètre de </br>" + myObsMapRadius + " mètres de ce point </center>\n<center>You are within a </br>" + myObsMapRadius + " meters radius from here.</center>").openPopup();
        circle.setLatLng(e.latlng).setRadius(myObsMapRadius).addTo(myObsMap);
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
        myObsMap.removeLayer(marker);
        myObsMap.removeLayer(circle);
    }

    // Events called on map & button click
    myObsMap.on('click', newObsOnMapClick);
    geoButtonElt.on('click', getLocationLeaflet);
    resetButtonElt.on('click', resetLocation);
});