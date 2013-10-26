<script>
    $(function() {
        $("#accordion").accordion({collapsible: true, active: false });
    });
</script>
<div id="test-wrapper">
    <div id="accordion">
        <?php foreach ($list["theams"] as $key => $theam) { ?> 
            <h3><?php echo $theam; ?></h3>
            <div>
                <?php foreach ($list["answers"] as $theam) { ?>  
                    <?php foreach ($theam as $value) { ?>
                        <?php if ($value["theam_id"] == $key) { ?>
                            <div class="flash-notice"><b><?php echo $value['question_num']; ?></b>. <?php echo $value['question_text']; ?> <?php if ($value['user_ansver_num'] == $value['true_answer']) { ?> <img src="/images/1352630656_clean.png" style="float: right;" /> <?php } ?></div>
                            <div id="answers">
                                <div rel="" <?php if ($value['true_answer'] == 1) { ?> class="flash-success"  <?php
                                } else {
                                    if ($value['user_ansver_num'] != 1) {
                                        ?>  class="flash-error" <?php } else { ?> class="flash-notice" style="background: url(/images/Actions-edit-delete-icon.png) left center no-repeat #FFF6BF; padding-left: 50px;"  <?php } ?>  <?php } ?> >
                                    <?php echo $value['answ_1']; ?></div>
                                <div rel="" <?php if ($value['true_answer'] == 2) { ?> class="flash-success"  <?php
                                } else {
                                    if ($value['user_ansver_num'] != 2) {
                                        ?>  class="flash-error" <?php } else { ?> class="flash-notice" style="background: url(/images/Actions-edit-delete-icon.png) left center no-repeat #FFF6BF; padding-left: 50px;"<?php } ?>   <?php } ?> >
                                    <?php echo $value['answ_2']; ?></div>
                                <div rel="" <?php if ($value['true_answer'] == 3) { ?> class="flash-success"  <?php
                                } else {
                                    if ($value['user_ansver_num'] != 3) {
                                        ?>  class="flash-error" <?php } else { ?> class="flash-notice" style="background: url(/images/Actions-edit-delete-icon.png) left center no-repeat #FFF6BF; padding-left: 50px;" <?php } ?>   <?php } ?> >
                                    <?php echo $value['answ_3']; ?></div>
                                <div rel="" <?php if ($value['true_answer'] == 4) { ?> class="flash-success"  <?php
                                } else {
                                    if ($value['user_ansver_num'] != 4) {
                                        ?>  class="flash-error" <?php } else { ?> class="flash-notice" style="background: url(/images/Actions-edit-delete-icon.png) left center no-repeat #FFF6BF; padding-left: 50px;" <?php } ?>   <?php } ?> >
                                    <?php echo $value['answ_4']; ?></div>
                                <div rel="" <?php if ($value['true_answer'] == 5) { ?> class="flash-success"  <?php
                                } else {
                                    if ($value['user_ansver_num'] != 5) {
                                        ?>  class="flash-error" <?php } else { ?> class="flash-notice" style="background: url(/images/Actions-edit-delete-icon.png) left center no-repeat #FFF6BF; padding-left: 50px;" <?php } ?>  <?php } ?> >
                                    <?php echo $value['answ_5']; ?></div>

                            </div>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>