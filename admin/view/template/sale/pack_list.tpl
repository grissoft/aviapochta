<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a uacc="sale-pl-b-1" href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-external-id"><?php echo $entry_pack_id; ?></label>
                <input type="text" name="filter_external_id" value="<?php echo $filter_external_id; ?>" placeholder="<?php echo $entry_pack_id; ?>" id="input-pack-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-pack-status-id"><?php echo $entry_pack_status; ?></label>
                <select name="filter_pack_status_id" id="input-pack-status-id" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($pack_statuses as $pack_status) { ?>
                  <?php if ($pack_status['pack_status_id'] == $filter_pack_status_id) { ?>
                  <option value="<?php echo $pack_status['pack_status_id']; ?>" selected="selected"><?php echo $pack_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $pack_status['pack_status_id']; ?>"><?php echo $pack_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
                <input type="text" name="filter_total" value="<?php echo $filter_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-date-modified"><?php echo $entry_total_weight; ?></label>
                <input type="text" name="filter_total_weight" value="<?php echo $filter_total_weight; ?>" placeholder="<?php echo $entry_total_weight; ?>" id="input-total-weight" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form method="post" enctype="multipart/form-data" id="form-pack">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                  <tr>
                      <td colspan="5" class="text-left selected-total"></td>
                      <td colspan="3" class="text-right">
                          С отмеченными:
                      </td>
                      <td colspan="3">
                          <a onclick="createParcel(); return false;" class="btn btn-primary parcel">Сформировать посылку</a>
                      </td>
                  </tr>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);$('input[name^=\'selected\']:first').trigger('change');" /></td>
                  <td class="text-right"></td>
                  <td class="text-right"><?php if ($sort == 'p.pack_number') { ?>
                    <a href="<?php echo $sort_pack; ?>" class="<?php echo strtolower($order); ?>">№</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_pack; ?>">№</a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.external_id') { ?>
                    <a href="<?php echo $sort_external_id; ?>" class="<?php echo strtolower($order); ?>">Упаковочный №</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_external_id; ?>">Упаковочный №</a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'customer') { ?>
                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.weight') { ?>
                    <a href="<?php echo $sort_total_weight; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_weight; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total_weight; ?>"><?php echo $column_total_weight; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.point') { ?>
                    <a href="<?php echo $sort_total_point; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total_point; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total_point; ?>"><?php echo $column_total_point; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php $fr = true; ?>
                <?php if ($packs) { ?>
                <?php foreach ($packs as $pack) { ?>
                <tr class="<?php if ($status_id != $pack['pack_status_id']) {?>disabled<?php } ?><?php if ($pack['partner_id']>0) {?> partner<?php } ?>">
                  <td class="text-center">
                        <?php if (in_array($pack['pack_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $pack['pack_id']; ?>" checked="checked" total="<?php echo $pack['total']; ?>" point="<?php echo $pack['point']; ?>" weight="<?php echo $pack['weight']; ?>" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $pack['pack_id']; ?>" total="<?php echo (float)$pack['total_ost'] ? $pack['total_ost'] : $pack['total']; ?>" point="<?php echo $pack['point']; ?>" weight="<?php echo $pack['weight']; ?>" />
                        <?php } ?>
                  </td>
                  <td class="text-right"><button pack-id="<?php echo $pack['pack_id']; ?>"  class="btn btn-box-tool" ><i class="fa fa-plus"></i></button></td>
                  <td class="text-right"><?php echo $pack['pack_number']; ?></td>
                  <td class="text-right"><?php echo $pack['external_id']; ?></td>
                  <td class="text-left"><?php echo $pack['customer']; ?></td>
                  <td class="text-left"><?php echo $pack['status']; ?></td>
                  <td class="text-right">
                  <?php if ((float)$pack['total_ost'] && $pack['total_ost']!=$pack['total']) { ?>
                    <?php echo $pack['total_ost_text']; ?><br>
                    <small style="color:#C4C2C2;"><?php echo $pack['total_text']; ?></small>
                  <?php } else { ?>
                    <?php echo $pack['total_text']; ?>
                  <?php } ?>
                  </td>
                  <td class="text-left"><?php echo $pack['date_added']; ?></td>
                  <td class="text-left"><?php echo $pack['weight_text']; ?></td>
                  <td class="text-left"><?php echo $pack['point']; ?></td>
                  <td class="text-right" style="white-space: nowrap;">
                      <!--<a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-2" href="<?php echo $pack['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info disabled"><i class="fa fa-eye"></i></a>-->
                      <a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-3" href="<?php echo $pack['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                      <a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-4" href="<?php echo $pack['delete']; ?>" id="button-delete<?php echo $pack['pack_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                  </td>
                </tr>
                <?php $fr = false; ?>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                  <tr>
                      <td colspan="5" class="text-left selected-total"></td>
                      <td colspan="2" class="text-right">
                          С отмеченными:
                      </td>
                      <td colspan="3">
                          <a onclick="createParcel(); return false;" class="btn btn-primary parcel">Сформировать посылку</a>
                      </td>
                  </tr>
              </tfoot>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
    <input type="hidden" value="<?php echo $create_parcel; ?>" id="create_parcel">
<script type="text/javascript"><!--
        function createParcel() {
                if($('input[name^=\'selected\']:checked').length) {
                        total = $('.selected-total').attr('data-total');
                        
                        if (total>150) {
                            if (!confirm('Посылка превышает invoice. Продолжить?')) return;
                        }    
                        
                        $('#form-pack').attr('action', $('#create_parcel').val());
                        $('#form-pack').submit();
                        
                }
        }
//--></script>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=sale/pack&token=<?php echo $token; ?>';
	
//	var filter_pack_id = $('input[name=\'filter_pack_id\']').val();
	
//	if (filter_pack_id) {
//		url += '&filter_pack_id=' + encodeURIComponent(filter_pack_id);
//	}
	
	var filter_external_id = $('input[name=\'filter_external_id\']').val();
	
	if (filter_external_id) {
		url += '&filter_external_id=' + encodeURIComponent(filter_external_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_pack_status_id = $('select[name=\'filter_pack_status_id\']').val();
	
	if (filter_pack_status_id != '*') {
		url += '&filter_pack_status_id=' + encodeURIComponent(filter_pack_status_id);
	}	

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	var filter_total_weight = $('input[name=\'filter_total_weight\']').val();
	
	if (filter_total_weight) {
		url += '&filter_total_weight=' + encodeURIComponent(filter_total_weight);
	}
				
	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
                                json.unshift({
					customer_id: '0',
					name: 'Неизвестный'
                                });
                                    
				response($.map(json, function(item) {
					return {
                                                category: item['customer_group'],
						label: item['customer_id'] + ': ' + item['name'],
						value: item['customer_id'],
						name:  item['name']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['name']);
	}	
});
//--></script> 
<script type="text/javascript"><!--
function calc() {
    var total = 0;
        var weight = 0;
        var point = 0;
        var c = 0;
        $('input[name^=\'selected\']:checked').each(function() {
                pack_id = $(this).val();
                if ($('.pack-id-'+pack_id).length) {
                    $('.pack-id-'+pack_id+':checked').each(function() {
                            tr = $(this).parent().parent();
                            quantity = tr.find('.quantity').find('input').val();
                            max = parseFloat(tr.find('.quantity').find('input').attr('data-max'));
                            if (quantity>max) {
                                quantity = max;
                                tr.find('.quantity').find('input').val(max);
                            }
                            price = tr.find('.price').text(); 
                            total += price*quantity; 
                            
                            tr.find('.total').html(price*quantity);
                            //total += parseFloat($(this).attr('total'));
                            weight += parseFloat($(this).attr('weight'));
                            point += parseInt($(this).attr('point'));
                    });
                } else {
                    total += parseFloat($(this).attr('total'));
                    weight += parseFloat($(this).attr('weight'));
                    point += parseInt($(this).attr('point'));
                }
                c++;
        });
        if(total || weight || point) {
                $('.selected-total').html('<div>Отмечено ' + c + ' упаковок. <br>Вес: ' + weight + ' кг. Кол-во мест: ' + point + '. Стоимость: $' + total + '</div>');
        } else {
                $('.selected-total').html('');
        }
        $('.selected-total').attr('data-total',total);
        if (total>150) {
            //$('.parcel').addClass('disabled');
            $('.selected-total div').addClass('text-red')
        } else {
            //$('.parcel').removeClass('disabled');
            $('.selected-total div').removeClass('text-red');
        }
}
$('#form-pack').on('change', 'input[name^=\'selected\'], input[name^=\'pack\']', function() {
   tr = $(this).parent().parent();
   if (tr.next().hasClass('details')) {
         checked = tr.find('input[type="checkbox"]').is(':checked');
         tr.next().find('input[type="checkbox"]').prop('checked',checked);
         
   }
   calc(); 
});
$('#form-pack').on('change keyup', 'input[name^=\'pack\']', function() {
    if($(this).parent().parent().find('input[type="checkbox"]:checked').length) {
        $(this).parents('.details').prev().find('input[type="checkbox"]').prop('checked',true);
    } else {
        $(this).parents('.details').prev().find('input[type="checkbox"]').prop('checked',false);
    }
   //tr = $(this).parents('.details').prev();
//   if (tr.next().hasClass('details')) {
//         checked = tr.find('input[type="checkbox"]').is(':checked');
//         tr.next().find('input[type="checkbox"]').prop('checked',checked);
//         
//   }
   calc(); 
});

$('input[name^=\'selected\']:first').trigger('change');

$('a[id^=\'button-delete\']').on('click', function(e) {
	e.preventDefault();
	
	if (confirm('<?php echo $text_confirm; ?>')) {
		location = $(this).attr('href');
	}
});

$('#form-pack button').on('click', function(e) {
    e.preventDefault();
    //if (e.target.nodeName=='INPUT') return;
    tr = $(this).parent().parent();
    if (!$(this).attr('pack-id')) return;
    
    $(this).find('i').toggleClass('fa-plus, fa-minus');
    if (tr.next().hasClass('details')) {
        if (tr.next().css('display')=='none') {
            tr.next().css('display','table-row');
        } else {
            tr.next().css('display','none');
        }
        return;
    }
    //if (!tr.find('input[type="checkbox"]').attr('checked')) tr.find('input[type="checkbox"]').click();
    checked = tr.find('input[type="checkbox"]').is(':checked');
    console.log(checked);
    pack_id = $(this).attr('pack-id');
    $.ajax({
            url: 'index.php?route=sale/pack/getpack&token=<?php echo $token; ?>&pack_id=' +  pack_id,
            dataType: 'json',            
            success: function(json) {   
                html = '<tr class="details"><td colspan="11">';
                html += '<table class="table table-bordered ">';
                html += '<thead>';
                html += '<tr>';
                html += '<td></td>';
                html += '<td>Товар</td>';
                html += '<td>Количество <small>текущее/осталось/всего</small></td>';
                html += '<td>Цена</td>';
                html += '<td>Мест</td>';
                html += '<td>Сумма</td>';
                html += '</tr>';
                html += '</thead>';
                html += '<tbody>';
                for (i in json.products) {
                    html += '<tr>';
                    html += '<td><input type="checkbox" class="pack-id-'+pack_id+'" name="pack['+pack_id+'][products]['+json.products[i].pack_product_id+']" weight="'+json.products[i].weight+'" point="'+json.products[i].point+'" total="'+json.products[i].quantity*json.products[i].price+'" value="'+json.products[i].pack_product_id+'"' + (checked ? ' checked="checked"' : '')+' /></td>';
                    html += '<td>'+json.products[i].name+'</td>';
                    html += '<td class="quantity"><input type="text" data-max="'+json.products[i].quantity_ost+'"  name="pack['+pack_id+'][quantity]['+json.products[i].pack_product_id+']" value="'+json.products[i].quantity_ost+'" /> / '+json.products[i].quantity_ost+' / '+json.products[i].quantity+'</td>';
                    html += '<td class="price">'+json.products[i].price+'</td>';
                    html += '<td>'+json.products[i].point+'</td>';
                    html += '<td class="total">'+json.products[i].quantity*json.products[i].price+'</td>';
                    html += '</tr>';
                }
                
                html += '</tbody>';
                html += '</table>';
                html += '</td></tr>';
                tr.after(html);
            }
        });
    
});
//--></script> 
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>