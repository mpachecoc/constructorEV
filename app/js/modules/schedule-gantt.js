/** 
 * Schedule - Gantt
 **/

// Once Page is Loaded
$(document).ready(function(){

    $('#spinner').fadeIn('slow');    
    $('#spinner').fadeOut('slow');
    
});

/** Functions */
function loadGanttFromServer(taskId, callback) {

    // This is a simulation: load data from the local storage if you have already played with the demo or a textarea with starting demo data
    // var ret=loadFromLocalStorage();
    // return ret;
  
    var events = {
      "tasks": [], 
      "selectedRow": 0, 
      "deletedTaskIds": [],
      "resources": [],
      "roles": [], 
      "canWrite": true, 
      "canDelete": true, 
      "canWriteOnParent": true, 
      canAdd: true
    };
  
    $.ajax({
        url: API_URL + '/tasks/by_project/' + ACTIVE_PROJECT,
        type: "GET",
        dataType: 'json',
        async: false,
    }).done(function(data) {
        if (data != 404){
          // Fill Tasks
          $.map(data, function (taskObj, i) {
              $.each(taskObj, function (i, taskArr) {
                  events.tasks.push({
                    "id": taskArr.id,
                    "name": taskArr.name,
                    "progress": taskArr.percentage,
                    "progressByWorklog": false,
                    "relevance": 0,
                    "type": "", 
                    "typeId": "", 
                    "description": "", 
                    "code": "",
                    "level": 0, 
                    "status": taskArr.percentage == 100 ? "STATUS_DONE" : "STATUS_ACTIVE", 
                    "depends": "", 
                    "canWrite": true, 
                    "start": dateToTimestamp(taskArr.start_date),
                    "duration": getBusinessDatesCount(taskArr.start_date, taskArr.end_date), 
                    "end": dateToTimestamp(taskArr.end_date), 
                    "startIsMilestone": false, 
                    "endIsMilestone": false, 
                    "collapsed": false, 
                    "assigs": [], 
                    "hasChild": false
                  },); 
              });
          });
        }
    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
    });
  
    return events;
  }
  
  function saveGanttOnServer() {
  
    // This is a simulation: save data to the local storage or to the textarea
    // saveInLocalStorage();
  
    $('#spinner').fadeIn('slow');
    var prj = ge.saveProject();
  
    $.each(prj.tasks, function (i, taskArr) {
      // Create Json
      var JSONData = {};
      JSONData['id'] = taskArr.id;
      JSONData['project_id'] = ACTIVE_PROJECT;
      JSONData['name'] = taskArr.name;
      JSONData['percentage'] = taskArr.progress;
      JSONData['start_date'] = timestampToDate(taskArr.start);
      JSONData['end_date'] = timestampToDate(taskArr.end);
      JSONData = JSON.stringify(JSONData); 
  
      // Uptade Tasks in DB
      $.ajax({
        url: API_URL + '/tasks/u/',
        type: 'PUT',
        dataType: 'json',
        data: JSONData
      }).done(function(data) {
        // $.gritter.add({
        //   title: '¡Exito!',
        //   text: 'Datos Modificados Correctamente.'
        // });
      }).fail(function (jqXHR) {
        $.gritter.add({
          title: '¡Error!',
          text: 'Los datos NO fueron modificados correctamente.'
        });
          console.log(jqXHR);
      });
    });
  
    // Get Tasks again from DB
    var project = loadGanttFromServer();
    ge.loadProject(project);
    ge.checkpoint(); //empty the undo stack
    
    $('#spinner').fadeOut('slow');
  
  }
  
  function newProject(){
    clearGantt();
  }
  
  function clearGantt() {
    ge.reset();
  }

