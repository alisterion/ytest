
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
                            <div style="width: 400px; text-align: center; padding-left: 70px; padding-top: 20px;"><img id="img_result" src="/site/getjpg"/></div>
                            <div style="padding-top: 20px; background: url(/images/info.png) left center no-repeat; padding-left: 35px; padding-top: 20px;"><a href="/site/userinfo/<?php echo $user['id']; ?>"> <?php echo $languages[$language][9]; ?></a></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        var interval = window.setInterval(updateImage, 5000);
        function updateImage() {
            $("#img_result").attr("src", "");
            $("#img_result").attr("src", "/site/getjpg");
        }
    });
</script>