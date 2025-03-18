/** 
 * APUs 
 **/

// Group vars
var group = {
    id_selected: '',
    name: ''
}

var load = {
    apuDataTable: '',
    apus: function() {

        // Destroy Data Table if exists
        if (this.apuDataTable){ 
            this.apuDataTable.fnDestroy();
        }

        // Fill APU Table and Select List
        getAllByProj('apu',function(resp) {
            if (resp != 404){

                // Fill Table Data Rows
                var rows = '';
                var options = '';
                $.map(resp, function (apuObj, i) {
                    $.each(apuObj, function (i, apuArr) {
                        var group = apuArr.group_id == null ? '' : apuArr.group_id;
                        rows = rows +
                            '<tr class="gradeC">' +
                                '<td class="center">' + group + '</td>' +
                                '<td>' + apuArr.id + '</td>' +
                                '<td>' + apuArr.actividad + '</td>' +
                                '<td class="center">' + apuArr.unidad + '</td>' +
                                '<td class="center">' + apuArr.cant + '</td>' +
                                '<td class="center">' + apuArr.tot_precio_unitario + '</td>' +
                                '<td class="center">' + apuArr.tot_precio_unitario + '</td>' +
                                '<td class="text-right">' +
                                    '<div class="btn-group btn-group-xs ">' +
                                        '<a id="'+apuArr.id+'" href="" class="btn btn-inverse edit-apu"><i class="fa fa-pencil"></i></a>' +
                                        '<a id="'+apuArr.id+'" href="" class="btn btn-danger delete-apu"><i class="fa fa-times"></i></a>' +
                                    '</div>' +
                                '</td>' +
                            '</tr>';
                        
                        var wg = apuArr.group_id ? '&check;' : '';
                        options = options + 
                            '<option value="'+apuArr.id+'">' + apuArr.actividad + ' ' + wg + '</option>';
                    });
                });

                // Add data to DOM
                $('#apu-rows').html(rows);
                $('#group-selection').html(options); 

                // Init 'DataTables' js 
                $('table.colVis').dataTable({
                    "sPaginationType": "bootstrap",
                    "sDom": "<'row separator bottom'<'col-md-3'f><'col-md-3'l><'col-md-6'C>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                    "oLanguage": {
                        "sLengthMenu": "_MENU_ por página"
                    },
                    "oColVis": {
                        "buttonText": "Mostrar / Ocultar Cols.",
                        "sAlign": "right"
                    },
                    "sScrollX": "100%",
                    "sScrollXInner": "100%",
                    "bScrollCollapse": true,
                    "fnInitComplete": function () {
                        load.apuDataTable = this;
                        fnInitCompleteCallback(this);
                    }
                });

                // Multiselect for Group Selection
                $('#group-selection').multiSelect({
                    selectableHeader: "<div class='custom-header'>APUs</div>",
                    selectionHeader: "<div class='custom-header group-text'>Grupo Seleccionado...</div>",
                    selectableFooter: "<div class='custom-header custom-footer'>APUs</div>",
                    selectionFooter: "<div class='custom-header custom-footer group-text'>Grupo Seleccionado...</div>"
                });
                $('#group-selection').multiSelect('refresh');
                
            }
        });
    }
}

// Once Page is Loaded
$(document).ready(function(){

    $('#spinner').fadeIn('slow');

    // Get 'APUs' from DB according to Project
    load.apus();

    // Get 'Groups' from DB according to Project
     getAllByProj('groups',function(resp) {
        if (resp != 404){ 

            // Fill Table Data Rows
            var rows = '';
            $.map(resp, function (groupObj, i) {
                $.each(groupObj, function (i, agroupArr) {
                    var n = i += 1;
                    rows = rows +
                        '<tr>' +
                            '<td>' + n + '</td>' +
                            '<td>' + agroupArr.name + '</td>' +
                            '<td class="text-right">' +
                                '<div class="btn-group btn-group-xs ">' +
                                    '<a id="'+agroupArr.id+'" class="btn btn-inverse select-group"><i class="fa fa-check"></i></a>' +
                                    '<a id="'+agroupArr.id+'" class="btn btn-danger delete-group"><i class="fa fa-times"></i></a>' +
                                '</div>' +
                            '</td>' +
                        '</tr>';
                });
            });

            // Add data to DOM
            $('#group-rows').append(rows);
        }
    });

    $('#spinner').fadeOut('slow');

});

// Go to New APU page
$(document).off('click', "#add-new-apu").on('click', "#add-new-apu", function(event) {
    
    event.preventDefault(); 
    $('#content').load('new-apu');

});

// Go to Edit APU page 
$(document).off('click', ".edit-apu").on('click', ".edit-apu", function(event) {

    event.preventDefault();

    // Get APU ID
    localStorage.setItem('apu_id', $(this).attr('id'));
    $('#content').load('edit-apu');
});

// GET by APU 
function getAllByAPU(dBtable,id,callback){
    $.ajax({
        url: API_URL + '/' + dBtable + '/by_apu/' + id,
        type: "GET",
        dataType: 'json'
    }).done(function(data) {
        callback(data);

    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
        callback(jqXHR.status);
    });
}

// Delete by APU
function deleteByAPU(dBtable,id) {
    $.ajax({
        url: API_URL + '/' + dBtable + '/d/' + id,
        type: 'DELETE',
        dataType: 'json',
    }).done(function(data) {
        console.log('Correctly Deleted: '+id);

    }).fail(function (jqXHR) {
        $.gritter.add({
            title: '¡Error!',
            text: 'No se pudo eliminar el registro.'
        });
        console.log('Failed, status code: ' + jqXHR.status);
    });
}

