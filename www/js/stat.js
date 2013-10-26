$(document).ready(function(){
     $(function() {
         var calendarVal = $( "#calendar" ).val();
        $( "#calendar" ).datepicker();
        $( "#calendar" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );
        $( "#calendar" ).val(calendarVal);
    });
    
     $(function() {
        $( document ).tooltip();
    });
    
    
    $("#calendar")
    .die("change")
    .live("change", function(){
        var date = $(this).val();
        $.ajax({
            url: "/site/Statistic",
            type:"POST",
            dataType : "html",
            data : {date:date},
            beforeSend:function(){
                $("#overley").show();
               
            },
            complete:function(data){
                $("#overley").hide();
            },
            error:function(){
                $("#overley").hide(); 
            // alert("Error");
            },
            success:function(data){
                $("#overley").hide();
                console.log(data);
                console.log($("#stat_table tr"));
                $("#stat_table tr").each(function(){
                    console.log($(this).attr('id'));
                    if(!$(this).hasClass('main-row')){
                        $(this).remove();
                    }
                })
                $("#stat_table").append(data);
            }
        })
    });
    
})