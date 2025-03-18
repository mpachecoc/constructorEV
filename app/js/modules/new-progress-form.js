/**
 * New Progress Form
 */

// Helper
var helper = {
    adv_form_exists: false,
    apus_modified: new Array(),
    adv_form_num: 0,
    new_adv_form_num: 0,
    sum_s2: 0,
    sum_s3: 0,
    alph: ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'],
    getHtmlIni: function(groupName){
        return '<div class="widget widget-inverse" data-toggle="collapse-widget">' +
                    '<div class="widget-head"><h4 class="heading">' + groupName + '</h4></div>' +
                    '<div class="widget-body">' + 
                        '<div class="row">' +
                            '<table id="advanced-form-table" class="table table-condensed">' +
                                '<thead><tr>' +
                                '<th></th>' +
                                '<th></th>' +
                                '<th></th>' +
                                '<th colspan="3" class="text-center">' +
                                    '<span class="label label-inverse label-stroke">Cantidades de Contrato</span>' +
                                '</th>' +
                                '<th colspan="3" class="text-center">' +
                                    '<span class="label label-info label-stroke">Avance Anterior Acumulado</span>' +
                                '</th>' +
                                '<th colspan="3" class="text-center">' +
                                    '<span class="label label-success label-stroke">Avance Presente Periodo</span>' +
                                '</th>' +
                                '<th colspan="3" class="text-center">' +
                                    '<span class="label label-info label-stroke">Avance Acumulado a la Fecha</span>' +
                                '</th>' +
                                '<th colspan="3" class="text-center">' +
                                    '<span class="label label-warning label-stroke">Saldo por Ejecutar</span>' +
                                '</th>' +
                                '</tr><tr>' +
                                '<th class="text-center">Item</th>' +
                                '<th>Descripcion</th>' +
                                '<th class="text-center">Und.</th>' +
                                '<th class="text-right line-left-s1">Cantidad</th>' +
                                '<th class="text-right">Precio Unit.</th>' +
                                '<th class="text-right">Subtotal</th>' +
                                '<th class="text-right line-left-s2">Cantidad</th>' +
                                '<th class="text-right">Financiero</th>' +
                                '<th class="text-center">Por. %</th>' +
                                '<th class="text-center line-left-s3">Cantidad</th>' +
                                '<th class="text-right">Financiero</th>' +
                                '<th class="text-center line-right-s3">Por. %</th>' +
                                '<th class="text-right">Cantidad</th>' +
                                '<th class="text-right">Financiero</th>' +
                                '<th class="text-center line-right-s4">Por. %</th>' +
                                '<th class="text-right">Cantidad</th>' +
                                '<th class="text-right">Financiero</th>' +
                                '<th class="text-center line-right-s5">Por. %</th>' +
                                '</tr></thead>' +
                                '<tbody>';
    },
    getHtmlEnd: function(){
        return  '</tbody>' +
                '</table>' + 
                '</div></div></div>';
    }
}

// APU
var apu = {
    totalProject: 0,
}

