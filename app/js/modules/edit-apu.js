/**
 * Edit APU
 */

// Save totals to display
var Totals = {
    supplies: 0,
    workers: 0,
    current_total_workers: 0,
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
    costo_herram_mano_obra: 0,
    
    /** Calculate and fill points 4,5,6 and 'Total Precio Unitario' */
    calcTotPrecioUnitario: function() {
        var sum_1_3 = Number(this.supplies) + Number(this.total_workers) + Number(this.equipments); 

        // 4: (1+2+3) * % de Gastos Generales
        this.general_admin = sum_1_3 * (Number(this.gastos_generales) / 100);
        $('input#tot_gastos_gral_admin').val(formatNum(this.general_admin));
        
        // 5: (1+2+3+4) * % Utilidad sobre Costo Directo
        var sum_1_4 = sum_1_3 + this.general_admin;
        this.utility = sum_1_4 * (Number(this.utilidad_costo_directo) / 100);
        $('input#tot_utilidad').val(formatNum(this.utility));

        // 6: (1+2+3+4+5) * % I.T.
        var sum_1_5 = Number(sum_1_4) + Number(this.utility); 
        this.taxes = sum_1_5 * (Number(this.it) / 100);
        $('input#tot_impuestos').val(formatNum(this.taxes));
        
        // Total Precio Unitario (Sum 1-6)
        this.total_precio_unit = Number(sum_1_5) + Number(this.taxes); 
        $('input#tot_precio_unitario').val(formatNum(this.total_precio_unit));

    },
    prependFirstRowIn3Equipment: function() {
      // Add first row of % of "herrmanientas mano de obra"
      $('tr.first_row_equipment').remove();

      var first_row = 
        '<tr class="first_row_equipment">' +
            '<td class="center"></td>' +
            '<td class="center">Herramienta - ' + (Totals.costo_herram_mano_obra * 100) + '% de M.O.</td>' +
            '<td class="center">' + Totals.costo_herram_mano_obra + '</td>' +
            '<td class="center">' + formatNum(Totals.total_workers) + '</td>' +
            '<td class="center">' + formatNum(Totals.costo_herram_mano_obra * Totals.total_workers) + '</td>' +
        '</tr>';

      $('#apu-equip').prepend(first_row);

      // Update Total of 3. Equipment
      // Check if 2. "Total Mano de Obra" has changed, if so, substract previous value 
      if (Totals.total_workers != Totals.current_total_workers) {
        Totals.equipments -= Number(formatNum(Totals.costo_herram_mano_obra * Totals.current_total_workers));
      }
      
      // Add new value of 2. "Total Mano de Obra"  
      Totals.equipments += Number(formatNum(Totals.costo_herram_mano_obra * Totals.total_workers));
      
      $('tr.equip_total').remove();
      
      var tot = 
          '<tr class="equip_total">' +
              '<td colspan="3"></td><th class="center">Total:</th><th class="center">' + formatNum(Totals.equipments) + '</th><td></td>' +
          '</tr>';
      
      $('#apu-equip').append(tot);  

      // Save current value of 2. "Total Mano de Obra"  
      Totals.current_total_workers = Totals.total_workers;

    },
    getAPUJson: function() {
        // Get 2 Forms
        var form = $('form#gral-apu');
        var form2 = $('form#totals-apu');

        // Serialize 
        var JSONData = serializeFormJSONAll(form);
        var JSONData2 = serializeFormJSONAll(form2);

        // Add missing data & convert current
        JSONData['tot_materiales'] = this.supplies;
        JSONData['tot_mano_de_obra'] = this.total_workers;
        JSONData['tot_equipo'] = this.equipments;
        JSONData2['tot_gastos_gral_admin'] = formatInvNum(JSONData2['tot_gastos_gral_admin']);
        JSONData2['tot_utilidad'] = formatInvNum(JSONData2['tot_utilidad']);
        JSONData2['tot_impuestos'] = formatInvNum(JSONData2['tot_impuestos']);
        JSONData2['tot_precio_unitario'] = formatInvNum(JSONData2['tot_precio_unitario']);

        // Merge Forms
        JSONData = $.extend({}, JSONData, JSONData2);

        return JSON.stringify(JSONData); 
    },
    getTaskJson: function() {
        var JSONData = {};
        JSONData['id'] = $('#id').val();
        JSONData['project_id'] = $('#project_id').val();
        JSONData['taskName'] = $('#actividad').val(); // DB row name to patch
        return JSON.stringify(JSONData); 
    }
}

