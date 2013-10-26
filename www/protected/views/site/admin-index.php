

<div style="padding-left: 50%; width: 100%;">
    <div id="flags">
        <span <?php if($language == 0){ ?> style="opacity: 0.3;" <?php } ?> data-id="0"><img src="/images/flags/Ukraine.png"/></span>
        <span <?php if($language == 1){ ?> style="opacity: 0.3;" <?php } ?> data-id="1"><img src="/images/flags/Russia.png"/></span>
        <span <?php if($language == 2){ ?> style="opacity: 0.3;" <?php } ?> data-id="2"><img src="/images/flags/United-Kingdom.png"/></span>
    </div>
</div>

<div id ="buttons" style="padding-top: 10px; padding-bottom: 30px;">
<a id="add_modul" href="#">Створити модуль</a>
<a id="load_theam_from_file" href="/site/LoadFromFile">Завантажити тему</a>
<a href="#"></a>
    
</div>

<style>
    .connectedSortable { list-style-type: none; margin: 0; padding: 0 0 2.5em; float: left; margin-right: 10px; }
    .connectedSortable li  {text-transform: lowercase; margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; width: 250px; cursor: pointer; }
</style>




<div>
    <ul id="sortable1" class="connectedSortable" style="float: left;">
        <?php foreach ($theams as $value) { ?>
            <li title="<?php echo $value['title']; ?>" id="<?php echo $value['id']; ?>" class="ui-state-default">ТЕМА №<?php echo $value['theam_visible_num']; ?></li>
        <?php } ?>
    </ul>
</div>


<div id="accordion" style="width: 450px;margin-left: 450px;">
    <?php foreach ($modules as $value) { ?> 
    <h3 rel="<?php echo $value['id']; ?>"  class="module_set_test"><?php echo $value['title']; ?></h3>
        <div  style="min-height: 300px;">
            <ul rel = "<?php echo $value['id']; ?>" class="connectedSortable" style="min-height: 300px; min-width: 300px; font-size: 10px !important;">
                <?php
                $theamsArray = array();
                foreach ($theamsInModule as $val) {
                    if ($value['id'] == $val['modul_id']) {
                        $theamsArray = explode(',', $val['theams_ids']);
                        break;
                    }
                }
                ?>
                <?php foreach ($theams as $value) {
                    if (in_array($value['id'], $theamsArray)) {
                        ?>
                <li title="<?php echo $value['title']; ?>" id="<?php echo $value['id']; ?>" class="ui-state-default">ТЕМА №<?php echo $value['theam_visible_num']; ?><span class="delete_theam_from_module" style="width: 15px; cursor: pointer; float: right;">X</span></li>
        <?php }
    } ?>
            </ul>
            <p></p>
        </div>

<?php } ?>
</div>



<div style="display: none;" id="errors_display">
    <div>
        Час: <input id="time" style="width: 15px;" type="text" id="" value="1" />
        Кількість питань: <input id="quest_count" style="width: 15px;" type="text" id="" value="5" />
        <input type="button" id="set_test_theam" value="Тестування" />
    </div>
    <div>
        <input type="button" id="delete_theam_bd" value="Видалити тему з БД" /> 
    </div>
    <div id="additional">
        <input type="hidden" value="" id="visible_theam_id" />
        <input type="hidden" value="" id="theam_id" />
    </div>
</div>


<div style="display: none;" id="set_module_div">
    <div>
        Час: <input id="time" style="width: 15px;" type="text" id="" value="1" />
        Кількість питань: <input id="quest_count" style="width: 15px;" type="text" id="" value="5" />
        <input type="button" id="set_test_module" value="Тестування" />
    </div>
    <div>
        <input type="button" id="delete_module_bd" value="Видалити модуль з БД" /> 
    </div>
    <div id="additional">
        <input type="hidden" value="" id="module_id" />
    </div>
</div>