// Delete APU and all relationships (supplies, workers & equipments)
$(document).off('click', ".delete-apu").on('click', ".delete-apu", function(event) {

    event.preventDefault();

    // Get APU ID
    var apuId = $(this).attr('id');
    var id = ACTIVE_PROJECT +'&'+ apuId;    

    // Delete APU from DB
    var conf = confirm('Esta seguro que desea eliminar el APU?');
    if(conf){
        $.ajax({
            url: API_URL + '/apu/d/' + id,
            type: "DELETE",
            dataType: 'json'
        }).done(function(data) {
            
            // Delete Tasks (For Schedule)
            deleteByAPU('tasks',id);

            // 1. Get "Supplies" of APU and Delete them
            getAllByAPU('apu_supplies',id,function(resp) {
                if (resp != 404){        
                    $.map(resp, function (obj, i) {
                        $.each(obj, function (i, data) {
                            var supp_id = id + '&' + data.supp_id;
                            deleteByAPU('apu_supplies',supp_id);
                        });
                    });
                }
            });

            // 2. Get "Workers" of APU and Delete them
            getAllByAPU('apu_cost_worker',id,function(resp) {
                if (resp != 404){        
                    $.map(resp, function (obj, i) {
                        $.each(obj, function (i, data) {
                            var cw_id = id + '&' + data.cw_id;
                            deleteByAPU('apu_cost_worker',cw_id);
                        });
                    });
                }
            });

            // 3. Get "Equipment" of APU and Delete them
            getAllByAPU('apu_equipments',id,function(resp) {
                if (resp != 404){        
                    $.map(resp, function (obj, i) {
                        $.each(obj, function (i, data) {
                            var equip_id = id + '&' + data.equip_id;
                            deleteByAPU('apu_equipments',equip_id);
                        });
                    });
                }
            });

            // Delete Row from DOM
            $('#'+apuId).parent().parent().parent().remove();    
            
            $.gritter.add({
                title: '¡Exito!',
                text: 'Se eliminó correctamente el APU.'
            });
    
        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });
    }
});

// Create New Group
$(document).off('click', "#add-group").on('click', "#add-group", function(event) {
    
    event.preventDefault(); 

    // Check Empty fields
    if (!$('input#group-name').val()){
        $.gritter.add({
            title: 'Campos Vacios!',
            text: 'Debe llenar el nombre del Grupo.'
        });
    }else{

        var form = $('#group-form');
        var JSONData = serializeFormJSONAll(form);
        JSONData['project_id'] = ACTIVE_PROJECT;
        JSONData = JSON.stringify(JSONData); 

        // Insert in 'Project' DB
        $.ajax({
            url: API_URL + '/groups/c/',
            type: "POST",
            dataType: 'json',
            data: JSONData
        }).done(function(data) { 
            var rows = '';
            $.map(data, function (groupObj, i) { 
                rows = '<tr>' +
                        '<td>' + groupObj.id + '</td>' +
                        '<td>' + groupObj.name + '</td>' +
                        '<td class="text-right">' +
                            '<div class="btn-group btn-group-xs ">' +
                                '<a id="'+groupObj.id+'" class="btn btn-inverse select-group"><i class="fa fa-check"></i></a>' +
                                '<a id="'+groupObj.id+'" class="btn btn-danger delete-group"><i class="fa fa-times"></i></a>' +
                            '</div>' +
                        '</td>' +
                    '</tr>';
            });

            // Add data to DOM
            $('#group-rows').append(rows);

            $.gritter.add({
                title: '¡Exito!',
                text: 'El Grupo fue creado correntamente.'
            });
            // Clear fields
            form.find("input[type=text], textarea").val("");

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });
    }

});

// Select Group and corresponding Options
$(document).off('click', ".select-group").on('click', ".select-group", function(event) {
    
    event.preventDefault(); 

    group.id_selected = $(this).attr('id');

    // Change the value and get the name of the "Group" selected
    getSingle('groups',group.id_selected,function(resp) {
        if (resp != 404){ 
            $.map(resp, function (groupObj, i) {
                $('div.group-text').text(groupObj.name);
            });
        }
    });

    // Select the options according to the "Group" selected
    getAllByProj('apu',function(resp) {
        if (resp != 404){
            // Empty selected elements
            $('#group-selection').multiSelect('deselect_all');

            // Get all APUs and check which ones belong to the selected Group (select them)
            $.map(resp, function (apuObj, i) { 
                $.each(apuObj, function (i, apuArr) { 
                    if (apuArr.group_id == group.id_selected){
                        $('#group-selection').multiSelect('select', apuArr.id);
                    }
                });
            });
            
        }
    });

});

// Save Group Assignation (APUs in Group)
$(document).off('click', "#save-group-assign").on('click', "#save-group-assign", function(event) {
    
    event.preventDefault(); 

    if(!group.id_selected) {
        $.gritter.add({
            title: '¡Alerta!',
            text: 'Debe seleccionar un Grupo.'
        });
    }else if(!$('#group-selection').val()) {
        $.gritter.add({
            title: '¡Alerta!',
            text: 'No existen ningun APU seleccionado.'
        });
    }else {
        var apus = $('#group-selection').val();
        
        // Go into every APU and save in "APU" & "Tasks" DB the corresponding Group
        $.each(apus, function (i, data) {
            var JSONData = {};
            JSONData['project_id'] = ACTIVE_PROJECT;
            JSONData['groupID'] = group.id_selected;
            JSONData['id'] = data;  // APU Id
            JSONData = JSON.stringify(JSONData); 

            patchInDB('apu',JSONData);
            patchInDB('tasks',JSONData);
        });

        // Load APUs again
        load.apus();
    }

});