// Once Page is Loaded
$(document).ready(function(){

    // Set Project Name 
    $('input#project_id').val(ACTIVE_PROJECT);

    // Get APU Id
    var apu_id = localStorage.getItem('apu_id');
    $('input#id').val(apu_id);
    var id = ACTIVE_PROJECT + '&' + apu_id;
    
    // Get APU data and fill inputs 
    getSingle('apu',id,function(resp) {
        if (resp != 404){
            $.map(resp, function (obj, i) {
                $('input#actividad').val(obj.actividad);
                $('input#unidad').val(obj.unidad);
                $('input#cant').val(obj.cant);
                $('select#moneda').val(obj.moneda);
            });
        }
    });

    // Get 'Beneficios Sociales', 'IVA', etc.
    getSingle('projects_comp',ACTIVE_PROJECT,function(resp) {
        if (resp != 404){        
            $.map(resp, function (data, i) {
                Totals.beneficios_sociales = Number(data.beneficios_sociales);
                Totals.iva = Number(data.iva);
                Totals.gastos_generales = Number(data.gastos_generales);
                Totals.utilidad_costo_directo = Number(data.utilidad_costo_directo);
                Totals.it = Number(data.it);
                Totals.costo_herram_mano_obra = Number(data.costo_herramientas) / 100;
            });
        }
    });

    // 1. Get "Supplies" of APU 
    getAllByAPU('apu_supplies',id,function(resp) {
        if (resp != 404){        
            $.map(resp, function (obj, i) {
                $.each(obj, function (i, data) {
                    var supp_name= '';
                    var supp_und= '';
                    
                    // Get Supply  
                    getSingle('supplies',data.supp_id,function(resp) {
                        if (resp != 404){   
                            $.map(resp, function (data, i) {
                                supp_name = data.name;
                                supp_und  = data.und;
                            });

                            // Fill 'Supply' Data Rows
                            var rows = '';
                            rows = rows +
                                '<tr>' +
                                    '<td class="center">' + data.supp_id + '</td>' +
                                    '<td class="center">' + supp_name + '</td>' +
                                    '<td class="center">' + supp_und + '</td>' +
                                    '<td class="center">' + data.cant + '</td>' +
                                    '<td class="center">' + data.precio_productivo + '</td>' +
                                    '<td class="center">' + data.costo_total + '</td>' +
                                    '<td class="text-right">' +
                                        '<div class="btn-group btn-group-xs ">' +
                                            '<a id="'+data.supp_id+'" href="" class="btn btn-danger del-supply"><i class="fa fa-times"></i></a>' +
                                        '</div>' +
                                    '</td>' +
                                '</tr>';
                            $('#apu-supplies').append(rows);

                            // Update Total
                            Totals.supplies += Number(data.costo_total);
                            $('tr.supply_total').remove();
                            var tot = 
                            '<tr class="supply_total">' +
                                '<td colspan="4"></td><th class="center">Total:</th><th class="center">' + formatNum(Totals.supplies) + '</th><td></td>' +
                            '</tr>';
                            $('#apu-supplies').append(tot);                    

                            // Calculate points 4,5,6 and 'Total Precio Unitario'
                            Totals.calcTotPrecioUnitario();
                        }
                    });
                });
            });
        }
    });

    // 2. Get "Workers" of APU 
    getAllByAPU('apu_cost_worker',id,function(resp) {
        if (resp != 404){        
            $.map(resp, function (obj, i) {
                $.each(obj, function (i, data) {
                    var cw_worker_id = '';

                    // Get Worker 
                    getSingle('cost_hrs_worker',data.cw_id,function(resp) {
                        if (resp != 404){   
                            $.map(resp, function (data, i) {
                                cw_worker_id = data.worker_id;
                            });

                            // Fill 'Worker' Data Rows
                            var rows = '';
                            rows = rows +
                                '<tr>' +
                                    '<td class="center">' + data.cw_id + '</td>' +
                                    '<td class="center cw_' + cw_worker_id + '"></td>' +
                                    '<td class="center">Hrs</td>' +
                                    '<td class="center">' + data.cant + '</td>' +
                                    '<td class="center">' + data.precio_productivo + '</td>' +
                                    '<td class="center">' + data.costo_total + '</td>' +
                                    '<td class="text-right">' +
                                        '<div class="btn-group btn-group-xs ">' +
                                            '<a id="'+data.cw_id+'" href="" class="btn btn-danger del-cw"><i class="fa fa-times"></i></a>' +
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
                            Totals.workers += Number(data.costo_total);
                            var cargas_sociales = formatNum(Totals.workers * (Totals.beneficios_sociales / 100));
                            var iva_cargas_sociales = formatNum((Totals.workers + Number(cargas_sociales)) * (Totals.iva / 100));
                            Totals.total_workers = Totals.workers + Number(cargas_sociales) + Number(iva_cargas_sociales);

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

                            // Prepend First Row of 3. "Equipment" with % of "mano de obra"
                            Totals.prependFirstRowIn3Equipment();

                        }
                    });
                });
            });
        }
    });

    // 3. Get "Equipment" of APU 
    getAllByAPU('apu_equipments',id,function(resp) {
        if (resp != 404){        
            $.map(resp, function (obj, i) {
                $.each(obj, function (i, data) {
                    var equip_name = '';

                    // Get Equipment 
                    getSingle('equipment',data.equip_id,function(resp) {
                        if (resp != 404){   
                            $.map(resp, function (data, i) {
                                equip_name = data.name;
                            });

                            // Fill 'Equipment' Data Rows
                            var rows = '';
                            rows = rows +
                                '<tr>' +
                                    '<td class="center">' + data.equip_id + '</td>' +
                                    '<td class="center">' + equip_name + '</td>' +
                                    '<td class="center">' + data.cant + '</td>' +
                                    '<td class="center">' + data.precio_productivo + '</td>' +
                                    '<td class="center">' + data.costo_total + '</td>' +
                                    '<td class="text-right">' +
                                        '<div class="btn-group btn-group-xs ">' +
                                            '<a id="'+data.equip_id+'" href="" class="btn btn-danger del-equip"><i class="fa fa-times"></i></a>' +
                                        '</div>' +
                                    '</td>' +
                                '</tr>';
                            $('#apu-equip').append(rows);

                            // Update Total
                            Totals.equipments += Number(formatNum(data.costo_total));
                            $('tr.equip_total').remove();
                            var tot = 
                            '<tr class="equip_total">' +
                                '<td colspan="3"></td><th class="center">Total:</th><th class="center">' + formatNum(Totals.equipments) + '</th><td></td>' +
                            '</tr>';
                            $('#apu-equip').append(tot);                    

                            // Calculate points 4,5,6 and 'Total Precio Unitario'
                            Totals.calcTotPrecioUnitario();
                        }
                    });
                });
            });
        }
    });


    // Get 'Supplies' from DB
    getAll('supplies', function(resp) {
        if (resp != 404){
            var options = '<optgroup label="Materiales">';
            $.map(resp, function (suppObj, i) {
                $.each(suppObj, function (i, suppArr) { 
                    options = options + 
                    '<option value="' + suppArr.id + '">' + suppArr.id + ' - ' + suppArr.name + ' - ' + suppArr.und + ' - Bs. ' + formatNum(suppArr.precio) + '</option>';   
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
                    '<option value="' + Arr.id + '">' + Arr.id + ' - ' + Arr.name + ' - Bs. ' + formatNum(Arr.precio) + '</option>';   
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

        // Get JSON for APU
        var JSONData = Totals.getAPUJson(); 
        var JSONData2 = Totals.getTaskJson(); 

        // Insert into DB
        saveInDB('apu','PUT','u',JSONData);
        patchInDB('tasks',JSONData2);
    }

});

/** 1. Add 'Supply' to APU */
$(document).off('click', "#add-supply-btn").on('click', "#add-supply-btn", function(event) {

    event.preventDefault();

    // Check if APU's been saved & empty fields
    if (!$('input#actividad').val()){
        $.gritter.add({
            title: '¡Campo Vacio!',
            text: 'En la sección anterior, debe llenar la "Actividad".'
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
                    Totals.supplies += Number(supp_costo_tot);
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
    if (!$('input#actividad').val()){
        $.gritter.add({
            title: '¡Campo Vacio!',
            text: 'En la sección anterior, debe llenar la "Actividad".'
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
                    Totals.workers += Number(cw_costo_tot);
                    var cargas_sociales = formatNum(Totals.workers * (Totals.beneficios_sociales / 100));
                    var iva_cargas_sociales = formatNum((Totals.workers + Number(cargas_sociales)) * (Totals.iva / 100));
                    Totals.total_workers = Totals.workers + Number(cargas_sociales) + Number(iva_cargas_sociales);

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

                    // Prepend First Row of 3. "Equipment" with % of "mano de obra"
                    Totals.prependFirstRowIn3Equipment();

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
    if (!$('input#actividad').val()){
        $.gritter.add({
            title: '¡Campo Vacio!',
            text: 'En la sección anterior, debe llenar la "Actividad".'
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
        var equip_id = 0;
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
                JSONData['precio_productivo'] = equip_price;
                JSONData['costo_total'] = equip_costo_tot; 
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
                    Totals.equipments += Number(equip_costo_tot);
                    $('tr.equip_total').remove();
                    var tot = 
                    '<tr class="equip_total">' +
                        '<td colspan="3"></td><th class="center">Total:</th><th class="center">' + formatNum(Totals.equipments) + '</th><td></td>' +
                    '</tr>';
                    $('#apu-equip').append(tot);                    

                    // Clear 'Cant' input
                    $('input#equip_cant').val(0);

                    // Calculate points 4,5,6 and 'Total Precio Unitario'
                    Totals.calcTotPrecioUnitario();

                }).fail(function (jqXHR) {
                    console.log('Failed, status code: ' + jqXHR.status);
                }); 

            }
        });
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
            Totals.supplies -= Number(supp_costo_tot);
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
            Totals.workers -= Number(cw_costo_tot);
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
            Totals.equipments -= Number(equip_costo_tot);
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