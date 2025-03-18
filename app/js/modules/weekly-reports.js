/** 
 * Weekly Reports
 **/

var load = {
    weeklyRepDataTable: '',
    weekly_reports: function() {

        // Destroy Data Table if exists
        if (this.weeklyRepDataTable){ 
            this.weeklyRepDataTable.fnDestroy();
        }

        // Fill "Progress Form" Table 
        getAllByProj('weekly_report',function(resp) {
            if (resp != 404){

                // Fill Table Data Rows
                var rows = '';
                $.map(resp, function (Obj, i) {
                    $.each(Obj, function (i, Arr) {
                        rows = rows +
                            '<tr class="gradeC">' +
                                '<td class="">Planilla de Avance de Obra Nro. ' + Arr.id + '</td>' +
                                '<td class="center">' + formatDate(Arr.date_ini) + '</td>' +
                                '<td class="center">' + formatDate(Arr.date_end) + '</td>' +
                                '<td class="center"></td>' +
                                '<td class="text-right">' +
                                    '<div class="btn-group btn-group-xs ">' +
                                        '<a id="'+Arr.id+'" href="" class="btn btn-inverse edit-pro-form"><i class="fa fa-pencil"></i></a>' +
                                        '<a id="'+Arr.id+'" href="" class="btn btn-danger delete-pro-form"><i class="fa fa-times"></i></a>' +
                                    '</div>' +
                                '</td>' +
                            '</tr>';
                        
                    });
                });

                // Add data to DOM
                $('#progress-form-rows').html(rows);

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
                        load.weeklyRepDataTable = this;
                        fnInitCompleteCallback(this);
                    }
                });

            }
        });
    }
}

// Once Page is Loaded
$(document).ready(function(){

    $('#spinner').fadeIn('slow');

    // Get 'Weekly Reports' from DB according to Project
    // load.weekly_reports();

    $('#spinner').fadeOut('slow');

});

// Go to New 'Weekly Report' page
$(document).off('click', "#add-weekly-report").on('click', "#add-weekly-report", function(event) {
    
    event.preventDefault(); 
    $('#content').load('new-weekly-report');

});

// Go to Edit 'Weekly Report' page 
$(document).off('click', ".edit-weekly-report").on('click', ".edit-weekly-report", function(event) {

    event.preventDefault();

    // Get Weekly Report ID
    localStorage.setItem('weekly_report_id', $(this).attr('id'));
    $('#content').load('edit-weekly-report');
});


// GET values from DB by Adv. Form ID 
function getByAdvFormId(id,callback){
    $.ajax({
        url: API_URL + '/apu_advanced_form/by_id/' + id,
        type: "GET",
        dataType: 'json',
    }).done(function(data) {
        callback(data);

    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
        callback(jqXHR.status);
    });
}

// Patch - Percentage in 'Tasks' table in DB (so, it's shown in gantt)
function patchPercentage(json){
    $.ajax({
        url: API_URL + '/tasks/p/',
        type: 'PATCH',
        dataType: 'json',
        data: json
    }).done(function(data) {
        console.log('Modified % in "Tasks" table');
    }).fail(function (jqXHR) {
        $.gritter.add({
            title: '¡Error!',
            text: 'El % no fue correctamente guardado.'
        });
        console.log('Failed, status code: ' + jqXHR.status);
    });
}

// Delete "Advanced/Progress Form" & "APU Advanced/Progress Form" 
$(document).off('click', ".delete-pro-form").on('click', ".delete-pro-form", function(event) {

    event.preventDefault();

    // Get "Progress Form" ID
    var progress_form_id = $(this).attr('id');
    var id = ACTIVE_PROJECT +'&'+ progress_form_id;

    // Delete "Progress Form" from DB
    var conf = confirm('Esta seguro que desea eliminar la Planilla?');
    if(conf){
        $.ajax({
            url: API_URL + '/advanced_form/d/' + id,
            type: "DELETE",
            dataType: 'json'
        }).done(function(data) {

            // Delete "APU Progress Form" from DB
            $.ajax({
                url: API_URL + '/apu_advanced_form/d/' + id,
                type: 'DELETE',
                dataType: 'json',
                beforeSend: function() {
                    
                    // Get APU IDs and %s, Get total % of APUs, substract and patch in 'Tasks' table in DB  
                    getByAdvFormId(id,function(resp) {
                        if (resp != 404){
                            $.map(resp, function (Obj, i) {
                                $.each(Obj, function (i, Arr) {
                                    
                                    // Get Total % of APU
                                    var tot_perc_xapu = 0;
                                    getSingleSync('tasks',ACTIVE_PROJECT+'&'+Arr.apu_id,function(resp) {
                                        if (resp != 404){
                                            $.map(resp, function (Obj, i) {
                                                tot_perc_xapu = Obj.percentage;
                                            });
                                        }
                                    });
                                    
                                    // Patch in 'Tasks' table in DB
                                    var JSONTasks = {};
                                    JSONTasks['project_id'] = ACTIVE_PROJECT;
                                    JSONTasks['id'] = Arr.apu_id;
                                    JSONTasks['taskPercentageComp'] = Number(tot_perc_xapu) - Number(Arr.percent); 
                                    JSONTasks = JSON.stringify(JSONTasks);
                                    patchPercentage(JSONTasks);
                                });
                            });
                        }
                    });
                },
            }).done(function(data) {
                console.log('Correctly Deleted: '+id);

            }).fail(function (jqXHR) {
                $.gritter.add({
                    title: '¡Error!',
                    text: 'No se pudo eliminar el registro.'
                });
                console.log('Failed, status code: ' + jqXHR.status);
            });

            // Delete Row from DOM
            $('#'+progress_form_id).parent().parent().parent().remove();    
            
            $.gritter.add({
                title: '¡Exito!',
                text: 'Se eliminó correctamente la Planilla.'
            });
    
        }).fail(function (jqXHR) {
            $.gritter.add({
                title: '¡Error!',
                text: 'No se pudo eliminar la Planilla.'
            });
            console.log('Failed, status code: ' + jqXHR.status);
        });
    }
});