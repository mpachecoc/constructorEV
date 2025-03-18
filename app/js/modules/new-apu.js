/**
 * New APU
 */

 // Init Vars to Check If they exist in DB
var ExistsInDB = {
    apu_exists: false,
}

// Save totals to display
var Totals = {
    supplies: 0,
    workers: 0,
    total_workers: 0,
    equipments: 0,
    general_admin: 0,
    utility: 0,
    taxes: 0,
    total_precio_unit: 0,
    beneficios_sociales: 0,
    iva: 0,
    gastos_generales: 0,
    utilidad_costo_directo: 0,
    it: 0,
    
    /** Calculate and fill points 4,5,6 and 'Total Precio Unitario' */
    calcTotPrecioUnitario: function() {
        var sum_1_3 = Number(this.supplies) + Number(this.total_workers) + Number(this.equipments);

        // 4: (1+2+3) * % de Gastos Generales
        this.general_admin = sum_1_3 * (Number(this.gastos_generales) / 100);
        $('input#tot_gastos_gral_admin').val(formatNum(this.general_admin));
        
        // 5: (1+2+3+4) * % Utilidad sobre Costo Directo
        var sum_1_4 = formatNum(sum_1_3 + this.general_admin);
        this.utility = sum_1_4 * (Number(this.utilidad_costo_directo) / 100);
        $('input#tot_utilidad').val(formatNum(this.utility));
        
        // 6: (1+2+3+4+5) * % I.T.
        var sum_1_5 = formatNum(Number(sum_1_4) + Number(this.utility)); 
        this.taxes = sum_1_5 * (Number(this.it) / 100);
        $('input#tot_impuestos').val(formatNum(this.taxes));
        
        // Total Precio Unitario (Sum 1-6)
        this.total_precio_unit = formatNum(Number(sum_1_5) + Number(this.taxes)); 
        $('input#tot_precio_unitario').val(this.total_precio_unit);
    },
    getAPUJson: function() {
        // Get 2 Forms
        var form = $('form#gral-apu');
        var form2 = $('form#totals-apu');

        // Serialize 
        var JSONData = serializeFormJSONAll(form);
        var JSONData2 = serializeFormJSONAll(form2);

        // Add missing data
        JSONData['tot_materiales'] = this.supplies;
        JSONData['tot_mano_de_obra'] = this.total_workers;
        JSONData['tot_equipo'] = this.equipments;

        // Merge Forms
        JSONData = $.extend({}, JSONData, JSONData2);

        return JSON.stringify(JSONData); 
    },
    getTaskJson: function(action) {
        // Construct JSON
        var JSONData = {};
        JSONData['id'] = $('#id').val();
        JSONData['project_id'] = $('#project_id').val();

        if (action == 'c'){
            var presup_date;

            // Get 'Presup' date to set 'start' & 'end'             
            $.ajax({
                url: API_URL + '/projects/s/' + ACTIVE_PROJECT,
                type: "GET",
                dataType: 'json',
                async: false,            
            }).done(function(data) {
                if (data != 404){   
                    $.map(data, function (data, i) { 
                        presup_date = data.presup_date;
                    });
                }
            }).fail(function (jqXHR) {
                console.log('Failed, status code: ' + jqXHR.status);
                callback(jqXHR.status);
            });
                
            JSONData['name'] = $('#actividad').val();
            JSONData['percentage'] = 0;
            JSONData['start_date'] = presup_date;
            JSONData['end_date'] = presup_date;

        }else if (action == 'u'){

            JSONData['taskName'] = $('#actividad').val(); // DB row name to patch
        }

        return JSON.stringify(JSONData); 
    }
}

