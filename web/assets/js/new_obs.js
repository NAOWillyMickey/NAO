/**
 * Created by wilfriedcottineau on 08/03/2018.
 */

/* JS NEW OBS MAP */

$(document).ready(function() {

    var myNewObsLat = $('#ornito_observationbundle_watching_latitude');
    var myNewObsLng = $('#ornito_observationbundle_watching_longitude');
    var myObsMap = L.map('newObsMap').setView([46.780, 3.001], 5);

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
        maxZoom: 25,
        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(myObsMap);

    /*  myObsMap.locate({setView: true, maxZoom: 18});*/

    function newObsOnLocationFound(e) {
        var myObsMapRadius = e.accuracy / 2;

        myNewObsLat.val(e.latlng.lat);
        myNewObsLng.val(e.latlng.lng);

        L.marker(e.latlng).addTo(myObsMap)
            .bindPopup("<center>Vous êtes dans un périmètre de </br>" + myObsMapRadius + " metres de ce point </center>").openPopup();

        L.circle(e.latlng, myObsMapRadius).addTo(myObsMap);

        $(myNewObsLat).val(e.latlng.lat);
        $(myNewObsLng).val(e.latlng.lng);
    }

    function newObsOnLocationError(e) {
        alert(e.message);
    }

    myObsMap.on('locationfound', newObsOnLocationFound);
    myObsMap.on('locationerror', newObsOnLocationError);
    myObsMap.locate({setView: true, maxZoom: 16});

    var newObsPopupLocation = L.popup();

    function newObsOnMapClick(e) {
        newObsPopupLocation
            .setLatLng(e.latlng)
            .setContent("<center>Vous avez cliqué</br>aux coordonnées suivantes :</center></br>-Latitude " + e.latlng.lat.toString() + "</br>- Longitude " + e.latlng.lng.toString())
            .openOn(myObsMap);
            myNewObsLat.val(e.latlng.lat);
            myNewObsLng.val(e.latlng.lng);
    }

    myObsMap.on('click', newObsOnMapClick);
});
