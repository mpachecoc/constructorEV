/**
 * Expenses-Reg.
 */

// Helper
var helper = {
    action: 'create',
    count: 0,
    expDataTable: '',
    totalDirect: 0,
    totalIndirect: 0,
    expID: 0,
    loadExpensesTable: function(){
        this.count = 0;
        this.totalDirect = 0;
        this.totalIndirect = 0;
        
        // Destroy Data Table if exists
        if (this.expDataTable){ 
            this.expDataTable.fnDestroy();
        }

        getAllByProj('expenses',function(resp) {
            if (resp != 404){ 
    
                // Fill Table with Rows
                var rows = '';
                $.map(resp, function (expObj, i) {
                    $.each(expObj, function (i, expArr) {
                        
                        // Get Group Name  
                        var group_name = '';
                        getSingleSync('groups',expArr.item,function(resp) {
                            if (resp != 404){   
                                $.map(resp, function (data, i) {
                                    group_name = data.name;
                                });
                            }
                        });
    
                        helper.count += 1;
                        var suff = expArr.type == 'directo' ? '.' : ''; 
                        var label = expArr.type == 'directo' ? 'label-success' : 'label-primary'; 
                        rows = rows + 
                        '<tr>' +
                            '<td>' + helper.count + '</td>' +
                            '<td style="text-transform: capitalize;"><span class="label ' + label + '">' + expArr.type + '' + suff + '</span></td>' +
                            '<td style="text-transform: capitalize;">' + expArr.discharge + '</td>' +
                            '<td>' + formatDate(expArr.date) + '</td>' +
                            '<td>' + expArr.supplier + '</td>' +
                            '<td>' + expArr.desc + '</td>' +
                            '<td>' + group_name + '</td>' +
                            '<td>' + expArr.sub_item + '</td>' +
                            '<td>' + expArr.object + '</td>' +
                            '<td>' + expArr.amount + '</td>' +
                            '<td>' + expArr.number + '</td>' +
                            '<td>' + expArr.invoice + '</td>' +
                            '<td>' + expArr.origin + '</td>' +
                            '<td>' + expArr.authorization + '</td>' +
                            '<td class="center">' + expArr.cond_1 + '</td>' +
                            '<td class="center">' + expArr.cond_2 + '</td>' +
                            '<td class="text-right">' +
                                '<div class="btn-group btn-group-xs ">' +
                                    '<a id="'+expArr.id+'" class="btn btn-inverse edit-expense"><i class="fa fa-pencil"></i></a>' +
                                    '<a id="'+expArr.id+'" class="btn btn-danger delete-expense"><i class="fa fa-times"></i></a>' +
                                '</div>' +
                            '</td>' +
                        '</tr>';
                        
                        // Sum of amounts to get totals
                        if (expArr.type == 'directo'){
                            helper.totalDirect += Number(expArr.amount);
                        }else{
                            helper.totalIndirect += Number(expArr.amount);
                        }

                    });
                });
    
                // Add data to DOM
                $('#expenses-rows').html(rows);
                $('span.tot-indirect').html('Bs.- ' + helper.totalIndirect.toFixed(2));
                $('span.tot-direct').html('Bs.- ' + helper.totalDirect.toFixed(2));
    
                // Init 'DataTables' js 
                $('table.colVis').dataTable({
                    "sPaginationType": "bootstrap",
                    "sDom": "<'row separator bottom'<'col-md-3'f><'col-md-3'l><'col-md-6'C>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
                    "oLanguage": {
                        "sLengthMenu": "_MENU_ por página"
                    },
                    "oColVis": {
                        "buttonText": "Mostrar / Ocultar Cols.",
                        "sAlign": "right",
                        "bRestore": true,
                        "sRestore": "Seleccionar Todas"
                    },
                    "sScrollX": "100%",
                    // "sScrollXInner": "100%",
                    "bScrollCollapse": true,
                    "fnInitComplete": function () {
                        helper.expDataTable = this;
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

    // Get 'Groups' from DB according to Project
    getAllByProj('groups',function(resp) {
        if (resp != 404){ 

            // Fill Select with Options
            var options = '';
            $.map(resp, function (groupObj, i) {
                $.each(groupObj, function (i, agroupArr) {
                    options = options + '<option value="'+agroupArr.id+'">' + agroupArr.name + '</option>';
                });
            });

            // Add data to DOM
            $('#item').append(options);
        }
    });

    // Get 'Expenses' from DB according to Project
    helper.loadExpensesTable();

    $('#spinner').fadeOut('slow');
    
});

// Save / Update Expenses in DB
$(document).off('click', "#save-expenses").on('click', "#save-expenses", function(event) {

    event.preventDefault();

    // Check Empty fields
    if (!$('#indirecto').is(':checked') && !$('#directo').is(':checked')){
        $.gritter.add({
            title: '¡Alerta!',
            text: 'Debe elegir si es gasto "Directo" o "Indirecto".'
        });
    }else{
        var form = $('#expenses-form');
        var JSONData = serializeFormJSONAll(form);
        JSONData['project_id'] = ACTIVE_PROJECT;
        if (helper.action == 'update'){ JSONData['id'] = helper.expID; }
        JSONData = JSON.stringify(JSONData); 

        // Check if data exists to Create or Update
        var type = helper.action == 'create' ? 'POST' : 'PUT';
        var file = helper.action == 'create' ? 'c' : 'u';

        // Insert in 'Expenses' DB
        $.ajax({
            url: API_URL + '/expenses/' + file + '/',
            type: type,
            dataType: 'json',
            data: JSONData
        }).done(function(data) { 

            // Re load data and datatable
            helper.loadExpensesTable()

            var message = helper.action == 'create' ? 'creado' : 'modificado';            
            $.gritter.add({
                title: '¡Exito!',
                text: 'El registro fue ' + message + ' correntamente.'
            });

            // Clear fields
            form.find("input[type=text], textarea").val("");
            helper.action = 'create';

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
            $.gritter.add({
                title: 'Error!',
                text: 'El registro NO fue creado correntamente.'
            });
        });

    }
});

// Delete Expense in DB
$(document).off('click', ".delete-expense").on('click', ".delete-expense", function(event) {

    event.preventDefault();

    // Get Expense ID
    var expId = $(this).attr('id');

    // Delete Expense from DB
    var conf = confirm('Esta seguro que desea eliminar el registro?');
    if(conf){
        $.ajax({
            url: API_URL + '/expenses/d/' + expId,
            type: "DELETE",
            dataType: 'json'
        }).done(function(data) { 

            // Re load data and datatable
            helper.loadExpensesTable()

            $.gritter.add({
                title: '¡Exito!',
                text: 'Se eliminó correctamente el registro.'
            });

        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
            $.gritter.add({
                title: 'Error!',
                text: 'El registro NO fue eliminado correntamente.'
            });
        });
    }

});

// Cancel
$(document).off('click', "#cancel-expenses").on('click', "#cancel-expenses", function(event) {

    event.preventDefault();

    // Clear fields
    $('#expenses-form').find("input[type=text], textarea").val("");
    helper.action = 'create';

});

// Load 'Expense' Data in Form to update
$(document).off('click', ".edit-expense").on('click', ".edit-expense", function(event) {

    event.preventDefault();

    // Get Expense ID
    var expId = $(this).attr('id');
    helper.expID = expId;

    // Get 'Expense' from DB
    getSingle('expenses',expId,function(resp) {
        if (resp != 404){   
            $.map(resp, function (data, i) {
                if (data.type == 'directo'){
                    $('#directo').prop("checked", true); 
                }else{
                    $('#indirecto').prop("checked", true); 
                }
                $('#discharge').val(data.discharge); 
                $('#date').val(formatDate(data.date));
                $('#supplier').val(data.supplier);
                $('#desc').val(data.desc);
                $('#item').val(data.item);
                $('#sub_item').val(data.sub_item);
                $('#object').val(data.object);
                $('#amount').val(data.amount);
                $('#number').val(data.number);
                $('#invoice').val(data.invoice);
                $('#origin').val(data.origin);
                $('#authorization').val(data.authorization);
                $('#cond_1').val(data.cond_1);
                $('#cond_2').val(data.cond_2);
            });
        }
    });

    // Change Action 
    helper.action = 'update';

});
