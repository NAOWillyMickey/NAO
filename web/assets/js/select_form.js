/**
 * Created by wilfriedcottineau on 02/03/2018.
 */
$(function () {
    var ordreElt = $('#ref-ordre');
    var familyElt = $('#ref-famille');
    var scientElt = $('#ref-nomScientifique');
    var vernElt = $('#ref-nomVernaculaire');
    var pathElt = $('#path');
    var formElt = $('#birdCardSearh');
    var speciesList = null;

    $(formElt).hide();

    $.ajax({
        type: 'GET',
        url: pathElt.attr("href"),
        dataType : "json",
        timeout: 12000,
        success: function(response) {
            speciesList = response.json;
            $(formElt).show(1000,"linear");
        },
        error: function() {
            alert('La requête n\'a pas abouti'); }
    });

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

    function amendListWithFamilyName(value) {
        var result = speciesList.filter(check);
        function check(obj) {
            if (obj.species_family === value) {
                return true;
            }
        }
        var ordre = result[0]['species_ordre'];
        if (ordre !== ordreElt.val()) {
            $('option[value="' + ordre + '"]').prop('selected', true);
        }
        fillListScientific(result);
        fillListVern(result);
    }

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
        if (vern === "") {
            $('option[value="noVern"]').prop('selected', true);
        } else {
            $('option[value="'+vern+'"]').prop('selected', true);
        }
        $('option[value="'+ordre+'"]').prop('selected', true);
        $('option[value="'+family+'"]').prop('selected', true);
    }

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
        if (ordre !== ordreElt.val()) {
            $('option[value="' + ordre + '"]').prop('selected', true);
        }
        if (family !== familyElt.val()) {
            $('option[value="'+family+'"]').prop('selected', true);
        }
        $('option[value="'+scientificName+'"]').prop('selected', true);
    }

    function fillListFamily(result) {
        $(familyElt).empty().append('<option disabled selected>----------</option>');
        var tableau = [];
        $.each(result, function(key, bird){
            if (jQuery.inArray(bird.species_family, tableau) === -1) {
                tableau.push(bird.species_family);
                var $toAdd = '<option value="' + bird.species_family + '">' + bird.species_family + '</option>';
                $(familyElt).append($toAdd);
            }
        })
    }

    function fillListScientific(result) {
        $(scientElt).empty().append('<option disabled selected>----------</option>');
        var tableau = [];
        $.each(result, function(key, bird){
            if (jQuery.inArray(bird.species_scientificName, tableau) === -1) {
                tableau.push(bird.species_scientificName);
                var $toAdd = '<option value="' + bird.species_scientificName + '">' + bird.species_scientificName + '</option>';
                $(scientElt).append($toAdd);
            }
        })
    }

    function fillListVern(result) {
        $(vernElt).empty().append('<option disabled selected value="noVern">----------</option>');
        var tableau = [];
        $.each(result, function(key, bird){
            if (jQuery.inArray(bird.species_vernFr, tableau) === -1 && bird.species_vernFr !== "") {
                tableau.push(bird.species_vernFr);
                var $toAdd = '<option value="' + bird.species_vernFr + '">' + bird.species_vernFr + '</option>';
                $(vernElt).append($toAdd);
            }
        })
    }
});