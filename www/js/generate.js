$(document).ready(function(){
    function generateTest(){
        var oData = Serialize();
        $.ajax({
            url: "/site/Generate",
            type:"POST",
            dataType : "json",
            data : oData,
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
                fHideErrors();
                if(data.status){
                    location.href = "/test";
                }else{
                    fShowErrors(data.errors);
                }
            //   $("#main-content").html(data.content);
            }
        })
    }
    
    
    
    
    
    function fHideErrors(){
        $('#user-data input[type=text]').css('border-color','#CCC');
    }
    
    
    function fShowErrors(errors){
       
       for(var i in errors){
           $('#user-data input[name='+i+']').css('border-color','#F80000');
       } 
       
       $(function() {
                        $( "#errors_display" ).dialog({
                            height: 140,
                            modal: true
                        });
                    });
    }
    
    
    function Serialize(){
        var Data = {};
        $('#user-data input[type=text]').each(function(){
            console.log($(this).attr('name'));
            var key = $(this).attr('name').toString();
            Data[key]=$(this).val();
        });
        return Data;
    }
    
   function loadTest(){
       alert("ПОЧАТОК ТЕСТУ!!!");
   }
    
    
    $("#begin-btn").die('click')
    .live("click",function(){
        generateTest(); 
    });
    
    
    $("#flags span").die('click')
    .live("click",function(){
        var lang = $(this).data('id');
        $.ajax({
            url: "/site/LanguageSet",
            type:"POST",
            dataType : "json",
            data : {
                lang:lang
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
                $("#main-content").html('');
                $("#main-content").html(data.content);
               
                
                $("#test-info").html(data.title);
                 if(!data.status){
                     $('input').attr("disabled","disabled");
                }
               
            }
        })
        
    })
})

