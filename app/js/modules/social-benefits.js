/**
 * Social-Benefits
 */

// Init Vars to Check If they exist in DB
var ExistsInDB = {
    patronal_exists : false,
    bonuses_exists : false,
    anual_exists : false,
    benefits_exists : false
}

// Once Page is Loaded
$(document).ready(function(){

    $('#spinner').fadeIn('slow');

    // B.1 Get 'Ap. Patronal' data from DB according to Project
    getSingle('patronal',ACTIVE_PROJECT,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                $('input#cnss').val(data.cnss);
                $('input#infocal').val(data.infocal);
                $('input#aporte_vivencia').val(data.aporte_vivencia);
                $('input#afps').val(data.afps);
                $('input#subtotal_ap').val(data.subtotal_ap);
                $('input#equivalente_dc').val(data.equivalente_dc);
            });
            ExistsInDB.patronal_exists = true;
        }
    });

    // B.2 Get 'Bonuses' data from DB according to Project
    getSingle('bonuses',ACTIVE_PROJECT,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                $('input#aguinaldo').val(data.aguinaldo);
                $('input#subsidios').val(data.subsidios);
                $('input#indemnizacion').val(data.indemnizacion);
                $('input#otros').val(data.otros);
                $('input#subtotal_bonos').val(data.subtotal_bonos);
            });
            ExistsInDB.bonuses_exists = true;
        }
    });

    // B.3 Get 'Anual Working Time' data from DB according to Project
    getSingle('annual_working',ACTIVE_PROJECT,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                $('input#year_days').val(data.year_days);
                $('input#inactividad').val(data.inactividad);
                $('input#vacaciones').val(data.vacaciones);
                $('input#feriados').val(data.feriados);
                $('input#lluvias').val(data.lluvias);
                $('input#enfermedades').val(data.enfermedades);
                $('input#dias_no_trab').val(data.dias_no_trab);
                $('input#subtotal_anual_work').val(data.subtotal_anual_work);
            });
            ExistsInDB.anual_exists = true;
        }
    });

    // B.4 Get 'Benefits %' data from DB according to Project
    getSingle('benefits',ACTIVE_PROJECT,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                $('input#dias_paga_x_ano').val(data.dias_paga_x_ano);
                $('input#dias_paga_x_apatronal').val(data.dias_paga_x_apatronal);
                $('input#dias_paga_x_bonos').val(data.dias_paga_x_bonos);
                $('input#total').val(data.total);
                $('input#tot_dias_paga').val(data.tot_dias_paga);
                $('input#tot_dias_habiles').val(data.tot_dias_habiles);
                $('input#tot_dias_paga_sin_trab').val(data.tot_dias_paga_sin_trab);
                $('input#porcentaje_carga_social').val(data.porcentaje_carga_social);
            });
            ExistsInDB.benefits_exists = true;
        }
    });
    
    $('#spinner').fadeOut('slow');

});

// B.1 Calculation Subtotal for 'Ap. Patronal' & 'Equivalente en d.c.'
$(document).on('keyup', ".calc-patronal", function(event) {

    // Subtotal
    var tot = 0;
    tot += Number($('#cnss').val());
    tot += Number($('#infocal').val());
    tot += Number($('#aporte_vivencia').val());
    tot += Number($('#afps').val());
    $('#subtotal_ap').val(tot);

    // Equ. en d.c.
    var equ = Math.round(((tot / 100) * 365)*100)/100;
    $('#equivalente_dc').val(equ);

    // B.4 Dias Pagados por Ap. Patronal & Total
    $('#dias_paga_x_apatronal').val(equ);
    setTotalB4();

});

// B.2 Calculation Subtotal for 'Bonuses'
$(document).on('keyup', ".calc-bonuses", function(event) {

    // Subtotal
    var tot = 0;
    tot += Number($('#aguinaldo').val());
    tot += Number($('#subsidios').val());
    tot += Number($('#indemnizacion').val());
    tot += Number($('#otros').val());
    $('#subtotal_bonos').val(tot);

    // B.4 Dias Pagados por Bonos & Total
    $('#dias_paga_x_bonos').val(tot);
    setTotalB4();

});

// B.3 Calculation Subtotal for 'Anual Working Time' & 'Dias no Trab.'
$(document).on('keyup', ".calc-anual", function(event) {

    // Sum of non working days
    var tot = 0;
    tot += Number($('#inactividad').val());
    tot += Number($('#vacaciones').val());
    tot += Number($('#feriados').val());
    tot += Number($('#lluvias').val());
    tot += Number($('#enfermedades').val());
    $('#dias_no_trab').val(tot);

    // Days of the year - Non working days
    var subtot = Number($('#year_days').val()) - tot;
    $('#subtotal_anual_work').val(subtot);

    // B.4 'Dias Pagados por Año', 'Total' & 'Total dias habiles'
    var days_paga_x_ano = Math.round((tot + subtot)*100)/100;
    $('#dias_paga_x_ano').val(days_paga_x_ano);
    setTotalB4();
    $('#tot_dias_habiles').val(subtot);

});

// B.4  
function setTotalB4() {
    
    // 'Sum (Total)'
    var b4_tot = 0;
    b4_tot += Number($('#dias_paga_x_apatronal').val());
    b4_tot += Number($('#dias_paga_x_bonos').val());
    b4_tot += Number($('#dias_paga_x_ano').val());
    $('#total').val(b4_tot);
    
    // 'Tot dias pagados'
    $('#tot_dias_paga').val(b4_tot);

    // 'Total Dias Pag. sin Trabajar'
    var tot_dias_habiles = Number($('#tot_dias_habiles').val());
    var days_wout_trab = Math.round((b4_tot - tot_dias_habiles)*100)/100;
    $('#tot_dias_paga_sin_trab').val(days_wout_trab);

    // '% de Carga Social' (* 100 so is %) (Check "tot_dias_habiles" isn't 0, so division is not infinite)
    if (tot_dias_habiles != 0) {
      var cs = Math.round(((days_wout_trab / tot_dias_habiles)*100)*100)/100;
      $('#porcentaje_carga_social').val(cs);
    }

}