// Once Page is Loaded
$(document).ready(function(){

    // Set Project Name 
    $('input#project_id').val(ACTIVE_PROJECT);

    // Get Apu Ids and set the new one
    getAllByProj('apu',function(resp) {
        if (resp != 404){
            var last_apu_id = '';
            $.map(resp, function (apuObj, i) {
                $.each(apuObj, function (i, apuArr) {
                    last_apu_id = apuArr.id;
                });
            });
            // Create new ID
            var res = last_apu_id.split("-");
            res = (Number(res[1])) + 1;
            var pre = (res >= 1 && res <= 9) ? '00' : '0';
            $('input#id').val('APU-' + pre + res);

        }else{
            $('input#id').val('APU-001');
        }
    });

    // Get 'Supplies' from DB
    getAll('supplies', function(resp) {
        if (resp != 404){
            var options = '<optgroup label="Materiales">';
            $.map(resp, function (suppObj, i) {
                $.each(suppObj, function (i, suppArr) {
                    options = options + 
                    '<option value="' + suppArr.id + '">' + suppArr.id + ' - ' + suppArr.name + ' - ' + suppArr.und + '</option>';   
                });
            });
            options = options + '</optgroup>';
            $('select.supplies').append(options);
        }
    });
    
    // Get 'Workers' (men-hrs-cost) from DB
    getAllByProj('cost_hrs_worker', function(resp) {
        if (resp != 404){
            $.map(resp, function (Obj, i) {
                $.each(Obj, function (i, Arr) {
    
                    // Get 'Name' according to woker ID             
                    getSingle('workers',Arr.worker_id,function(resp) {
                        if (resp != 404){   
                            $.map(resp, function (data, i) {
                                var options = '<option value="' + Arr.id + '">' + Arr.worker_id + ' - ' + data.name + ' - ' + Arr.bs_x_hr + ' Bs./Hr.</option>';   
                                $('select.workers').append(options);
                            });
                        }
                    });

                });
            });
        }
    });

    // Get 'Equipment' from DB
    getAll('equipment', function(resp) {
        if (resp != 404){
            var options = '<optgroup label="Equipamento y Maquinaria">';
            $.map(resp, function (Obj, i) {
                $.each(Obj, function (i, Arr) {
                    options = options + 
                    '<option value="' + Arr.id + '">' + Arr.id + ' - ' + Arr.name + ' - ' + Arr.precio + '</option>';   
                });
            });
            options = options + '</optgroup>';
            $('select.equipment').append(options);
        }
    });

    /* Select2 -  Init Advanced Select Controls */
	if (typeof $.fn.select2 != 'undefined'){
        
        // Supplies
		if ($('#supp_id').length)
			$('#supp_id').select2();
        
        // Workers
		if ($('#cw_id').length)
			$('#cw_id').select2();
        
        // Equipment
		if ($('#equip_id').length)
			$('#equip_id').select2();
    }
    
    // Get 'Beneficios Sociales', 'IVA', etc.
    getSingle('projects_comp',ACTIVE_PROJECT,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                Totals.beneficios_sociales = Number(data.beneficios_sociales);
                Totals.iva = Number(data.iva);
                Totals.gastos_generales = Number(data.gastos_generales);
                Totals.utilidad_costo_directo = Number(data.utilidad_costo_directo);
                Totals.it = Number(data.it);
            });
        }
    });

});

// Save 'Datos Generales' of APU
$(document).off('click', "#save-gral-apu").on('click', "#save-gral-apu", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('input#actividad').val()){
        $.gritter.add({
            title: '¡Campos Vacios!',
            text: 'Debe llenar el campo "Actividad".'
        });
    }else{

        // Check if data exists to Create or Update
        var type = ExistsInDB.apu_exists ? 'PUT' : 'POST';
        var file = ExistsInDB.apu_exists ? 'u' : 'c';
        if (file == 'c'){
            ExistsInDB.apu_exists = true;
        }

        // Get JSON for APU and Task (for schedule)
        var JSONData = Totals.getAPUJson(); 
        var JSONData2 = Totals.getTaskJson(file); 
        // console.log(JSONData); return false;

        // Insert/Update into DB
        saveInDB('apu',type,file,JSONData);
        if (file == 'c'){
            saveInDB('tasks',type,file,JSONData2);
        }else if (file == 'u'){
            patchInDB('tasks',JSONData2);
        }
    }

});

