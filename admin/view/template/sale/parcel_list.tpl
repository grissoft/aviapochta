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
                <label class="control-label" for="input-external-id"><?php echo $entry_parcel_id; ?></label>
                <input type="text" name="filter_external_id" value="<?php echo $filter_external_id; ?>" placeholder="<?php echo $entry_parcel_id; ?>" id="input-parcel-id" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-pack-status-id"><?php echo $entry_parcel_status; ?></label>
                <select name="filter_parcel_status_id" id="input-pack-status-id" class="form-control">
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
        <form method="post" enctype="multipart/form-data" id="form-parcel">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
            
              <thead>
              <tr>
            <td colspan="4">
            <a onclick="mergeParcel();" data-toggle="tooltip" title="Объеденить" class="btn btn-primary"><i class="fa fa-th-large"></i></a>
            </td>
                      <td colspan="6">
                        <div class="col-sm-7">
                          <div class="form-group">
                            <select name="air_id" class="form-control">
                            <?php foreach ($airs as $air) { ?>
                            <option value="<?php echo $air['air_id']; ?>"><?php echo $air['name']; ?></option>
                            <?php } ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <div class="form-group">
                            <a onclick="createAir(); return false;" class="btn btn-primary parcel" style="width: 100%;">В самолет</a>
                          </div>
                        </div>  
                      <!--</div> -->
                      </td>
                  </tr>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);$('input[name^=\'selected\']:first').trigger('change');" /></td>
                   <td class="text-right"></td>
                  <td class="text-right"><?php if ($sort == 'p.parcel_id') { ?>
                    <a href="<?php echo $sort_parcel; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_parcel_id; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_parcel; ?>"><?php echo $column_parcel_id; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.external_id') { ?>
                    <a href="<?php echo $sort_external_id; ?>" class="<?php echo strtolower($order); ?>">Доп. №</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_external_id; ?>">Доп. №</a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.total') { ?>
                    <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
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
                <?php if ($parcels) { ?>
                <?php foreach ($parcels as $parcel) { ?>
                <tr class="<?php if ($status_id != $parcel['parcel_status_id'] || $parcel['use']) {?>disabled<?php } ?><?php if ($parcel['partner_id']>0) {?> partner<?php } ?>">
                  <td class="text-center"><?php if (in_array($parcel['parcel_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $parcel['parcel_id']; ?>" checked="checked" total="<?php echo $parcel['total']; ?>" point="<?php echo $parcel['point']; ?>" weight="<?php echo $parcel['weight']; ?>" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $parcel['parcel_id']; ?>" total="<?php echo $parcel['total']; ?>" point="<?php echo $parcel['point']; ?>" weight="<?php echo $parcel['weight']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="text-center"><button parcel-id="<?php echo $parcel['parcel_id']; ?>"  class="btn btn-box-tool" ><i class="fa fa-plus"></i></button></td>
                  <td class="text-right"><?php echo $parcel['parcel_number']; ?></td>
                  <td class="text-right"><?php echo $parcel['external_id']; ?></td>
                  <td class="text-left"><?php echo $parcel['status']; ?></td>
                  <td class="text-left"><?php echo $parcel['date_added']; ?></td>
                  <td class="text-right"><?php echo $parcel['total_text']; ?></td>
                  <td class="text-left"><?php echo $parcel['weight_text']; ?></td>
                  <td class="text-left"><?php echo $parcel['point']; ?></td>
                  <td class="text-right">
                      <!--<a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-2" href="<?php echo $parcel['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info disabled"><i class="fa fa-eye"></i></a>-->
                      <a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-3" href="<?php echo $parcel['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                      <a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-3" href="<?php echo $parcel['print']; ?>" target="_blank" id="button-print<?php echo $parcel['parcel_id']; ?>" data-toggle="tooltip" title="Экспресс накладная" class="btn btn-primary"><i class="fa fa-print"></i></a>
                      <a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-4" href="<?php echo $parcel['delete']; ?>" id="button-delete<?php echo $parcel['parcel_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                  </td>
                </tr>
                <?php $fr = false; ?>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                  <tr>
                      
                      <td colspan="4">
                      </td>
                      <td colspan="6">
                      
                      <!--<div class="row">  -->
                        <div class="col-sm-7">
                          <div class="form-group">
                            <select onchange="$('select[name=air_id]').val($(this).val());" class="form-control">
                            <?php foreach ($airs as $air) { ?>
                            <option value="<?php echo $air['air_id']; ?>"><?php echo $air['name']; ?></option>
                            <?php } ?>
                            </select>
                          </div>
                        </div>
                        <div class="col-sm-5">
                          <div class="form-group">
                            <a onclick="createAir(); return false;" class="btn btn-primary parcel" style="width: 100%;">В самолет</a>
                          </div>
                        </div>  
                      <!--</div> -->
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
    <input type="hidden" value="<?php echo $create_air; ?>" id="create_air">
    <input type="hidden" value="<?php echo $merge_parcel; ?>" id="merge_parcel">
<script type="text/javascript"><!--
        function createAir() {
                if($('input[name^=\'selected\']:checked').length) {
                        $('#form-parcel').attr('action', $('#create_air').val());
                        $('#form-parcel').submit();
                }
        }
        function mergeParcel() {
                if($('input[name^=\'selected\']:checked').length) {
                        $('#form-parcel').attr('action', $('#merge_parcel').val());
                        $('#form-parcel').submit();
                }
        }
//--></script>
<script type="text/javascript"><!--
$('#form-parcel button').on('click', function(e) {
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
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=sale/parcel&token=<?php echo $token; ?>';
	
//	var filter_parcel_id = $('input[name=\'filter_parcel_id\']').val();
	
//	if (filter_parcel_id) {
//		url += '&filter_parcel_id=' + encodeURIComponent(filter_parcel_id);
//	}
	
	var filter_external_id = $('input[name=\'filter_external_id\']').val();
	
	if (filter_external_id) {
		url += '&filter_external_id=' + encodeURIComponent(filter_external_id);
	}
	
	var filter_parcel_status_id = $('select[name=\'filter_parcel_status_id\']').val();
	
	if (filter_parcel_status_id != '*') {
		url += '&filter_parcel_status_id=' + encodeURIComponent(filter_parcel_status_id);
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
$('input[name^=\'selected\']:first').trigger('change');

$('a[id^=\'button-delete\']').on('click', function(e) {
	e.preventDefault();
	
	if (confirm('<?php echo $text_confirm; ?>')) {
		location = $(this).attr('href');
	}
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