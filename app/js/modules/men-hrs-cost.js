/**
 * Men-hrs-cost
 */

// Init Vars to Check If they exist in DB
var ExistsInDB = {
    cost_man_hrs_exists : false
}

// Calculations for Worker
var calculation = {
    // Vars
    worker_arr: new Array(),
    hrs_trab_x_dia: 0,
    comida_completa_dia: 0,
    hrs_mes_x_persona: 0,
    hrs_trabajadas_mes: 0,
    relac_gastos_hrs_trab: 0,
    coef_ap_patronales: 0,
    coef_aguinaldo_liq: 0,
    coef_desc_afp: 0,
    basico: 0,
    bono: 0,
    precio_doble: 0,
    hrs_extra_mes: 0,
    tot_hrs_extra: 0,
    sueldo_tot_mes: 0,
    ap_patronales: 0,
    descuento_afp: 0,
    liquido_pagable: 0,
    aguinaldo_liq: 0,
    comida_mensual: 0,
    transporte: 0,
    epp: 0,
    calc_epp: 0,
    tot_comida_mes: 0,
    cant: 0,
    sum_cant: 0,
    gasto_uni_x_cargo: 0,
    gasto_mensual_tot: 0,
    sum_gasto_mensual_tot: 0,
    bs_x_hr: 0,
    
    // Functions
    getHrsTrabMes: function(){
        return this.hrs_trabajadas_mes = this.hrs_mes_x_persona * this.sum_cant;
    },
    getRelGastosHrsTrab: function(){
        this.hrs_trabajadas_mes = Number(this.hrs_trabajadas_mes);
        this.sum_gasto_mensual_tot = Number(this.sum_gasto_mensual_tot);
        // return this.relac_gastos_hrs_trab = (Math.round((this.sum_gasto_mensual_tot / this.hrs_trabajadas_mes)*100)/100).toFixed(7);
        return this.relac_gastos_hrs_trab = (this.sum_gasto_mensual_tot / this.hrs_trabajadas_mes).toFixed(7);
    },
    sumBasicoBono: function(){
        this.basico = Number(this.basico);
        this.bono = Number(this.bono);
        return (Math.round((this.basico+this.bono)*100)/100).toFixed(2);
    },
    getPrecioDoble: function(){
        this.precio_doble = (Math.round((this.basico/30/8*2)*100)/100).toFixed(2);
        return this.precio_doble;
    },
    getHrsExtraMes: function(){
        this.hrs_extra_mes = (Math.round((((6*this.hrs_trab_x_dia)-48)*4)*100)/100).toFixed(2);
        return this.hrs_extra_mes;
    },
    getTotHrsExtra: function(){
        this.tot_hrs_extra = (Math.round((this.precio_doble * this.hrs_extra_mes)*100)/100).toFixed(2);
        return this.tot_hrs_extra;
    },
    getSueldoTotMes: function(){
        this.tot_hrs_extra = Number(this.tot_hrs_extra);
        return this.sueldo_tot_mes = (Math.round((this.basico + this.bono + this.tot_hrs_extra)*100)/100).toFixed(2);
    },
    getApPatronales: function(){
        this.sueldo_tot_mes = Number(this.sueldo_tot_mes);
        return this.ap_patronales = (Math.round((this.sueldo_tot_mes * this.coef_ap_patronales)*100)/100).toFixed(2);
    },
    getDescuentoAfp: function(){
        return this.descuento_afp = (Math.round((this.sueldo_tot_mes * this.coef_desc_afp)*100)/100).toFixed(2);
    },
    getLiquidoPagable: function(){
        this.descuento_afp = Number(this.descuento_afp);
        return this.liquido_pagable = (Math.round((this.sueldo_tot_mes - this.descuento_afp)*100)/100).toFixed(2);
    },
    getAguinaldoLiq: function(){
        return this.aguinaldo_liq = (Math.round((this.sueldo_tot_mes * this.coef_aguinaldo_liq)*100)/100).toFixed(2);
    },
    getComidaMensual: function(){
        return this.comida_mensual = (Math.round((this.comida_completa_dia * this.hrs_mes_x_persona / 9)*100)/100).toFixed(2);
    },
    getTransporte: function(){
        return this.transporte = (Math.round(this.transporte*100)/100).toFixed(2);
    },
    getEpp: function(){
        return this.calc_epp = (Math.round((this.epp / 6)*100)/100).toFixed(2);
    },
    getTotComidaMes: function(){
        this.comida_mensual = Number(this.comida_mensual);
        this.cant = Number(this.cant);
        return this.tot_comida_mes = (Math.round((this.comida_mensual * this.cant)*100)/100).toFixed(2);
    },
    getGastoUnixCargo: function(){
        this.liquido_pagable = Number(this.liquido_pagable);
        this.ap_patronales = Number(this.ap_patronales);
        return this.gasto_uni_x_cargo = (Math.round((this.liquido_pagable + this.comida_mensual + this.ap_patronales)*100)/100).toFixed(2);
    },
    getGastoMensualTot: function(){
        this.transporte = Number(this.transporte);
        this.calc_epp = Number(this.calc_epp);
        this.aguinaldo_liq = Number(this.aguinaldo_liq);
        return this.gasto_mensual_tot = (Math.round(((this.liquido_pagable + this.comida_mensual + this.ap_patronales + this.transporte + this.calc_epp + this.aguinaldo_liq) * this.cant)*100)/100).toFixed(2);
    },
    getBsxHr: function(){
        this.gasto_mensual_tot = Number(this.gasto_mensual_tot);
        return this.bs_x_hr = (Math.round((this.gasto_mensual_tot / (this.cant * this.hrs_mes_x_persona))*100)/100).toFixed(2);
    },
    getGastoMensualTotAtOnce: function(){
        this.getPrecioDoble();
        this.getHrsExtraMes();
        this.getTotHrsExtra();
        this.getSueldoTotMes();
        this.getApPatronales();
        this.getDescuentoAfp();
        this.getLiquidoPagable();
        this.getAguinaldoLiq();
        this.getComidaMensual();
        this.getTransporte();
        this.getEpp();
        this.getTotComidaMes();
        this.getGastoUnixCargo();
        return this.getGastoMensualTot();
    }
}

