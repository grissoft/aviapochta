<h1>Результаты поиска</h1>
<?php if($find) { ?>
<div>Категория: <?php echo $category_group; ?></div>
<div>Вес: <?php echo $weight; ?> кг</div>
<div>Количество мест: <?php echo $point; ?></div>
<?php if($last_comment) { ?>
<div><?php echo $last_comment; ?></div>
<?php } ?>
<?php if($pack_histories) { ?>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <td>Дата</td>
            <td>Статус</td>
        </tr>
    </thead>
    <tbody>
<?php foreach($pack_histories as $pack_history) { ?>
        <tr>
            <td><?php echo $pack_history['date_added']; ?></td>
            <td><?php echo $pack_history['pack_status']; ?></td>
        </tr>
        
<?php } ?>
    </tbody>
</table>
<?php } ?>
<?php } else { ?>

<div style="text-align:center;">Нет данных</div>

<?php } ?>