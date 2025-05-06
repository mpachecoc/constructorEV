/** Serialize a Form and return as a JSON (only inputs filled) */
(function ($) {
    $.fn.serializeFormJSON = function () {

        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
})(jQuery);

/** Serialize a Form and return as a JSON (All inputs) */
function serializeFormJSONAll($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

/** Get Today's Date */
function getTodaysDate(){

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0'+dd
    } 

    if (mm < 10) {
        mm = '0'+mm
    } 

    today = yyyy + '-' + mm + '-' + dd;
    return today;    
}

/** Get First Letters of Project Name */
function getFirstLetters(str) {
    var name = '';
    var res = str.split(" ");
    $.each(res, function (i, word) {
        name = name + word.charAt(0).toUpperCase();
    });
    return name;
}

/** Format Date from DB to (dd-mm-aaaa) */
function formatDate(date){
    if (date != null || date != ''){
        var d = new Date(date.replace(/-/g, '\/'));
        var day = d.getDate();
        var month = d.getMonth() + 1;
        var year = d.getFullYear();
        if (day < 10) {
            day = "0" + day;
        }
        if (month < 10) {
            month = "0" + month;
        }
        // return day + "/" + month + "/" + year;
        return day + "-" + month + "-" + year;
    }else{
        return '';
    }
}

/** For all tables that use 'DataTables' js */
function fnInitCompleteCallback(that)
{
    var p = that.parents('.dataTables_wrapper').first();
    var l = p.find('.row').find('label');

    l.each(function(index, el) {
        var iw = $("<div>").addClass('col-md-8').appendTo($(el).parent());
        $(el).parent().addClass('form-group margin-none').parent().addClass('form-horizontal');
        $(el).find('input, select').addClass('form-control').removeAttr('size').appendTo(iw);
        $(el).addClass('col-md-4 control-label');
    });

    var s = p.find('select');
    s.addClass('.selectpicker').selectpicker();
}

/** Format number with 2 decimals and (,) for thousands */
function formatNum(num){
    num = Number(num).toFixed(2);
    return Number(num).toLocaleString('en');
    // return String(num).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"); 
}

/** Inverse Format remove (,) for thousands to make calculations */
function formatInvNum(num){ 
    return Number(num.replace(/\,/g,''));
}

/** Convert Date (from DB) to Timestamp */
function dateToTimestamp(date){
    var dates = date.split("-");
    var newDate = dates[1]+"/"+dates[2]+"/"+dates[0];
    return new Date(newDate).getTime();
}

/** Convert Timestamp to Date (for DB) */
function timestampToDate(ts){
    var d = new Date(ts);
    var day = d.getDate();
    var month = d.getMonth() + 1;
    var year = d.getFullYear();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    return year + "-" + month + "-" + day;
}

/** Get difference in days between 2 dates (from DB) */
function diff2Dates(date_ini, date_end){
    var dates = date_ini.split("-");
    var newDate = dates[1]+"/"+dates[2]+"/"+dates[0];
    var dt1 = new Date(newDate);
    
    dates = date_end.split("-");
    newDate = dates[1]+"/"+dates[2]+"/"+dates[0];
    var dt2 = new Date(newDate);

    var diff = Math.floor((Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) - Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate()) ) /(1000 * 60 * 60 * 24));
    var duration = diff += 1;
    return duration;
}

/** Calculate Business Days */
function getBusinessDatesCount(date_ini, date_end) {
    var dates = date_ini.split("-");
    var newDate = dates[1]+"/"+dates[2]+"/"+dates[0];
    var startDate = new Date(newDate);
    
    dates = date_end.split("-");
    newDate = dates[1]+"/"+dates[2]+"/"+dates[0];
    var endDate = new Date(newDate);

    var count = 0;
    var curDate = startDate;
    while (curDate <= endDate) {
        var dayOfWeek = curDate.getDay();
        if(!((dayOfWeek == 6) || (dayOfWeek == 0)))
           count++;
        curDate.setDate(curDate.getDate() + 1);
    }
    return count;
}

