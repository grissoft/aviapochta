
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
.p15 {
  padding-top: 15px;
  padding-bottom:15px;
}
.table td {
    border:none !important;
}
.box-blue {
    border: 1px solid #63B2DE;
    text-align: right;
    padding: 15px;
    font-weight: bold;
}
.rounded {
    border-radius:10px;
}
.rotate90 {
    -moz-transform: rotate(90deg);  /* Firefox */
    -o-transform: rotate(90deg);  /* Opera */
    -webkit-transform: rotate(90deg);  /* Safari y Chrome */
    width:200px;
    margin-top:100px;
    margin-left:-50px;
}
</style>
</head>
<!--<?php print_r($parcel) ;?>-->
<body>
<div class="container">
               <!--<?php print_r($parcel); ?>-->
    <div class="row p15" style="border-bottom:1px dashed #63B2DE;">
        <div class="col-sm-4"><img src="view/image/logo.png" /></div>
        <div class="col-sm-3">
            <img src="index.php?route=sale/parcel/ean<?php echo '&text=' . $parcel['parcel_number'] . '&token=' . $token; ?>" />        
        </div>
        <div class="col-sm-5 bold italic">
        Express invoce (Экспересс накладная)<br>
        №<?php echo $parcel['parcel_number']; ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-11">
            <div class="row">
                <div class="col-sm-6 bold italic">
                    <div class="p15">Shipper`s (Грузоотправитель)</div>
                </div>
                <div class="col-sm-6  bold italic">
                <div class="p15">Recipient (получатель)</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 bold italic box-blue rounded">
                    Set in the invoice for the order "SHINE INTERNATIONAL LOGISTIC LIMITED" (Указан в накладной по распоряжению "SHINE INTERNATIONAL LOGISTIC LIMITED")
                </div>
                <div class="col-sm-6 bold italic">
                    Экспресс доставка Китай - Украина.<br>
                    Ваши грузы доставлены из надежных рук в надежные руки<br><br>
                    www.aviapochta.com.ua
                </div>
            </div>
            <div class="row">
                <div class="col-sm-8 bold italic">
                    <div class="p15">Information about posting (Информация о посылке)</div>
                </div>
                <div class="col-sm-4">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 box-blue rounded">
                      <div class="row p15"> 
                      <table class="table">
                        <tr>
                            <td>WEIGHT (ВЕС)</td>
                            <td><div class="box-blue"><?php echo $parcel['weight']; ?></div></td>
                            <td class="text-left">kg (кг)</td>
                            <td>QUANTITY (КОЛ-ВО)</td>
                            <td><div class="box-blue">
                            <?php $x=0; foreach($parcel['parcel_packs'] as $v) {$x+=$v['quantity'];} echo $x; ?>
                            </div></td>
                            <td class="text-left">pcs (штук)</td>
                        </tr>
                        <tr>
                            <td>VOLUME (ОБЪЕМ)</td>
                            <td><div class="box-blue">
                            <?php $x=0; foreach($parcel['parcel_packs'] as $v) {$x+=$v['volume'];} echo $x; ?>
                            </div></td>
                            <td class="text-left">m<sup>3</sup> (м<sup>3</sup>)</td>
                            <td>PRICE (ЦЕНА)</td>
                            <td><div class="box-blue"><?php echo (float)$parcel['total']; ?></div></td>
                            <td class="text-left">KYN (юань)</td>
                        </tr>
                      </table>
                      </div>
                
                      
                      <div class="row p15">
                        <div class="col-sm-4">
                            Description of goods <br>(Описание товара)
                        </div>
                        <div class="col-sm-8">
                            <div class="box-blue">
                            <?php $x=array(); foreach($parcel['parcel_packs'] as $v) {$x[]=$v['name'];} echo implode(", ",$x); ?>
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <div class="col-sm-1"><div class="rotate90"> <img src="index.php?route=sale/parcel/ean<?php echo '&text=' . $parcel['parcel_number'] . '&widthScale=3&token=' . $token; ?>" /> </div></div>
    </div>
  
    

</div>
<script type="text/javascript">
//window.print();
</script>
</body>
</html>