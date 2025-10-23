$(document).ready(function() {
  $.post(copy_js.base_url+'manages/paintDataCalendar',{}, function(result){
    $('#calendarView').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'agendaWeek,agendaDay,listWeek'
      },
      hiddenDays: [ 0, 6 ],
      minTime: "07:00:00",
      maxTime: "19:00:00",
      defaultDate: new Date(),
      businessHours: {
        start: '07:00', // hora final
        end: '19:00', // hora inicial
        dow: [ 1, 2, 3, 4, 5] // dias de semana, 0=Domingo
      },
      buttonText:{month:"Mes",week:"Semana",day:"Día",list:"Agenda",today:'Hoy'},
      closeText:"Cerrar",
      allDaySlot: false,
      prevText:"&#x3C;Ant",
      nextText:"Sig&#x3E;",
      monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
      monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
      dayNames: ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],
      dayNamesShort: ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
      allDayHtml: "Todo<br/>el día",
      noEventsMessage:"No hay eventos para mostrar",
      defaultView: 'agendaWeek',
      navLinks: false,
      editable: false,
      eventLimit: false,
      events: $.parseJSON(result)
    });


   $(".fc-event").each(function(){
        var colors = ['#00b307', '#ff8b01', '#004794','#002690', '#80a3c8', '#0b840f','#008cff', '#00c583', '#00c5e2','#9e9e9e', '#000bff', '#c70000'];
        var random_color = colors[Math.floor(Math.random() * colors.length)];
        $(this).css('background-color', random_color);
    });
  });
});