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
        <!--<div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-external-id"><?php echo $entry_air_id; ?></label>
                <input type="text" name="filter_external_id" value="<?php echo $filter_external_id; ?>" placeholder="<?php echo $entry_air_id; ?>" id="input-air-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-air-status-id"><?php echo $entry_air_status; ?></label>
                <select name="filter_air_status_id" id="input-air-status-id" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($air_statuses as $air_status) { ?>
                  <?php if ($air_status['air_status_id'] == $filter_air_status_id) { ?>
                  <option value="<?php echo $air_status['air_status_id']; ?>" selected="selected"><?php echo $air_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $air_status['air_status_id']; ?>"><?php echo $air_status['name']; ?></option>
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
        </div>  -->
        <form method="post" enctype="multipart/form-data" id="form-air">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);$('input[name^=\'selected\']:first').trigger('change');" /></td>
                  <td class="text-right"><?php if ($sort == 'a.air_id') { ?>
                    <a href="<?php echo $sort_air; ?>" class="<?php echo strtolower($order); ?>">№</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_air; ?>">№</a>
                    <?php } ?></td>
                 
                  <td class="text-left"><?php if ($sort == 'a.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">Самолет</a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>">Самолет</a>
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
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php $fr = true; ?>
                <?php if ($airs) { ?>
                <?php foreach ($airs as $air) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($air['air_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $air['air_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $air['air_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="text-right"><?php echo $air['air_id']; ?></td>
                  <td class="text-left"><?php echo $air['name']; ?></td>
                  <td class="text-left"><?php echo $air['status']; ?></td>
                  <td class="text-left"><?php echo $air['date_added']; ?></td>
                  <td class="text-right">
                      <!--<a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-2" href="<?php echo $air['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info disabled"><i class="fa fa-eye"></i></a>-->
                      <a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-3" href="<?php echo $air['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                      <a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-3" href="<?php echo $air['print']; ?>" target="_blank" id="button-print<?php echo $air['air_id']; ?>" data-toggle="tooltip" title="Сопроводительная накладная на партию" class="btn btn-primary"><i class="fa fa-print"></i></a>
                      <a uac<?php echo $fr ? 'c' : ''; ?>="sale-pl-b-4" href="<?php echo $air['delete']; ?>" id="button-delete<?php echo $air['air_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                      
                  </td>
                </tr>
                <?php $fr = false; ?>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
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
                        $('#form-air').attr('action', $('#create_parcel').val());
                        $('#form-air').submit();
                }
        }
//--></script>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=sale/air&token=<?php echo $token; ?>';
	
//	var filter_air_id = $('input[name=\'filter_air_id\']').val();
	
//	if (filter_air_id) {
//		url += '&filter_air_id=' + encodeURIComponent(filter_air_id);
//	}
	
	var filter_external_id = $('input[name=\'filter_external_id\']').val();
	
	if (filter_external_id) {
		url += '&filter_external_id=' + encodeURIComponent(filter_external_id);
	}
	
	var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_air_status_id = $('select[name=\'filter_air_status_id\']').val();
	
	if (filter_air_status_id != '*') {
		url += '&filter_air_status_id=' + encodeURIComponent(filter_air_status_id);
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