/** 1. Add 'Supply' to APU */
$(document).off('click', "#add-supply-btn").on('click', "#add-supply-btn", function(event) {

    event.preventDefault();

    // Check if APU's been saved & empty fields
    if (!ExistsInDB.apu_exists){
        $.gritter.add({
            title: '¡APU no ha sido guardado!',
            text: 'En la sección anterior, debe llenar y guardar al menos la "Actividad".'
        });
    }
    else if (!$('input#supp_cant').val()){
        $.gritter.add({
            title: '¡Campos Vacios!',
            text: 'Debe llenar la Cantidad.'
        });
        $('input#supp_cant').css("border-color", "#a94442");

    }else{

        // Init required vars
        var form = $('form#add-supply');
        var supp_id = '';
        var supp_name= '';
        var supp_und= '';
        var supp_price = 0;
        var supp_costo_tot = 0;
        $('input#supp_cant').css("border-color", "");

        // Get Supply selected to get rest of its data
        getSingle('supplies',$('select#supp_id').val(),function(resp) {
            if (resp != 404){   
                $.map(resp, function (data, i) {
                    supp_id = data.id;
                    supp_name = data.name;
                    supp_und  = data.und;
                    supp_price = Number(data.precio);
                    supp_costo_tot = supp_price * Number($('input#supp_cant').val());
                });

                // Create JSON with Form
                var JSONData = serializeFormJSONAll(form);
                JSONData['proj_id'] = ACTIVE_PROJECT;
                JSONData['apu_id'] = $('input#id').val();
                JSONData['precio_productivo'] = formatNum(supp_price);
                JSONData['costo_total'] = formatNum(supp_costo_tot);
                JSONData = JSON.stringify(JSONData);

                // Insert in 'APU-Supplies' DB
                $.ajax({
                    url: API_URL + '/apu_supplies/c/',
                    type: "POST",
                    dataType: 'json',
                    data: JSONData
                }).done(function(data) {
                    // Fill 'Supply' Data Rows
                    var rows = '';
                    rows = rows +
                        '<tr>' +
                            '<td class="center">' + supp_id + '</td>' +
                            '<td class="center">' + supp_name + '</td>' +
                            '<td class="center">' + supp_und + '</td>' +
                            '<td class="center">' + $('input#supp_cant').val() + '</td>' +
                            '<td class="center">' + formatNum(supp_price) + '</td>' +
                            '<td class="center">' + formatNum(supp_costo_tot) + '</td>' +
                            '<td class="text-right">' +
                                '<div class="btn-group btn-group-xs ">' +
                                    // '<a id="'+supp_id+'&'+$('input#id').val()+'" href="" class="btn btn-inverse edit-apu"><i class="fa fa-pencil"></i></a>' +
                                    '<a id="'+supp_id+'" href="" class="btn btn-danger del-supply"><i class="fa fa-times"></i></a>' +
                                '</div>' +
                            '</td>' +
                        '</tr>';
                    $('#apu-supplies').append(rows);

                    // Update Total
                    Totals.supplies += Number(formatNum(supp_costo_tot));
                    $('tr.supply_total').remove();
                    var tot = 
                    '<tr class="supply_total">' +
                        '<td colspan="4"></td><th class="center">Total:</th><th class="center">' + formatNum(Totals.supplies) + '</th><td></td>' +
                    '</tr>';
                    $('#apu-supplies').append(tot);                    

                    // Clear 'Cant' input
                    $('input#supp_cant').val('');

                    // Calculate points 4,5,6 and 'Total Precio Unitario'
                    Totals.calcTotPrecioUnitario();

                }).fail(function (jqXHR) {
                    console.log('Failed, status code: ' + jqXHR.status);
                }); 

            }
        });
    }
});

