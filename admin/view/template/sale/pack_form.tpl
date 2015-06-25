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
      <?php if (isset($error['products'])) { ?>
                          <div class="text-danger" style="padding: 10px 0;"><?php echo $error['products']; ?></div>
                    <?php } ?> 
        <form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-pack">
            <input type="hidden" value="<?php echo $language_id; ?>" name="language_id">
            <input type="hidden" value="<?php echo $currency_id; ?>" name="currency_id">
            <input type="hidden" value="<?php echo $currency_code; ?>" name="currency_code">
            <input type="hidden" value="<?php echo $currency_value; ?>" name="currency_value">
          <ul id="order" class="nav nav-tabs nav-justified">
            <li class="active"><a href="#tab-customer" data-toggle="tab"><?php echo $tab_pack; ?></a></li>
            <!--<li><a href="#tab-cart" data-toggle="tab"><?php echo $tab_product; ?></a></li>-->
            <li><a href="#tab-history" data-toggle="tab">История</a></li>
          </ul>
          
          <div class="tab-content">
            <div class="tab-pane active" id="tab-customer">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <div class="col-sm-10">
                          
                  <input type="text" name="customer" value="<?php echo $customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                        
                  <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
                  <?php if (isset($error['customer_id'])) { ?>
                          <div class="text-danger"><?php echo $error['customer_id']; ?></div>
                    <?php } ?>  
                </div>
              </div>
                
              <div class="form-group">
                <label class="col-sm-2 control-label">Склад</label>
                <div class="col-sm-10">
                          <?php if($pack_id) { ?>
                          <div class="input-group">
                          <?php } ?>
                  <select name="sklad_id" class="form-control">
                      <?php foreach($sklads as $sklad) { ?>
                      <?php if($sklad['sklad_id'] == $sklad_id) { ?>
                      <option selected="selected" value="<?php echo $sklad['sklad_id']; ?>"><?php echo $sklad['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $sklad['sklad_id']; ?>"><?php echo $sklad['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                  </select>
                          <?php if($pack_id) { ?>
                          <span class="input-group-btn"><button uacc="save-sklad" onclick="customeSave('<?php echo $pack_id; ?>', 'select[name=\'sklad_id\']', '#tab-customer');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          </div>
                          <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label">Категория товаров</label>
                <div class="col-sm-10">
                          
                  <select name="category_group_id" class="form-control">
                      <?php foreach($category_groups as $category_group) { ?>
                      <?php if($category_group['category_group_id'] == $category_group_id) { ?>
                      <option selected="selected" value="<?php echo $category_group['category_group_id']; ?>"><?php echo $category_group['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $category_group['category_group_id']; ?>"><?php echo $category_group['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                  </select>
                          
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-external-id">Упаковочный № (1С)</label>
                <div class="col-sm-10">
                       
                    <input id="input-external-id" type="text" name="external_id" value="<?php echo $external_id; ?>" placeholder="Упаковочный № (1С)" class="form-control" />
                    <?php if (isset($error['external_id'])) { ?>
                          <div class="text-danger"><?php echo $error['external_id']; ?></div>
                    <?php } ?>     
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-parcel-id">№ посылки</label>
                <div class="col-sm-10">
                    <input id="input-parcel-id" type="text" readonly="readonly" name="parcel_id" value="<?php echo $parcel_id; ?>" placeholder="Упаковочный № (1С)" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-comment">Комментарий</label>
                <div class="col-sm-10">
                          
                    <textarea id="input-comment" name="comment" placeholder="Комментарий" class="form-control"><?php echo $comment; ?></textarea>
                          
                </div>
              </div>
            <legend>Товары</legend>
              <div class="table-responsive">
                <div style="padding-bottom: 5px;" class="text-right">
                      <button uacc="add-product" type="button" id="button-product-add" class="btn btn-success"><i class="fa fa-plus"></i></button>
                          <?php if($pack_id) { ?>
                      <button uacc="save-products" type="button" onclick="customeSave('<?php echo $pack_id; ?>', '#table-products input, #update-products', '#table-products');" id="button-product-save" class="btn btn-primary"><i class="fa fa-save"></i></button>
                          <?php } ?>
                </div>
                  <input type="hidden" name="update_products" id="update-products" value="1">
                  
                <table id="table-products" class="table table-bordered">
                  <thead>
                    <tr>
                      <td class="text-left required"><?php echo $column_product; ?></td>
                      <td class="text-right required"><?php echo $column_quantity; ?></td>
                      <td class="text-right required"><?php echo $column_price; ?></td>
                      <td class="text-right required"><?php echo $column_total; ?></td>
                      <td class="text-right required"><?php echo $column_weight; ?></td>
                      <td class="text-right required">Вес нетто</td>
                      <td class="text-right required">Объем</td>
                      <td class="text-right required">Кол.мест</td>
                      <td class="text-right">Ссылка</td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody id="cart">
                    <?php $product_row = 0; ?>
                    <?php if ($products) { ?>
                    <?php foreach ($products as $product) { ?>
                    <tr id="product-<?php echo $product_row; ?>">
                      <td class="text-left">
                          <div class="input-group">
                        <input type="text" name="products[<?php echo $product_row; ?>][name]" value="<?php echo $product['name']; ?>" class="form-control" />
                          <?php if($pack_id && 1==2) { ?>
                          <span class="input-group-btn"><button uac<?php echo !$product_row ? 'c' : ''; ?>="save-product-name" onclick="customeSave('<?php echo $pack_id; ?>', 'input[name=\'products[<?php echo $product_row; ?>][pack_product_id]\'], input[name=\'products[<?php echo $product_row; ?>][name]\']', '#table-products');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          <?php } ?>
                          </div>
                        <input type="hidden" name="products[<?php echo $product_row; ?>][pack_product_id]" value="<?php echo $product['pack_product_id']; ?>" />
                      </td>
                      <td class="text-right product-quantity">
                          <div class="input-group">
                          <input type="text" name="products[<?php echo $product_row; ?>][quantity]" value="<?php echo $product['quantity']; ?>" class="form-control  digitOnly" />
                          <?php if($pack_id && 1==2) { ?>
                        <span class="input-group-btn"><button uac<?php echo !$product_row ? 'c' : ''; ?>="save-product-q" onclick="customeSave('<?php echo $pack_id; ?>', 'input[name=\'products[<?php echo $product_row; ?>][pack_product_id]\'], input[name=\'products[<?php echo $product_row; ?>][quantity]\']', '#table-products');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          <?php } ?>
                          </div>
                          </td>
                      <td class="text-right product-price">
                          <div class="input-group">
                          <input type="text" name="products[<?php echo $product_row; ?>][price]" value="<?php echo $product['price']; ?>" class="form-control  doubleOnly" />
                          <?php if($pack_id && 1==2) { ?>
                        <span class="input-group-btn"><button uac<?php echo !$product_row ? 'c' : ''; ?>="save-product-price" onclick="customeSave('<?php echo $pack_id; ?>', 'input[name=\'products[<?php echo $product_row; ?>][pack_product_id]\'], input[name=\'products[<?php echo $product_row; ?>][price]\']', '#table-products');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          <?php } ?>
                          </div>
                      </td>
                      <td class="text-right product-total"><?php echo $product['total']; ?></td>
                      <td class="text-right product-weight">
                          <div class="input-group">
                          <input type="text" name="products[<?php echo $product_row; ?>][weight]" value="<?php echo $product['weight']; ?>" class="form-control item-weight  doubleOnly" />
                          <?php if($pack_id && 1==2) { ?>
                        <span class="input-group-btn"><button uac<?php echo !$product_row ? 'c' : ''; ?>="save-product-weight" onclick="customeSave('<?php echo $pack_id; ?>', 'input[name=\'products[<?php echo $product_row; ?>][pack_product_id]\'], input[name=\'products[<?php echo $product_row; ?>][weight]\']', '#table-products');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          <?php } ?>
                          </div>
                      </td>
                      <td class="text-right product-weight-netto">
                          <div class="input-group">
                          <input type="text" name="products[<?php echo $product_row; ?>][weight_netto]" value="<?php echo $product['weight_netto']; ?>" class="form-control item-weight-netto  doubleOnly" />
                          <?php if($pack_id && 1==2) { ?>
                        <span class="input-group-btn"><button uac<?php echo !$product_row ? 'c' : ''; ?>="save-product-weight-netto" onclick="customeSave('<?php echo $pack_id; ?>', 'input[name=\'products[<?php echo $product_row; ?>][pack_product_id]\'], input[name=\'products[<?php echo $product_row; ?>][weight_netto]\']', '#table-products');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          <?php } ?>
                          </div>
                      </td>
                      <td class="text-right">
                          <div class="input-group">
                          <input type="text" name="products[<?php echo $product_row; ?>][volume]" value="<?php echo $product['volume']; ?>" class="form-control doubleOnly" />
                          <?php if($pack_id && 1==2) { ?>
                        <span class="input-group-btn"><button uac<?php echo !$product_row ? 'c' : ''; ?>="save-product-volume" onclick="customeSave('<?php echo $pack_id; ?>', 'input[name=\'products[<?php echo $product_row; ?>][pack_product_id]\'], input[name=\'products[<?php echo $product_row; ?>][volume]\']', '#table-products');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          <?php } ?>
                          </div>
                      </td>
                      <td class="text-right">
                          <div class="input-group">
                          <input type="text" name="products[<?php echo $product_row; ?>][point]" value="<?php echo $product['point']; ?>" class="form-control digitOnly" />
                          <?php if($pack_id && 1==2) { ?>
                        <span class="input-group-btn"><button uac<?php echo !$product_row ? 'c' : ''; ?>="save-product-point" onclick="customeSave('<?php echo $pack_id; ?>', 'input[name=\'products[<?php echo $product_row; ?>][pack_product_id]\'], input[name=\'products[<?php echo $product_row; ?>][point]\']', '#table-products');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          <?php } ?>
                          </div>
                      </td>
                      <td class="text-right">
                          <div class="input-group">
                          <input type="text" name="products[<?php echo $product_row; ?>][url]" value="<?php echo $product['url']; ?>" class="form-control" />
                          <?php if($pack_id && 1==2) { ?>
                        <span class="input-group-btn"><button uac<?php echo !$product_row ? 'c' : ''; ?>="save-product-url" onclick="customeSave('<?php echo $pack_id; ?>', 'input[name=\'products[<?php echo $product_row; ?>][pack_product_id]\'], input[name=\'products[<?php echo $product_row; ?>][url]\']', '#table-products');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>
                          <?php } ?>
                          </div>
                      </td>
                      <td><a uac<?php echo !$product_row ? 'c' : ''; ?>="delete-product" onclick="$('#product-<?php echo $product_row; ?>').remove();" data-toggle_="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>
                    </tr>
                    <?php $product_row++; ?>
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
$('body').delegate('.item-weight', 'change', function() {
        var val  = parseFloat($(this).val());
        var $netto = $(this).parent().parent().parent().find('.item-weight-netto');
        var val2 = $netto.val();
        if(!parseFloat(val2)) {
                $netto.val(val * 0.95);
        }
});

function loadHistory(href) {
        if(href == undefined) {
                href = 'index.php?route=sale/pack/history&pack_id=<?php echo (int)$pack_id; ?>&token=<?php echo $token; ?>';
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

function customeSave(pack_id, selector, refresh) {
        var a = $(selector).serialize();
	$.ajax({
		url: 'index.php?route=sale/pack/customeupdate&token=<?php echo $token; ?>&pack_id=' +  pack_id,
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

function afterLoad() {
    // Customer
    $('input[name=\'customer\']').autocomplete({
            'source': function(request, response) {
                    $.ajax({
                            url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                            dataType: 'json',			
                            success: function(json) {
                                    json.unshift({
                                            customer_id: '0',
                                            customer_number: '0',
                                            external_id: '',
                                            customer_group_id: '',						
                                            name: 'Неизвестный',
                                            customer_group: '',
                                            firstname: '',
                                            lastname: '',
                                            email: '',
                                            telephone: '',
                                            fax: '',
                                            custom_field: [],
                                            address: []			
                                    });				

                                    response($.map(json, function(item) {
                                            return {
                                                    category: item['customer_group'],
                                                    label: item['customer_number'] +(item['external_id'] ? '/'+item['external_id'] : '')+': ' + item['name'],
                                                    customer: item['name'],
                                                    value: item['customer_id'],
                                                    customer_group_id: item['customer_group_id'],						
                                                    firstname: item['firstname'],
                                                    lastname: item['lastname'],
                                                    email: item['email'],
                                                    telephone: item['telephone'],
                                                    fax: item['fax'],
                                                    custom_field: item['custom_field'],
                                                    address: item['address']
                                            }
                                    }));
                            }
                    });
            },
            'select': function(item) {
                    $('#tab-customer input[name=\'customer\']').val(item['customer']);
                    $('#tab-customer input[name=\'customer_id\']').val(item['value']);

            }
    });
    
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

var product_row = <?php echo $product_row; ?>;
$('#button-product-add').on('click', function() {
        html = '';
        html +=     '<tr id="product-' + product_row + '">';
        html +=     '<td class="text-left">';
        html +=     '<div class="input-group">';
        html +=     '<input type="text" name="products[' + product_row + '][name]" class="form-control" />';
                          <?php if($pack_id && 1==2) { ?>
        html +=     '<span class="input-group-btn"><button uac="save-product-name" onclick="customeSave(\'<?php echo $pack_id; ?>\', \'input[name=\\\'products[' + product_row + '][pack_product_id]\\\'], input[name=\\\'products[' + product_row + '][name]\\\']\', \'#table-products\');" type="button" class="btn btn-info"><i class="fa fa-save"></i></button></span>';
                          <?php } ?>
        html +=     '</div>';
        html +=     '<input type="hidden" name="products[' + product_row + '][pack_product_id]" />';
        html +=     '</td>';
        html +=     '<td class="text-right product-quantity">';
        html +=     '<div class="input-group">';
        html +=     '<input type="text" name="products[' + product_row + '][quantity]" class="form-control digitOnly" />';
                          <?php if($pack_id && 1==2) { ?>
        html +=     '<span class="input-group-btn"><button uac="save-product-q" onclick="customeSave(\'<?php echo $pack_id; ?>\', \'input[name=\\\'products[' + product_row + '][pack_product_id]\\\'], input[name=\\\'products[' + product_row + '][quantity]\\\']\', \'#table-products\');" type="button" class="btn btn-info"><i class="fa fa-save"></i></button></span>';
                          <?php } ?>
        html +=     '</div>';
        html +=     '</td>';
        html +=     '<td class="text-right product-price">';
        html +=     '<div class="input-group">';
        html +=     '<input type="text" name="products[' + product_row + '][price]" class="form-control doubleOnly" />';
                          <?php if($pack_id && 1==2) { ?>
        html +=     '<span class="input-group-btn"><button uac="save-product-price" onclick="customeSave(\'<?php echo $pack_id; ?>\', \'input[name=\\\'products[' + product_row + '][pack_product_id]\\\'], input[name=\\\'products[' + product_row + '][price]\\\']\', \'#table-products\');" type="button" class="btn btn-info"><i class="fa fa-save"></i></button></span>';
                          <?php } ?>
        html +=     '</div>';
        html +=     '</td>';
        html +=     '<td class="text-right product-total"></td>';
        html +=     '<td class="text-right product-weight">';
        html +=     '<div class="input-group">';
        html +=     '<input type="text" name="products[' + product_row + '][weight]" class="form-control item-weight doubleOnly" />';
                          <?php if($pack_id && 1==2) { ?>
        html +=     '<span class="input-group-btn"><button uac="save-product-weight" onclick="customeSave(\'<?php echo $pack_id; ?>\', \'input[name=\\\'products[' + product_row + '][pack_product_id]\\\'], input[name=\\\'products[' + product_row + '][weight]\\\']\', \'#table-products\');" type="button" class="btn btn-info"><i class="fa fa-save"></i></button></span>';
                          <?php } ?>
        html +=     '</div>';
        html +=     '</td>';
        
        html +=     '<td class="text-right product-weight-netto">';
        html +=     '<div class="input-group">';
        html +=     '<input type="text" name="products[' + product_row + '][weight_netto]" class="form-control item-weight-netto doubleOnly" />';
                          <?php if($pack_id && 1==2) { ?>
        html +=     '<span class="input-group-btn"><button uac="save-product-weight-netto" onclick="customeSave(\'<?php echo $pack_id; ?>\', \'input[name=\\\'products[' + product_row + '][pack_product_id]\\\'], input[name=\\\'products[' + product_row + '][weight_netto]\\\']\', \'#table-products\');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>';
                          <?php } ?>
        html +=     '</div>';
        html +=     '</td>';
        html +=     '<td class="text-right">';
        html +=     '<div class="input-group">';
        html +=     '<input type="text" name="products[' + product_row + '][volume]" class="form-control doubleOnly" />';
                          <?php if($pack_id && 1==2) { ?>
        html +=     '<span class="input-group-btn"><button uac="save-product-volume" onclick="customeSave(\'<?php echo $pack_id; ?>\', \'input[name=\\\'products[' + product_row + '][pack_product_id]\\\'], input[name=\\\'products[' + product_row + '][volume]\\\']\', \'#table-products\');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>';
                          <?php } ?>
        html +=     '</div>';
        html +=     '</td>';
        html +=     '<td class="text-right">';
        html +=     '<div class="input-group">';
        html +=     '<input type="text" name="products[' + product_row + '][point]" class="form-control digitOnly" />';
                          <?php if($pack_id && 1==2) { ?>
        html +=     '<span class="input-group-btn"><button uac="save-product-point" onclick="customeSave(\'<?php echo $pack_id; ?>\', \'input[name=\\\'products[' + product_row + '][pack_product_id]\\\'], input[name=\\\'products[' + product_row + '][point]\\\']\', \'#table-products\');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>';
                          <?php } ?>
        html +=     '</div>';
        html +=     '</td>';
        html +=     '<td class="text-right">';
        html +=     '<div class="input-group">';
        html +=     '<input type="text" name="products[' + product_row + '][url]" class="form-control" />';
                          <?php if($pack_id && 1==2) { ?>
        html +=     '<span class="input-group-btn"><button uac="save-product-url" onclick="customeSave(\'<?php echo $pack_id; ?>\', \'input[name=\\\'products[' + product_row + '][pack_product_id]\\\'], input[name=\\\'products[' + product_row + '][url]\\\']\', \'#table-products\');" type="button" class="btn btn-primary"><i class="fa fa-save"></i></button></span>';
                          <?php } ?>
        html +=     '</div>';
        html +=     '</td>';
        
        html +=     '<td><a uac="delete-product" onclick="$(\'#product-' + product_row + '\').remove();" data-toggle_="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a></td>';
        html +=     '</tr>';
        $('#table-products tbody').append(html);
        product_row++;

});

afterLoad();
calc();
$('#table-products').on('keydown', 'input, select, textarea', function(e) {
    var self = $(this)
      , form = self.parents('#table-products')
      , focusable
      , next
      ;
    if (e.keyCode == 13) {
        calc();
        focusable = form.find('input').filter(':visible');
        next = focusable.eq(focusable.index(this)+1);
        if (next.length) {
            next.focus().select();
        } else {
            $('#button-product-add').click();
            focusable = form.find('input').filter(':visible');
            next = focusable.eq(focusable.index(this)+1);
            next.focus().select();
        }
        return false;
    }
});
$('#table-products').on('keyup', 'input, select, textarea', function(e) {
    calc();
});
function calc(){
    
    $('#table-products tr').each(function(index){
        if ($(this).find('input').length) {
            quantity = $(this).find('.product-quantity input').val();
            price = $(this).find('.product-price input').val();
            weight = $(this).find('.product-weight input').val();
            price = price.replace(",",".");         
            weight = weight.replace(",",".")
            $(this).find('.product-price input').val(price);
            $(this).find('.product-weight input').val(weight);
            $(this).find('.product-total').html((quantity*price).toFixed(2));
            $(this).find('.product-weight-netto').html((weight*0.95).toFixed(2));
        }
    });
}
//--></script></div>

<?php echo $footer; ?>