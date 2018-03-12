/**
 * Created by Mickey on 09/03/2018.
 */

/*******************************************************************************************************************************************
********************************************************************************************************************************************

                                           JS  OBS' DISLPAYER MAP

*********************************************************************************************************************************************
*********************************************************************************************************************************************/

$(document).ready(function() {

  /*********************************************************************
                 SEARH PANEL CONTROLLER (RIGHT SIDEBAR)
  ************************************************************************/
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

  /*********************************************************************
                 GENERAL VAR AND CONTROL FOR THE MAP
  ************************************************************************/
  var myLatitude = $('#myLatInput');
  var myLongitude = $('#myLongInput');
  var mymap = L.map('mapContainer').setView([46.780, 3.001], 6);

  /*********************************************************************
                 LAYERS FOR THE MAP - MARKER AND BACKGROUND MAP
  ************************************************************************/
  var markers = new L.layerGroup();
  L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
      '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
      'Imagery © <a href="http://mapbox.com">Mapbox</a>',
    id: 'mapbox.streets'
  }).addTo(mymap);


  /*********************************************************************
                  GEOLOCALISATION FUNCTIONALITIES
  ************************************************************************/
  /*  mymap.locate({setView: true, maxZoom: 18});*/

  function onLocationFound(e) {
    var radius = e.accuracy / 2;
    myLatitude.val(e.latlng.lat);
    myLongitude.val(e.latlng.lng);
    L.marker(e.latlng).addTo(mymap)
      .bindPopup("<div style='width: 120px;  align='center' class='margin-top-10 italic font-size-12'>Vous êtes dans un périmètre de " + radius + " metres de ce point</div>").openPopup();
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
      .setContent("<div style='max-width: 190px;min-width: 150px;' align='center' class='center marginAuto font-size-12'><h6 >Vous avez cliqué aux coordonnées suivante :</h6><p class='center marginAuto badge badge-warning font-size-12 '>Latitude " + e.latlng.lat.toString() + "</p><p class='center marginAuto badge badge-info font-size-12'>Longitude " + e.latlng.lng.toString() + "</p></div>")
      .openOn(mymap);
  }

  mymap.on('click', onMapClick);
  mymap.on('click', function(e) {
    myLatitude.val(e.latlng.lat);
    myLongitude.val(e.latlng.lng);
  });

/*******************************************************************************************************************************************
********************************************************************************************************************************************

                                          TOOLTIP CONSTRUCTOR SERVICE

*********************************************************************************************************************************************
*********************************************************************************************************************************************/

  /* VARIABLE FOR THE CONSTRUCTOR */
  var myTooltipContent = '';
  var myTooltipUserPicture = null;
  var myTooltipUserPicWebPath = '';
  var myTooltipUserPicAlt = '';
  var myTooltipUsername = '';
  var myTooltipObsDate = '';
  var myTooltipObsSciName = '';
  var myTooltipObsTitle = '';
  var myTooltipObsPic = '';
  var myTooltipObsPicWebPath = '';
  var myTooltipObsPicAlt = '';
  /* CSS STYLE */
  var myTooltipContainerStyle = "style='width: 120px;'";
  var myTooltipUserStyle = "style='margin-left: -25px;margin-top: -25px;'";
  var myTooltipDateStyle = "style='width: 120px;' align='right'";
  var myTooltipObsSciNameStyle = "style='width: 120px;' align='center' class='margin-top-10 italic font-size-12'";
  var myTooltipObsTitleStyle = "style='width: 120px;' align='center' class='text-uppercase font-size-12 card-title'";
  var myTootipObsPicContainerStyle = "style='width: 120px;' align='center'";