// Once Page is Loaded
$(document).ready(function(){

    // Get 'Datos Generales' data from DB according to Project
    getSingle('proj_cost_man_hrs',ACTIVE_PROJECT,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                $('input#hrs_trab_x_dia').val(data.hrs_trab_x_dia);
                $('input#comida_completa_dia').val(data.comida_completa_dia);
                $('input#hrs_mes_x_persona').val(data.hrs_mes_x_persona);
                $('input#hrs_trabajadas_mes').val(data.hrs_trabajadas_mes);
                $('input#total_mensual').val(data.total_mensual);
                $('input#relac_gastos_hrs_trab').val(data.relac_gastos_hrs_trab);
                $('input#coef_ap_patronales').val(data.coef_ap_patronales);
                $('input#coef_aguinaldo_liq').val(data.coef_aguinaldo_liq);
                $('input#coef_desc_afp').val(data.coef_desc_afp);

                // Save to use when adding a worker
                calculation.hrs_trab_x_dia = Number(data.hrs_trab_x_dia);
                calculation.comida_completa_dia = Number(data.comida_completa_dia);
                calculation.hrs_mes_x_persona = Number(data.hrs_mes_x_persona);
                calculation.sum_gasto_mensual_tot = Number(data.total_mensual);
                calculation.coef_ap_patronales = Number(data.coef_ap_patronales);
                calculation.coef_aguinaldo_liq = Number(data.coef_aguinaldo_liq);
                calculation.coef_desc_afp = Number(data.coef_desc_afp);
            });
            ExistsInDB.cost_man_hrs_exists = true;
        }
    });

    // Fill 'Select' with Workers    
    getAll('workers', function(resp) {
        if (resp != 404){
            var options = '<optgroup label="Trabajadores">';
            $.map(resp, function (workerObj, i) {
                $.each(workerObj, function (i, workerArr) {
                    options = options + '<option value="' + workerArr.id + '">' + workerArr.name + '</option>'                
                });
            });
            options = options + '</optgroup>';
            $('select.workers').append(options);
        }        
    });

    // Get 'Cost Man Hrs' data from DB according to Project
    getAllByProj('cost_hrs_worker',function(resp) {
        if (resp != 404){
            var rows = '';
            var rows_res = '';
            $.map(resp, function (dataObj, i) {
                $.each(dataObj, function (i, dataArr) {
                    
                    // Save data in 'Calculation' Obj
                    calculation.basico = dataArr.basico;
                    calculation.bono = dataArr.bono;
                    calculation.epp = dataArr.epp;
                    calculation.cant = dataArr.cant;
                    calculation.sum_cant += Number(dataArr.cant);
                    calculation.transporte = dataArr.transporte;
                    
                    // Save ID of workers to get the 'Name' below 
                    if($.inArray(dataArr.worker_id, calculation.worker_arr) == -1){
                        calculation.worker_arr.push(dataArr.worker_id);
                    }

                    // Fill 'Worker' Data Rows
                    rows = rows +
                    '<tr>' +
                    '<td class="center ' + dataArr.worker_id + '"></td>' +
                    '<td class="center">' + dataArr.basico + '</td>' +
                    '<td class="center">' + dataArr.bono + '</td>' +
                    '<td class="center">' + calculation.sumBasicoBono() + '</td>' +
                    '<td class="center">' + dataArr.cant + '</td>' +
                    '<td class="center">' + calculation.getPrecioDoble() + '</td>' +
                    '<td class="center">' + calculation.getHrsExtraMes() + '</td>' +
                    '<td class="center">' + calculation.getTotHrsExtra() + '</td>' +
                    '<td class="center">' + calculation.getSueldoTotMes() + '</td>' +
                    '<td class="center">' + calculation.getApPatronales() + '</td>' +
                    '<td class="center">' + calculation.getDescuentoAfp() + '</td>' +
                    '<td class="center">' + calculation.getLiquidoPagable() + '</td>' +
                    '<td class="center">' + calculation.getAguinaldoLiq() + '</td>' +
                    '<td class="center">' + calculation.getComidaMensual() + '</td>' +
                    '<td class="center">' + calculation.getTransporte() + '</td>' +
                    '<td class="center">' + calculation.getEpp() + '</td>' +
                    '<td class="center">' + calculation.getTotComidaMes() + '</td>' +
                    '<td class="center">' + calculation.getGastoUnixCargo() + '</td>' +
                    '<td class="center">' + dataArr.gasto_mensual_tot + '</td>' +
                    '<td class="text-right">' +
                        '<div class="btn-group btn-group-xs ">' +
                            '<a id="'+dataArr.id+'" href="" class="btn btn-danger del-cost-man-hr"><i class="fa fa-times"></i></a>' +
                        '</div>' +
                    '</td>';

                    // Fill 'Resumen' Data Rows                    
                    rows_res = rows_res +
                    '<tr>' +
                        '<td class="' + dataArr.worker_id + '"></td>' +
                        '<td class="center">' + dataArr.bs_x_hr + '</td>' +
                    '</tr>';
                });
            });
            
            // Add Total 
            rows = rows + 
            '<tr class="tr_total_mensual">' +
                '<td colspan="18"></td><td class="center">' + calculation.sum_gasto_mensual_tot + '</td><td></td>' +
            '</tr>';

            // Append data in DOM
            $('#worker-row').append(rows);
            $('#resumen-row').append(rows_res);

            // According to woker IDs get the 'Name' and insert in table 
            $.each(calculation.worker_arr, function (i, workArr) {
                getSingle('workers',workArr,function(resp) {
                    if (resp != 404){   
                        $.map(resp, function (data, i) {
                            $('td.' + workArr).html(data.name);
                        });
                    }
                });
            });

        }
    });
    
});