// Once Page is Loaded
$(document).ready(function(){

    $('#spinner').fadeIn('slow'); 

    // Get Number of last "Advanced Form" in DB (Planilla)
    getAllByProj('advanced_form',function(resp) {
        if (resp != 404){ 
            $.map(resp, function (Obj, i) { 
                $.each(Obj, function (i, Arr) {
                    helper.adv_form_num = Arr.id;
                    helper.new_adv_form_num = Number(Arr.id) + 1;
                    $('input#doc-name').val('Planilla de Avance de Obra Nro. ' + helper.new_adv_form_num);
                });
            });
        }else{
            helper.new_adv_form_num = 1;
            $('input#doc-name').val('Planilla de Avance de Obra Nro. ' + helper.new_adv_form_num);
        }
    }); 

    /** Get All APUs by Group, then the "Advanced Form" */
   getAllByProj('groups',function(resp) {
        if (resp != 404){ 

        // Fill Table Data Rows
        var widget = '';
        var total_apu_project = 0;
        var total_apu_s2 = 0;

        $.map(resp, function (groupObj, i) {
            $.each(groupObj, function (i, groupArr) {

                // Const. Widget and Table
                var pref = helper.alph[i]; 
                widget = widget + helper.getHtmlIni(pref+') '+groupArr.name);

                // Init APUs x Group
                var num = 1;
                var total_apu_xg = 0;

                 // Get APUs by Group (As group ID is unique it's not necessary the project)
                 getByGroup('apu',groupArr.id,function(resp) {
                    if (resp != 404){        
                        $.map(resp, function (obj, i) {
                            $.each(obj, function (i, data) {
                                
                                // Sum totals from all APUs by Groups
                                total_apu_xg += Number(data.tot_precio_unitario);
                                total_apu_project += (Number(data.tot_precio_unitario) * Number(data.cant));

                                // Get SUM of Last 'Advanced Forms' for this APU (if exists) (Sec. 2)
                                var cant_s2 = 0;
                                var total_s2 = 0;
                                var percent_s2 = 0;
                                getByAPU('apu_advanced_form',data.id,function(resp) {
                                    if (resp != 404){        
                                        $.map(resp, function (aaf_obj, i) {
                                            $.each(aaf_obj, function (i, aaf_arr) { 
                                                cant_s2 += Number(aaf_arr.cant);
                                                total_s2 += Number(aaf_arr.total);
                                                percent_s2 += Number(aaf_arr.percent);
                                            });
                                        });
                                    }
                                });

                                // Fill Rows
                                widget = widget + 
                                    '<tr>' +
                                        '<td class="text-center">' + pref+'-'+num + '</td>' +
                                        '<td>' + data.actividad + '</td>' +
                                        '<td class="text-center">' + data.unidad + '</td>' +
                                        // Sec. 1 "Cantidades de Contrato"
                                        '<td class="text-right line-left-s1" id="'+data.id+'-cant-s1">' + formatNum(data.cant) + '</td>' +
                                        '<td class="text-right" id="'+data.id+'-punit">' + formatNum(data.tot_precio_unitario) + '</td>' +
                                        '<td class="text-right" id="'+data.id+'-subtot">' + formatNum(data.tot_precio_unitario * data.cant) + '</td>' +
                                        // Sec. 2 "Avance Anterior Acumulado"
                                        '<td class="text-right line-left-s2" id="'+data.id+'-cant-s2">' + formatNum(cant_s2) + '</td>' +
                                        '<td class="text-right">' + formatNum(total_s2) + '</td>' +
                                        '<td class="text-center">' + formatNum(percent_s2) +
                                            '<div class="progress progress-mini">' +
                                                '<div class="progress-bar progress-bar-info" style="width: '+percent_s2+'%;"></div>' +
                                            '</div>' +
                                        '</td>' +
                                        // Sec. 3 "Avance Presente Periodo"
                                        '<td class="text-right line-left-s3">' +
                                            '<input type="text" id="'+data.id+'" class="form-control adv-form-in" />' +
                                        '</td>' +
                                        '<td class="text-right" id="'+data.id+'-tot-s3"></td>' +
                                        '<td class="text-center line-right-s3" id="'+data.id+'-perc-s3"></td>' +
                                        // Sec. 4 "Avance Acumulado a la Fecha"
                                        '<td class="text-right" id="'+data.id+'-cant-s4"></td>' +                                        
                                        '<td class="text-right" id="'+data.id+'-tot-s4"></td>' +
                                        '<td class="text-center line-right-s4" id="'+data.id+'-perc-s4"></td>' +
                                        // Sec. 5 "Saldo por Ejecutar"
                                        '<td class="text-right" id="'+data.id+'-cant-s5"></td>' +                                        
                                        '<td class="text-right" id="'+data.id+'-tot-s5"></td>' +
                                        '<td class="text-center line-right-s5" id="'+data.id+'-perc-s5"></td>' +
                                    '</tr>';

                                    // Sum Totals
                                    total_apu_s2 += Number(total_s2);
                                    num++;
                            });
                        });
                    }
                });

                widget = widget + 
                    '<tr>' +
                        '<td colspan="4"></td><td class="text-right strong">' + formatNum(total_apu_xg) + '</td><td colspan="13"></td>' +
                    '<tr>';

                // Close tags Widget and Table
                widget = widget + helper.getHtmlEnd();

            });
        });

        // Fill Totals in last widget
        apu.totalProject = total_apu_project.toFixed(2);
        helper.sum_s2 = total_apu_s2;
        $('td.tot-proj').html(formatNum(total_apu_project));
        $('td.tot-s2').html(formatNum(total_apu_s2));
        var per_s2 = ((total_apu_s2 / total_apu_project) * 100).toFixed(2);
        var bar_s2 = per_s2 + 
                    '<div class="progress progress-mini">' +
                        '<div class="progress-bar progress-bar-info" style="width: '+per_s2+'%;"></div>' +
                    '</div>';
    
        $('td.perc-s2').html(bar_s2); 
        
        // Add data to DOM
        $('div.adv-form-cont').html(widget);
        InitWidgetToggle();
        $('.adv-form-in').keyup(); // Simulate a keyup of each 'Cant' input to calculate the rest of the form

        }
    });

    /** Fill Project Currency in tables at the end */
    $('td.proj-currency').html(CURRENCY_PROJ);
    
    $('#spinner').fadeOut('slow');
    
});

