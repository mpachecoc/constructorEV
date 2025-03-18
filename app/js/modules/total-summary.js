/**
 * Total-Summary
 */

// APU
var apu = {
    totals: new Array(),
    totalProject: 0,
}

// Once Page is Loaded
$(document).ready(function(){

    $('#spinner').fadeIn('slow');

    /** 
     *  Get "Total APUs" (Table 1)
     **/ 
    getAllByProj('apu',function(resp) {
        if (resp != 404){

            // Init 
            var total_proyecto = 0;
            var total_materiales = 0;
            var total_mano_obra = 0;
            var total_equipo = 0;
            var total_herramientas = 0;
            var total_gastos_admin = 0;
            var total_utilidad = 0;
            var total_factura = 0;

            $.map(resp, function (apuObj, i) {
                $.each(apuObj, function (i, apuArr) {
                    // Sum totals from all APUs
                    total_proyecto += Number(apuArr.tot_precio_unitario);
                    total_materiales += Number(apuArr.tot_materiales);
                    total_mano_obra += Number(apuArr.tot_mano_de_obra);
                    total_equipo += Number(apuArr.tot_equipo);
                    total_herramientas += Number(apuArr.tot_equipo);
                    total_gastos_admin += Number(apuArr.tot_gastos_gral_admin);
                    total_utilidad += Number(apuArr.tot_utilidad);
                    total_factura += Number(apuArr.tot_impuestos);
                });
            });
            
            // Display Project Total
            $('.total-proj').html('TOTAL PROYECTO Bs.- ' + total_proyecto.toFixed(2));
            apu.totalProject = total_proyecto.toFixed(2);

            // Save totals in array to display 
            apu.totals = [total_mano_obra.toFixed(2), 
                          total_materiales.toFixed(2), 
                          total_herramientas.toFixed(2), 
                          total_equipo.toFixed(2), 
                          total_gastos_admin.toFixed(2), 
                          total_factura.toFixed(2), 
                          total_utilidad.toFixed(2), 
                          ""];
            
            // Fill "tot-apus-table" (Table 1)
            var rows = '';
            var sections = ["Mano de Obra", 
                            "Materiales", 
                            "Herramientas", 
                            "Equipos", 
                            "Gastos Admin.", 
                            "Factura", 
                            "Utilidad", 
                            ""];

            $.each(sections, function (i, sectArr) {
                rows = rows + 
                    '<tr>' +
                        '<td>' + sectArr + '</td>' +
                        '<td class="text-right">0%</td>' +
                        '<td class="text-right">' + apu.totals[i] + '</td>' +
                    '</tr>';
            });

            // Add data to DOM
            $('#tot-apus-rows').html(rows);
        }
    });

    /** 
     *  Get "Información de Activos" (Table 5)  
    **/
    getAllByProj('groups',function(resp) {
        if (resp != 404){ 

            // Fill Table Data Rows
            var rows = '';
            var total_apu_project = 0;
            var total_exp_project = 0;
            $.map(resp, function (groupObj, i) {
                $.each(groupObj, function (i, groupArr) {

                    /** 
                     * 1 CALCULATE TOTAL APUs 
                     **/ 

                    // Init x Group
                    var total_apu_xg = 0;

                     // Get APUs by Group (As group ID is unique it's not necessary the project)
                     getByGroup('apu',groupArr.id,function(resp) {
                        if (resp != 404){        
                            $.map(resp, function (obj, i) {
                                $.each(obj, function (i, data) {
                                    // Sum totals from all APUs by Groups
                                    total_apu_xg += Number(data.tot_precio_unitario);
                                    total_apu_project += Number(data.tot_precio_unitario);
                                });
                            });
                        }
                    });

                    /** 
                     * 2 CALCULATE TOTAL EXPENSES 
                     **/

                    // Init Totals x Group
                    var total_expense_xg = 0;

                    // Get Expenses by Group (As group ID is unique it's not necessary the project)
                    getByGroup('expenses',groupArr.id,function(resp) {
                        if (resp != 404){        
                            $.map(resp, function (obj, i) {
                                $.each(obj, function (i, data) {
                                    total_expense_xg += Number(data.amount);
                                    total_exp_project += Number(data.amount);
                                });
                            });
                        }
                    });

                     // Fill "act-info-table" (table 5)
                     var diff = total_apu_xg - total_expense_xg;
                     rows = rows +
                        '<tr>' +
                            '<td>' + groupArr.name + '</td>' +
                            '<td class="text-right">' + total_apu_xg.toFixed(2) + '</td>' +
                            '<td class="text-right">' + total_expense_xg.toFixed(2) + '</td>' +
                            '<td class="strong text-right">' + diff.toFixed(2) + '</td>' +
                        '</tr>';

                });
            });

            // Fill Totals
            var total_diff = total_apu_project - total_exp_project;
            rows = rows +
                '<tr>' +
                    '<td class="strong text-right">TOTALES: </td>' +
                    '<td class="strong text-right">' + total_apu_project.toFixed(2) + '</td>' +
                    '<td class="strong text-right">' + total_exp_project.toFixed(2) + '</td>' +
                    '<td class="strong text-right">' + total_diff.toFixed(2) + '</td>' +
                '</tr>';

            // Add data to DOM
            $('#act-info-rows').append(rows);

            // Set Progress Bar 
            var percent = ((total_exp_project * 100) / total_apu_project).toFixed(0);
            var html_percent = ((total_exp_project * 100) / total_apu_project).toFixed(0);
            if(percent > 100){ percent = 100; }
            $('#proj-progress-bar .progress-bar').width(percent + "%");
            $('#proj-progress-bar .percent').html(total_exp_project.toFixed(2) + " (" + html_percent + "%)");    
           
        }
    });

    /** 
     *  Get "Total APUs x Groups" (Table 2)
     **/ 
    getAllByProj('groups',function(resp) {
        if (resp != 404){ 

            // Fill Table Data Rows
            var rows = '';
            $.map(resp, function (groupObj, i) {
                $.each(groupObj, function (i, groupArr) {

                    // Init x Group
                    var total_xg_materiales = 0;
                    var total_xg_mano_obra = 0;
                    var total_xg_equipo = 0;
                    var total_xg_herramientas = 0;
                    var total_xg_gastos_admin = 0;
                    var total_xg_utilidad = 0;
                    var total_xg_factura = 0;

                     // Get APUs by Group (As group ID is unique it's not necessary the project)
                     getByGroup('apu',groupArr.id,function(resp) {
                        if (resp != 404){        
                            $.map(resp, function (obj, i) {
                                $.each(obj, function (i, data) {
                                    // Sum totals from all APUs by Groups
                                    total_xg_materiales += Number(data.tot_materiales);
                                    total_xg_mano_obra += Number(data.tot_mano_de_obra);
                                    total_xg_equipo += Number(data.tot_equipo);
                                    total_xg_herramientas += Number(data.tot_equipo);
                                    total_xg_gastos_admin += Number(data.tot_gastos_gral_admin);
                                    total_xg_utilidad += Number(data.tot_utilidad);
                                    total_xg_factura += Number(data.tot_impuestos);
                                });
                            });
                        }
                    });

                    // Fill "tot-apus-xgroup-table" (Table 2)
                    var total_xg = total_xg_mano_obra + total_xg_materiales + total_xg_herramientas + total_xg_equipo + total_xg_gastos_admin + total_xg_factura + total_xg_utilidad;
                    rows = rows +
                        '<tr>' +
                            '<td>' + groupArr.name + '</td>' +
                            '<td>' + total_xg_mano_obra.toFixed(2) + '</td>' +
                            '<td>' + total_xg_materiales.toFixed(2) + '</td>' +
                            '<td>' + total_xg_herramientas.toFixed(2) + '</td>' +
                            '<td>' + total_xg_equipo.toFixed(2) + '</td>' +
                            '<td>' + total_xg_gastos_admin.toFixed(2) + '</td>' +
                            '<td>' + total_xg_factura.toFixed(2) + '</td>' +
                            '<td>' + total_xg_utilidad.toFixed(2) + '</td>' +
                            '<td class="strong">' + total_xg.toFixed(2) + '</td>' +
                        '</tr>';
                    
                });
            });

            // Create last row with Totals
            rows = rows +
                '<tr>' +
                    '<td class="text-right strong">TOTALES: </td>' +
                    '<td class="strong">' + apu.totals[0] + '</td>' +
                    '<td class="strong">' + apu.totals[1] + '</td>' +
                    '<td class="strong">' + apu.totals[2] + '</td>' +
                    '<td class="strong">' + apu.totals[3] + '</td>' +
                    '<td class="strong">' + apu.totals[4] + '</td>' +
                    '<td class="strong">' + apu.totals[5] + '</td>' +
                    '<td class="strong">' + apu.totals[6] + '</td>' +
                    '<td class="strong">' + apu.totalProject + '</td>' +
                '</tr>';

            // Add data to DOM
            $('#tot-apus-xgroup-rows').append(rows);
        }
    });
    
    /** 
     *  Get "Gastos Directos" (Table 3) 
     *      "Gastos Indirectos" (Table 4) 
     **/
    getAllByProj('groups',function(resp) {
        if (resp != 404){ 

            // Fill Table Data Rows
            var rows_dir = '';
            var rows_ind = '';

            // Init Totals x Group
            var total_xg_materiales = 0;
            var total_xg_mano_obra = 0;
            var total_xg_equipo = 0;
            var total_xg_herramientas = 0;
            var total_xg_gastos_admin = 0;
            var total_xg_utilidad = 0;
            var total_xg_factura = 0;

            $.map(resp, function (groupObj, i) {
                $.each(groupObj, function (i, groupArr) {

                    // Init Totals - Direct
                    var total_dir_materiales = 0;
                    var total_dir_mano_obra = 0;
                    var total_dir_equipo = 0;
                    var total_dir_herramientas = 0;
                    var total_dir_gastos_admin = 0;
                    var total_dir_utilidad = 0;
                    var total_dir_factura = 0;
                    // Init Totals - Indirect
                    var total_ind_materiales = 0;
                    var total_ind_mano_obra = 0;
                    var total_ind_equipo = 0;
                    var total_ind_herramientas = 0;
                    var total_ind_gastos_admin = 0;
                    var total_ind_utilidad = 0;
                    var total_ind_factura = 0;


                    // Get Expenses by Group (As group ID is unique it's not necessary the project)
                    getByGroup('expenses',groupArr.id,function(resp) {
                        if (resp != 404){        
                            $.map(resp, function (obj, i) {
                                $.each(obj, function (i, data) {

                                    // Check if Expenses Object & Type ("Directo" or "Indirecto") 
                                        switch (data.object) { 
                                            case 'Mano de Obra':
                                                data.type == 'directo' ? total_dir_mano_obra += Number(data.amount) : total_ind_mano_obra += Number(data.amount);
                                                total_xg_mano_obra += Number(data.amount);
                                                break;
                                            case 'Materiales': 
                                                data.type == 'directo' ? total_dir_materiales += Number(data.amount) : total_ind_materiales += Number(data.amount);
                                                total_xg_materiales += Number(data.amount);                                                
                                                break;
                                            case 'Herramientas': 
                                                data.type == 'directo' ? total_dir_herramientas += Number(data.amount) : total_ind_herramientas += Number(data.amount);
                                                total_xg_herramientas += Number(data.amount); 
                                                break;		
                                            case 'Equipos': 
                                                data.type == 'directo' ? total_dir_equipo += Number(data.amount) : total_ind_equipo += Number(data.amount);
                                                total_xg_equipo += Number(data.amount); 
                                                break;
                                            case 'Gastos Admin.': 
                                                data.type == 'directo' ? total_dir_gastos_admin += Number(data.amount) : total_ind_gastos_admin += Number(data.amount);
                                                total_xg_gastos_admin += Number(data.amount);
                                                break;
                                            case 'Factura': 
                                                data.type == 'directo' ? total_dir_factura += Number(data.amount) : total_ind_factura += Number(data.amount);
                                                total_xg_factura += Number(data.amount);
                                                break;
                                            case 'Utilidad': 
                                                data.type == 'directo' ? total_dir_utilidad += Number(data.amount) : total_ind_utilidad += Number(data.amount);
                                                total_xg_utilidad += Number(data.amount);
                                                break;
                                        }

                                });
                            });
                        }
                    });

                    // Fill "direct-exp-table" (table 3)
                    var total_dir = total_dir_mano_obra + total_dir_materiales + total_dir_herramientas + total_dir_equipo + total_dir_gastos_admin + total_dir_factura + total_dir_utilidad;
                    rows_dir = rows_dir +
                        '<tr>' +
                            '<td>' + groupArr.name + '</td>' +
                            '<td>' + total_dir_mano_obra.toFixed(2) + '</td>' +
                            '<td>' + total_dir_materiales.toFixed(2) + '</td>' +
                            '<td>' + total_dir_herramientas.toFixed(2) + '</td>' +
                            '<td>' + total_dir_equipo.toFixed(2) + '</td>' +
                            '<td>' + total_dir_gastos_admin.toFixed(2) + '</td>' +
                            '<td>' + total_dir_factura.toFixed(2) + '</td>' +
                            '<td>' + total_dir_utilidad.toFixed(2) + '</td>' +
                            '<td class="strong">' + total_dir.toFixed(2) + '</td>' +
                        '</tr>';

                    // Fill "indirect-exp-table" (table 4)
                    var total_ind = total_ind_mano_obra + total_ind_materiales + total_ind_herramientas + total_ind_equipo + total_ind_gastos_admin + total_ind_factura + total_ind_utilidad;
                    rows_ind = rows_ind +
                        '<tr>' +
                            '<td>' + groupArr.name + '</td>' +
                            '<td>' + total_ind_mano_obra.toFixed(2) + '</td>' +
                            '<td>' + total_ind_materiales.toFixed(2) + '</td>' +
                            '<td>' + total_ind_herramientas.toFixed(2) + '</td>' +
                            '<td>' + total_ind_equipo.toFixed(2) + '</td>' +
                            '<td>' + total_ind_gastos_admin.toFixed(2) + '</td>' +
                            '<td>' + total_ind_factura.toFixed(2) + '</td>' +
                            '<td>' + total_ind_utilidad.toFixed(2) + '</td>' +
                            '<td class="strong">' + total_ind.toFixed(2) + '</td>' +
                        '</tr>';

                });
            });

            var tot_exp_xg = total_xg_mano_obra + total_xg_materiales + total_xg_herramientas + total_xg_equipo + total_xg_gastos_admin + total_xg_factura + total_xg_utilidad;
            rows_ind = rows_ind +
                '<tr>' +
                    '<td class="text-right strong">TOTALES: </td>' +
                    '<td class="strong">' + total_xg_mano_obra.toFixed(2) + '</td>' +
                    '<td class="strong">' + total_xg_materiales.toFixed(2) + '</td>' +
                    '<td class="strong">' + total_xg_herramientas.toFixed(2) + '</td>' +
                    '<td class="strong">' + total_xg_equipo.toFixed(2) + '</td>' +
                    '<td class="strong">' + total_xg_gastos_admin.toFixed(2) + '</td>' +
                    '<td class="strong">' + total_xg_factura.toFixed(2) + '</td>' +
                    '<td class="strong">' + total_xg_utilidad.toFixed(2) + '</td>' +
                    '<td class="strong">' + tot_exp_xg.toFixed(2) + '</td>' +
                '</tr>';

            // Add data to DOM
            $('#direct-exp-rows').append(rows_dir);
            $('#indirect-exp-rows').append(rows_ind);

        }
    });

    $('#spinner').fadeOut('slow');
    
});

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