// Calculation 'Datos Generales'
$(document).on('keyup', "#hrs_trab_x_dia", function(event) {
    var hrs_trab_x_dia = Number($('#hrs_trab_x_dia').val());
    $('#hrs_mes_x_persona').val((hrs_trab_x_dia * 6 * 4));
});

// Save 'Datos Generales' in DB
$(document).off('click', "#save-cost-man-hrs").on('click', "#save-cost-man-hrs", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('input#hrs_trab_x_dia').val()){
        $.gritter.add({
            title: '¡Campos Vacios!',
            text: 'Debe llenar el campo: "Horas Trabajo por Dia".'
        });
    }else{

        // Init required vars
        var id = ACTIVE_PROJECT;
        var form = $('form#cost-man-hrs-data');

        // Check if data exists to Create or Update
        var type = ExistsInDB.cost_man_hrs_exists ? 'PUT' : 'POST';
        var file = ExistsInDB.cost_man_hrs_exists ? 'u' : 'c';
        if (file == 'c'){
            ExistsInDB.cost_man_hrs_exists = true;
        }

        // Create JSON with Form
        var JSONData = serializeFormJSONAll(form);
        JSONData['id'] = id;
        JSONData = JSON.stringify(JSONData);

        // Insert into DB
        saveInDB('proj_cost_man_hrs',type,file,JSONData);
    }
});

