<div class="span-13 last">
    <div id="yw0" class="portlet">
        <div class="portlet-decoration">
            <div class="portlet-title"><?php echo $languages[$language][0]; ?></div>
        </div>
        <div class="portlet-content">
            <div class="chart3">
                <div>
                    <div class="text">
                        <div id="gvChartDiv1" class="gvChart" style="position: relative;">
                            <div style="position: relative; width: 492px; height: 300px;" dir="ltr">

                                <div id="user-data">
                                    <div class="form">
                                        <div class="span-12">
                                             <div id="flags">
                                                 <span <?php if($language == 0){ ?> style="opacity: 0.3;" <?php } ?> data-id="0"><img src="/images/flags/Ukraine.png"/></span>
                                                <span <?php if($language == 1){ ?> style="opacity: 0.3;" <?php } ?> data-id="1"><img src="/images/flags/Russia.png"/></span>
                                                <span <?php if($language == 2){ ?> style="opacity: 0.3;" <?php } ?> data-id="2"><img src="/images/flags/United-Kingdom.png"/></span>
                                            </div>
                                            <div class="row">
                                                <label for="text3"><?php echo $languages[$language][2]; ?></label>
                                                <input type="text" id="last_name" name="last_name" value="" style="width:300px">
                                            </div>
                                            <div class="row">
                                                <label class="required" for="text"><?php echo $languages[$language][1]; ?></label>
                                                <input type="text" id="name" name="name" value="" style="width:300px">        </div>
                                            
                                            <div class="row">
                                                <label for="text3"><?php echo $languages[$language][3]; ?></label>
                                                <input type="text" id="group" name="group" value="" style="width:300px">            
                                            </div>
                                          
                                            <div class="span-11">
                                            <?php
                                            $this->widget('zii.widgets.jui.CJuiButton', array(
                                                'name' => 'begin-btn',
                                                'caption' => $languages[$language][5],
                                                'value' => 'begin',
                                                'htmlOptions' => array(
                                                    'style' => 'height:40px; font-size:18px; margin-left: 170px;',
                                                    'class' => ' button grey'
                                                ),
                                         
                                                    )
                                            );
                                            ?>
                                                </div>

                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
