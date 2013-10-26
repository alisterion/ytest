$(document).ready(function(){
    var answerNum = 0;   
   
   
    $('#answers .flash-error').die('click').live('click',function(){
        $('#answers div').removeClass('flash-success');
        $('#answers div').addClass('flash-error');
        $(this).removeClass('flash-error');
        $(this).addClass('flash-success');
        answerNum = $(this).attr('rel');
        $('#send_answer').show(); 
    });
    
    function sendAnswer(){
        $.ajax({
            url: "/site/answer",
            type:"POST",
            dataType : "json",
            data : {
                user_answer: answerNum
            },
            beforeSend:function(){
                $("#overley").show();
               
            },
            complete:function(data){
                $("#overley").hide();
            },
            error:function(){
                $("#overley").hide(); 
                alert("Error");
            },
            success:function(data){
                
                $("#overley").hide();
                if(data.status == "finish"){
                    clearInterval(intervalID);
                    showUserStat();
                    return false;
                }
                if(data.status){
                    $("#main-content").html(data.content);
                }
                
            }
        })
        answerNum = 0;
    }
    
    
    $( "#send_answer" ).die('click').live('click',function( event ) {
        sendAnswer();
        return false;
    });
    
    
    function showUserStat(){
         $.ajax({
            url: "/site/UserStat",
            type:"POST",
            dataType : "json",
            beforeSend:function(){
                
               $("#overley").show();
            },
            complete:function(data){
               $("#overley").hide();
            },
            error:function(){
                $("#overley").hide();
                
            },
            success:function(data){
              $("#main-content").html(data.content);
              $("#overley").hide();
            }
        })
    }
    
    
    var intervalID = window.setInterval(user_time, 1000);
    
    
    function user_time(){
        $.ajax({
            url: "/site/getUserInfo",
            type:"POST",
            dataType : "json",
            beforeSend:function(){
                
               
            },
            complete:function(data){
               
            },
            error:function(){
                
                
            },
            success:function(data){
                var nowDate = new Date(data.now);
                var endDate = new Date(data.end);
                var sec = (endDate-nowDate)/1000;
                var hour = Math.floor(sec / 3600); 
                var min = Math.floor(sec / 60) - hour * 60;
                var second = sec % 60;
                var temeStr = '';
                if(hour>0) { 
                    temeStr+= hour +" : ";
                }
                if(min>0) {
                    temeStr += min+ " : ";
                }
                if(second>0){
                    if(second<10){
                        temeStr+= "0"+second;
                    }else {
                        temeStr+=second;
                    }
                    
                }
                $('#timer_text').html(temeStr);
                
                if(hour == 0 && min == 0 && sec == 0){
                    sendAnswer();
                }
                sec = 0;
            }
        })
    }
    
    
})