/** 2. Add 'Worker' to APU */
$(document).off('click', "#add-worker-btn").on('click', "#add-worker-btn", function(event) {

    event.preventDefault();

    // Check if APU's been saved & empty fields
    if (!ExistsInDB.apu_exists){
        $.gritter.add({
            title: '¡APU no ha sido guardado!',
            text: 'En la sección "Datos Generales", debe llenar y guardar al menos la "Actividad".'
        });
    }
    else if (!$('input#cw_cant').val()){
        $.gritter.add({
            title: '¡Campos Vacios!',
            text: 'Debe llenar la Cantidad.'
        });
        $('input#cw_cant').css("border-color", "#a94442");

    }else{

        // Init required vars
        var form = $('form#add-worker');
        var cw_id = '';
        var cw_worker_id = '';
        var cw_price = 0;
        var cw_costo_tot = 0;
        $('input#cw_cant').css("border-color", "");

        // Get Worker selected to get rest of its data
        getSingle('cost_hrs_worker',$('select#cw_id').val(),function(resp) {
            if (resp != 404){   
                $.map(resp, function (data, i) {
                    cw_id = data.id;
                    cw_worker_id = data.worker_id;
                    cw_price = Number(data.bs_x_hr);
                    cw_costo_tot = cw_price * Number($('input#cw_cant').val());
                });

                // Create JSON with Form
                var JSONData = serializeFormJSONAll(form);
                JSONData['proj_id'] = ACTIVE_PROJECT;                
                JSONData['apu_id'] = $('input#id').val();
                JSONData['precio_productivo'] = formatNum(cw_price);
                JSONData['costo_total'] = formatNum(cw_costo_tot);
                JSONData = JSON.stringify(JSONData);

                // Insert in 'APU-CostWorker' DB
                $.ajax({
                    url: API_URL + '/apu_cost_worker/c/',
                    type: "POST",
                    dataType: 'json',
                    data: JSONData
                }).done(function(data) {
                    // Fill 'Worker' Data Rows
                    var rows = '';
                    rows = rows +
                        '<tr>' +
                            '<td class="center">' + cw_id + '</td>' +
                            '<td class="center cw_' + cw_worker_id + '"></td>' +
                            '<td class="center">Hrs</td>' +
                            '<td class="center">' + $('input#cw_cant').val() + '</td>' +
                            '<td class="center">' + formatNum(cw_price) + '</td>' +
                            '<td class="center">' + formatNum(cw_costo_tot) + '</td>' +
                            '<td class="text-right">' +
                                '<div class="btn-group btn-group-xs ">' +
                                    // '<a id="'+cw_id+'&'+$('input#id').val()+'" href="" class="btn btn-inverse edit-apu"><i class="fa fa-pencil"></i></a>' +
                                    '<a id="'+cw_id+'" href="" class="btn btn-danger del-cw"><i class="fa fa-times"></i></a>' +
                                '</div>' +
                            '</td>' +
                        '</tr>';
                    $('#apu-workers').append(rows);

                    // According to woker ID get the 'Name' and insert in table             
                    getSingle('workers',cw_worker_id,function(resp) {
                        if (resp != 404){   
                            $.map(resp, function (data, i) {
                                $('td.cw_' + cw_worker_id).html(data.name);
                            });
                        }
                    });

                    // Update Total
                    Totals.workers += Number(formatNum(cw_costo_tot));
                    var cargas_sociales = formatNum(Totals.workers * (Totals.beneficios_sociales / 100));
                    var iva_cargas_sociales = formatNum((Totals.workers + Number(cargas_sociales)) * (Totals.iva / 100));
                    Totals.total_workers = formatNum(Totals.workers + Number(cargas_sociales) + Number(iva_cargas_sociales));

                    $('tr.worker_total').remove();
                    var tot = 
                    '<tr class="worker_total">' +
                        '<td colspan="4"></td><th class="center">Sub-Total:</th><th class="center">' + formatNum(Totals.workers) + '</th><td></td>' +
                    '</tr>' +
                    '<tr class="worker_total">' +
                        '<td></td><td>Cargas Sociales de M.O.</td><td colspan="2"></td><td class="center">' + Totals.beneficios_sociales + ' %</td><td class="center">' + cargas_sociales + '</td><td></td>' +
                    '</tr>' +
                    '<tr class="worker_total">' +
                        '<td></td><td>IVA de Sub-Total M.O. + Cargas Sociales</td><td colspan="2"></td><td class="center">' + Totals.iva + ' %</td><td class="center">' + iva_cargas_sociales + '</td><td></td>' +
                    '</tr>' +
                    '<tr class="worker_total">' +
                        '<td colspan="4"></td><th class="center">Total:</th><th class="center">' + formatNum(Totals.total_workers) + '</th><td></td>' +
                    '</tr>';
                    $('#apu-workers').append(tot);                    

                    // Clear 'Cant' input
                    $('input#cw_cant').val('');

                    // Calculate points 4,5,6 and 'Total Precio Unitario'
                    Totals.calcTotPrecioUnitario();

                }).fail(function (jqXHR) {
                    console.log('Failed, status code: ' + jqXHR.status);
                }); 

            }
        });
    }
});

