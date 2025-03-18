/**
 * Main
 */

/** Check If User is Logged */
if(!userData){
    window.location.href = "index";
}

/** Once Page is Loaded */
$(document).ready(function(){

    $('#spinner').fadeIn('slow');

    // Get Menu Selected
    // if (!MENU){
        // Load Dashboard Page and Active Side Menu
        $('#content').load('dashboard');
        $("ul.list-unstyled li").first().addClass("active");

    // }else{
        // Load Selected MENU and add 'Active' Class to Selected Menu
        // $('#content').load(MENU);
        // $('a#' + MENU).parent().addClass("active");
    // }

    // Get Projects according to User and Display
    var p_opt = '<optgroup label="Proyectos">';
        $.each(userProjArr, function (i, proj) {
            var selected = '';
            if (proj == ACTIVE_PROJECT){
                selected = 'selected';
            }
            p_opt = p_opt + '<option value="' + proj + '" '+selected+'>' + proj + '</option>'
        });
    p_opt = p_opt + '</optgroup>';
    $('select.projects').append(p_opt);

    $('#spinner').fadeOut('slow');

});

/** Once Selected Another Side Menu, Load It */
$(document).on('click', ".side-menu", function(event) {

    event.preventDefault();

    // Remove 'Active' class from Side Menu
    $("ul.list-unstyled li").removeClass("active");

    // Load Side Menu Selected
    var menu = $(this).attr('id');

    // if (menu === 'supplies' || menu == 'apu'){
        // localStorage.setItem('menu', menu);
        // setTimeout('window.location.href = "main";',300);
    // }else{

        $('#content').load(menu);

        // Add 'Active' Class to Selected Menu
        $(this).parent().addClass("active");
    // }

});

/** Save Project Selected */
$(document).on('change', ".projects", function(event) {

    event.preventDefault();

    // Get Project and Save
    var project = $(this).val();
    localStorage.setItem('active_project', project);
    console.log('Saved: ' + project);
    setTimeout('window.location.href = "main";',300);

});

/** Logout */
$(document).on('click', "#logout", function(event) {

    event.preventDefault();
    
    localStorage.clear();
    setTimeout('window.location.href = "index";',300);

});