/** 
 *  Ajax calls to DB 
 **/

// Ajax - GET All values from DB.
function getAll(dBtable,callback){
    $.ajax({
        url: API_URL + '/' + dBtable + '/',
        type: "GET",
        dataType: 'json'
    }).done(function(data) {
        callback(data);

    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
        callback(jqXHR.status);
    });
}

// Ajax - GET Single value from DB with active Project. 
function getSingle(dBtable,id,callback){
    $.ajax({
        url: API_URL + '/' + dBtable + '/s/' + id,
        type: "GET",
        dataType: 'json'
    }).done(function(data) {
        callback(data);

    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
        callback(jqXHR.status);
    });
}

// Ajax - GET Single value from DB with active Project. (ASYNC false)
function getSingleSync(dBtable,id,callback){
    $.ajax({
        url: API_URL + '/' + dBtable + '/s/' + id,
        type: "GET",
        dataType: 'json',
        async: false,            
    }).done(function(data) {
        callback(data);

    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
        callback(jqXHR.status);
    });
}

// Ajax - GET All values from DB with active Project. 
function getAllByProj(dBtable,callback){
    $.ajax({
        url: API_URL + '/' + dBtable + '/by_project/' + ACTIVE_PROJECT,
        type: "GET",
        dataType: 'json'
    }).done(function(data) {
        callback(data);

    }).fail(function (jqXHR) {
        console.log('Failed, status code: ' + jqXHR.status);
        callback(jqXHR.status);
    });
}

// Ajax - POST / PUT data to DB
function saveInDB(dBtable,type,file,json) {
    $.ajax({
        url: API_URL + '/' + dBtable + '/' + file + '/',
        type: type,
        dataType: 'json',
        data: json
    }).done(function(data) {
        $.gritter.add({
            title: '¡Exito!',
            text: 'Datos Guardados Correctamente.'
        });
    }).fail(function (jqXHR) {
        $.gritter.add({
            title: '¡Error!',
            text: 'Los datos no fueron correctamente guardados.'
        });
        console.log('Failed, status code: ' + jqXHR.status);
    });
}

// Ajax - PATCH data to DB
function patchInDB(dBtable,json) {
    $.ajax({
        url: API_URL + '/' + dBtable + '/p/',
        type: 'PATCH',
        dataType: 'json',
        data: json
    }).done(function(data) {
        $.gritter.add({
            title: '¡Exito!',
            text: 'Datos Guardados Correctamente.'
        });
    }).fail(function (jqXHR) {
        $.gritter.add({
            title: '¡Error!',
            text: 'Los datos no fueron correctamente guardados.'
        });
        console.log('Failed, status code: ' + jqXHR.status);
    });
}

// Ajax - GET All values from DB from a selected Project. 
async function getAllBySelectedProj(dBtable,project_id,callback){
  await $.ajax({
      url: API_URL + '/' + dBtable + '/by_project/' + project_id,
      type: "GET",
      dataType: 'json'
  }).done(function(data) {
      callback(data);

  }).fail(function (jqXHR) {
      console.log('Failed, status code: ' + jqXHR.status);
      callback(jqXHR.status);
  });
}

// Ajax - DELETE data to DB
function deleteInDB(dBtable,pathURL) {
  $.ajax({
      url: API_URL + '/' + dBtable + '/d/' + pathURL + '/',
      type: "DELETE",
      dataType: 'json'
  }).done(function(data) {
      $.gritter.add({
          title: '¡Exito!',
          text: 'Datos Eliminados Correctamente.'
      });
  }).fail(function (jqXHR) {
      $.gritter.add({
          title: '¡Error!',
          text: 'Los datos no fueron correctamente eliminados.'
      });
      console.log('Failed, status code: ' + jqXHR.status);
  });
}