// Add Worker to Project (DB)
$(document).off('click', "#add-cost-man-hrs").on('click', "#add-cost-man-hrs", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('input#basico').val() || !$('input#cant').val() || $('input#basico').val() == 0 || $('input#cant').val() == 0){
        $.gritter.add({
            title: '¡Campos Vacios!',
            text: 'Debe llenar los campos "Básico" y "Cantidad".'
        });
    }else{

        // Init required vars
        var proj_id = ACTIVE_PROJECT;
        var form = $('form#cost-man-hrs');
        
        // Set data for calculations
        calculation.basico = Number($('input#basico').val());
        calculation.bono = Number($('input#bono').val());
        calculation.cant = Number($('input#cant').val());
        calculation.epp = Number($('input#epp').val()); 
        calculation.transporte = Number($('input#transporte').val());

        // Create JSON with Form
        var JSONData = serializeFormJSONAll(form);
        JSONData['proj_id'] = proj_id;
        JSONData['gasto_mensual_tot'] = calculation.getGastoMensualTotAtOnce();
        JSONData['bs_x_hr'] = calculation.getBsxHr();
        JSONData = JSON.stringify(JSONData); 

        // Insert into DB and display in DOM
        $.ajax({
            url: API_URL + '/cost_hrs_worker/c/',
            type: "POST",
            dataType: 'json',
            data: JSONData
        }).done(function(data) {

            // Get ID of created row & Worker
            var id = '';
            var worker_id = '';
            $.map(data, function (dataObj, i) {
                id = dataObj.id;
                worker_id = dataObj.worker_id;
            });

            // Fill 'Worker' Data Rows
            var rows = '';
            rows = rows +
                '<tr>' +
                    '<td class="center ' + worker_id + '"></td>' +
                    '<td class="center">' + calculation.basico.toFixed(2) + '</td>' +
                    '<td class="center">' + calculation.bono.toFixed(2) + '</td>' +
                    '<td class="center">' + calculation.sumBasicoBono() + '</td>' +
                    '<td class="center">' + calculation.cant + '</td>' +
                    '<td class="center">' + calculation.getPrecioDoble() + '</td>' +
                    '<td class="center">' + calculation.getHrsExtraMes() + '</td>' +
                    '<td class="center">' + calculation.getTotHrsExtra() + '</td>' +
                    '<td class="center">' + calculation.getSueldoTotMes() + '</td>' +
                    '<td class="center">' + calculation.getApPatronales() + '</td>' +
                    '<td class="center">' + calculation.getDescuentoAfp() + '</td>' +
                    '<td class="center">' + calculation.getLiquidoPagable() + '</td>' +
                    '<td class="center">' + calculation.getAguinaldoLiq() + '</td>' +
                    '<td class="center">' + calculation.getComidaMensual() + '</td>' +
                    '<td class="center">' + calculation.getTransporte() + '</td>' +
                    '<td class="center">' + calculation.getEpp() + '</td>' +
                    '<td class="center">' + calculation.getTotComidaMes() + '</td>' +
                    '<td class="center">' + calculation.getGastoUnixCargo() + '</td>' +
                    '<td class="center">' + calculation.getGastoMensualTot() + '</td>' +
                    '<td class="text-right">' +
                        '<div class="btn-group btn-group-xs ">' +
                            '<a id="'+id+'" href="" class="btn btn-danger del-cost-man-hr"><i class="fa fa-times"></i></a>' +
                        '</div>' +
                    '</td>' +
                '</tr>';
            $('#worker-row').append(rows);

            // Fill 'Resumen' Data Rows
            var rows_res = '';
            rows_res = rows_res +
                '<tr>' +
                    '<td class="' + worker_id + '"></td>' +
                    '<td class="center">' + calculation.getBsxHr() + '</td>' +
                '</tr>';
            $('#resumen-row').append(rows_res);

            // According to woker ID get the 'Name' and insert in table             
            getSingle('workers',worker_id,function(resp) {
                if (resp != 404){   
                    $.map(resp, function (data, i) {
                        $('td.' + worker_id).html(data.name);
                    });
                }
            });

            // Clear Inputs
            $('form#cost-man-hrs').find("input[type=text], textarea").val("0");

            //Re-calculate 'Datos Generales' accordingly 
            reCalcDatosGrales('add');

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });
    }
});

