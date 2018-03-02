/**
 * Created by wilfriedcottineau on 02/03/2018.
 */
$(function () {
    var ordreElt = $('#ref-ordre');
    var familyElt = $('#ref-famille');
    var scientElt = $('#ref-nomScientifique');
    var vernElt = $('#ref-nomVernaculaire');
    var pathElt = $('#path');

    $('select').change(function() {
        var value = $(this).val();
        var name = $(this).attr('name');
        $.ajax({
            type: 'GET',
            url: pathElt.attr("href")+'/'+name+'/'+value,
            dataType : "json",
            timeout: 8000,
            success: function(response) {
                if (name === 'ordre') {
                    return amendListWithOrdreName(response);
                } else if (name === 'family') {
                    return amendListWithFamilyName(response);
                } else if (name === 'scientificName') {
                    return amendListWithScientificName(response);
                } else {
                    return amendListWithVernName(response);
                }
            },
            error: function() {
                alert('La requête n\'a pas abouti'); }
        });
    });

    function amendListWithOrdreName(response) {
        fillListFamily(response);
        fillListScientific(response);
        fillListVern(response);
    }

    function amendListWithFamilyName(response) {
        var ordre = response.json[0].ordre;
        if (ordre !== ordreElt.val()) {
            $('option[value="' + ordre + '"]').prop('selected', true);
        }
        fillListScientific(response);
        fillListVern(response);
    }

    function amendListWithScientificName(response) {
        var ordre = response.json[0].ordre;
        var family = response.json[0].family;
        var vern = response.json[0].vernFr;
        if (vern === "") {
            $('option[value="noVern"]').prop('selected', true);
        } else {
            $('option[value="'+vern+'"]').prop('selected', true);
        }
        $('option[value="'+ordre+'"]').prop('selected', true);
        $('option[value="'+family+'"]').prop('selected', true);
    }

    function amendListWithVernName(response) {
        var ordre = response.json[0].ordre;
        var family = response.json[0].family;
        var scientificName = response.json[0].scientificName;
        if (ordre !== ordreElt.val()) {
            $('option[value="' + ordre + '"]').prop('selected', true);
        }
        if (family !== familyElt.val()) {
            $('option[value="'+family+'"]').prop('selected', true);
        }
        $('option[value="'+scientificName+'"]').prop('selected', true);
    }

    function fillListFamily(response) {
        $(familyElt).empty().append('<option disabled selected>----------</option>');
        var tableau = [];
        $.each(response.json, function(key, value){
            if (jQuery.inArray(value.family, tableau) === -1) {
                tableau.push(value.family);
                var $toAdd = '<option value="' + value.family + '">' + value.family + '</option>';
                $(familyElt).append($toAdd);
            }
        })
    }

    function fillListScientific(response) {
        $(scientElt).empty().append('<option disabled selected>----------</option>');
        var tableau = [];
        $.each(response.json, function(key, value){
            if (jQuery.inArray(value.scientificName, tableau) === -1) {
                tableau.push(value.scientificName);
                var $toAdd = '<option value="' + value.scientificName + '">' + value.scientificName + '</option>';
                $(scientElt).append($toAdd);
            }
        })
    }

    function fillListVern(response) {
        $(vernElt).empty().append('<option disabled selected value="noVern">----------</option>');
        var tableau = [];
        $.each(response.json, function(key, value){
            if (jQuery.inArray(value.vernFr, tableau) === -1 && value.vernFr !== "") {
                tableau.push(value.vernFr);
                var $toAdd = '<option value="' + value.vernFr + '">' + value.vernFr + '</option>';
                $(vernElt).append($toAdd);
            }
        })
    }
});