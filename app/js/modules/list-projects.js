/**
 * List Projects
 */

// Once Page is Loaded
$(document).ready(function(){

    // Array of Projects assigned to User
    $.each(userProjArr, function (i, projId) {
    
        // Get Project's Data from DB
        $.ajax({
            url: API_URL + '/projects/s/' + projId,
            type: "GET",
            dataType: 'json'
        }).done(function(data) {
            
            // Fill Table Data Rows
            var rows = '';

            $.map(data, function (project, i) {

                rows = rows +
                '<tr>' +
                    '<td>' + project.id + '</td>' +
                    '<td>' + project.name + '</td>' +
                    '<td>' +
                        '<div class="progress progress-mini">' +
                            '<div class="progress-bar progress-bar-primary" style="width: 10%;">' +
                        '</div></div>' + 
                    '</td>' +
                    '<td class="center">' + formatDate(project.elaboration_date) + '</td>' +
                    '<td class="center">' + formatDate(project.presup_date) + '</td>' +
                    '<td class="center">' + formatDate(project.end_date) + '</td>' +
                    '<td class="center">' + project.calendar_days + '</td>' +
                    '<td class="center">' + project.currency + '</td>' +
                    '<td class="text-right">' +
                        '<div class="btn-group btn-group-xs ">' +
                            '<a id="'+project.id+'" href="" class="btn btn-inverse edit-project"><i class="fa fa-pencil"></i></a>' +
                        '</div>' +
                    '</td>' +
                '</tr>';

            });

            $('#project-rows').append(rows);

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });

    });
});

// Edit Project btn
$(document).on('click', ".edit-project", function(event) {

    event.preventDefault();

    // Get Project ID
    localStorage.setItem('projectIdToEdit', $(this).attr('id'));
    $('#content').load('edit-project');

});