function reCalcDatosGrales (action){

    // Calc. 'Horas Trabajadas Mes'
    if (action == 'add'){
        calculation.sum_cant += calculation.cant;
    }else{
        calculation.sum_cant -= calculation.cant;
    }
    $('input#hrs_trabajadas_mes').val(calculation.getHrsTrabMes());

    // Calc. 'Total Mensual'
    if (action == 'add'){
        calculation.sum_gasto_mensual_tot += Number(calculation.getGastoMensualTot());
    }else{
        calculation.sum_gasto_mensual_tot -= Number(calculation.gasto_mensual_tot);
    }
    calculation.sum_gasto_mensual_tot = calculation.sum_gasto_mensual_tot.toFixed(2);
    $('input#total_mensual').val(calculation.sum_gasto_mensual_tot);
    $('tr.tr_total_mensual').remove();
    var tots = 
    '<tr class="tr_total_mensual">' +
        '<td colspan="18"></td><td class="center">' + calculation.sum_gasto_mensual_tot + '</td><td></td>' +
    '</tr>';
    $('#worker-row').append(tots);

    // Calc. 'Relacion de Gastos/Hrs. Trabajadas'
    $('input#relac_gastos_hrs_trab').val(calculation.getRelGastosHrsTrab());            

    // Save 'Datos Generales' Section
    $('#save-cost-man-hrs').trigger('click');
}

// Delete Worker to Project (DB)
$(document).off('click', ".del-cost-man-hr").on('click', ".del-cost-man-hr", function(event) {

    event.preventDefault();

    // Get Item ID
    var id = $(this).attr('id'); 

    // Get Data from DB for Calculations, before delete it
    getSingle('cost_hrs_worker',id,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                calculation.cant = data.cant;
                calculation.gasto_mensual_tot = data.gasto_mensual_tot;
            });
        }
    });

    var conf = confirm('Esta seguro que desea eliminar el registro?');
    if(conf){
        
        // Delete from DB 
        $.ajax({
            url: API_URL + '/cost_hrs_worker/d/' + id,
            type: "DELETE",
            dataType: 'json'
        }).done(function(data) {
    
            // Delete Row from DOM
            $('#'+id).parent().parent().parent().remove();            
    
            // Re-calculate 'Datos Generales' accordingly 
            reCalcDatosGrales('delete');

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });
    }

});