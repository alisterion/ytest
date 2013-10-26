$(document).ready(function(){
    

    $(function() {
        $( document ).tooltip();
    });


    $(function() {
        $( ".connectedSortable" ).sortable({
            connectWith: ".connectedSortable",
            helper: "clone",
            revert: "invalid",
            activate: function(){
              
            },
            beforeStop: function( event, ui){
               
            },
            stop : function( event, ui){
                console.log($(ui.item).parent());
                $(ui.item).find('.delete_theam_from_module').remove();
                if($(ui.item).parent().attr('id')=='sortable1'){
                    $(ui.item).find('.delete_theam_from_module').remove();
                    
                    deleteTheamFromModul();
                    return;
                }
                $(ui.item).append('<span class="delete_theam_from_module" style="width: 15px; cursor: pointer; float: right;">X</span>');
                var moduleId = $(ui.item).parent().attr('rel');
                var theamId = $(ui.item).attr('id');
                var oParam = {
                    module:moduleId, 
                    theam:theamId
                };
                saveModule(oParam);
            }
            
        }).disableSelection();
    });
    
    
    function saveModule(oData){
        $.ajax({
            url: "/site/SaveModuleStatus",
            type:"POST",
            dataType : "json",
            data :oData,
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
                $("#overley").hide();
            //location.href = "/site/admin";
            }
        })
    }
    
    
    
    function setModuleTest(id){
         $(function() {          
            $( "#set_module_div" ).find('#additional #module_id').val(id);
            $( "#set_module_div" ).dialog({
                height: 200,
                width: 300,
                modal: true
            });
        });
    }
    
    
    $(function() {
        $( "#accordion" ).accordion();
    });
    
    $(".module_set_test")
    .die('dblclick')
    .live("dblclick",function(){
        var id = $(this).attr('rel');
        setModuleTest(id)
    });
    $(".delete_theam_from_module")
    .die('click')
    .live("click",function(){
        var id = $(this).parent().attr('id');
        var module = $(this).parents('ul').attr('rel');
        deleteTheamFromModul($(this),id,module);
    });
    
    function deleteTheamFromModul(item,id,module){
        if(!confirm("Видалити "+ $(item).parent().html())){
            return;
        }
        $.ajax({
            url: "/site/DeleteFromModule",
            type:"POST",
            dataType : "json",
            data :{
                id:id,
                module:module
            },
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
                $("#overley").hide();
                if(data.status){
                   $(item).parent().remove(); 
                }
            //location.href = "/site/admin";
            }
        })
    }
    
    $("#set_test_theam").die('click')
    .live("click",function(){
        var aParams = $(this).parents('#errors_display').find('input[type=text], input[type=hidden]');
        var oData = {};
        aParams.each(function(){
            oData[$(this).attr('id')] = $(this).val(); 
        })
        console.log(oData);    
        setTestTheam(oData);
    });
    
    $("#set_test_module").die('click')
    .live("click",function(){
        var aParams = $(this).parents('#set_module_div').find('input[type=text], input[type=hidden]');
        var oData = {};
        aParams.each(function(){
            oData[$(this).attr('id')] = $(this).val(); 
        })
        console.log(oData);    
        setTestModule(oData);
    });
    
    
    
    function setTestTheam(oData){
        $.ajax({
            url: "/site/SetTestTheam",
            type:"POST",
            dataType : "json",
            data :oData,
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
                $("#overley").hide();
            // location.href = "/site/admin";
            }
        })
    }
    function setTestModule(oData){
        $.ajax({
            url: "/site/SetTestModule",
            type:"POST",
            dataType : "json",
            data :oData,
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
                $("#overley").hide();
            // location.href = "/site/admin";
            }
        })
    }
        
        
    $(".module span").die('click')
    .live("click",function(){
        $(this).parent().find('.module-list').toggle();
    })
    
    
    

    
    $("li.ui-state-default").die('dblclick')
    .live("dblclick",function(){
        
        var visible_cont = ($(this).html());
        var visible_id = visible_cont.match(/[0-9]+/g);
        var id = $(this).attr('id');
        $(function() {          
            $( "#errors_display" ).find('#additional #theam_id').val(id);
            $( "#errors_display" ).find('#additional #visible_theam_id').val(visible_id);
            $( "#errors_display" ).dialog({
                height: 200,
                width: 300,
                modal: true
            });
        });
    })
    
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
                location.href = "/site/admin";
            }
        })
        
    })
  
  
    function createModul(modulName){
        $.ajax({
            url: "/site/CreateModule",
            type:"POST",
            dataType : "json",
            data : {
                title:modulName
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
                location.href = "/site/admin";
            }
        })
    }
    
    
    $(function() {
        $( "#add_modul" )
        .button()
        .click(function( event ) {
            $(this).hide();
            var modul_name = prompt("Введіть назву модуля", "Назва модуля");
              
            createModul(modul_name);
            $(this).show();
            return false;
        });
            
        $( "#load_theam_from_file" )
        .button()
        .click(function( event ) {
              
            });
    });
    
    
    
    
    $("#delete_theam_bd").die('click')
    .live("click",function(){
        var id = $('#visible_theam_id').val();
        if(!confirm("Ви дійсно впевнені що хочите видалити тему №"+id)){
        return;
        }
        $.ajax({
            url: "/site/DeleteTheam",
            type:"POST",
            dataType : "json",
            data : {
                id:id
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
                location.href = "/site/admin";
            }
        })
        
    })
    $("#delete_module_bd").die('click')
    .live("click",function(){
        var id = $('#module_id').val();
        if(!confirm("Ви дійсно впевнені що хочите видалити цей модуль?")){
        return;
        }
        $.ajax({
            url: "/site/DeleteModul",
            type:"POST",
            dataType : "json",
            data : {
                id:id
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
                location.href = "/site/admin";
            }
        })
    })
    
    
});