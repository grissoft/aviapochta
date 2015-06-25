<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <a uacc="parcel-save" onclick="$('#form-parcel').submit();" class="btn btn-primary" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Сохранить"><i class="fa fa-save"></i></a>
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
        <form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-parcel">
          <ul id="order" class="nav nav-tabs nav-justified">
            <li class="active"><a href="#tab-main" data-toggle="tab"><?php echo $tab_parcel; ?></a></li>
            <li><a href="#tab-pack" data-toggle="tab"><?php echo $tab_pack; ?></a></li>
            <li><a href="#tab-history" data-toggle="tab">История</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-main">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-external-id">Доп. №</label>
                <div class="col-sm-10">
                          <?php if($parcel_id) { ?>
                          <div class="input-group">
                          <?php } ?>
                    <input id="input-external-id" type="text" name="external_id" value="<?php echo $external_id; ?>" placeholder="Доп. №" class="form-control" />
                          <?php if($parcel_id) { ?>
                          <span class="input-group-btn"><button uacc="save-external" onclick="customeSave('<?php echo $parcel_id; ?>', 'input[name=\'external_id\']', '#tab-main');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          </div>
                          <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-comment">Комментарий</label>
                <div class="col-sm-10">
                          <?php if($parcel_id) { ?>
                          <div class="input-group">
                          <?php } ?>
                    <textarea id="input-comment" name="comment" placeholder="Комментарий" class="form-control"><?php echo $comment; ?></textarea>
                          <?php if($parcel_id) { ?>
                          <span class="input-group-btn"><button uacc="save-comment" onclick="customeSave('<?php echo $parcel_id; ?>', 'textarea[name=\'comment\']', '#tab-main');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          </div>
                          <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label">Статус упаковки</label>
                <div class="col-sm-10">
                          <?php if($parcel_id) { ?>
                          <div class="input-group">
                          <?php } ?>
                  <select name="parcel_status_id" class="form-control">
                      <?php foreach($parcel_statuses as $parcel_status) { ?>
                      <?php if($parcel_status['parcel_status_id'] == $parcel_status_id) { ?>
                      <option selected="selected" value="<?php echo $parcel_status['parcel_status_id']; ?>"><?php echo $parcel_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $parcel_status['parcel_status_id']; ?>"><?php echo $parcel_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                  </select>
                          <?php if($parcel_id) { ?>
                          <span class="input-group-btn"><button uacc="save-parcel_status" onclick="customeSave('<?php echo $parcel_id; ?>', 'select[name=\'parcel_status_id\']', '#tab-main');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          </div>
                          <?php } ?>
                </div>
              </div>
            </div>
              
            <div class="tab-pane" id="tab-pack">
              <div class="table-responsive">
                <!--<div style="padding-bottom: 5px;" class="text-right">
                    
                    <div class="form-group col-sm-11">
                      <label class="col-sm-2 control-label" for="input-add-pack">Добавить упаковку</label>
                      <div class="col-sm-10">
                                <?php if($parcel_id) { ?>
                                <div class="input-group">
                                <?php } ?>
                                    <input id="input-add-pack" type="text" value="" placeholder="Подбор упаковки" class="form-control" />
                                <?php if($parcel_id) { ?>
                                <span class="input-group-btn"><button uacc="save-products" type="button" onclick="customeSave('<?php echo $parcel_id; ?>', '#table-packs input, #update-packs', '#table-packs');" id="button-pack-save" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                                </div>
                                <?php } ?>
                      </div>
                    </div>
                    
                </div>  -->
                  <input type="hidden" name="update_packs" id="update-packs" value="1">
                <table id="table-packs" class="table table-bordered">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $column_pack_id; ?></td>
                      <td class="text-left"><?php echo $column_customer; ?></td>
                      <td class="text-right">Дата</td>
                      <td class="text-left">Статус</td>
                 <!--     <td class="text-right"><?php echo $column_weight; ?></td>
                      <td class="text-right">Кол.мест</td>
                      <td class="text-right">Стоимость</td>-->
                      <td></td>
                    </tr>
                  </thead>
                  <tbody id="cart">
                    <?php $pack_row = 0; ?>
                    <?php if ($parcels) { ?>
                    <?php foreach ($parcels as $pack) { ?>
                    <tr id="pack-<?php echo $pack['pack_id']; ?>" style="background: #F0F0F0;">
                      <td class="text-left">
                        <?php echo $pack['pack_number']; ?>
                      </td>
                      <td class="text-left">
                          <?php echo $pack['customer']; ?>
                          </td>
                      <td class="text-right">
                          <?php echo $pack['date_added']; ?>
                      </td>
                      <td class="text-left">
                          <?php echo $pack['pack_status']; ?>
                          </td>
                     <!-- <td class="text-right"><?php echo $pack['weight_text']; ?></td>
                      <td class="text-right">
                          <?php echo $pack['point_total']; ?>
                      </td>
                      <td class="text-right">
                          <?php echo $pack['total']; ?>
                      </td>  -->
                      <td class="text-center"><!--<a uac<?php echo !$pack_row ? 'c' : ''; ?>="delete-pack" onclick="$('#pack-<?php echo $pack['pack_id']; ?>').remove();" data-toggle_="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>--></td>
                    </tr>
                    
                    <tr class="details">
                    <td colspan="8">
                        <table class="table table-bordered">
                        <thead>
                        <tr>
                            <td></td>
                            <td>Товар</td>
                            <td>Количество</td>
                            <td>Цена</td>
                            <td>Мест</td>
                            <td>Сумма</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pack['products'] as $product) { ?>
                            <tr>
                            <td></td>
                            <td> <?php echo $product['name']; ?></td>
                            <td> <?php echo $product['quantity']; ?></td>
                            <td> <?php echo $product['price']; ?></td>
                            <td> <?php echo $product['point']; ?></td>
                            <td> <?php echo $product['total']; ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        </table>
                    </td>
                    </tr>
                    <?php $pack_row++; ?>
                    <?php } ?>
                    
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
              
            <div class="tab-pane" id="tab-history">
             
            </div>
              
          </div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
function loadHistory(href) {
        if(href == undefined) {
                href = 'index.php?route=sale/parcel/history&parcel_id=<?php echo (int)$parcel_id; ?>&token=<?php echo $token; ?>';
        }
        $('#tab-history').load(href, function() {
                $('#tab-history a').click(function() {
                        var href = $(this).attr('href');
                        loadHistory(href);
                        return false;
                });
        });
}

loadHistory();

function customeSave(parcel_id, selector, refresh) {
        var a = $(selector).serialize();
	$.ajax({
		url: 'index.php?route=sale/parcel/customeupdate&token=<?php echo $token; ?>&parcel_id=' +  parcel_id,
                data: a,
                type: 'post',
                dataType: 'json',
                success: function(data) {
                        if($(refresh).length) {
                                $(refresh).load(document.location + ' ' + refresh + ' > *', function() { afterLoad(); });
                        }
                }
        });
}

    // Packs
    $('#input-add-pack').autocomplete({
            'source': function(request, response) {
                    $.ajax({
                            url: 'index.php?route=sale/pack/fullsearch&token=<?php echo $token; ?>&filter_free=1&filter_name=' +  encodeURIComponent(request),
                            dataType: 'json',			
                            success: function(json) {
                                    			

                                    response($.map(json, function(item) {
                                            return {
                                                    pack_id: item['pack_id'],
                                                    customer: item['customer'],						
                                                    pack_status: item['pack_status'],
                                                    date_added: item['date_added'],
                                                    point: item['point'],
                                                    weight: item['weight'],
                                                    total: item['total'],
                                                    label: '№' + item['pack_id'] + ' от ' + item['date_added'] + ' Клиент: ' + item['customer'],
                                                    value: item['pack_id']
                                            }
                                    }));
                            }
                    });
            },
            'select': function(item) {
                    $('#input-add-pack').val('');
                    addPack(item);

            }
    });
    
function afterLoad() {
    
    $('.date').datetimepicker({
            pickTime: false
    });

    $('.datetime').datetimepicker({
            pickDate: true,
            pickTime: true
    });

    $('.time').datetimepicker({
            pickDate: false
    });	
}

var pack_row = <?php echo $pack_row; ?>;
function addPack(pack) {
        $('#pack-' + pack['pack_id']).remove();
        html = '';
        html += '<tr id="pack-' + pack['pack_id'] + '">';
        html += '<td class="text-left">';
        html += '<input type="hidden" name="parcel_packs[' + pack_row + '][pack_id]" value="' + pack['pack_id'] + '" />';
        html += '<input type="hidden" name="parcel_packs[' + pack_row + '][parcel_pack_id]" value="" />';
        html += pack['pack_id'];
        html += '</td>';
        html += '<td class="text-left">';
        html += pack['customer'];
        html += '</td>';
        html += '<td class="text-right">';
        html += pack['date_added'];
        html += '</td>';
        html += '<td class="text-left">';
        html += pack['pack_status'];
        html += '</td>';
        html += '<td class="text-right">' + pack['weight'] + ' кг</td>';
        html += '<td class="text-right">';
        html += pack['point'];
        html += '</td>';
        html += '<td class="text-right">';
        html += pack['total'];
        html += '</td>';
        html += '<td class="text-center"><a uac="delete-pack" onclick="$(\'#pack-' + pack_row + '\').remove();" data-toggle_="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>';
        html += '</tr>';
            
        $('#table-packs tbody').append(html);
        pack_row++;

};

afterLoad();

//--></script></div>
<?php echo $footer; ?>