/** 3. Add 'Equipment' to APU */
$(document).off('click', "#add-equip-btn").on('click', "#add-equip-btn", function(event) {

    event.preventDefault();

    // Check if APU's been saved & empty fields
    if (!ExistsInDB.apu_exists){
        $.gritter.add({
            title: '¡APU no ha sido guardado!',
            text: 'En la sección "Datos Generales", debe llenar y guardar al menos la "Actividad".'
        });
    }
    else if (!$('input#equip_cant').val()){
        $.gritter.add({
            title: '¡Campos Vacios!',
            text: 'Debe llenar la Cantidad.'
        });
        $('input#equip_cant').css("border-color", "#a94442");

    }else{

        // Init required vars
        var form = $('form#add-equip');
        var equip_id = '';
        var equip_name= '';
        var equip_price = 0;
        var equip_costo_tot = 0;
        $('input#equip_cant').css("border-color", "");

        // Get 'Equipment' selected to get rest of its data
        getSingle('equipment',$('select#equip_id').val(),function(resp) {
            if (resp != 404){   
                $.map(resp, function (data, i) {
                    equip_id = data.id;
                    equip_name = data.name;
                    equip_price = Number(data.precio);
                    equip_costo_tot = equip_price * Number($('input#equip_cant').val());
                });

                // Create JSON with Form
                var JSONData = serializeFormJSONAll(form);
                JSONData['proj_id'] = ACTIVE_PROJECT;                
                JSONData['apu_id'] = $('input#id').val();
                JSONData['precio_productivo'] = formatNum(equip_price);
                JSONData['costo_total'] = formatNum(equip_costo_tot);
                JSONData = JSON.stringify(JSONData);

                // Insert in 'APU-Equipments' DB
                $.ajax({
                    url: API_URL + '/apu_equipments/c/',
                    type: "POST",
                    dataType: 'json',
                    data: JSONData
                }).done(function(data) {
                    // Fill 'Equipment' Data Rows
                    var rows = '';
                    rows = rows +
                        '<tr>' +
                            '<td class="center">' + equip_id + '</td>' +
                            '<td class="center">' + equip_name + '</td>' +
                            '<td class="center">' + $('input#equip_cant').val() + '</td>' +
                            '<td class="center">' + formatNum(equip_price) + '</td>' +
                            '<td class="center">' + formatNum(equip_costo_tot) + '</td>' +
                            '<td class="text-right">' +
                                '<div class="btn-group btn-group-xs ">' +
                                    // '<a id="'+equip_id+'&'+$('input#id').val()+'" href="" class="btn btn-inverse edit-apu"><i class="fa fa-pencil"></i></a>' +
                                    '<a id="'+equip_id+'" href="" class="btn btn-danger del-equip"><i class="fa fa-times"></i></a>' +
                                '</div>' +
                            '</td>' +
                        '</tr>';
                    $('#apu-equip').append(rows);

                    // Update Total
                    Totals.equipments += Number(formatNum(equip_costo_tot));
                    $('tr.equip_total').remove();
                    var tot = 
                    '<tr class="equip_total">' +
                        '<td colspan="3"></td><th class="center">Total:</th><th class="center">' + formatNum(Totals.equipments) + '</th><td></td>' +
                    '</tr>';
                    $('#apu-equip').append(tot);                    

                    // Clear 'Cant' input
                    $('input#equip_cant').val('');

                    // Calculate points 4,5,6 and 'Total Precio Unitario'
                    Totals.calcTotPrecioUnitario();

                }).fail(function (jqXHR) {
                    console.log('Failed, status code: ' + jqXHR.status);
                }); 

            }
        });
    }
});

