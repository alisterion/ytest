<div id="test-wrapper">
    <div class="flash-notice"><?php echo $question_number.".".$qResult['question']; ?></div>
    
    <div id="answers">
        
        <?php 
        $a=array(5);
     $a[1]=$qResult['rnd']['a1'];
     $a[2]= $qResult['rnd']['a2']; 
     $a[3]=$qResult['rnd']['a3']; 
     $a[4]=$qResult['rnd']['a4']; 
     $a[5]=$qResult['rnd']['a5'];
        
     
     for($i=1;$i<6;$i++){
         
        ?>
        <div rel="<?php echo $a[$i];?>" class="flash-error"><?php echo $qResult['answers'][$a[$i]]; ?></div>
        <?php } ?>
    </div>
    
    
    <div style="text-align: center;">
        <a id="send_answer" style="display: none;" href="#"><img src="/images/big_icons/icon-redo.png"/></a>
    </div>
    
    <div id="timer"><div onclick="return false;" id="timer_text"></div></div>
    
</div>
