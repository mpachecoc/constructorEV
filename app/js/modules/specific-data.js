/**
 * Specific Data
 */

// Init Vars to Check If they exist in DB
var ExistsInDB = {
    projects_comp : false
}

// Once Page is Loaded
$(document).ready(function(){

    // Check if data exists in DB according to Project
    getSingle('projects_comp',ACTIVE_PROJECT,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                $('input#costo_herramientas').val(data.costo_herramientas);
                $('input#beneficios_sociales').val(data.beneficios_sociales);
                $('input#gastos_generales').val(data.gastos_generales);
                $('input#utilidad_costo_directo').val(data.utilidad_costo_directo);
                $('input#iva').val(data.iva);
                $('input#it').val(data.it);
                $('input#factor_de_paso').val(data.factor_de_paso);
                $('input#compra_sin_factura').val(data.compra_sin_factura);
            });
            ExistsInDB.projects_comp = true;
        }
    });

});

// Calculation '7. Factor de Paso'
$(document).on('keyup', ".calc-fdp", function(event) {

    var tot = 0;
    tot += Number($('#gastos_generales').val());
    tot += Number($('#utilidad_costo_directo').val());
    tot += Number($('#iva').val());
    tot += Number($('#it').val());
    $('#factor_de_paso').val(tot);

});

// Calculation '8. Factor de Reduccion Compra sin Factura'
$(document).on('keyup', ".calc-sfact", function(event) {

    var tot = 100;
    tot -= Number($('#iva').val());
    tot -= Number($('#it').val());
    $('#compra_sin_factura').val(tot);

});

// Save 'Specific Data' in DB
$(document).off('click', "#save-specific-data").on('click', "#save-specific-data", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('input#costo_herramientas').val() && !$('input#gastos_generales').val() && !$('input#utilidad_costo_directo').val() && !$('input#iva').val() && !$('input#it').val()){
        $.gritter.add({
            title: '¡Campos Vacios!',
            text: 'Debe llenar los puntos 1, 3, 4, 5 y 6.'
        });
    }else{

        // Init required vars
        var id = ACTIVE_PROJECT;
        var form = $('form#specific-data-form');

        // Check if data exists to Create or Update
        var type = ExistsInDB.projects_comp ? 'PUT' : 'POST';
        var file = ExistsInDB.projects_comp ? 'u' : 'c';
        if (file == 'c'){
            ExistsInDB.projects_comp = true;
        }

        // Create JSON with Form
        var JSONData = serializeFormJSONAll(form);
        JSONData['id'] = id;
        JSONData = JSON.stringify(JSONData);
        // console.log(JSONData); return false;

        // Insert into DB
        saveInDB('projects_comp',type,file,JSONData);
    }

});

// Cancel current 'Specific Data'
$(document).off('click', "#cancel-specific-data").on('click', "#cancel-specific-data", function(event) {

    event.preventDefault();

    // Check if data exists in DB according to Project
    getSingle('projects_comp',ACTIVE_PROJECT,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                $('input#costo_herramientas').val(data.costo_herramientas);
                $('input#beneficios_sociales').val(data.beneficios_sociales);
                $('input#gastos_generales').val(data.gastos_generales);
                $('input#utilidad_costo_directo').val(data.utilidad_costo_directo);
                $('input#iva').val(data.iva);
                $('input#it').val(data.it);
                $('input#factor_de_paso').val(data.factor_de_paso);
                $('input#compra_sin_factura').val(data.compra_sin_factura);
            });
            ExistsInDB.projects_comp = true;
        }
    });
});