/** Enabled / Disabled save button according to Agreement checked or not */
$(document).on('click', "#agree-apu", function(event) {

    var isChecked = $('#agree-apu').is(":checked");
    if (isChecked){
        $('#save-totals-apu').prop('disabled', false);
    }else{
        $('#save-totals-apu').prop('disabled', true);
    }
});

/** Save points from 1 to 6 and 'Total Precio Unitario' */
$(document).off('click', "#save-totals-apu").on('click', "#save-totals-apu", function(event) {

    event.preventDefault();

    if (!$('input#tot_precio_unitario').val()){
        $.gritter.add({
            title: '¡Campos Vacios!',
            text: 'El "Total Precio Unitario" debe tener algun valor.'
        });
    }else{
        // Get JSON for APU
        var JSONData = Totals.getAPUJson(); 

        // Insert into DB
        saveInDB('apu','PUT','u',JSONData);
    }
});

/** 1. Delete Supply */
$(document).off('click', ".del-supply").on('click', ".del-supply", function(event) {

    event.preventDefault();
    var supp_id = $(this).attr('id');
    var id = ACTIVE_PROJECT +'&'+ $('input#id').val() +'&'+ supp_id;
    var supp_costo_tot = '';

    // Get 'Costo Total' from DB for Calculations, before delete it
    getSingle('apu_supplies',id,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                supp_costo_tot = data.costo_total;
            });
        }
    });
    var conf = confirm('Esta seguro que desea eliminar el registro?');
    if(conf){
        // Delete from DB 
        $.ajax({
            url: API_URL + '/apu_supplies/d/' + id,
            type: "DELETE",
            dataType: 'json'
        }).done(function(data) {
            
            // Delete Row from DOM
            $('#'+supp_id).parent().parent().parent().remove();            
            
            // Update Total
            Totals.supplies -= Number(formatNum(supp_costo_tot));
            $('tr.supply_total').remove();
            var tot = 
            '<tr class="supply_total">' +
                '<td colspan="4"></td><th class="center">Total:</th><th class="center">' + formatNum(Totals.supplies) + '</th><td></td>' +
            '</tr>';
            $('#apu-supplies').append(tot);                    

            // Calculate points 4,5,6 and 'Total Precio Unitario'
            Totals.calcTotPrecioUnitario();

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });
    }
});

/** 2. Delete 'Worker' */
$(document).off('click', ".del-cw").on('click', ".del-cw", function(event) {

    event.preventDefault();
    var worker_id = $(this).attr('id');
    var id = ACTIVE_PROJECT +'&'+ $('input#id').val() +'&'+ worker_id;
    var cw_costo_tot = '';

    // Get 'Costo Total' from DB for Calculations, before delete it
    getSingle('apu_cost_worker',id,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                cw_costo_tot = data.costo_total;
            });
        }
    });
    var conf = confirm('Esta seguro que desea eliminar el registro?');
    if(conf){
        // Delete from DB 
        $.ajax({
            url: API_URL + '/apu_cost_worker/d/' + id,
            type: "DELETE",
            dataType: 'json'
        }).done(function(data) {
            
            // Delete Row from DOM
            $('#'+worker_id).parent().parent().parent().remove();            
            console.log(cw_costo_tot);
            // Update Total
            Totals.workers -= Number(formatNum(cw_costo_tot));
            var cargas_sociales = formatNum(Totals.workers * (Totals.beneficios_sociales / 100));
            var iva_cargas_sociales = formatNum((Totals.workers + Number(cargas_sociales)) * (Totals.iva / 100));
            Totals.total_workers = formatNum(Totals.workers + Number(cargas_sociales) + Number(iva_cargas_sociales));

            $('tr.worker_total').remove();
            var tot = 
            '<tr class="worker_total">' +
                '<td colspan="4"></td><th class="center">Sub-Total:</th><th class="center">' + formatNum(Totals.workers) + '</th><td></td>' +
            '</tr>' +
            '<tr class="worker_total">' +
                '<td></td><td>Cargas Sociales de M.O.</td><td colspan="2"></td><td class="center">' + Totals.beneficios_sociales + ' %</td><td class="center">' + cargas_sociales + '</td><td></td>' +
            '</tr>' +
            '<tr class="worker_total">' +
                '<td></td><td>IVA de Sub-Total M.O. + Cargas Sociales</td><td colspan="2"></td><td class="center">' + Totals.iva + ' %</td><td class="center">' + iva_cargas_sociales + '</td><td></td>' +
            '</tr>' +
            '<tr class="worker_total">' +
                '<td colspan="4"></td><th class="center">Total:</th><th class="center">' + formatNum(Totals.total_workers) + '</th><td></td>' +
            '</tr>';
            $('#apu-workers').append(tot);  

            // Calculate points 4,5,6 and 'Total Precio Unitario'
            Totals.calcTotPrecioUnitario();

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });
    }
});

