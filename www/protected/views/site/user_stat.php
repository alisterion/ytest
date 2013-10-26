
<div>
    
    
</div>


<div class="span-13 last">
    <div id="yw0" class="portlet">
        <div class="portlet-decoration">
            <div class="portlet-title"><?php echo $languages[$language][8]; ?></div>
        </div>
        <div class="portlet-content">
            <div class="chart3">
                <div>
                    <div class="text">
                        <div style="font-size: 16px;" id="gvChartDiv1" class="gvChart" style="position: relative;">
                           
                            <span ><?php echo $user['name']." ".$user['last_name']." [".$languages[$language][3].":".$user['group']."]"; ?></span>
                            <div style="width: 400px; text-align: center; padding-top: 20px;"><?php echo $languages[$language][6]." ".$languages[$language][7] ?>:
                                <span style="color: #CC0000; font-weight: bold;"><?php echo $user['points']; ?></span> <span style="color: #76C376; font-weight: bold;">(<?php echo $max_quest; ?>)</span></div>
                            <div style="padding-top: 20px; background: url(/images/info.png) left center no-repeat; padding-left: 35px; padding-top: 20px;"><a href="/site/userinfo/<?php echo $user['id']; ?>"> <?php echo $languages[$language][9];?></a></div>
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>