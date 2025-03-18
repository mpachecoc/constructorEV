/**
 * Login
 */

$(document).on('click', ".login-btn", function(event) {

    event.preventDefault();

    var user = $('#exampleInputEmail1').val();
    var pass = $('#exampleInputPassword1').val();

    // Check Empty values
    if (!user || !pass ){
        $.gritter.add({
            title: 'Campos Vacios!',
            text: 'Debe llenar el Usuario y Password.'
        });
        return false;
    }

    // Login
    $.ajax({
        url: API_URL + '/users/login/' + user + '&' + pass,
        type: "GET",
        dataType: 'json'
    }).done(function(data) {

        // Save User Data
        localStorage.setItem('user',JSON.stringify(data));

        // Get User Projects
        $.map(data, function (user, i) {

            $.ajax({
                url: API_URL + '/project_user/by_user/' + user.id,
                type: "GET",
                dataType: 'json'
            }).done(function(projects) {

                // Save User Projects
                localStorage.setItem('user_projects',JSON.stringify(projects));
            });

        });

        // Go to Main page
        setTimeout('window.location.href = "main";',300);

    }).fail(function (jqXHR) {

        $.gritter.add({
            title: 'Credenciales Incorrectas!',
            text: 'El usuario y/o password son incorrectos.'
        });
        console.log('Failed, status code: ' + jqXHR.status);
    }).always(function () {

    });

});