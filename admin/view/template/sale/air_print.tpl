<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
<style type="text/css">
.container {
    font-size:16px;
    line-height: 30px;
    width: 1000px;
}
.p15_0 {
  padding: 15px 0;  
}
.box-blue {
    border: 1px solid #63B2DE;
    text-align: right;
    padding: 15px;
    font-weight: bold;
}
</style>
</head>
<body>
<div class="container">

    <div class="row p15_0" style="border-bottom:1px solid #BBBBBB;">
        <div class="col-sm-10"><div class="p15_0">Waybill (Сопроводительная накладная на партию)</div></div>
        <div class="col-sm-2">
            <div class="box-blue"><?php echo $air_info['name']; ?></div>
        </div>
    </div>
    <div class="row p15_0">
        <div class="col-sm-4">Internet seller (отправитель)</div>
        <div class="col-sm-8">On behalf of the mentioned in the invoice or bill seller</div>
    </div>
    <div class="row p15_0">
        <div class="col-sm-4">Сonsignee (Грузополучатель)</div>
        <div class="col-sm-8">LIMITED LIABILITY "LOGISTIC DELIVERY EXPRESS" BY ORDER TRADE ALLIANCE LLC UKRAINE, KOTOVSK , 50-LETIA OKTYABRYA ST,  BUILDING 216A</div>
    </div>
    <div class="row p15_0">
        <div class="col-sm-4">Shiper (Грузоотправитель)</div>
        <div class="col-sm-8">Mentioned in the air waybill for the order "TRADE ALLIANCE LLC"</div>
    </div>
    <div class="row p15_0" style="border-bottom:1px solid #BBBBBB; margin-bottom: 15px;">
        <div class="col-sm-4">Country of origin (Страна)</div>
        <div class="col-sm-8">China</div>
    </div>
    <?php foreach($air_info['parcels'] as $key => $parcel) { ?>
    <div class="row p15_0" style="border:1px solid #63B2DE; margin-bottom: 20px;">
    <div class="col-sm-12">
        <div class="row">
        <div class="col-sm-8">
            Reciever (Получатель)
        </div>
        <div class="col-sm-4 text-right">
            Position (Место)
        </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
        <div class="col-sm-8">
        <table class="table table-bordered">
            <tr>
                <td><?php echo $parcel['customer']; ?></td>
            </tr>
            <tr>  
                <td><?php echo $parcel['address']; ?></td>
            </tr>    
        </table>
        </div>
        <div class="col-sm-4">
            <div class="box-blue" style="padding: 25px 15px;"><?php echo $parcel['external_id']; ?></div>
        </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-12">
            Package information (Информация о посылках)<br>
            <table class="table table-bordered">
                <tr>
                    <td>Package number (Номер посылки)</td>
                    <td>Description (описание)</td>
                    <td>Weight (Вес), kgm</td>
                    <td>Price (Цена), CNY</td>
                </tr> 
                <?php foreach ($parcel['packs'] as $pack) {?>   
                <tr>
                    <td><?php echo $pack['pack_number']; ?></td>
                    <td><?php echo $pack['name']; ?></td>
                    <td><?php echo $pack['weight']; ?></td>
                    <td><?php echo $pack['price']; ?></td>
                </tr> 
                <?php } ?> 
            </table>
            </div>
        
        </div>
        
        </div>
    </div>
    <?php } //foreach ?>
    
    
    
    </div>
</div>
<script type="text/javascript">
window.print();
</script>
</body>
</html>