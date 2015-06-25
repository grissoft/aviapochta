<h1>Результаты поиска</h1>
<?php if($find) { ?>
<?php if($pack_histories) { ?>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <td>Дата</td>
            <td>Статус</td>
            <td>Вес</td>
             <td>Объем</td>
            <td>Стоимсоть</td>
            <td>Кол.-во мест</td>
        </tr>
    </thead>
    <tbody>
<?php foreach($pack_histories as $pack_history) { ?>
        <tr>
            <td><?php echo $pack_history['date_added']; ?></td>
            <td><?php echo $pack_history['pack_status']; ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        
<?php } ?>
    </tbody>
</table>
<?php } ?>
<?php } else { ?>

<div style="text-align:center;">Нет данных</div>

<?php } ?>