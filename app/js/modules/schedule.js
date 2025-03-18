/** 
 * Schedule 
 **/

function JQSliderCreate()
{
	$(this)
		.removeClass('ui-corner-all ui-widget-content')
		.wrap('<span class="ui-slider-wrap"></span>')
		.find('.ui-slider-handle')
		.removeClass('ui-corner-all ui-state-default');
}

var Tasks = {
    getFromDB: function() {
        var events = [];
        $.ajax({
            url: API_URL + '/tasks/by_project/' + ACTIVE_PROJECT,
            type: "GET",
            dataType: 'json',
            async: false,
        }).done(function(data) {
            if (data != 404){
                // Fill Tasks
                var li = '';
                $.map(data, function (taskObj, i) {
                    $.each(taskObj, function (i, taskArr) {
                        if(taskArr.start_date == '0000-00-00'){
                            li = li +
                            '<li id="'+ taskArr.id +'" class="glyphicons move"><i></i>' + taskArr.name + ' (' + taskArr.percentage + '%)</li>';
                        }else{
                            events.push({
                                id: taskArr.id,
                                title: taskArr.name + ' (' + taskArr.percentage + '%)',
                                start: taskArr.start_date,
                                end: taskArr.end_date,
                                allDay: true
                            },);
                        }
                    });
                });
                // Add data to DOM
                $('ul.tasks').append(li);
            }
        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);
        });

        return events;
    },
    updateInCalendar: function(date) {
        // Update Task with new Start and End Dates
        var title = date.title.split('(');
        var perc = title[1].split('%');
        
        var JSONData = {};
        JSONData['id'] = date.id;
        JSONData['project_id'] = ACTIVE_PROJECT;
        JSONData['name'] = title[0];
        JSONData['percentage'] = perc[0];
        JSONData['start_date'] = date.start;
        JSONData['end_date'] = date.end;
        JSONData = JSON.stringify(JSONData); 
        
        $.ajax({
            url: API_URL + '/tasks/u/',
            type: 'PUT',
            dataType: 'json',
            data: JSONData
        }).done(function(data) {
            // Render the Event on the Calendar
            $('#calendar').fullCalendar('renderEvent', date, true);
            
        }).fail(function (jqXHR) {
            console.log('Failed, status code: ' + jqXHR.status);                
        });
    }
}

