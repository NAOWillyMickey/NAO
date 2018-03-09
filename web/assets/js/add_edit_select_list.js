/**
 * Created by wilfriedcottineau on 09/03/2018.
 */

/* SELECT LIST */

$(document).ready(function() {

    var vernList = [];
    var firstSelect = $('select:first').attr('id');
    var selectedOption = $("select option:selected").val();

    $('option[value=' + selectedOption + ']').prop('selected', true);

    $('#ref-nomVernaculaire option').each(function () {
        vernList.push($(this).val());
    });

    $('select').change(function () {
        var id = $(this).attr('id');
        var value = $(this).val();
        if (id === firstSelect) {
            if (jQuery.inArray(value, vernList) === -1) {
                $('option[value="emptyVern"]').prop('selected', true);
            } else {
                $('option[value="' + value + '"]').prop('selected', true);
            }
        } else {
            $('option[value="' + value + '"]').prop('selected', true);
        }
    });
});