// Init Widget Toggle
function InitWidgetToggle(){
    $('.widget[data-toggle="collapse-widget"] .widget-body')
		.on('show.bs.collapse', function(){
			$(this).parents('.widget:first').attr('data-collapse-closed', "false");
		})
		.on('shown.bs.collapse', function(){
			setTimeout(function(){ $(window).resize(); }, 500);
		})
		.on('hidden.bs.collapse', function(){
			$(this).parents('.widget:first').attr('data-collapse-closed', "true");
		});
	
    $('.widget[data-toggle="collapse-widget"]').each(function()
    {
        // append toggle button
        if (!$(this).find('.widget-head > .collapse-toggle').length)
            $('<span class="collapse-toggle"></span>').appendTo($(this).find('.widget-head'));
        
        // make the widget body collapsible
        $(this).find('.widget-body').not('.collapse').addClass('collapse');
        
        // verify if the widget should be opened
        if ($(this).attr('data-collapse-closed') !== "true")
            $(this).find('.widget-body').addClass('in');
        
        // bind the toggle button
        $(this).find('.collapse-toggle').on('click', function(){
            $(this).parents('.widget:first').find('.widget-body').collapse('toggle');
        });
    });
}

// GET APUs by Group 
function getByGroup(dBtable,id,callback){
    $.ajax({
        url: API_URL + '/' + dBtable + '/by_group/' + id,
        type: "GET",
        dataType: 'json',
        async: false,                    
    }).done(function(data) {
        callback(data);

    }).fail(function (jqXHR) {
        console.log('Status code: ' + jqXHR.status + '. Group ID: ' + id);
        callback(jqXHR.status);
    });
}

// Get Last 'Advanced Form' by APU (if exists)
function getByAPU(dBtable,apu_id,callback){
    $.ajax({
        url: API_URL + '/' + dBtable + '/by_apu/' + ACTIVE_PROJECT + '&' + apu_id,
        type: "GET",
        dataType: 'json',
        async: false,                    
    }).done(function(data) {
        callback(data);

    }).fail(function (jqXHR) {
        console.log('Status code: ' + jqXHR.status + '. APU ID: ' + apu_id);
        callback(jqXHR.status);
    });
}

// Calculation 'Datos Generales'
$(document).on('keyup', ".adv-form-in", function(event) {
    
    // Get APU, S3 Cant, Precio Unitario
    var apu_id = $(this).attr('id');
    var cant_s3 = Number($(this).val()); 
    var apu_precio_unit = formatInvNum($('#'+apu_id+'-punit').html());
    var apu_subtotal = formatInvNum($('#'+apu_id+'-subtot').html()); 

    // S3 Total
    var tot_s3 = (cant_s3 * apu_precio_unit).toFixed(2); 
    $('#'+apu_id+'-tot-s3').html(formatNum(tot_s3));
    
    // S3 Percent & Progress bar
    var percent_s3 = ((Number(tot_s3) / apu_subtotal) * 100).toFixed(0);
    var percent = percent_s3 + 
                    '<div class="progress progress-mini">' +
                        '<div class="progress-bar progress-bar-success" style="width: '+percent_s3+'%;"></div>' +
                    '</div>';
    
    $('#'+apu_id+'-perc-s3').html(percent); 
    
    // S4 Cant
    var cant_s2 = formatInvNum($('#'+apu_id+'-cant-s2').html()); 
    var cant_s4 = cant_s2 + cant_s3;
    $('#'+apu_id+'-cant-s4').html(formatNum(cant_s4));

    // S4 Total
    var tot_s4 = (cant_s4 * apu_precio_unit).toFixed(2);
    $('#'+apu_id+'-tot-s4').html(formatNum(tot_s4));

    // S4 Percent & Progress bar
    var percent_s4 = ((Number(tot_s4) / apu_subtotal) * 100).toFixed(0);
    var percent = percent_s4 + 
                    '<div class="progress progress-mini">' +
                        '<div class="progress-bar progress-bar-info" style="width: '+percent_s4+'%;"></div>' +
                    '</div>';
    
    $('#'+apu_id+'-perc-s4').html(percent); 

    // S5 Cant
    var cant_s1 = formatInvNum($('#'+apu_id+'-cant-s1').html()); 
    var cant_s5 = cant_s1 - cant_s4;
    $('#'+apu_id+'-cant-s5').html(formatNum(cant_s5));

    // S5 Total
    var tot_s5 = (apu_subtotal - tot_s4).toFixed(2);
    $('#'+apu_id+'-tot-s5').html(formatNum(tot_s5));

    // S5 Percent & Progress bar
    var percent_s5 = ((Number(tot_s5) / apu_subtotal) * 100).toFixed(0);
    var percent = percent_s5 + 
                    '<div class="progress progress-mini">' +
                        '<div class="progress-bar progress-bar-warning" style="width: '+percent_s5+'%;"></div>' +
                    '</div>';
    
    $('#'+apu_id+'-perc-s5').html(percent); 

});

