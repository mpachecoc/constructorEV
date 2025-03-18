/**
 * Supplies
 */

// Once Page is Loaded
$(document).ready(function(){

    // Get 'Supplies' from DB
    $.ajax({
        url: API_URL + '/supplies/',
        type: "GET",
        dataType: 'json'
    }).done(function(data) {

        console.log(data);

        // Fill Table Data Rows
        var rows = '';

        $.map(data, function (supplyObj, i) {
            console.log('Num. of Supplies: ' + supplyObj.length);
            $.each(supplyObj, function (i, supplyArr) {
                rows = rows +
                    '<tr class="gradeC">' +
                        '<td class="center">' + supplyArr.id + '</td>' +
                        '<td>' + supplyArr.name + '</td>' +
                        '<td>' + supplyArr.desc + '</td>' +
                        '<td class="center">' + supplyArr.und + '</td>' +
                        '<td class="center">' + supplyArr.moneda + '</td>' +
                        '<td class="center">' + supplyArr.precio + '</td>' +
                        '<td class="text-right">' +
                            '<div class="btn-group btn-group-xs ">' +
                                '<a id="'+supplyArr.id+'" href="" class="btn btn-inverse edit-supply"><i class="fa fa-pencil"></i></a>' +
                                '<a id="'+supplyArr.id+'" href="" class="btn btn-danger delete-supply"><i class="fa fa-times"></i></a>' +
                            '</div>' +
                        '</td>' +
                    '</tr>';
            });
        });
        // Add data to DOM
        $('#supply-rows').append(rows);

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
                fnInitCompleteCallback(this);
            }
        });

    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
    });


});

// Edit Supply btn
$(document).on('click', ".edit-supply", function(event) {

    event.preventDefault();

    // Get Supply ID
    var supplyId = $(this).attr('id');
    alert('Edit: ' + supplyId);

});

// Delete Supply btn
$(document).on('click', ".delete-supply", function(event) {

    event.preventDefault();

    // Get Supply ID
    var supplyId = $(this).attr('id');
    alert('Delete: ' + supplyId);

});