/** 3. Delete Equipment */
$(document).off('click', ".del-equip").on('click', ".del-equip", function(event) {

    event.preventDefault();
    var equip_id = $(this).attr('id');
    var id = ACTIVE_PROJECT +'&'+ $('input#id').val() +'&'+ equip_id;
    var equip_costo_tot = '';

    // Get 'Costo Total' from DB for Calculations, before delete it
    getSingle('apu_equipments',id,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                equip_costo_tot = data.costo_total;
            });
        }
    });
    var conf = confirm('Esta seguro que desea eliminar el registro?');
    if(conf){
        // Delete from DB 
        $.ajax({
            url: API_URL + '/apu_equipments/d/' + id,
            type: "DELETE",
            dataType: 'json'
        }).done(function(data) {
            
            // Delete Row from DOM
            $('#'+equip_id).parent().parent().parent().remove();            
            console.log(equip_costo_tot);
            // Update Total
            Totals.equipments -= Number(formatNum(equip_costo_tot));
            $('tr.equip_total').remove();
            var tot = 
            '<tr class="equip_total">' +
                '<td colspan="3"></td><th class="center">Total:</th><th class="center">' + formatNum(Totals.equipments) + '</th><td></td>' +
            '</tr>';
            $('#apu-equip').append(tot);                    

            // Calculate points 4,5,6 and 'Total Precio Unitario'
            Totals.calcTotPrecioUnitario();

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });
    }
});

/** Create New Supplier (from popup) */
$(document).off('click', "#create-new-supp").on('click', "#create-new-supp", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('input#supp-new-name').val() || !$('input#supp-new-price').val()){
        $.gritter.add({
            title: '¡Campos Vacios!',
            text: 'Debe llenar el Nombre y Precio.'
        });
    }else{

        // Get form Json and Save in DB
        var form = $('form#supp-new-form');
        var JSONData = serializeFormJSONAll(form);
        JSONData = JSON.stringify(JSONData);

        $.ajax({
            url: API_URL + '/supplies/c/',
            type: 'POST',
            dataType: 'json',
            data: JSONData
        }).done(function(data) {
            
            // Get 'Supplies' from DB
            getAll('supplies', function(resp) {
                if (resp != 404){
                    var options = '<optgroup label="Materiales">';
                    $.map(resp, function (suppObj, i) {
                        $.each(suppObj, function (i, suppArr) {
                            options = options + 
                            '<option value="' + suppArr.id + '">' + suppArr.id + ' - ' + suppArr.name + ' - ' + suppArr.und + '</option>';   
                        });
                    });
                    options = options + '</optgroup>';
                    $('select.supplies').html(options);
                }
            });

            $.gritter.add({
                title: '¡Exito!',
                text: 'Datos Guardados Correctamente.'
            });

            // Clear Form
            form.find("input[type=text], textarea").val("");

        }).fail(function (jqXHR) {
            $.gritter.add({
                title: '¡Error!',
                text: 'Los datos no fueron correctamente guardados.'
            });
            console.log('Failed, status code: ' + jqXHR.status);
        });

    }

});