// Patch - Percentage in 'Tasks' table in DB (so, it's shown in gantt)
function patchPercentage(json){
    $.ajax({
        url: API_URL + '/tasks/p/',
        type: 'PATCH',
        dataType: 'json',
        data: json
    }).done(function(data) {
        console.log('Saved %');
    }).fail(function (jqXHR) {
        $.gritter.add({
            title: '¡Error!',
            text: 'El % no fue correctamente guardado.'
        });
        console.log('Failed, status code: ' + jqXHR.status);
    });
}


// Save "Advanced Form" & "APU Advanced Form" 
$(document).off('click', "#save-adv-form").on('click', "#save-adv-form", function(event) {
    
    event.preventDefault(); 

    if(!$('#date-ini').val() || !$('#date-end').val()) {
        $.gritter.add({
            title: '¡Campos Vacios!',
            text: 'Debe llenar el Periodo de Inicio y Fin.'
        });
    }else {
        
        // Save "Advanced Form" in DB
        var JSONData = {};
        JSONData['proj_id'] = ACTIVE_PROJECT;
        JSONData['id'] = helper.new_adv_form_num;
        JSONData['date_ini'] = $('#date-ini').val();
        JSONData['date_end'] = $('#date-end').val();
        JSONData = JSON.stringify(JSONData); 

        // Check if data exists to Create or Update
        var type = helper.adv_form_exists ? 'PUT' : 'POST';
        var file = helper.adv_form_exists ? 'u' : 'c';
        if (file == 'c'){
            helper.adv_form_exists = true;
        }

        // Insert/Update in DB
        saveInDB('advanced_form',type,file,JSONData);
       
        // Reset Totals to count from 0
        helper.sum_s3 = 0;
        
        // Save "APUs Advanced Form", Iterate all APUs
        $('input.adv-form-in').each(function (i) {
            
            // Check & save the ones with the value filled 
            if($(this).val()){
                
                // JSON
                var apu_id = $(this).attr('id');
                var apu_precio_unit = formatInvNum($('#'+apu_id+'-punit').html()); 
                var apu_subtotal = formatInvNum($('#'+apu_id+'-subtot').html()); 
                var total = (Number($(this).val()) * apu_precio_unit).toFixed(2);
                var percent = ((Number(total) / apu_subtotal) * 100).toFixed(0);

                var JSONData = {};
                JSONData['proj_id'] = ACTIVE_PROJECT;
                JSONData['apu_id'] = apu_id;
                JSONData['adv_form_id'] = helper.new_adv_form_num;
                JSONData['cant'] = $(this).val();
                JSONData['total'] = total;
                JSONData['percent'] = percent;
                JSONData = JSON.stringify(JSONData);
                
                // Check if data exists to Create or Update
                var type = 'POST';
                var file = 'c';
                if($.inArray(apu_id, helper.apus_modified) != -1){
                    type = 'PUT';
                    file = 'u';
                }

                // Save/Update in DB
                $.ajax({
                    url: API_URL + '/apu_advanced_form/' + file + '/',
                    type: type,
                    dataType: 'json',
                    data: JSONData
                }).done(function(data) {

                    // Save APU modified in array
                    helper.apus_modified.push(apu_id);

                    // Get old percentage % from 'Tasks' DB 
                    var old_perc = 0;
                    getByAPU('apu_advanced_form',apu_id,function(resp) {
                        if (resp != 404){        
                            $.map(resp, function (obj, i) {
                                $.each(obj, function (i, arr) { 
                                    if(arr.adv_form_id < helper.new_adv_form_num){
                                        old_perc += Number(arr.percent);                                                    
                                    }
                                });
                            });
                        }
                    });

                    // Patch 'Tasks' DB with %
                    var JSONTasks = {};
                    JSONTasks['project_id'] = ACTIVE_PROJECT;
                    JSONTasks['id'] = apu_id;
                    JSONTasks['taskPercentageComp'] = Number(percent) + Number(old_perc); 
                    JSONTasks = JSON.stringify(JSONTasks);
                    patchPercentage(JSONTasks);


                    // "Totals" last widget
                    // S3 (Avance Presente Periodo)
                    helper.sum_s3 += Number(total); 
                    $('td.tot-s3').html(formatNum(helper.sum_s3));

                    var per_s3 = ((helper.sum_s3 / apu.totalProject) * 100).toFixed(2);
                    var bar_s3 = per_s3 + 
                                '<div class="progress progress-mini">' +
                                    '<div class="progress-bar progress-bar-success" style="width: '+per_s3+'%;"></div>' +
                                '</div>';
                
                    $('td.perc-s3').html(bar_s3); 

                    // S4 (Avance Acumulado a la Fecha)
                    var sum_s4 = helper.sum_s2 + helper.sum_s3;
                    $('td.tot-s4').html(formatNum(sum_s4));

                    var per_s4 = ((sum_s4 / apu.totalProject) * 100).toFixed(2);
                    var bar_s4 = per_s4 + 
                                '<div class="progress progress-mini">' +
                                    '<div class="progress-bar progress-bar-info" style="width: '+per_s4+'%;"></div>' +
                                '</div>';
                
                    $('td.perc-s4').html(bar_s4); 
                    
                    // S5 (Saldo por Ejecutar)
                    var sum_s5 = apu.totalProject - sum_s4;
                    $('td.tot-s5').html(formatNum(sum_s5));

                    var per_s5 = ((sum_s5 / apu.totalProject) * 100).toFixed(2);
                    var bar_s5 = per_s5 + 
                                '<div class="progress progress-mini">' +
                                    '<div class="progress-bar progress-bar-warning" style="width: '+per_s5+'%;"></div>' +
                                '</div>';
                
                    $('td.perc-s5').html(bar_s5); 

                    // Table (Resumen del Certificado)
                    $('td.imp-acu-fecha').html(formatNum(sum_s4)); 
                    $('td.imp-pres-per').html(formatNum(helper.sum_s3)); 
                    $('td.imp-porc-fecha').html(per_s4 + '%');  

                    // Table (Retenciones de Garantia) 
                    $('td.ret-per-ant').html(formatNum(helper.sum_s2 * 0.1)); 
                    $('td.ret-pres-per').html(formatNum(helper.sum_s3 * 0.1)); 
                    $('td.tot-ret').html(formatNum((helper.sum_s2 + helper.sum_s3) * 0.1));
                    
                    // Table (Información de la Facturación)
                    var ret_garan = helper.sum_s3 * 0.1;
                    $('td.ret-garan').html(formatNum(ret_garan)); 
                    var pago_antic = 0;  // REVIEW if input or where is it from
                    $('td.pago-antic').html(pago_antic); 
                    var tot_cert = helper.sum_s3 - pago_antic;
                    $('td.tot-cert').html(formatNum(tot_cert));
                    var tot_fact = tot_cert - ret_garan;
                    $('td.tot-fact').html(formatNum(tot_fact)); 

                    // Table (Estado de la Situacion a Fecha)
                    $('td.mont-contra').html(formatNum(apu.totalProject)); 
                    var antic_anter = 0;
                    var liq_per_ant = helper.sum_s2 - (helper.sum_s2 * 0.1) - antic_anter;
                    $('td.liq-per-ant').html(formatNum(liq_per_ant)); 
                    $('td.liq-pres-per').html(formatNum(tot_fact));
                    var liq_acum = liq_per_ant + tot_fact; 
                    $('td.liq-acum').html(formatNum(liq_acum));

                    /** MISSING "Descuentos por Anticipo" & "Pago por Planillas" */
                    
                }).fail(function (jqXHR) {
                    $.gritter.add({
                        title: '¡Error!',
                        text: 'Los datos no fueron correctamente guardados.'
                    });
                    console.log('Failed, status code: ' + jqXHR.status);
                });

            }

        });

        $.gritter.add({
            title: '¡Exito!',
            text: 'Planilla Guardada Correctamente. '
        });

    }

});