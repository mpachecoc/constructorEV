/**
 * List Projects
 */

// Vars
let project_selected = '';
let assigned_currently_users = [];

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
                          '<a id="'+project.id+'" href="" class="btn btn-inverse edit-project" style="margin-right:5px;"><i class="fa fa-pencil"></i></a> ' +
                          '<a id="'+project.id+','+project.name+'" href="#group-users-modal" data-toggle="modal" class="btn btn-inverse proj-user-modal"><i class="fa fa-fw fa-group"></i></a>' +
                        '</div>' +
                    '</td>' +
                '</tr>';

            });

            $('#project-rows').append(rows);

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });

    });

    // Get users for multiSelect
    getAll('users',function(resp) {
      if (resp != 404){

        let users = '';

        $.map(resp, function (obj, i) { 
          $.each(obj, function (i, arr) { 
            users += '<option value="'+arr.id+'">' + arr.username + '</option>';
          });
        });
        
        $('#proj-user-selection').html(users); 

        // Multiselect for Project/User Selection
        $('#proj-user-selection').multiSelect({
          selectableHeader: "<div class='custom-header'>Usuarios:</div>",
          selectionHeader: "<div class='custom-header project-title'>Proyecto...</div>",
          selectableFooter: "<div class='custom-header custom-footer'>Usuarios</div>",
          selectionFooter: "<div class='custom-header custom-footer project-title'>Proyecto...</div>"
        });

        $('#proj-user-selection').multiSelect('refresh');
      }
    });

});

// Edit Project btn
$(document).on('click', ".edit-project", function(event) {

    event.preventDefault();

    // Get Project ID
    localStorage.setItem('projectIdToEdit', $(this).attr('id'));
    $('#content').load('edit-project');

});


// MultiSelect => Open modal and according to project selected show users assgined
$(document).on('click', ".proj-user-modal", async function(event) {

  event.preventDefault(); 

  // Get Project ID and name
  const project = $(this).attr('id'); 

  const project_id = project.split(",")[0];
  project_selected = project_id; // Save global
  
  const project_name = project.split(",")[1];
  
  let users_assigned_arr = [];


  // Load project's name in multiSelect
  $('div.project-title').text(project_name);


  // Get Users assigned to Project
  await getAllBySelectedProj('project_user',project_id,function(resp) {
    if (resp != 404){
      
      $.map(resp, function (obj, i) { 
          $.each(obj, function (i, arr) { 
              users_assigned_arr.push(arr.user_id);
          });
      });

      // Save global
      assigned_currently_users = users_assigned_arr;

    }
  });
  
  // Get Users 
  getAll('users',function(resp) {
    if (resp != 404){
      // Empty selected elements
      $('#proj-user-selection').multiSelect('deselect_all');

      // Get all Users and check which ones are assgined to the selected Project
      $.map(resp, function (obj, i) { 
          $.each(obj, function (i, arr) { 
            if (users_assigned_arr.includes(arr.id)){
                $('#proj-user-selection').multiSelect('select', arr.id);
              }
          });
      });
    }
  });

});

// Save Users Assigned to the corresponding project
$(document).off('click', "#save-proj-user-assign").on('click', "#save-proj-user-assign", function(event) {
    
  event.preventDefault();

  // Get current Date
  const now = new Date();
  const mysqlDate = now.toISOString().slice(0, 10);

  const assigned_new_users = $('#proj-user-selection').val();

  // Convert to Set to compare
  const actualSet = new Set(assigned_currently_users);
  const newSet = new Set(assigned_new_users);

  // Filter 3 groups (already assigned, added, removed)
  const already_assigned = assigned_new_users.filter(user => actualSet.has(user));
  const added = assigned_new_users.filter(user => !actualSet.has(user));
  const removed = assigned_currently_users.filter(user => !newSet.has(user));


  // Save the Users Added
  $.each(added, function (i, userId) {
    var JSONData = {};
    JSONData['project_id'] = project_selected;
    JSONData['user_id'] = userId;
    JSONData['assigned_date'] = mysqlDate;
    JSONData = JSON.stringify(JSONData); 

    saveInDB('project_user','POST','c',JSONData);
  });

  // Delete the Users Removed
  $.each(removed, function (i, userId) {
      
    var path_URL = userId + '&' + project_selected;
    deleteInDB('project_user',path_URL);

  });

  $('button.close').click(); 

});