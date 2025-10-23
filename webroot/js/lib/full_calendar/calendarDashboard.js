$(document).ready(function() {
  
  $.post(copy_js.base_url+'manages/paintDataCalendarDashboard',{}, function(result){
    $('#calendarDashboard').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'listWeek'
      },
      hiddenDays: [ 0 ],
      defaultDate: new Date(),
      businessHours: {
        start: '08:00', // hora final
        end: '18:00', // hora inicial
        dow: [ 1, 2, 3, 4, 5 , 6] // dias de semana, 0=Domingo
      },
      buttonText:{month:"Mes",week:"Semana",day:"Día",list:"Agenda",today:'Hoy'},
      closeText:"Cerrar",
      prevText:"&#x3C;Ant",
      nextText:"Sig&#x3E;",
      monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
      monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
      dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
      allDayHtml: "Todo<br/>el día",
      noEventsMessage:"No hay eventos para mostrar",
      defaultView: 'listWeek',
      navLinks: false,
      editable: false,
      eventLimit: false,
      events: $.parseJSON(result)
    });
  });

});