<?php echo $header; ?>
<div class="container">
<div class="title">Введите номер посылки для того, чтобы отследить свой груз</div>
<form id="form">
                        <div id="search">
                            <input class="input-lg" type="text" placeholder="Введите номер посылки с СМС" name="pack_id" id="pack-id" value="<?php echo $pack_id; ?>">
                            <input type="button"  value="Отследить" onclick="document.getElementById('form').submit();" class="btn btn-lg">
                        </div>
                    </form>
                    <?php echo $pack_data; ?>
</div>
<?php echo $footer; ?>