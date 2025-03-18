/**
 * Edit Project
 */

// Vars
let projCreatedBy = '';
let projDuration = 0;
let projExchange = 0;

// Once Page is Loaded
$(document).ready(function(){

    // Get Project ID
    var projId = localStorage.getItem('projectIdToEdit');

    // Get Project's Data from DB
    $.ajax({
        url: API_URL + '/projects/s/' + projId,
        type: "GET",
        dataType: 'json'
    }).done(function(data) { console.log(data);
        
        $.map(data, function (project, i) {

            $('input#edit-proj-id').val(project.id);
            $('input#edit-proj-name').val(project.name);
            $('input#edit-proj-location').val(project.location);
            $('input#edit-proj-elab_date').val(formatDate(project.elaboration_date));
            $('input#edit-proj-presup_date').val(formatDate(project.presup_date));
            $('input#edit-proj-end_date').val(formatDate(project.end_date));
            $('input#edit-proj-cal_days').val(project.calendar_days);
            $('select#edit-proj-currency').val(project.currency);
            $('input#edit-proj-contract').val(project.contract_num);
            projCreatedBy = project.created_by;
            projDuration = project.duration;
            projExchange = project.exchange_rate;
        });

    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
    });

});

// Edit Project btn
$(document).off('click', "#save-edited-proj").on('click', "#save-edited-proj", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('input#edit-proj-name').val()){
        $.gritter.add({
            title: 'Campos Vacios!',
            text: 'Debe llenar el "Nombre del Proyecto".'
        });
        $('input#edit-proj-name').css("border-color", "#a94442");

    }else{ 

        // Init required vars
        var projId = localStorage.getItem('projectIdToEdit');
        var createdBy = projCreatedBy;
        var form = $('#edit-proj-form');
        $('input#edit-proj-name').css("border-color", "");

        // Create JSON with Form
        var JSONData = serializeFormJSONAll(form);
        JSONData['id'] = projId;
        JSONData['created_by'] = createdBy;
        JSONData['duration'] = projDuration;
        JSONData['exchange_rate'] = projExchange;

        JSONData = JSON.stringify(JSONData);

        // Update 'Project' in DB
        $.ajax({
            url: API_URL + '/projects/u/',
            type: "POST",
            dataType: 'json',
            data: JSONData
        }).done(function(data) {

            $.gritter.add({
                title: '¡Exito!',
                text: 'El Proyecto fue modificado.'
            });
            // Back to list
            $('#content').load('list-projects');

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });

    }

});

// Cancel Project btn
$(document).off('click', "#cancel-edited-proj").on('click', "#cancel-edited-proj", function(event) {

    event.preventDefault();
    $('#content').load('list-projects');

});