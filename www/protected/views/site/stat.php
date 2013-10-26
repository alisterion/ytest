<?php //var_dump($users); 
$languagesTitle = array(
 '0'=>'Українська',  
 '1'=>'Російська',  
 '2'=>'Ангілйська',  
);

?>

<div id="stat_content">
<table class="nice_table" id="stat_table" width="600">
<tbody>
<tr class="main-row">
    <th colspan="6"> Статистика на дату:<input id ="calendar" value="<?php echo $date; ?>" type="text"></th>
</tr>
<tr class="main-row">
<td>ПРІЗВИЩЕ</td>
<td>ІМ'Я</td>
<td>ГРУПА</td>
<td>БАЛИ</td>
<td>МОДУЛЬ/ТЕМА</td>
<td>МОВА</td>
</tr>

<?php foreach ($users as $value) {
    
?>

<tr title="<?php echo $value['last_name']; ?> <?php echo $value['name']; ?> (<?php echo $value['group']; ?>) [<?php echo $value['points']; ?> з <?php echo $value['max_points']; ?>]" >
<td><a href="<?php echo '/site/userinfo/'.$value['id']; ?>" ><?php echo $value['last_name']; ?></a></td>
<td><a href="<?php echo '/site/userinfo/'.$value['id']; ?>"><?php echo $value['name']; ?></a></td>
<td><?php echo $value['group']; ?></td>
<td><?php echo $value['points']; ?> з <?php echo $value['max_points']; ?></td>
<td><?php if(!empty($value['module_number'])) { ?>  Модуль № <?php echo $value['module_number']; }  ?><?php if(!empty($value['them_num'])) { ?>  Тема № <?php echo $value['them_num']; }  ?></td>
<td><?php echo $languagesTitle[$value['language']] ?></td>

</tr>

<?php } ?>
</tbody>
</table>
</div>