// POST / PUT data to DB
function saveData(dBtable,type,file,json) {
    var section = {'patronal': 'B.1', 'bonuses': 'B.2', 'annual_working': 'B.3', 'benefits': 'B.4'};
    $.ajax({
        url: API_URL + '/' + dBtable + '/' + file + '/',
        type: type,
        dataType: 'json',
        data: json
    }).done(function(data) {
        $.gritter.add({
            title: '¡Exito!',
            text: 'Datos Guardados Correctamente para la sección ' + section[dBtable]
        });

    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
    });
}

// Save B.4 in DB 'Social Benefits'
function saveB4() {

    // Init required vars
    var id = ACTIVE_PROJECT;
    var form = $('form#benefits-percentage');

    // Check if data exists to Create or Update
    var type = ExistsInDB.benefits_exists ? 'PUT' : 'POST';
    var file = ExistsInDB.benefits_exists ? 'u' : 'c';
    if (file == 'c'){
        ExistsInDB.benefits_exists = true;
    }

    // Create JSON with Form
    var JSONData = serializeFormJSONAll(form);
    JSONData['id'] = id;
    JSONData = JSON.stringify(JSONData);

    // Insert into DB
    saveData('benefits',type,file,JSONData);

    // EXTRA: Save 'Porcentaje Carga Social' in 'projects_comp' DB for 'Specific Data' page
    getSingle('projects_comp',ACTIVE_PROJECT,function(resp) {
        var JSONData2 = {};
        JSONData2['id'] = ACTIVE_PROJECT;

        if (resp != 404){
            // Update:  JSON to insert a new item
            JSONData2['projBeneficiosSocialesManoDeObra'] = $('input#porcentaje_carga_social').val();
            JSONData2 = JSON.stringify(JSONData2); 

            // Insert into DB
            patchInDB('projects_comp',JSONData2);       

        }else{
            // Create: Set JSON to insert a new item
            JSONData2['beneficios_sociales'] = $('input#porcentaje_carga_social').val();
            JSONData2 = JSON.stringify(JSONData2); 

            // Insert into DB
            saveInDB('projects_comp','POST','c',JSONData2);       
        }
    });

}

// Save B.1 in DB 'Patronal'
$(document).off('click', "#save-patronal-input").on('click', "#save-patronal-input", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('input#subtotal_ap').val()){
        $.gritter.add({
            title: 'Campos Vacios!',
            text: 'Debe llenar al menos un campo de la sección B.1'
        });
    }else{

        // Init required vars
        var id = ACTIVE_PROJECT;
        var form = $('form#patronal-input');

        // Check if data exists to Create or Update
        var type = ExistsInDB.patronal_exists ? 'PUT' : 'POST';
        var file = ExistsInDB.patronal_exists ? 'u' : 'c';
        if (file == 'c'){
            ExistsInDB.patronal_exists = true;
        }

        // Create JSON with Form
        var JSONData = serializeFormJSONAll(form);
        JSONData['id'] = id;
        JSONData = JSON.stringify(JSONData);

        // Insert into DB
        saveData('patronal',type,file,JSONData);

        // Update new data for B.4
        saveB4();
    }
});

// Save B.2 in DB 'Bonuses'
$(document).off('click', "#save-bonuses").on('click', "#save-bonuses", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('input#subtotal_bonos').val()){
        $.gritter.add({
            title: 'Campos Vacios!',
            text: 'Debe llenar al menos un campo de la sección B.2'
        });
    }else{

        // Init required vars
        var id = ACTIVE_PROJECT;
        var form = $('form#bonuses');

        // Check if data exists to Create or Update
        var type = ExistsInDB.bonuses_exists ? 'PUT' : 'POST';
        var file = ExistsInDB.bonuses_exists ? 'u' : 'c';
        if (file == 'c'){
            ExistsInDB.bonuses_exists = true;
        }

        // Create JSON with Form
        var JSONData = serializeFormJSONAll(form);
        JSONData['id'] = id;
        JSONData = JSON.stringify(JSONData);

        // Insert into DB
        saveData('bonuses',type,file,JSONData);

        // Update new data for B.4
        saveB4();
    }
});

// Save B.3 in DB 'Anual Working Time'
$(document).off('click', "#save-anual-working").on('click', "#save-anual-working", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('input#subtotal_anual_work').val()){
        $.gritter.add({
            title: 'Campos Vacios!',
            text: 'Debe llenar al menos un campo de la sección B.3'
        });
    }else{

        // Init required vars
        var id = ACTIVE_PROJECT;
        var form = $('form#annual-working-time');

        // Check if data exists to Create or Update
        var type = ExistsInDB.anual_exists ? 'PUT' : 'POST';
        var file = ExistsInDB.anual_exists ? 'u' : 'c';
        if (file == 'c'){
            ExistsInDB.anual_exists = true;
        }

        // Create JSON with Form
        var JSONData = serializeFormJSONAll(form);
        JSONData['id'] = id;
        JSONData = JSON.stringify(JSONData);

        // Insert into DB
        saveData('annual_working',type,file,JSONData);

        // Update new data for B.4
        saveB4();
    }
});