// Once Page is Loaded
$(document).ready(function(){

    $('#spinner').fadeIn('slow');

    /** JS - Uniform Init */ 
    if ($('.uniformjs').length) 
        $('.uniformjs').find(":checkbox, :radio").uniform();
        
	/** Initialize the Calendar */
	$('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			// right: 'month,agendaWeek,agendaDay'
			right: 'month'
        },
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            agendaWeek: 'Sem.',
            agendaDay: 'Dia'
        },
        monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
        dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		editable: true,
        droppable: true,
        // allowEventDelete: true,
		events: Tasks.getFromDB(),
		drop: function(date, allDay) 
		{
			// retrieve the dropped element's stored Event Object
			var originalEventObject = $(this).data('eventObject');
			
			// we need to copy it, so that multiple events don't have a reference to the same object
			var copiedEventObject = $.extend({}, originalEventObject);
			
			// assign it the date that was reported
			copiedEventObject.id = $(this).attr('id');
			copiedEventObject.start = date;
            copiedEventObject.allDay = allDay;

            // Update Task with new Start and End Dates
            var JSONData = {};
            var id = $(this).attr('id');
            JSONData['id'] = id;
            JSONData['project_id'] = ACTIVE_PROJECT;
            JSONData['name'] = $.trim($(this).text()).split('(')[0];
            JSONData['percentage'] = 0;
            JSONData['start_date'] = date;
            JSONData['end_date'] = date;
            JSONData = JSON.stringify(JSONData); 
            
            $.ajax({
                url: API_URL + '/tasks/u/',
                type: 'PUT',
                dataType: 'json',
                data: JSONData
            }).done(function(data) {

                // render the event on the calendar
                // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
                
                // is the "remove after drop" checkbox checked?
                // if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    // $(this).remove(); 
                    $('li#'+id).remove(); 
                    // }
                    
            }).fail(function (jqXHR) {
                console.log('Failed, status code: ' + jqXHR.status);                
            });
            
        },
        eventDrop : function(date, allDay) {
            // Update Task with new Start and End Dates
            Tasks.updateInCalendar(date);

        },
        eventResize: function(date, allDay) { 
            // Update Task with new Start and End Dates
            Tasks.updateInCalendar(date);

        },
        // --- DISABLED IN UI ---
		// eventClick: function(date, allDay){
        //     var title = date.title.split('(');
        //     var perc = title[1].split('%');
        //     bootbox.dialog({
        //         title: "Editar Tarea",
        //         message: "<label class='control-label'>Tarea: </label>" + 
        //                 "<input type='text' id='name' value='"+ title[0] +"' class='form-control' />" +
        //                 "<div class='separator top'></div>" + 
        //                 "<label class='control-label'>Porcentaje de Compleción (%): </label>" + 
        //                 "<div class='slider-range-min row form-horizontal'>" +
        //                     "<div class='col-md-2'>" +
        //                         "<div class='control-group'>" +
        //                             "<input type='text' id='percentage' value='"+ perc[0] +"' class='form-control' />" +
        //                         "</div>" +
        //                     "</div>" +
        //                     "<div class='col-md-10' style='padding-top: 9px;'>" +
        //                         "<div class='slider slider-primary'></div>" +
        //                     "</div>" +
        //                 "</div>",
        //         size: "small",
        //         buttons: {
        //             noclose: {
        //                 label: "Borrar Tarea",
        //                 className: "btn-danger",
        //                 callback: function() {
        //             		bootbox.confirm("Está seguro que desea borrar la tarea?", function(result) {
        //                         if (result){
        //                             $.ajax({
        //                                 url: API_URL + '/tasks/d/' + date.id,
        //                                 type: 'DELETE',
        //                                 dataType: 'json'
        //                             }).done(function(data) {
        //                                 // Update in DOM
        //                                 $('#calendar').fullCalendar('removeEvents',date.id);
        
        //                                 $.gritter.add({
        //                                     title: '¡Exito!',
        //                                     text: 'La Tarea fue eliminada correctamente.'
        //                                 });
        //                             }).fail(function (jqXHR) {
        //                                 $.gritter.add({
        //                                     title: '¡Error!',
        //                                     text: 'La Tarea no fue eliminada correctamente.'
        //                                 });
        //                             });
        //                         }
        //                     });
        //                 }
        //             },
        //             success: {
        //                 label: "Guardar",
        //                 className: "btn-success",
        //                 callback: function() {

        //                     // Get all data to Update
        //                     var JSONData = {};
        //                     JSONData['id'] = date.id;
        //                     JSONData['project_id'] = ACTIVE_PROJECT;
        //                     JSONData['name'] = $('#name').val();
        //                     JSONData['percentage'] = $('#percentage').val();
        //                     JSONData['start_date'] = date.start;
        //                     JSONData['end_date'] = date.end;
        //                     JSONData = JSON.stringify(JSONData); 
        //                     $.ajax({
        //                         url: API_URL + '/tasks/u/',
        //                         type: 'PUT',
        //                         dataType: 'json',
        //                         data: JSONData
        //                     }).done(function(data) {
        //                         // Update in DOM
        //                         date.title = $('#name').val() + ' (' + $('#percentage').val() + '%)';
        //                         $('#calendar').fullCalendar('updateEvent', date);

        //                         $.gritter.add({
        //                             title: '¡Exito!',
        //                             text: 'Datos Modificados Correctamente.'
        //                         });
        //                     }).fail(function (jqXHR) {
        //                         $.gritter.add({
        //                             title: '¡Error!',
        //                             text: 'Los datos no fueron modificados correctamente.'
        //                         });
        //                     });
                            
        //                 }
        //             }

        //         }
        //     });

        //     /** JQueryUI Slider: Range fixed minimum (for Task Percentage) */
        //     if ($('.slider-range-min').size() > 0){
        //         $( ".slider-range-min .slider" ).slider({
        //             create: JQSliderCreate,
        //             range: "min",
        //             value: perc[0],
        //             min: 0,
        //             max: 100,
        //             slide: function( event, ui ) {
        //                 $( ".slider-range-min #percentage" ).val(ui.value);
        //             },
        //             start: function() { if (typeof mainYScroller != 'undefined') mainYScroller.disable(); },
        //             stop: function() { if (typeof mainYScroller != 'undefined') mainYScroller.enable(); }
        //         });
        //         $( ".slider-range-min #percentage" ).val($( ".slider-range-min .slider" ).slider( "value" ));
        //     }

		// }
    });
    
    /** Initialize the External Events */
	$('#external-events ul li').each(function() 
	{
	
		// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
		// it doesn't need to have a start or end
		var eventObject = {
			title: $.trim($(this).text()) // use the element's text as the event title
		};
		
		// store the Event Object in the DOM element so we can get to it later
		$(this).data('eventObject', eventObject);
		
		// make the event draggable using jQuery UI
		$(this).draggable(
		{
			zIndex: 999,
			revert: true,      // will cause the event to go back to its
			revertDuration: 0,  //  original position after the drag,
			start: function() { if (typeof mainYScroller != 'undefined') mainYScroller.disable(); },
	        stop: function() { if (typeof mainYScroller != 'undefined') mainYScroller.enable(); }
		});
		
    });
    
    $('#spinner').fadeOut('slow');
});

// Create New Task --- DISABLED IN UI ---
$('#add-new-task').click(function() {
    bootbox.prompt("Nombre de Nueva Tarea:", function(name) {                
        if (name) {                                             
            // Create Json
            var JSONData = {};
            JSONData['project_id'] = ACTIVE_PROJECT;
            JSONData['name'] = name;
            JSONData['percentage'] = 0;
            JSONData = JSON.stringify(JSONData);

            $.ajax({
                url: API_URL + '/tasks/c/',
                type: 'POST',
                dataType: 'json',
                data: JSONData
            }).done(function(data) {
                // Fill New Task
                $.map(data, function (data, i) {
                    var li = '<li id="'+ data.id +'" class="glyphicons move"><i></i>' + data.name + ' (' + data.percentage + '%)</li>';
                    $('ul.tasks').append(li);
                  
                    // Create New Calendar Object and make it draggable
                    var eventObject = { title: $.trim($('li#'+data.id).text()) };
            		$('li#'+data.id).data('eventObject', eventObject);
                    $('li#'+data.id).draggable({
                        zIndex: 999,
                        revert: true,      // will cause the event to go back to its
                        revertDuration: 0,  //  original position after the drag,
                        start: function() { if (typeof mainYScroller != 'undefined') mainYScroller.disable(); },
                        stop: function() { if (typeof mainYScroller != 'undefined') mainYScroller.enable(); }
                    });
                });
            }).fail(function (jqXHR) {
                $.gritter.add({
                    title: '¡Error!',
                    text: 'Los Tarea no fue creada correctamente.'
                });
                console.log('Failed, status code: ' + jqXHR.status);
            });
        }else{
            $.gritter.add({
                title: '¡Error!',
                text: 'Debe llenar el nombre de la tarea.'
            });
        }
    });
});