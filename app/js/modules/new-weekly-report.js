/**
 * New Weekly Report
 */

// Helper
var helper = {
    sum_s3: 0,
    getHtmlIni: function(){
        return '<div';
    },
}

// Once Page is Loaded
$(document).ready(function(){

    $('#spinner').fadeIn('slow'); 

    /** Fill Project Name */
    $('#proj_name').val(ACTIVE_PROJECT);

    // Get Number of last "Advanced Form" in DB (Planilla)
    // getAllByProj('advanced_form',function(resp) {
    //     if (resp != 404){ 
    //         $.map(resp, function (Obj, i) { 
    //             $.each(Obj, function (i, Arr) {
    //                 helper.adv_form_num = Arr.id;
    //                 helper.new_adv_form_num = Number(Arr.id) + 1;
    //                 $('input#doc-name').val('Planilla de Avance de Obra Nro. ' + helper.new_adv_form_num);
    //             });
    //         });
    //     }else{
    //         helper.new_adv_form_num = 1;
    //         $('input#doc-name').val('Planilla de Avance de Obra Nro. ' + helper.new_adv_form_num);
    //     }
    // }); 

    
    $('#spinner').fadeOut('slow');
    
});

// (Informe Fotografico) - Save Img. & Desc.
$(document).off('click', "#save-photo-info").on('click', "#save-photo-info", function(event) {

    event.preventDefault(); 
    
    var file = $('#info-photo-img')[0].files[0];

    if (file.type != "image/jpeg" && file.type != "image/png" && file.type != "image/jpg" && file.type != "image/bmp"){
        $.gritter.add({
            title: '¡Error!',
            text: 'El formato de la imagen es invalido.'
        });
    }else{
    
        var description = $('#info-photo-desc').val();
        var form_data_var = new FormData();
        form_data_var.append('file', file);
        console.log(form_data_var);

        // $.ajax({
        //     url: API_URL + '/images/c/',
        //     type: 'POST',
        //     // dataType: 'json',
        //     data: form_data_var,
        //     contentType: false,
        //     cache: false,
        //     processData:false,
        // }).done(function(data) {

            var row =
                '<tr class="gradeC">' +
                    '<td class="left">' + description + '</td>' +
                    '<td class="center"><img src="js/industria-de-la-construccion.jpg" name="test" /></td>' +
                    '<td class="text-right">' +
                        '<div class="btn-group btn-group-xs ">' +
                            '<a id="" href="" class="btn btn-danger del-photo-info"><i class="fa fa-times"></i></a>' +
                        '</div>' +
                    '</td>' +
                '</tr>';
                 
                // Add data to DOM
                $('#photo-info-rows').append(row);

                // Clear
                $('#info-photo-desc').val('');

        // }).fail(function (jqXHR) {
        //     $.gritter.add({
        //         title: '¡Error!',
        //         text: 'El datos no fueron correctamente guardados.'
        //     });
        //     console.log('Failed, status code: ' + jqXHR.status);
        // });

    }
    
});