/* CONSTRUCTOR FUNCTION */
function constuctMyTooltip() {

  if (myTooltipUserPicture == null) {
    myTooltipUserPicture = "<i class='fa fa-user shadowed' aria-hidden='true'></i>";
  }
  else {
      myTooltipUserPicture = "<img title='" + myTooltipUsername + "' class='float-left profil-picture-mini shadowed' src='" + myTooltipUserPicWebPath + "' alt='" + myTooltipUserPicAlt + "'>";
    }

  if (myTooltipObsPic != null) {
    myTooltipObsPic = "<img  alt='" + myTooltipObsPicAlt + "' title='" + myTooltipObsPicAlt + "' class='obs-picture-mini' src='" + myTooltipObsPicWebPath + "'>";
  }
  else {
      myTooltipObsPic = "<i class='fa fa-twitter-square fa-2x')></i>";
    }
  /* CONSTRUCTOR */
  var myTooltipUserContent = "<span class='profil-picture-mini'>" + myTooltipUserPicture + " <span class='font-size-10 badge badge-success'>" + myTooltipUsername + "</span></span>";
  var myTooltipUser = "<div " + myTooltipUserStyle + " >" + myTooltipUserContent + "</div>";
  myTooltipContent = myTooltipContent + myTooltipUser;
  var myTooltipDateContainer = "<div " + myTooltipDateStyle + " ><time class='font-size-1em obs-time'><i class='fa fa-calendar'></i>" + myTooltipObsDate + "</time></div>";
  myTooltipContent = myTooltipContent + myTooltipDateContainer;
  var myTooltipObsContent = "<h2 " + myTooltipObsSciNameStyle + " > " + myTooltipObsSciName + " </h2><hr/><h3 " + myTooltipObsTitleStyle + " >" + myTooltipObsTitle + "</h3><div " + myTootipObsPicContainerStyle + " >" + myTooltipObsPic + "<hr /><h2 class='font-size-10'>Périmètre Observation - 100 m à 200 m de ce point / Around 100 m à 200 m of ths point</h2></div>";
  myTooltipContent = myTooltipContent + myTooltipObsContent;
  var myTooltipContainer = "<div " + myTooltipContainerStyle + " >" + myTooltipContent + "</div>";

  return myTooltipContainer;
}

function resetMyTooltip() {
  myTooltipContent = '';
  myTooltipUserPicture = '';
  myTooltipUserPicWebPath = '';
  myTooltipUserPicAlt = '';
  myTooltipUsername = '';
  myTooltipObsDate = '';
  myTooltipObsSciName = '';
  myTooltipObsTitle = '';
  myTooltipObsPic = '';
  myTooltipObsPicWebPath = '';
  myTooltipObsPicAlt = '';
}

/*********************************************************************
                TOOLTIP LOADER SERVICE
************************************************************************/
if (myItems != 1 ) {

  myItems = jQuery.parseJSON(myItems);

  $.each(myItems, function(key, item) {
    for (var i = 0; i < item.length; i++) {

      myTooltipUserPicture = 1;

      if (myTooltipUserPicture != null) {
        myTooltipUserPicAlt = item[i].user.username;
        myTooltipUserPicWebPath = '/uploads/avatar/' + item[i].user.id  + ".jpeg";
      }

      myTooltipUsername = item[i].user.username;
      myTooltipObsDate = item[i].date.date;
      myTooltipObsSciName = item[i].species.scientificName;
      myTooltipObsTitle = item[i].title;
      myTooltipObsPic = item[i].image;

      if (myTooltipObsPic != null) {
        myTooltipObsPicAlt = item[i].image.alt;
        myTooltipObsPicWebPath = '/uploads/photos/' + item[i].image.id + "." + item[i].image.extension;
      }

      myTooltip = constuctMyTooltip();
      marker = new L.marker([item[i].latitude, item[i].longitude]).bindPopup(myTooltip);
      L.circle([item[i].latitude, item[i].longitude], 200).addTo(mymap);
      markers.addLayer(marker);
      mymap.addLayer(markers);
      marker.on('click', onMarkerClick(item[i].id));
      console.log(marker._leaflet_id);
      resetMyTooltip();
    }
  });

}
console.log(myItems);

/*********************************************************************
                TOOLTIP REACTOR SERVICE
************************************************************************/

  function onMarkerClick(id) {
    $('tr').removeClass('active');
    $('tr #' + id).addClass('active');
    console.log('hh')
  }

});
