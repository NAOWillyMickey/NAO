/**
 * Created by wilfriedcottineau on 02/03/2018.
 */
$(function () {
    var ordreElt = $('#ref-ordre');
    var familyElt = $('#ref-family');
    var scientElt = $('#ref-nomScientifique');
    var vernElt = $('#ref-nomVernaculaire');
    var pathElt = $('#path');
    var formElt = $('#birdCardSearh');
    var resetElt = $('#resetButton');
    var speciesList = null;
    var familyList = [];
    var scientificList = [];
    var vernList = [];

    // Hide select form until all data are loaded
    $(formElt).hide();

    // Get all values from each select and push it into an array (need for the reset button)
    $('#ref-family option').each(function() {
        familyList.push($(this).val());
    });
    $('#ref-nomScientifique option').each(function() {
        scientificList.push($(this).val());
    });
    $('#ref-nomVernaculaire option').each(function() {
        vernList.push($(this).val());
    });

    // Get all species and store it into an array, then show the select form
    $.ajax({
        type: 'GET',
        url: pathElt.attr("href"),
        dataType : "json",
        timeout: 10000,
        success: function(response) {
            speciesList = response.json;
            $(formElt).show(1000,"linear");
        },
        error: function(jqXHR, textStatus) {
            var msg = "Request failed : " + textStatus + ". Please reload this page. Thank you.";
            $('#option-menu').append('<div id="errorMsg" class="alert alert-warning">'+msg+'</div>');
        }
    });

    // The function when click on reset button
    $(resetElt).click(function () {
        $(familyElt).empty().append('<option disabled selected value="emptyFamily"></option>');
        $(scientElt).empty().append('<option disabled selected value="emptyScientific"></option>');
        $(vernElt).empty().append('<option disabled selected value="emptyVern"></option>');
        $('option[value="emptyOrdre"]').prop('selected', true);
        resetList(familyList, familyElt);
        resetList(scientificList, scientElt);
        resetList(vernList, vernElt);
    });

    // Remove the first element of the array given in parameter and insert content of each element in the set of matched elements
    function resetList(tableau, selectElt) {
        tableau.shift();
        tableau.forEach(function(value) {
            var $toAdd = '<option value="' + value + '">' + value + '</option>';
            $(selectElt).append($toAdd);
        });
    }

    // The function when a value is selected
    $('select').change(function() {
        var value = $(this).val();
        var name = $(this).attr('name');
        if (name === 'ordre') {
            return amendListWithOrdreName(value);
        } else if (name === 'family') {
            return amendListWithFamilyName(value);
        } else if (name === 'scientificName') {
            return amendListWithScientificName(value);
        } else {
            return amendListWithVernName(value);
        }
    });

    // Creates and returns a new array containing all the elements of the original array that satisfy a condition.
    // Then fill the select list we need
    function amendListWithOrdreName(value) {
        var result = speciesList.filter(check);
        function check(obj) {
            if (obj.species_ordre === value) {
                return true;
            }
        }
        fillListFamily(result);
        fillListScientific(result);
        fillListVern(result);
    }

    // Creates and returns a new array containing all the elements of the original array that satisfy a condition.
    // Then fill the select list we need
    function amendListWithFamilyName(value) {
        var result = speciesList.filter(check);
        function check(obj) {
            if (obj.species_family === value) {
                return true;
            }
        }
        var ordre = result[0]['species_ordre'];
        // Selected a value by default
        if (ordre !== ordreElt.val()) {
            $('option[value="' + ordre + '"]').prop('selected', true);
        }
        fillListScientific(result);
        fillListVern(result);
    }

    // Creates and returns a new array containing all the elements of the original array that satisfy a condition.
    // Then fill the select list we need
    function amendListWithScientificName(value) {
        var result = speciesList.filter(check);
        function check(obj) {
            if (obj.species_scientificName === value) {
                return true;
            }
        }
        var ordre = result[0]['species_ordre'];
        var family = result[0]['species_family'];
        var vern = result[0]['species_vernFr'];
        // Selected values by default
        if (vern === "") {
            $('option[value="emptyVern"]').prop('selected', true);
        } else {
            $('option[value="'+vern+'"]').prop('selected', true);
        }
        $('option[value="'+ordre+'"]').prop('selected', true);
        $('option[value="'+family+'"]').prop('selected', true);
    }

    // Creates and returns a new array containing all the elements of the original array that satisfy a condition.
    // Then fill the select list we need
    function amendListWithVernName(value) {
        var result = speciesList.filter(check);
        function check(obj) {
            if (obj.species_vernFr === value) {
                return true;
            }
        }
        var ordre = result[0]['species_ordre'];
        var family = result[0]['species_family'];
        var scientificName = result[0]['species_scientificName'];
        // Selected values by default
        if (ordre !== ordreElt.val()) {
            $('option[value="' + ordre + '"]').prop('selected', true);
        }
        if (family !== familyElt.val()) {
            $('option[value="'+family+'"]').prop('selected', true);
        }
        $('option[value="'+scientificName+'"]').prop('selected', true);
    }

    // Insert content of each element in the set of matched elements
    function fillListFamily(result) {
        $(familyElt).empty().append('<option disabled selected value="emptyFamily"></option>');
        var tableau = [];
        $.each(result, function(key, bird){
            if (jQuery.inArray(bird.species_family, tableau) === -1) { // Check if value already exists
                tableau.push(bird.species_family);
                var $toAdd = '<option value="' + bird.species_family + '">' + bird.species_family + '</option>';
                $(familyElt).append($toAdd);
            }
        })
    }

    // Insert content of each element in the set of matched elements
    function fillListScientific(result) {
        $(scientElt).empty().append('<option disabled selected value="emptyScientific"></option>');
        var tableau = [];
        $.each(result, function(key, bird){
            if (jQuery.inArray(bird.species_scientificName, tableau) === -1) { // Check if value already exists
                tableau.push(bird.species_scientificName);
                var $toAdd = '<option value="' + bird.species_scientificName + '">' + bird.species_scientificName + '</option>';
                $(scientElt).append($toAdd);
            }
        })
    }

    // Insert content of each element in the set of matched elements
    function fillListVern(result) {
        $(vernElt).empty().append('<option disabled selected value="emptyVern"></option>');
        var tableau = [];
        $.each(result, function(key, bird){
            if (jQuery.inArray(bird.species_vernFr, tableau) === -1 && bird.species_vernFr !== "") { // Check if value already exists
                tableau.push(bird.species_vernFr);
                var $toAdd = '<option value="' + bird.species_vernFr + '">' + bird.species_vernFr + '</option>';
                $(vernElt).append($toAdd);
            }
        })
    }
});