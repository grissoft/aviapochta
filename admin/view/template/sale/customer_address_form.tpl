<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button uacc="s_cgf-b-1" type="submit" form="form-customer-group" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer-group" class="form-horizontal">
          
                      <input type="hidden" name="address[address_id]" value="<?php echo $address['address_id']; ?>" />
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-firstname">Имя, Отчество</label>
                        <div class="col-sm-10">
                          <input type="text" name="address[firstname]" value="<?php echo $address['firstname']; ?>" placeholder="Имя, Отчество" id="input-firstname" class="form-control" />
                          <?php if (isset($error_address['firstname'])) { ?>
                          <div class="text-danger"><?php echo $error_address['firstname']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-lastname">Фамилия</label>
                        <div class="col-sm-10">
                          <input type="text" name="address[lastname]" value="<?php echo $address['lastname']; ?>" placeholder="Фамилия" id="input-lastname" class="form-control" />
                          <?php if (isset($error_address['lastname'])) { ?>
                          <div class="text-danger"><?php echo $error_address['lastname']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-zone">Область</label>
                        <div class="col-sm-10">
                          <select name="address[zone_id]" id="input-zone" class="form-control">
                          <option value=""> --- Выберите --- </option>
                            <?php foreach ($zones as $zone) { ?>
                            <option value="<?php echo $zone['zone_id']; ?>" <?php if ($zone['zone_id'] == $address['zone_id']) { ?> selected="selected"<?php } ?>><?php echo $zone['name']; ?></option>
                            <?php } ?>
                          </select>
                          <?php if (isset($error_address['zone_id'])) { ?>
                          <div class="text-danger"><?php echo $error_address['zone_id']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                     <div class="form-group required">
                        
                        <label class="col-sm-2 control-label" for="input-city">Населенный пункт</label>
                        <div class="col-sm-10">
                          <input type="text" name="address[city]" value="<?php echo $address['city']; ?>" placeholder="Населенный пункт" id="input-city" class="form-control" />
                           <?php if (isset($error_address['city'])) { ?>
                          <div class="text-danger"><?php echo $error_address['city']; ?></div>
                          <?php } ?>
                        </div>
                       
                      </div>
                      
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-address-1">Район</label>
                        <div class="col-sm-10">
                          <input type="text" name="address[region]" value="<?php echo $address['region']; ?>" placeholder="Район" id="input-region" class="form-control" />
                        </div>
                      </div>
                      
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-address-1">Улица</label>
                        <div class="col-sm-10">
                          <input type="text" name="address[address_1]" value="<?php echo $address['address_1']; ?>" placeholder="Улица" id="input-address-1" class="form-control" />
                          <?php if (isset($error_address['address_1'])) { ?>
                          <div class="text-danger"><?php echo $error_address['address_1']; ?></div>
                          <?php } ?>
                        </div>
                        
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-2 control-label" for="input-dom">Дом</label>
                        <div class="col-sm-10">
                          <input type="text" name="address[dom]" value="<?php echo $address['dom']; ?>" placeholder="Дом" id="input-dom" class="form-control" />
                          <?php if (isset($error_address['dom'])) { ?>
                          <div class="text-danger"><?php echo $error_address['dom']; ?></div>
                          <?php } ?>
                        </div>
                        
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-kv">Квартира</label>
                        <div class="col-sm-10">
                          <input type="text" name="address[kv]" value="<?php echo $address['kv']; ?>" placeholder="Квартира" id="input-kv" class="form-control" />
                        </div>
                      </div>
                    
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>