/**
 * New Project
 */

// Once Page is Loaded
$(document).ready(function(){
    
});

// Create New Project
$(document).off('click', "#save-new-proj").on('click', "#save-new-proj", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('input#proj-name').val()){
        $.gritter.add({
            title: 'Campos Vacios!',
            text: 'Debe llenar al menos el "Nombre del Proyecto".'
        });
        $('input#proj-name').css("border-color", "#a94442");

    }else{

        // Init required vars
        var id = 'Pro-' + getFirstLetters($('input#proj-name').val());
        var createdBy = userName;
        var form = $('#create-proj-form');
        $('input#proj-name').css("border-color", "");

        // Create JSON with Form
        var JSONData = serializeFormJSONAll(form);
        JSONData['id'] = id;
        JSONData['created_by'] = createdBy;
        JSONData['duration'] = 0;
        JSONData['exchange_rate'] = 0;

        JSONData = JSON.stringify(JSONData); 
        // console.log(JSONData, userID); return false; 

        // Insert in 'Project' DB
        $.ajax({
            url: API_URL + '/projects/c/',
            type: "POST",
            dataType: 'json',
            data: JSONData
        }).done(function(data) {

            $.gritter.add({
                title: '¡Exito!',
                text: 'El Proyecto fue creado y será listado en sus proyectos.'
            });
            // Clear fields
            form.find("input[type=text], textarea").val("");

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });

    }

});

// Cancel New Project
$(document).off('click', "#cancel-new-proj").on('click', "#cancel-new-proj", function(event) {

    event.preventDefault();
    $('#create-proj-form').find("input[type=text], textarea").val("");

});