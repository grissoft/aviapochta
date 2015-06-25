<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <a uacc="pack-save" onclick="$('#form-pack').submit();" class="btn btn-primary" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Сохранить"><i class="fa fa-save"></i></a>
          <a href="<?php echo $cancel; ?>" class="btn btn-default" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_cancel; ?>"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pack">
  
          <ul id="order" class="nav nav-tabs nav-justified">
            <li class="active"><a href="#tab-data" data-toggle="tab">Данные</a></li>
            <li><a href="#tab-history" data-toggle="tab">История</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-data">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name">Самолет</label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="Самолет" id="input-name" class="form-control" />
                          
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="date-departure">Дата вылета</label>
                <div class="col-sm-4">
                <div class="input-group date">
                  <input type="text" name="date_departure" value="<?php echo $date_departure; ?>" placeholder="Дата вылета" data-date-format="DD-MM-YYYY" id="date-departure" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="date-arrival">Дата прилета</label>
                <div class="col-sm-4">
                  <div class="input-group date">
                      <input type="text" name="date_arrival" value="<?php echo $date_arrival; ?>" placeholder="Дата прилета" data-date-format="DD-MM-YYYY" id="date-arrival" class="form-control" />
                      <span class="input-group-btn">
                      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                      </span></div>
                  </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="date-doc">Подготовка документов</label>
                <div class="col-sm-4">
                  <div class="input-group datetime">
                      <input type="text" name="date_doc" value="<?php echo $date_doc; ?>" placeholder="Подготовка документов" data-date-format="DD-MM-YYYY H:mm" id="date-doc" class="form-control" />
                      <span class="input-group-btn">
                      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                      </span></div>
                  </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="date-gruz">Забор груза</label>
                <div class="col-sm-4">
                  <div class="input-group date">
                      <input type="text" name="date_gruz" value="<?php echo $date_gruz; ?>" placeholder="Забор груза" data-date-format="DD-MM-YYYY" id="date-gruz" class="form-control" />
                      <span class="input-group-btn">
                      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                      </span></div>
                  </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="input-air-status-id">Статус</label>
                <div class="col-sm-10">
                <select name="status_id" id="input-air-status-id" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($pack_statuses as $pack_status) { ?>
                  <?php if ($pack_status['pack_status_id'] == $air_status_id) { ?>
                  <option value="<?php echo $pack_status['pack_status_id']; ?>" selected="selected"><?php echo $pack_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $pack_status['pack_status_id']; ?>"><?php echo $pack_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                </div>
              </div>  
              
              
              
              <?php $fr = true; ?>
                <?php if ($parcels) { ?>
              <legend>Посылки</legend>
              <div class="table-responsive" id="parcels">
                <table class="table table-bordered table-hover">
              <thead>
                <tr>
                   <td class="text-right"></td>
                  <td class="text-right">№</td>
                  <td class="text-right">Доп. №</td>
                  <td class="text-left">Статус</td>
                  <td class="text-left">Дата добавления</td>
                  <td class="text-right">Итого</td>
                  <td class="text-left">Вес</td>
                  <td class="text-left">Мест</td>
                  <td class="text-left">Адрес</td>
                  
                </tr>
              </thead>
              <tbody>
                
                <?php foreach ($parcels as $parcel) { ?>
                <tr>
                  <td class="text-center"><button parcel-id="<?php echo $parcel['parcel_id']; ?>"  class="btn btn-box-tool" ><i class="fa fa-plus"></i></button></td>
                  <td class="text-right"><?php echo $parcel['parcel_number']; ?></td>
                  <td class="text-right"><?php echo $parcel['external_id']; ?></td>
                  <td class="text-left"><?php echo $parcel['status']; ?></td>
                  <td class="text-left"><?php echo $parcel['date_added']; ?></td>
                  <td class="text-right"><?php echo $parcel['total_text']; ?></td>
                  <td class="text-left"><?php echo $parcel['weight_text']; ?></td>
                  <td class="text-left"><?php echo $parcel['point']; ?></td>
                  <td class="text-left">
                  <?php echo $parcel['city'].", р-н ".$parcel['region'].", ул. ".$parcel['address_1']." ".$parcel['dom'].($parcel['kv'] ? ", кв.".$parcel['kv'] : ""); ?>
                  </td>
                </tr>
                <?php $fr = false; ?>
                <?php } ?>
              
              
              </tbody>
            </table>
              </div>
                 <?php } ?>
            </div>
              
          
              
            <div class="tab-pane" id="tab-history">
             
            </div>
              
          </div>
        </form>
      </div>
    </div>
  </div>


<script type="text/javascript"><!--
$('#parcels button').on('click', function(e) {
    
    e.preventDefault();
    tr = $(this).parent().parent();
    if (!$(this).attr('parcel-id')) return;
    
    $(this).find('i').toggleClass('fa-plus, fa-minus');
    if (tr.next().hasClass('details')) {
        if (tr.next().css('display')=='none') {
            tr.next().css('display','table-row');
        } else {
            tr.next().css('display','none');
        }
        return;
    }

    parcel_id = $(this).attr('parcel-id');
    $.ajax({
            url: 'index.php?route=sale/parcel/getparcel&token=<?php echo $token; ?>&parcel_id=' +  parcel_id,
            dataType: 'json',            
            success: function(json) {   
                    html = '<tr class="details">';
                    html += '<td colspan="10">';
                    
                    
                    for (i in json.parcels) {
                        
                    html += '<table class="table table-bordered">';
                    html += '<thead>';    
                    html += '<tr style="background: #F0F0F0;">';
                    html += '<td class="text-left">'+json.parcels[i].pack_number+'</td>';
                    html += '<td class="text-left">'+json.parcels[i].customer+'</td>';
                    html += '<td class="text-right">'+json.parcels[i].date_added+'</td>';
                    html += '<td class="text-left">'+json.parcels[i].pack_status+'</td>';
                    html += '</tr>';
                    html += '</thead>';
                    html += '</table>';
                    html += '    <table class="table table-bordered">';
                    html += '    <thead>';
                    html += '    <tr>';
                    html += '        <td></td>';
                    html += '        <td>Товар</td>';
                    html += '        <td>Количество</td>';
                    html += '        <td>Цена</td>';
                    html += '        <td>Мест</td>';
                    html += '        <td>Сумма</td>';
                    html += '    </tr>';
                    html += '    </thead>';
                    html += '    <tbody>';
                            for (j in json.parcels[i].products) {
                                html += '        <tr>';
                                html += '        <td></td>';
                                html += '        <td>'+json.parcels[i].products[j].name+'</td>';
                                html += '        <td>'+json.parcels[i].products[j].quantity+'</td>';
                                html += '        <td>'+json.parcels[i].products[j].price+'</td>';
                                html += '        <td>'+json.parcels[i].products[j].point+'</td>';
                                html += '        <td>'+json.parcels[i].products[j].total+'</td>';
                                html += '        </tr>';
                            }
                    }
                    html += '    </tbody>';
                    html += '    </table>';
                    html += '</td>';
                    html += '</tr>';
    
                 tr.after(html);    
            }
        });
    
});
//--></script> 
<script src="view/javascript/jquery/datetimepicker/moment-with-locales.js" type="text/javascript"></script>
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
    pickTime: false,
    language: 'ru',
    minDate: new Date()
});
$('.datetime').datetimepicker({
    language: 'ru',
    minDate: new Date()
});
//--></script>
</div>
<?php echo $footer; ?>
