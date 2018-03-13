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
  var listItemLinkFocus = '';
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
    } else {
      myTooltipUserPicture = "<img title='" + myTooltipUsername + "' class='float-left profil-picture-mini shadowed' src='" + myTooltipUserPicWebPath + "' alt='" + myTooltipUserPicAlt + "'>";
    }

    if (myTooltipObsPic != null) {
      myTooltipObsPic = "<img  alt='" + myTooltipObsPicAlt + "' title='" + myTooltipObsPicAlt + "' class='obs-picture-mini' src='" + myTooltipObsPicWebPath + "'>";
    } else {
      myTooltipObsPic = "<i title='Pas de photo / No-picture' class='fa fa-camera-retro fa-3x')></i>";
    }
    /* CONSTRUCTOR */
    var myTooltipUserContent = "<span class='profil-picture-mini'>" + myTooltipUserPicture + " <span class='font-size-10 badge badge-success'>" + myTooltipUsername + "</span></span>";
    var myTooltipUser = "<div " + myTooltipUserStyle + " >" + myTooltipUserContent + "</div>";
    myTooltipContent = myTooltipContent + myTooltipUser;
    var myTooltipDateContainer = "<div " + myTooltipDateStyle + " ><time class='font-size-1em obs-time'><i class='fa fa-calendar'></i>" + myTooltipObsDate + "</time></div>";
    myTooltipContent = myTooltipContent + myTooltipDateContainer;
    var myTooltipObsContent = "<h2 " + myTooltipObsSciNameStyle + " > " + myTooltipObsSciName + " </h2><hr/><h3 " + myTooltipObsTitleStyle + " >" + myTooltipObsTitle + "</h3><div " + myTootipObsPicContainerStyle + " >" + myTooltipObsPic + "<hr /><h2 class='font-size-10'>Périmètre Observation - 100 m à 200 m de ce point / Around 100 m à 200 m of ths point</h2></div>";
    myTooltipContent = myTooltipContent + myTooltipObsContent;
    var myTooltipContainer = "<div " + myTooltipContainerStyle + " ><a style='text-decoration: none;' href='#" + listItemLinkFocus + "' id='obsPicker-" + listItemLinkFocus + "'>" + myTooltipContent + "</div></a>";

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
                  TOOLTIP REACTOR SERVICE
  ************************************************************************/

  function onMarkerClick(e) {
    var itemId = e.target._leaflet_id;
    listItemsClassSelector(itemId);
  }
  function listItemsClassSelector(id) {

    var element = document.getElementsByTagName("tr");
    var displayInfoElts = document.getElementsByClassName('itemDisplayInfoMap');

    for (var i = 0; i < displayInfoElts.length; i++) {
      displayInfoElts[i].classList.remove("badge-dark");
      if (displayInfoElts[i].innerHTML == 'Selected') {
        displayInfoElts[i].innerHTML = "vue";
        displayInfoElts[i].classList.add("badge-warning")
      }
    }

    for (var i = 0; i < element.length; i++) {
      if (element[i].classList.value == 'activeItem') {
        element[i].classList.add("visitedItem")
      }
      element[i].classList.remove("activeItem")
    }

      element = document.getElementById(id);
      if (element.classList.value == 'visitedItem') {
        element.classList.remove("visitedItem")
      }
      element.classList.add("activeItem")
      document.getElementById('displayInfo-' + id).style.visibility = "visible";
      document.getElementById('displayInfo-' + id).innerHTML = "Selected";
      document.getElementById('displayInfo-' + id).classList.add("badge-dark");
      document.getElementById('displayInfo-' + id).classList.remove("badge-warning");
      document.getElementById('displayInfo-' + id).focus();

  }
  function insertAfter(referenceNode, newNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}


  // Open a marker
  $('tr').on("click", function() {
    // Remove the marker
    marker = markers._layers[$(this).attr('id')];
    marker.openPopup();
    listItemsClassSelector($(this).attr('id'));
  });
  /*********************************************************************
                  TOOLTIP LOADER SERVICE
  ************************************************************************/
  if (myItems != 1) {

    myItems = jQuery.parseJSON(myItems);

    $.each(myItems, function(key, item) {
      for (var i = 0; i < item.length; i++) {

        myTooltipUserPicture = null;

        if (myTooltipUserPicture != null) {
          myTooltipUserPicAlt = item[i].user.username;
          myTooltipUserPicWebPath = '/uploads/avatar/' + item[i].user.id + ".jpeg";
        }

        myTooltipUsername = item[i].user.username;
        myTooltipObsDate = item[i].date.date;
        myTooltipObsSciName = item[i].species.scientificName;
        myTooltipObsTitle = item[i].title;
        myTooltipObsPic = item[i].image;
        listItemLinkFocus = "obs-" + item[i].id;

        if (myTooltipObsPic != null) {
          myTooltipObsPicAlt = item[i].image.alt;
          myTooltipObsPicWebPath = '/uploads/photos/' + item[i].image.id + "." + item[i].image.extension;
        }

        myTooltip = constuctMyTooltip();
        marker = new L.marker([item[i].latitude, item[i].longitude]).bindPopup(myTooltip);
        L.circle([item[i].latitude, item[i].longitude], 200).addTo(mymap);
        markers.addLayer(marker);
        mymap.addLayer(markers);

        document.getElementById(item[i].id).id = marker._leaflet_id;
        document.getElementById('maplink-' + item[i].id).id = 'maplink-' + marker._leaflet_id;
        document.getElementById('displayInfo-' + item[i].id).id = 'displayInfo-' + marker._leaflet_id;
        marker.on('click', onMarkerClick);
        resetMyTooltip();
      }
    });


  }

  var fff = document.getElementById('obsPic-2');
  console.log(fff);

});
