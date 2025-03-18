
/** API URL */
var API_URL = 'http://localhost/construct-beta/app/api';

/** User Data */
var userID;
var userName;
var userRol;
var userRoles;
var userData;
var userProjects;
var userProjArr = [];
var ACTIVE_PROJECT;
var CURRENCY_PROJ;

if (localStorage.getItem('user')){
    userData = JSON.parse(localStorage.getItem('user'));

    $.map(userData, function (user, i) {
        userID = user.id;
        userName = user.username;
        userRol = user.rol;
        userRoles = user.roles;
    });
}

/** Get User Projects */
if (localStorage.getItem('user_projects')){
    userProjects = JSON.parse(localStorage.getItem('user_projects'));

    $.map(userProjects, function (user, i) {
        $.each(user, function (i, userArr) {
            userProjArr.push(userArr.project_id);
        });
    });
}

/** Get Active Project */
if (localStorage.getItem('active_project')){
    ACTIVE_PROJECT = localStorage.getItem('active_project');
    
    // Get Active Project Props
    $.ajax({
        url: API_URL + '/projects/s/' + ACTIVE_PROJECT,
        type: "GET",
        dataType: 'json'
    }).done(function(data) {
        $.map(data, function (project, i) {
            CURRENCY_PROJ = project.currency;
            // = project.name
            // = formatDate(project.elaboration_date)
            // = formatDate(project.presup_date)
            // = formatDate(project.end_date)
            // = project.calendar_days
        });
    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
    });
    
}else{
    if(userProjArr[0]){
        // If there is nothing selected, select the 1st Project
        ACTIVE_PROJECT = userProjArr[0]; 
    }else{
        $.gritter.add({
            title: '¡Error!',
            text: 'El Usuario no tiene Proyectos Asignados.'
        });
    }
}