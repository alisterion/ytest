<div id="test-wrapper">
    <div class="flash-notice">
        <?php echo $question_number . "." . $qResult['question']; ?>
        <?php if (!empty($qResult["img"])) { ?>
            <div style="padding-top: 10px;">
                <img style="max-height: 300px;" src="<?php echo Yii::app()->baseUrl . $qResult["img"]; ?>"/>
            </div>
        <?php } ?>
    </div>

    <div id="answers">

        <?php
        $a = array(5);
        $a[1] = $qResult['rnd']['a1'];
        $a[2] = $qResult['rnd']['a2'];
        $a[3] = $qResult['rnd']['a3'];
        $a[4] = $qResult['rnd']['a4'];
        $a[5] = $qResult['rnd']['a5'];


        for ($i = 1; $i < 6; $i++) {
            ?>
            <div rel="<?php echo $a[$i]; ?>" class="<?php if ($a[$i] == (int) $qResult["answer_num"]) { ?> flash-success <?php } else { ?>flash-error <?php } ?>"><?php echo $qResult['answers'][$a[$i]]; ?></div>
        <?php } ?>
    </div>


    <div style="text-align: center;">
        <input id="max_question" value="<?php echo $max_question; ?>" type="hidden"/>
        <a id="get_prew" data-question="<?php echo $question_number - 1; ?>"  href="#"><img src="/images/back.png"/></a>
        <a id="send_answer" data-question="<?php echo $question_number; ?>" style="display: none;"  href="#"></a>
        <a id="get_next" href="#"><img src="/images/next.png"/></a>
        <a id="finish_test" style="margin-left: 75px;" href="#"><img src="/images/finish_flag.png"/></a>
    </div>

    <div id="timer"><div onclick="return false;" id="timer_text"></div></div>

</div>
